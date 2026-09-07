<?php

declare(strict_types=1);

namespace Pulse\AI;

class Agent
{
    protected array $tools = [];

    public static function extractTools(string|object $classOrInstance): array
    {
        $agent = new self();
        $refClass = new \ReflectionClass($classOrInstance);
        foreach ($refClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $attrs = $method->getAttributes(AiTool::class);
            if (!empty($attrs)) {
                $agent->registerTool($classOrInstance, $method->getName());
            }
        }
        return $agent->getToolSchemas();
    }

    public function registerTool(string|object $target, string $methodName): self
    {
        $refMethod = new \ReflectionMethod($target, $methodName);
        $attr = $refMethod->getAttributes(AiTool::class)[0] ?? null;
        $description = $attr ? $attr->newInstance()->description : "Executes {$methodName}";

        $parameters = [];
        foreach ($refMethod->getParameters() as $param) {
            $type = $param->getType() ? (string)$param->getType() : 'string';
            $parameters[$param->getName()] = [
                'type' => $type === 'int' ? 'integer' : ($type === 'bool' ? 'boolean' : 'string'),
                'description' => "Parameter {$param->getName()}",
            ];
        }

        $this->tools[$methodName] = [
            'target' => $target,
            'method' => $methodName,
            'description' => $description,
            'parameters' => $parameters,
        ];

        return $this;
    }

    public function getToolSchemas(): array
    {
        $schemas = [];
        foreach ($this->tools as $name => $tool) {
            $schemas[] = [
                'type' => 'function',
                'function' => [
                    'name' => $name,
                    'description' => $tool['description'],
                    'parameters' => [
                        'type' => 'object',
                        'properties' => $tool['parameters'],
                        'required' => array_keys($tool['parameters']),
                    ],
                ],
            ];
        }
        return $schemas;
    }

    public function executeTool(string $toolName, array $arguments): mixed
    {
        if (!isset($this->tools[$toolName])) {
            throw new \InvalidArgumentException("Tool [{$toolName}] is not registered on this Pulse Agent.");
        }

        $tool = $this->tools[$toolName];
        $target = $tool['target'];
        $method = $tool['method'];

        if (is_string($target)) {
            $target = \Pulse\Container\Container::getInstance()->resolve($target);
        }

        return $target->{$method}(...$arguments);
    }
}
