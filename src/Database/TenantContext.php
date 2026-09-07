<?php

declare(strict_types=1);

namespace Pulse\Database;

class TenantContext
{
    private static ?string $currentTenantId = null;

    public static function setTenantId(?string $tenantId): void
    {
        self::$currentTenantId = $tenantId;
    }

    public static function getTenantId(): ?string
    {
        return self::$currentTenantId;
    }

    public static function set(?string $tenantId): void
    {
        self::$currentTenantId = $tenantId;
    }

    public static function get(): ?string
    {
        return self::$currentTenantId;
    }

    public static function has(): bool
    {
        return self::$currentTenantId !== null && self::$currentTenantId !== '';
    }

    public static function clear(): void
    {
        self::$currentTenantId = null;
    }
}
