<?php

declare(strict_types=1);

namespace Pulse\AI;

class AiEngine
{
    public static function streamCompletion(string $prompt, ?callable $onChunk = null): void
    {
        $words = explode(' ', "Pulse AI-Native Streaming: Generating fast, token-by-token completions directly in PHP!");
        foreach ($words as $word) {
            if ($onChunk) {
                $onChunk($word . ' ');
            }
            usleep(50000);
        }
    }
}
