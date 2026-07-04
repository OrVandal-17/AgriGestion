<?php

namespace App\Core;

use App\Middleware\Auth;

class Router
{
    private array $routes = [];

    public function get(string $pattern, callable $handler, array $roles = []): void
    {
        $this->add('GET', $pattern, $handler, $roles);
    }

    public function post(string $pattern, callable $handler, array $roles = []): void
    {
        $this->add('POST', $pattern, $handler, $roles);
    }

    public function put(string $pattern, callable $handler, array $roles = []): void
    {
        $this->add('PUT', $pattern, $handler, $roles);
    }

    public function delete(string $pattern, callable $handler, array $roles = []): void
    {
        $this->add('DELETE', $pattern, $handler, $roles);
    }

    private function add(string $method, string $pattern, callable $handler, array $roles): void
    {
        $this->routes[] = compact('method', 'pattern', 'handler', 'roles');
    }

    public function dispatch(Request $request, string $basePath = ''): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method) {
                continue;
            }
            $params = $this->match($route['pattern'], $uri);
            if ($params === null) {
                continue;
            }
            $request->params = $params;

            // Toute route avec des roles requiert une authentification prealable.
            // roles = ['any'] -> authentifie, peu importe le role.
            if (!empty($route['roles'])) {
                $user = Auth::check($request);
                if ($user === null) {
                    Response::error('Authentification requise', 401);
                    return;
                }
                if (!in_array('any', $route['roles'], true) && !in_array($user['role'], $route['roles'], true)) {
                    Response::error('Acces refuse pour ce role', 403);
                    return;
                }
                $request->user = $user;
            }

            ($route['handler'])($request);
            return;
        }

        Response::error('Route introuvable', 404);
    }

    private function match(string $pattern, string $uri): ?array
    {
        $patternParts = explode('/', trim($pattern, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        if (count($patternParts) !== count($uriParts)) {
            return null;
        }

        $params = [];
        foreach ($patternParts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $params[substr($part, 1, -1)] = $uriParts[$i];
            } elseif ($part !== $uriParts[$i]) {
                return null;
            }
        }
        return $params;
    }
}
