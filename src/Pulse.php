<?php

declare(strict_types=1);

namespace Pulse;

// Register Framework-level PSR-4 autoloader for standalone execution
spl_autoload_register(function (string $class): void {
    if (str_starts_with($class, 'Pulse\\')) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, 6)) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    if (str_starts_with($class, 'App\\')) {
        $file = dirname(__DIR__) . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

use Pulse\Container\Container;
use Pulse\Http\Request;
use Pulse\Http\Response;
use Pulse\Routing\Router;
use Pulse\View\ViewEngine;
use Pulse\Middleware\Pipeline;
use Pulse\Middleware\CsrfMiddleware;
use Pulse\Middleware\SecurityHeadersMiddleware;
use Pulse\Component\StateHydrator;
use Pulse\Component\Component;
use Pulse\Events\EventBus;
use Pulse\Queue\QueueManager;
use Pulse\Profiler\PerformanceProfiler;
use Pulse\Plugins\PluginManager;

class Pulse
{
    public const VERSION = '2.0.0';
    public const CODENAME = 'Quantum';

    private static ?Pulse $instance = null;
    public readonly Container $container;
    public readonly Router $router;
    public readonly ViewEngine $viewEngine;
    public readonly EventBus $events;
    public readonly QueueManager $queue;
    public readonly PluginManager $plugins;
    public ?PerformanceProfiler $profiler = null;
    protected string $basePath;
    protected array $globalMiddleware = [
        SecurityHeadersMiddleware::class,
        CsrfMiddleware::class,
    ];

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/\\');
        $this->container = Container::getInstance();
        $this->router = new Router();
        $this->viewEngine = new ViewEngine($this->basePath . '/resources/views');
        $this->events = new EventBus();
        $this->queue = new QueueManager();
        $this->plugins = new PluginManager();
        $this->profiler = new PerformanceProfiler();

        // Register core singletons
        $this->container->instance(Pulse::class, $this);
        $this->container->instance(Container::class, $this->container);
        $this->container->instance(Router::class, $this->router);
        $this->container->instance(ViewEngine::class, $this->viewEngine);
        $this->container->instance(EventBus::class, $this->events);
        $this->container->instance(QueueManager::class, $this->queue);

        self::$instance = $this;
    }

    public static function getInstance(): Pulse
    {
        if (self::$instance === null) {
            throw new \RuntimeException("Pulse framework instance not initialized.");
        }
        return self::$instance;
    }

    public function handle(Request $request): Response
    {
        // 1. Reactive Component Action Dispatch
        if ($request->isReactiveAction()) {
            return $this->handleReactiveAction($request);
        }

        // 2. Route Matching
        $route = $this->router->match($request);
        if (!$route) {
            return Response::html("<h1>404 Not Found</h1><p>Route {$request->uri} not matched.</p>", 404);
        }

        // 3. Middleware Pipeline Execution
        $pipeline = new Pipeline();
        $middlewares = array_merge($this->globalMiddleware, $route->middleware);

        return $pipeline
            ->send($request)
            ->through($middlewares)
            ->then(function (Request $req) use ($route) {
                $handler = $route->handler;
                $params = $route->parameters;

                $result = $this->container->call($handler, $params);

                // Tri-Mode Content Negotiation
                if ($req->wantsJson()) {
                    return is_array($result) || is_object($result) 
                        ? Response::json($result) 
                        : Response::json(['content' => (string)$result]);
                }

                if ($req->isSpa()) {
                    return Response::spa([
                        'url' => $req->uri,
                        'title' => $this->extractPageTitle((string)$result),
                        'html' => (string)$result,
                        'timestamp' => microtime(true),
                    ]);
                }

                if ($result instanceof Response) {
                    return $result;
                }

                $html = (string)$result;
                if ($this->profiler && str_contains($html, '</body>')) {
                    $toolbar = $this->profiler->renderToolbarHtml();
                    $html = str_replace('</body>', "{$toolbar}</body>", $html);
                }

                return Response::html($html);
            });
    }

    protected function handleReactiveAction(Request $request): Response
    {
        $data = $request->json ?? $request->post;
        $snapshot = $data['snapshot'] ?? null;
        $checksum = $data['checksum'] ?? null;
        $updates = $data['updates'] ?? [];
        $action = $data['action'] ?? null;
        $params = $data['params'] ?? [];

        if (!$snapshot || !$checksum) {
            return Response::json(['error' => 'Missing component snapshot or checksum'], 400);
        }

        try {
            $component = StateHydrator::verifyAndHydrate($snapshot, $checksum);

            if (!empty($updates)) {
                $component->syncState($updates);
            }

            if (!empty($action)) {
                $component->callAction($action, $params);
            }

            if ($this->profiler) {
                $this->profiler->recordComponent();
            }

            return Response::json([
                'id' => $component->id,
                'html' => $component->toHtml(),
                'state' => $component->getPublicState(),
                'toasts' => $component->getToasts(),
                'listeners' => $component->getListeners(),
                'success' => true
            ]);
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    protected function extractPageTitle(string $html): string
    {
        if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
            return trim($matches[1]);
        }
        return 'Pulse Application';
    }
}

// Unified Global Helper Functions
if (!function_exists('pulse')) {
    function pulse(): Pulse {
        return Pulse::getInstance();
    }
}

if (!function_exists('app')) {
    function app(): Pulse {
        return Pulse::getInstance();
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = []): string {
        return pulse()->viewEngine->render($template, $data);
    }
}

if (!function_exists('component')) {
    function component(string $class, array $params = []): string {
        return pulse()->viewEngine->component($class, $params);
    }
}

if (!function_exists('route')) {
    function route(string $name, array $params = []): string {
        return pulse()->router->url($name, $params);
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('event')) {
    function event(object $event): object {
        return pulse()->events->dispatch($event);
    }
}

if (!function_exists('queue')) {
    function queue(): QueueManager {
        return pulse()->queue;
    }
}
