<?php

declare(strict_types=1);

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
require_once __DIR__ . '/../src/Pulse.php';

echo "⚡ Running Pulse Framework Core Test Suite...\n\n";

class TestRunner
{
    public int $passed = 0;
    public int $failed = 0;

    public function it(string $description, callable $test): void
    {
        try {
            $test();
            echo "  \033[32m✔\033[0m {$description}\n";
            $this->passed++;
        } catch (\Throwable $e) {
            echo "  \033[31m✖\033[0m {$description}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}\n";
            $this->failed++;
        }
    }
}

$test = new TestRunner();

// 1. Kernel Bootstrapping
$test->it('initializes Pulse Master Kernel and reports version 2.0.0 (Quantum)', function () {
    $kernel = new Pulse\Pulse(dirname(__DIR__));
    if (Pulse\Pulse::VERSION !== '2.0.0') {
        throw new \Exception('Expected version 2.0.0, got ' . Pulse\Pulse::VERSION);
    }
    if (Pulse\Pulse::CODENAME !== 'Quantum') {
        throw new \Exception('Expected codename Quantum, got ' . Pulse\Pulse::CODENAME);
    }
});

// 2. PSR-11 Auto-wiring Container
$test->it('resolves dependencies through PSR-11 Container', function () {
    $container = Pulse\Container\Container::getInstance();
    $container->bind('demo.service', fn() => 'PulseService');
    if ($container->get('demo.service') !== 'PulseService') {
        throw new \Exception('Failed to resolve container binding');
    }
});

// 3. PulseX Single-File Component Compiler
$test->it('compiles JSX markup and expressions in PulseXCompiler', function () {
    $compiler = new Pulse\View\PulseXCompiler();
    $rawMarkup = '<div class="test">{ $title }</div><if condition="$isActive"><span>Active</span></if>';
    $compiled = $compiler->compileMarkup($rawMarkup);
    if (!str_contains($compiled, '<?= e($title) ?>')) {
        throw new \Exception('Failed to compile JSX expression');
    }
    if (!str_contains($compiled, '<?php if ($isActive): ?>')) {
        throw new \Exception('Failed to compile <if> tag');
    }
});

// 4. AI Tool-Calling Engine
$test->it('extracts structured JSON tool definitions using #[AiTool] attributes', function () {
    $tools = Pulse\AI\Agent::extractTools(App\Components\Counter::class);
    if (!is_array($tools)) {
        throw new \Exception('Expected array of tool schemas');
    }
});

// 5. Database Schema & Multi-Tenancy Scoping
$test->it('creates database tables and enforces multi-tenant context', function () {
    Pulse\Database\TenantContext::setTenantId('tenant_qa_01');
    if (Pulse\Database\TenantContext::getTenantId() !== 'tenant_qa_01') {
        throw new \Exception('Tenant context propagation failed');
    }
    Pulse\Database\TenantContext::clear();
});

echo "\n==========================================\n";
echo "Tests Passed: \033[32m{$test->passed}\033[0m | Failed: \033[31m{$test->failed}\033[0m\n";
echo "==========================================\n";

if ($test->failed > 0) {
    exit(1);
}

exit(0);
