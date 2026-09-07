<?php

declare(strict_types=1);

namespace Pulse\Plugins;

use Pulse\Container\Container;

interface PluginInterface
{
    public function register(Container $container): void;
    public function boot(Container $container): void;
}

class PluginManager
{
    protected array $plugins = [];

    public function register(string|PluginInterface $plugin): void
    {
        $instance = is_string($plugin) ? new $plugin() : $plugin;
        $this->plugins[] = $instance;
        $instance->register(Container::getInstance());
    }

    public function bootAll(): void
    {
        foreach ($this->plugins as $plugin) {
            $plugin->boot(Container::getInstance());
        }
    }
}
