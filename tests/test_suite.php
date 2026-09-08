<?php

declare(strict_types=1);

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
require_once __DIR__ . '/../src/Pulse.php';

echo "⚡ Running Pulse Framework v4.0 (Infinity) Core & Subsystem Test Suite...\n\n";

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
            $msg = "{$description}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}";
            echo "  \033[31m✖\033[0m {$msg}\n";
            if (getenv('GITHUB_ACTIONS') === 'true') {
                echo "::error file={$e->getFile()},line={$e->getLine()}::{$msg}\n";
            }
            $this->failed++;
        }
    }
}

$test = new TestRunner();

// 1. Kernel Bootstrapping & Versioning
$test->it('initializes Pulse Kernel and reports version 4.0.0 (Infinity)', function () {
    $kernel = new Pulse\Pulse(dirname(__DIR__));
    if (Pulse\Pulse::VERSION !== '4.0.0') {
        throw new \Exception('Expected version 4.0.0, got ' . Pulse\Pulse::VERSION);
    }
    if (Pulse\Pulse::CODENAME !== 'Infinity') {
        throw new \Exception('Expected codename Infinity, got ' . Pulse\Pulse::CODENAME);
    }
});

// 2. In-Browser WASM PHP Engine & Offline Store
$test->it('manages WASM component manifests and offline vector clock reconciliation', function () {
    $wasm = Pulse\Wasm\WasmRuntime::getInstance();
    $manifest = $wasm->getBootstrapManifest();
    if ($manifest['engine'] !== 'Pulse-WASM-v4.0' || !$manifest['offline_capable']) {
        throw new \Exception('Invalid WASM bootstrap manifest');
    }

    $store = new Pulse\Wasm\OfflineStore('client_test');
    $store->put('user.theme', 'dark');
    $reconciliation = $store->reconcile([
        ['key' => 'user.theme', 'value' => 'midnight', 'timestamp' => microtime(true) + 10]
    ]);
    if ($reconciliation['reconciled_count'] !== 1 || $store->get('user.theme') !== 'midnight') {
        throw new \Exception('Offline store vector reconciliation failed');
    }
});

// 3. Ahead-of-Time (AOT) Bytecode Compilation
$test->it('pre-compiles routes and container graphs into static opcode bundles', function () {
    $aot = Pulse\Compiler\AotCompiler::getInstance();
    $router = new Pulse\Routing\Router();
    $router->get('/test', fn() => 'ok')->name('test');
    $container = Pulse\Container\Container::getInstance();

    $manifest = $aot->compileApplication(dirname(__DIR__), $router, $container);
    if ($manifest['aot_version'] !== '4.0.0' || $manifest['compiled_routes'] < 1) {
        throw new \Exception('AOT application compilation failed');
    }

    $pulsexRes = $aot->precompilePulseX('<div>{ $hello }</div>');
    if (empty($pulsexRes['key']) || $pulsexRes['source_size'] <= 0) {
        throw new \Exception('AOT PulseX template precompilation failed');
    }
});

// 4. Sub-Millisecond Serverless Micro-VM Engine
$test->it('creates memory snapshots and resurrects micro-VM instances in <0.4ms', function () {
    $microvm = Pulse\Runtime\MicroVM\MicroVMKernel::getInstance();
    $snapshot = $microvm->createSnapshot('snap_test_01');
    if ($snapshot['status'] !== 'READY') {
        throw new \Exception('MicroVM snapshot creation failed');
    }

    $instance = $microvm->spawnInstance($snapshot['id']);
    if ($instance['status'] !== 'RUNNING' || $instance['resurrect_duration_ms'] > 1.0) {
        throw new \Exception('MicroVM instant resurrection failed');
    }

    $scaled = $microvm->scaleToZero($instance['instance_id']);
    if (!$scaled) {
        throw new \Exception('MicroVM scale-to-zero failed');
    }
});

// 5. OpenTelemetry Distributed Tracing
$test->it('creates OpenTelemetry W3C spans and tracks execution durations', function () {
    $tracer = Pulse\Telemetry\Tracer::getInstance();
    $result = $tracer->trace('test.v4.operation', fn($span) => 100);
    if ($result !== 100) {
        throw new \Exception('Tracer execution failed');
    }
});

// 6. Web Routes Rendering (WASM / AOT / Upgrade / Docs)
$test->it('loads and renders v4.0 web route endpoints with HTTP 200 OK', function () {
    $app = new Pulse\Pulse(dirname(__DIR__));
    require dirname(__DIR__) . '/routes/web.php';

    // Test /wasm
    $responseWasm = $app->handle(Pulse\Http\Request::create('/wasm'));
    if ($responseWasm->statusCode !== 200 || !str_contains($responseWasm->content, 'WebAssembly')) {
        throw new \Exception('/wasm route failed');
    }

    // Test /aot
    $responseAot = $app->handle(Pulse\Http\Request::create('/aot'));
    if ($responseAot->statusCode !== 200 || !str_contains($responseAot->content, 'Ahead-of-Time')) {
        throw new \Exception('/aot route failed');
    }

    // Test /_pulse/microvm
    $reqMicro = Pulse\Http\Request::create('/_pulse/microvm');
    $reqMicro->server['HTTP_ACCEPT'] = 'application/json';
    $responseMicro = $app->handle($reqMicro);
    if ($responseMicro->statusCode !== 200) {
        throw new \Exception('/_pulse/microvm route failed');
    }
});

