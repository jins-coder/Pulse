# Routing — Pulse Documentation ⚡

- [Basic Routing](#basic-routing)
- [Tri-Mode Universal Content Negotiation](#tri-mode-universal-content-negotiation)
- [Route Parameters & Model Binding](#route-parameters--model-binding)
- [Route Groups & Middleware](#route-groups--middleware)
- [Named Routes & URL Generation](#named-routes--url-generation)

---

## Basic Routing

All Pulse routes are registered in `routes/web.php`. The simplest routes accept a URI and a closure:

```php
use Pulse\Http\Response;

$router->get('/greeting', function ($request) {
    return 'Hello from Pulse Framework!';
});
```

You may also bind routes to controllers or view templates:

```php
$router->get('/', function ($request) {
    return Response::view('pages/home', [
        'title' => 'Welcome to Pulse'
    ]);
});
```

---

## Tri-Mode Universal Content Negotiation

Unlike traditional frameworks where you must write separate API routes and SSR web controllers, Pulse routes are **Tri-Mode Universal**:

```php
$router->get('/projects', function ($request) {
    $projects = Project::all();

    return Response::view('pages/projects', [
        'projects' => $projects
    ]);
});
```

A single route automatically adapts its response based on how it is invoked:

1. **Direct Browser URL Bar (SSR)**: Returns full HTML with layouts, CSS, and SEO meta tags.
2. **SPA Link Click (`wire:navigate`)**: Returns an ultra-lightweight DOM-morph JSON payload (<2KB) for instantaneous client-side navigation without full page reload.
3. **API Request (`Accept: application/json`)**: Returns serialized JSON data suitable for external API consumers.

---

## Route Parameters & Model Binding

Route parameters are defined using `{param}` syntax:

```php
$router->get('/users/{id}', function ($request, $id) {
    return "User Profile ID: " . e($id);
});
```

---

## Route Groups & Middleware

Middleware can be attached to route groups for authentication, rate limiting, and CSRF protection:

```php
$router->group(['middleware' => ['auth', 'tenant']], function ($router) {
    $router->get('/dashboard', function ($request) {
        return Response::view('pages/dashboard');
    });

    $router->get('/settings', function ($request) {
        return Response::view('pages/settings');
    });
});
```
