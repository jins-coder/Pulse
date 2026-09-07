<?php

declare(strict_types=1);

namespace Pulse\View;

class PulseXCompiler
{
    protected string $cachePath;

    public function __construct(string $cachePath = '')
    {
        $this->cachePath = $cachePath ?: dirname(__DIR__, 2) . '/storage/framework/views';
        if (!is_dir($this->cachePath)) {
            @mkdir($this->cachePath, 0777, true);
        }
    }

    /**
     * Compile a .pulse / .phx template into executable PHP and extracted client scripts
     */
    public function compile(string $templateContent): array
    {
        $phpCode = '';
        $clientJs = '';
        $scopedCss = '';

        // 1. Extract <php> server logic block if present
        if (preg_match('/<php>(.*?)<\/php>/is', $templateContent, $matches)) {
            $phpCode = trim($matches[1]);
            $templateContent = str_replace($matches[0], '', $templateContent);
        }

        // 2. Extract <script client> block if present
        if (preg_match('/<script\s+client(?:\s+lang=["\'](.*?)["\'])?>(.*?)<\/script>/is', $templateContent, $matches)) {
            $clientJs = trim($matches[2]);
            $templateContent = str_replace($matches[0], '', $templateContent);
        }

        // 3. Extract <style scoped> block if present
        if (preg_match('/<style(?:\s+scoped)?>(.*?)<\/style>/is', $templateContent, $matches)) {
            $scopedCss = trim($matches[1]);
            $templateContent = str_replace($matches[0], '', $templateContent);
        }

        // 4. Compile JSX-like Template Markup into pure PHP
        $compiledHtml = $this->compileMarkup(trim($templateContent));

        return [
            'php' => $phpCode,
            'html' => $compiledHtml,
            'clientJs' => $clientJs,
            'scopedCss' => $scopedCss,
        ];
    }

    /**
     * Compile JSX-style syntax to PHP
     */
    public function compileMarkup(string $html): string
    {
        // 1. Convert Unescaped {!! $expr !!}
        $html = preg_replace('/\{\!\!(.*?)\!\!\}/s', '<?= $1 ?>', $html);

        // 2. Convert Escaped { $expr } -> <?= e($expr) ?>
        $html = preg_replace_callback('/(?<!\{)\{\s*([^\{].*?)\s*\}(?!\})/s', function ($matches) {
            $expr = trim($matches[1]);
            // Skip JS object literals inside tags if any
            if (str_starts_with($expr, '"') || str_starts_with($expr, "'")) {
                return $matches[0];
            }
            return "<?= e({$expr}) ?>";
        }, $html);

        // 3. Convert <if condition="..."> ... </if>
        $html = preg_replace('/<if\s+condition=["\'](.*?)["\']>/i', '<?php if ($1): ?>', $html);
        $html = preg_replace('/<elseif\s+condition=["\'](.*?)["\']>/i', '<?php elseif ($1): ?>', $html);
        $html = preg_replace('/<else\s*\/?>/i', '<?php else: ?>', $html);
        $html = preg_replace('/<\/if>/i', '<?php endif; ?>', $html);

        // 4. Convert <for each="..."> ... </for>
        $html = preg_replace('/<for\s+each=["\'](.*?)["\']>/i', '<?php foreach ($1): ?>', $html);
        $html = preg_replace('/<\/for>/i', '<?php endforeach; ?>', $html);

        // 5. Convert <x-component-name :prop="$expr" prop="val" />
        $html = preg_replace_callback('/<x-([a-zA-Z0-9\-_]+)([^>]*)(?:\/>|>(.*?)<\/x-\1>)/s', function ($matches) {
            $componentName = $this->kebabToStudly($matches[1]);
            $rawAttrs = $matches[2] ?? '';
            $slotContent = $matches[3] ?? '';

            $props = $this->parseProps($rawAttrs);
            if (!empty($slotContent)) {
                $props['slot'] = "'" . addslashes(trim($slotContent)) . "'";
            }

            $propsPhp = '[' . implode(', ', array_map(fn($k, $v) => "'{$k}' => {$v}", array_keys($props), $props)) . ']';
            return "<?= component(\\App\\Components\\{$componentName}::class, {$propsPhp}) ?>";
        }, $html);

        return $html;
    }

    protected function parseProps(string $rawAttrs): array
    {
        $props = [];
        // Match :prop="$expr" or prop="static value"
        preg_match_all('/(:?)([a-zA-Z0-9_\-]+)=(["\'])(.*?)\3/s', $rawAttrs, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $isBound = $match[1] === ':';
            $key = $match[2];
            $value = $match[4];

            if ($isBound) {
                $props[$key] = $value; // PHP expression
            } else {
                $props[$key] = var_export($value, true); // Static string
            }
        }

        return $props;
    }

    protected function kebabToStudly(string $kebab): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $kebab)));
    }
}