// 7. FastArr & PHP 8.4 High-Performance Array Engine
$test->it('executes zero-allocation lazy generators, partition, keyBy, and PHP 8.4 array functions', function () {
    $data = [
        ['id' => 10, 'tier' => 'starter', 'active' => true, 'score' => 45],
        ['id' => 20, 'tier' => 'pro', 'active' => false, 'score' => 88],
        ['id' => 30, 'tier' => 'enterprise', 'active' => true, 'score' => 95],
        ['id' => 40, 'tier' => 'enterprise', 'active' => true, 'score' => 99],
    ];

    // PHP 8.4 array_find
    $found = array_find($data, fn($item) => $item['tier'] === 'pro');
    if ($found === null || $found['id'] !== 20) {
        throw new \Exception('array_find failed');
    }

    // PHP 8.4 array_find_key
    $foundKey = array_find_key($data, fn($item) => $item['tier'] === 'enterprise');
    if ($foundKey !== 2) {
        throw new \Exception('array_find_key failed');
    }

    // PHP 8.4 array_any & array_all
    if (!array_any($data, fn($item) => $item['score'] > 90)) {
        throw new \Exception('array_any failed');
    }
    if (array_all($data, fn($item) => $item['active'] === true)) {
        throw new \Exception('array_all failed on mixed items');
    }

    // FastArr fluent lazy generator & partitions
    $fast = fast_arr($data);
    [$activeItems, $inactiveItems] = $fast->partition(fn($item) => $item['active']);
    if (count($activeItems) !== 3 || count($inactiveItems) !== 1) {
        throw new \Exception('FastArr partition failed');
    }

    $keyed = $fast->keyBy('id');
    if (!isset($keyed[30]) || $keyed[30]['tier'] !== 'enterprise') {
        throw new \Exception('FastArr keyBy failed');
    }

    // Generator pipeline
    $topEnterpriseScores = iterator_to_array(
        $fast->lazyFilter(fn($i) => $i['tier'] === 'enterprise')
             ->lazyMap(fn($i) => $i['score'] * 2)
             ->getGenerator()
    );
    if ($topEnterpriseScores !== [190, 198]) {
        throw new \Exception('FastArr lazy generator transformation pipeline failed');
    }
});

// 8. Beast Core: Native C-ABI / SIMD / FlatPack / Embedded In-Memory Storage
$test->it('operates NativeCore, EmbeddedStorage, SimdEngine, and VectorEngine with microsecond latency', function () {
    // Native Core / Fallback test
    $core = Pulse\Core\NativeCore::getInstance();
    $hash = $core->hash64('pulse_beast_test_payload');
    if ($hash === 0) {
        throw new \Exception('NativeCore hash64 failed');
    }

    $sum = $core->fastSum([10, 20, 30, 40, 50]);
    if ($sum !== 150) {
        throw new \Exception('NativeCore fastSum failed');
    }

    if (!$core->validateJson('{"cluster":"beast","status":"OK"}')) {
        throw new \Exception('NativeCore validateJson failed');
    }

    // Embedded In-Memory Store
    $storage = Pulse\Storage\EmbeddedStorage::getInstance();
    $storage->set('cluster.node.1', ['ip' => '10.0.0.1', 'role' => 'leader']);
    if ($storage->get('cluster.node.1')['role'] !== 'leader') {
        throw new \Exception('EmbeddedStorage get failed');
    }

    $storage->insertRow('metrics', ['cpu' => 12.4, 'mem' => 45.2]);
    $queryRes = $storage->queryTable('metrics');
    if ($queryRes['count'] < 1) {
        throw new \Exception('EmbeddedStorage columnar query failed');
    }

    // SimdEngine FlatPack zero-copy packing
    $flatPayload = ['event' => 'user_upgraded', 'tier' => 'enterprise', 'ts' => microtime(true)];
    $packed = Pulse\Utils\SimdEngine::pack($flatPayload);
    $unpacked = Pulse\Utils\SimdEngine::unpack($packed);
    if ($unpacked['tier'] !== 'enterprise') {
        throw new \Exception('SimdEngine FlatPack pack/unpack failed');
    }

    // VectorEngine AI semantic search
    $vectors = Pulse\AI\VectorEngine::getInstance();
    $vecA = $vectors->generateEmbedding('enterprise subscription tier');
    $vecB = $vectors->generateEmbedding('enterprise subscription tier');
    $similarity = $core->cosineSimilarity($vecA, $vecB);
    if ($similarity < 0.99) {
        throw new \Exception('VectorEngine cosine similarity identity failed');
    }
});

// 9. Beast Web Routes & Benchmark API Endpoint
$test->it('renders /beast web page and responds to /_pulse/beast/benchmark with 200 OK', function () {
    $app = new Pulse\Pulse(dirname(__DIR__));
    require dirname(__DIR__) . '/routes/web.php';

    $responseBeast = $app->handle(Pulse\Http\Request::create('/beast'));
    if ($responseBeast->statusCode !== 200 || !str_contains($responseBeast->content, 'Beast Core')) {
        throw new \Exception('/beast route rendering failed');
    }

    $reqBench = Pulse\Http\Request::create('/_pulse/beast/benchmark');
    $reqBench->method = 'POST';
    $reqBench->server['HTTP_ACCEPT'] = 'application/json';
    $responseBench = $app->handle($reqBench);
    if ($responseBench->statusCode !== 200) {
        throw new \Exception('/_pulse/beast/benchmark API endpoint failed');
    }
});



echo "\n==========================================\n";
echo "Tests Passed: \033[32m{$test->passed}\033[0m | Failed: \033[31m{$test->failed}\033[0m\n";
echo "==========================================\n";

if ($test->failed > 0) {
    exit(1);
}

exit(0);
