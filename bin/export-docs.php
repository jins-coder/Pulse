#!/usr/bin/env php
<?php

declare(strict_types=1);

$baseDir = dirname(__DIR__);

require_once $baseDir . '/src/Pulse.php';

$app = new \Pulse\Pulse($baseDir);

// Register web routes
require_once $baseDir . '/routes/web.php';

$outDir = $argv[1] ?? ($baseDir . '/_site');
$baseUrl = $argv[2] ?? ''; // e.g. /Pulse or empty for root

if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

echo "\033[35m⚡ Pulse Static Documentation Exporter (Laravel Style)\033[0m\n";
echo "Exporting pre-rendered pages to: \033[36m{$outDir}\033[0m\n\n";

$routes = [
    '/' => 'home',
    '/docs' => 'docs',
    '/about' => 'about',
];

$rendered = [];

foreach ($routes as $uri => $slug) {
    $request = \Pulse\Http\Request::create($uri);
    $response = $app->handle($request);
    $html = $response->content;

    // If a base URL is specified for GitHub Pages (e.g. /Pulse), adjust absolute root paths
    if (!empty($baseUrl)) {
        $cleanBase = rtrim($baseUrl, '/');
        $html = str_replace('href="/favicon.svg"', 'href="' . $cleanBase . '/favicon.svg"', $html);
        $html = str_replace('src="/pulse.js"', 'src="' . $cleanBase . '/pulse.js"', $html);
        $html = str_replace('href="/assets/', 'href="' . $cleanBase . '/assets/', $html);
        $html = str_replace('src="/assets/', 'src="' . $cleanBase . '/assets/', $html);
        $html = str_replace('href="/docs"', 'href="' . $cleanBase . '/docs/"', $html);
        $html = str_replace('href="/about"', 'href="' . $cleanBase . '/about/"', $html);
        $html = str_replace('href="/"', 'href="' . $cleanBase . '/"', $html);
    }

    if ($slug === 'docs') {
        // Also save /docs as root index.html so the doc site is the primary landing page on GitHub Pages
        file_put_contents($outDir . '/index.html', $html);
        $docsDir = $outDir . '/docs';
        if (!is_dir($docsDir)) mkdir($docsDir, 0777, true);
        file_put_contents($docsDir . '/index.html', $html);
        $rendered[] = ['route' => $uri, 'file' => 'index.html & docs/index.html', 'size' => strlen($html)];
    } else {
        $pageDir = $outDir . '/' . $slug;
        if (!is_dir($pageDir)) mkdir($pageDir, 0777, true);
        file_put_contents($pageDir . '/index.html', $html);
        $rendered[] = ['route' => $uri, 'file' => "{$slug}/index.html", 'size' => strlen($html)];
    }
}

// Copy public assets
$publicDir = $baseDir . '/public';
if (file_exists($publicDir . '/favicon.svg')) {
    copy($publicDir . '/favicon.svg', $outDir . '/favicon.svg');
}
if (file_exists($publicDir . '/pulse.js')) {
    copy($publicDir . '/pulse.js', $outDir . '/pulse.js');
}

// Copy assets folder recursively
function copyDirectory(string $src, string $dst): void {
    if (!is_dir($src)) return;
    if (!is_dir($dst)) mkdir($dst, 0777, true);
    $dir = opendir($src);
    while (($file = readdir($dir)) !== false) {
        if ($file !== '.' && $file !== '..') {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

copyDirectory($publicDir . '/assets', $outDir . '/assets');

// Create .nojekyll to prevent GitHub Pages from ignoring folders or assets starting with underscores
file_put_contents($outDir . '/.nojekyll', '');

// Output summary
echo "  \033[32m✔\033[0m Pre-rendered static documentation pages:\n";
foreach ($rendered as $item) {
    $kb = round($item['size'] / 1024, 2);
    echo "    • {$item['route']} -> {$item['file']} ({$kb} KB)\n";
}
echo "  \033[32m✔\033[0m Static assets synced (favicon.svg, pulse.js, assets/)\n";
echo "  \033[32m✔\033[0m GitHub Pages .nojekyll flag added\n\n";
echo "\033[32m✨ Documentation ready for GitHub Pages deployment in {$outDir}\033[0m\n";
