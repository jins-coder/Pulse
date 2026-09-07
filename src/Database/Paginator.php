<?php

declare(strict_types=1);

namespace Pulse\Database;

class Paginator
{
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $currentPage,
        public readonly int $lastPage
    ) {}

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->items,
            'total' => $this->total,
            'per_page' => $this->perPage,
            'current_page' => $this->currentPage,
            'last_page' => $this->lastPage,
        ];
    }
}
