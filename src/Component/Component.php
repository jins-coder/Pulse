<?php

declare(strict_types=1);

namespace Pulse\Component;

abstract class Component
{
    public string $id;
    protected array $listeners = [];
    protected array $toasts = [];

    public function __construct(?string $id = null)
    {
        $this->id = $id ?? 'cmp_' . substr(bin2hex(random_bytes(6)), 0, 8);
    }

    public function toast(string $message, string $type = 'success'): void
    {
        $this->toasts[] = [
            'id' => 'toast_' . substr(bin2hex(random_bytes(4)), 0, 8),
            'message' => $message,
            'type' => $type,
            'timestamp' => microtime(true),
        ];
    }

    public function getToasts(): array
    {
        return $this->toasts;
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }

    public function mount(...$params): void
    {
        // Lifecycle hook
    }

    public function updating(string $property, mixed $value): void
    {
        // Hook before state mutation
    }

    public function updated(string $property, mixed $value): void
    {
        // Hook after state mutation
    }

    public function syncState(array $newState): void
    {
        foreach ($newState as $key => $value) {
            if (property_exists($this, $key)) {
                $refProp = new \ReflectionProperty($this, $key);
                if ($refProp->isPublic() && !$refProp->isStatic() && !$refProp->isReadOnly()) {
                    $this->updating($key, $value);
                    $this->{$key} = $value;
                    $this->updated($key, $value);
                }
            }
        }
    }

    public function callAction(string $method, array $params = []): mixed
    {
        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException("Action {$method} does not exist on component " . static::class);
        }

        $refMethod = new \ReflectionMethod($this, $method);
        if (!$refMethod->isPublic() || $refMethod->isStatic()) {
            throw new \RuntimeException("Cannot invoke non-public or static action {$method}.");
        }

        return $this->{$method}(...$params);
    }

    public function getPublicState(): array
    {
        $state = [];
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic() && !$property->isReadOnly()) {
                $state[$property->getName()] = $property->getValue($this);
            }
        }
        return $state;
    }

    public function setPublicState(array $state): void
    {
        foreach ($state as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function render(): string;

    public function toHtml(): string
    {
        $html = trim($this->render());
        $state = $this->getPublicState();
        $snapshot = StateHydrator::createSnapshot(static::class, $this->id, $state);
        $checksum = StateHydrator::generateChecksum($snapshot);

        $componentMeta = sprintf(
            'data-component="%s" data-id="%s" data-snapshot="%s" data-checksum="%s"',
            htmlspecialchars(static::class, ENT_QUOTES),
            htmlspecialchars($this->id, ENT_QUOTES),
            htmlspecialchars($snapshot, ENT_QUOTES),
            htmlspecialchars($checksum, ENT_QUOTES)
        );

        if (preg_match('/^<([a-zA-Z0-9\-]+)([^>]*)>/', $html, $matches)) {
            $tag = $matches[1];
            $existingAttrs = $matches[2];
            $replacement = "<{$tag} {$componentMeta} {$existingAttrs}>";
            return preg_replace('/^<[a-zA-Z0-9\-]+[^>]*>/', $replacement, $html, 1);
        }

        return "<div {$componentMeta}>{$html}</div>";
    }
}
