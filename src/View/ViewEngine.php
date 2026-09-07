<?php

declare(strict_types=1);

namespace Pulse\View;

use Pulse\Component\Component;

class ViewEngine
{
    protected string $viewsPath;
    protected array $sections = [];
    protected ?string $currentSection = null;
    protected ?string $layout = null;
    protected array $sharedData = [];
    public readonly PulseXCompiler $compiler;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/\\');
        $this->compiler = new PulseXCompiler();
    }

    public function share(string $key, mixed $value): void
    {
        $this->sharedData[$key] = $value;
    }

    public function render(string $view, array $data = []): string
    {
        $viewFile = $this->resolvePath($view);
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View template not found: {$viewFile}");
        }

        $mergedData = array_merge($this->sharedData, $data);
        extract($mergedData, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutPath = $this->resolvePath($this->layout);
            $this->layout = null;
            $layoutData = array_merge($mergedData, ['content' => $content, 'sections' => $this->sections]);
            extract($layoutData, EXTR_SKIP);
            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }

        return $content;
    }

    public function extends(string $layout): void
    {
        $this->layout = $layout;
    }

    public function section(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection(): void
    {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }

    public function yieldSection(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    public function component(string $class, array $params = []): string
    {
        if (!class_exists($class)) {
            throw new \RuntimeException("Component class {$class} not found.");
        }

        /** @var Component $component */
        $component = new $class();
        if (method_exists($component, 'mount')) {
            $component->mount(...$params);
        }
        foreach ($params as $key => $value) {
            if (property_exists($component, $key)) {
                $component->{$key} = $value;
            }
        }

        return $component->toHtml();
    }

    protected function resolvePath(string $view): string
    {
        $path = str_replace('.', DIRECTORY_SEPARATOR, $view);
        return $this->viewsPath . DIRECTORY_SEPARATOR . $path . '.php';
    }
}
