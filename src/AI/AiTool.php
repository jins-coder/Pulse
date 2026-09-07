<?php

declare(strict_types=1);

namespace Pulse\AI;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION)]
class AiTool
{
    public function __construct(
        public readonly string $description,
        public readonly array $parameterDescriptions = []
    ) {}
}
