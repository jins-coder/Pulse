<?php

declare(strict_types=1);

namespace Pulse\Realtime;

class ChannelManager
{
    protected static array $channels = [];
    protected static array $authorizers = [];

    public static function channel(string $channelPattern, callable $authorizer): void
    {
        self::$authorizers[$channelPattern] = $authorizer;
    }

    public static function broadcast(string $channel, string $event, array $payload): void
    {
        if (!isset(self::$channels[$channel])) {
            self::$channels[$channel] = [];
        }

        self::$channels[$channel][] = [
            'channel' => $channel,
            'event' => $event,
            'data' => $payload,
            'timestamp' => microtime(true),
        ];
    }

    public static function getChannelMessages(string $channel, float $sinceTimestamp = 0): array
    {
        $messages = self::$channels[$channel] ?? [];
        return array_values(array_filter($messages, fn($m) => $m['timestamp'] > $sinceTimestamp));
    }

    public static function syncCrdt(string $channel, array $crdtState): void
    {
        self::broadcast($channel, 'crdt:sync', [
            'origin' => CrdtStateSync::getInstance()->getNodeId(),
            'state' => $crdtState,
            'synced_at' => microtime(true),
        ]);
    }
}
