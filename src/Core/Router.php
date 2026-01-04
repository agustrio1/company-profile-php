<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private array $groupMiddlewares = [];

    public function get(string $uri, callable|array $action): self
    {
        return $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable|array $action): self
    {
        return $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, callable|array $action): self
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, callable|array $action): self
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    public function any(string $uri, callable|array $action): self
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            $this->addRoute($method, $uri, $action);
        }
        return $this;
    }

    private function addRoute(string $method, string $uri, callable|array $action): self
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middlewares' => $this->groupMiddlewares
        ];

        return $this;
    }

    // Accept array|string|object (middleware instance)
    public function middleware(array|string|object $middlewares): self
    {
        if (empty($this->routes)) {
            return $this;
        }
        
        $lastRoute = &$this->routes[count($this->routes) - 1];
        
        // Convert to array if not already
        $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];
        
        $lastRoute['middlewares'] = array_merge($lastRoute['middlewares'], $middlewares);
        
        return $this;
    }

    public function group(array $attributes, callable $callback): void
    {
        $previousMiddlewares = $this->groupMiddlewares;
        
        if (isset($attributes['middleware'])) {
            $middlewares = is_array($attributes['middleware']) 
                ? $attributes['middleware'] 
                : [$attributes['middleware']];
            
            $this->groupMiddlewares = array_merge($this->groupMiddlewares, $middlewares);
        }
        
        $callback($this);
        
        $this->groupMiddlewares = $previousMiddlewares;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $uri = $request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['uri']);
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                
                // Run middlewares
                foreach ($route['middlewares'] as $middleware) {
                    // Handle both string class names and instances
                    if (is_string($middleware)) {
                        $middlewareInstance = new $middleware();
                    } else {
                        $middlewareInstance = $middleware;
                    }
                    
                    $result = $middlewareInstance->handle($request);
                    
                    if ($result instanceof Response) {
                        return $result;
                    }
                }

                // Execute action
                if (is_callable($route['action'])) {
                    // FIX: Pass Request as first argument, then route params
                    $response = call_user_func_array($route['action'], array_merge([$request], $matches));
                } else {
                    [$controller, $method] = $route['action'];
                    $controllerInstance = new $controller();
                    
                    // FIX: Pass Request as first argument, then route params
                    $response = call_user_func_array(
                        [$controllerInstance, $method], 
                        array_merge([$request], $matches)
                    );
                }

                if (is_string($response)) {
                    return Response::make()->setContent($response);
                }

                return $response;
            }
        }

        return Response::make()->setStatusCode(404)->setContent('404 Not Found');
    }

    private function convertToRegex(string $uri): string
    {
        $uri = preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $uri);
        return '#^' . $uri . '$#';
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}