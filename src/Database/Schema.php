<?php

declare(strict_types=1);

namespace Pulse\Database;

class Schema
{
    public static function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $sql = $blueprint->toSql();
        Model::getConnection()->query($sql);
    }

    public static function dropIfExists(string $table): void
    {
        $sql = "DROP TABLE IF EXISTS {$table};";
        Model::getConnection()->query($sql);
    }
}

abstract class Migration
{
    abstract public function up(): void;
    abstract public function down(): void;
}
