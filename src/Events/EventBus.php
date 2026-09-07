<?php

declare(strict_types=1);

namespace Pulse\Events;

use Pulse\Container\Container;

class EventBus
{
    protected array $listeners = [];

    public function listen(string $eventClass, callable|string $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): object
    {
        $eventClass = get_class($event);
        $listeners = $this->listeners[$eventClass] ?? [];

        foreach ($listeners as $listener) {
            if (is_string($listener)) {
                Container::getInstance()->call([$listener, 'handle'], ['event' => $event]);
            } elseif (is_callable($listener)) {
                Container::getInstance()->call($listener, ['event' => $event]);
            }
        }

        return $event;
    }
}
