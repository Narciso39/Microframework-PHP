<?php
class Router
{
    private static $routes = [];
    private static $middlewares = [];

    public static function add($method, $path, $handler, $middlewares = [])
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => is_callable($handler) ? $handler : $handler,
            'middlewares' => $middlewares
        ];
    }

    public static function middleware($middleware, $routes)
    {
        self::$middlewares[] = [
            'middleware' => $middleware,
            'routes' => $routes
        ];
    }

    public static function execute()
    {
        $request = new Request();
        $response = new Response();
        $path = $request->getPath();
        $method = $request->getMethod();

        try {
            foreach (self::$routes as $route) {
                $pattern = "#^" . preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $route['path']) . "$#";

                if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $request->setParams($params);


                    if (!self::processMiddlewares($route, $request, $response)) {
                        return;
                    }


                    $action = self::resolveAction($method, $route['path']);


                    if (is_callable($route['controller'])) {
                        call_user_func($route['controller'], $request, $response);
                    } else {
                        $controller = new $route['controller']();


                        if (!method_exists($controller, $action)) {
                            $response->status(405)->json([
                                'error' => 'Method not allowed',
                                'details' => "Action '$action' not found in controller"
                            ]);
                            return;
                        }

                        $controller->$action($request, $response);
                    }
                    return;
                }
            }

            $response->status(404)->json(['error' => 'Route not found']);
        } catch (Throwable $e) {
            $response->status(500)->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ]);
        }
    }
    private static function processMiddlewares(array $route, Request $request, Response $response): bool
    {

        if (!empty(self::$middlewares)) {
            foreach (self::$middlewares as $mw) {

                if (isset($mw['routes']) && is_array($mw['routes']) && in_array($route['path'], $mw['routes'])) {
                    $middleware = new $mw['middleware']();
                    if (!$middleware->handle($request, $response)) {
                        return false;
                    }
                }
            }
        }


        if (!empty($route['middlewares']) && is_array($route['middlewares'])) {
            foreach ($route['middlewares'] as $mw) {
                $middleware = new $mw();
                if (!$middleware->handle($request, $response)) {
                    return false;
                }
            }
        }

        return true;
    }
    private static function handleController(array $route, Request $request, Response $response)
    {
        $controller = new $route['controller']();


        $action = $route['action'];

        if (!method_exists($controller, $action)) {
            $response->status(405)->json([
                'error' => 'Method not allowed',
                'details' => "Método '$action' não existe no controller"
            ]);
            return;
        }

        $controller->$action($request, $response);
    }
    private static function resolveAction(string $httpMethod, string $path): string
    {
        if ($path === '/auth' && $httpMethod === 'POST') {
            return 'authenticate';
        }
    
        if ($httpMethod === 'GET' && str_contains($path, '{')) {
            return 'show';
        }
    
        return match ($httpMethod) {
            'GET' => 'index',
            'POST' => 'store',
            'PUT' => 'update',
            'DELETE' => 'destroy',
            default => throw new \Exception("HTTP method not supported: $httpMethod")
        };
    }
}
