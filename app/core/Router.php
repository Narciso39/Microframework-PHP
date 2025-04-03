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
            'handler' => $handler,
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

        foreach (self::$routes as $route) {
            $pattern = "#^" . preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $route['path']) . "$#";

            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setParams($params);


                foreach (self::$middlewares as $mw) {
                    if (in_array($route['path'], $mw['routes'])) {
                        $middleware = new $mw['middleware']();
                        if (!$middleware->handle($request, $response)) {
                            return;
                        }
                    }
                }


                foreach ($route['middlewares'] as $mw) {
                    $middleware = new $mw();
                    if (!$middleware->handle($request, $response)) {
                        return; // Middleware bloqueou a requisição
                    }
                }

                $handlerParts = explode('@', $route['handler']);
                $controllerName = $handlerParts[0];
                $methodName = $handlerParts[1] ?? 'index';

                $controller = new $controllerName();
                $controller->$methodName($request, $response);
                return;
            }
        }


        $response->status(404)->json(['error' => 'Route not found']);
    }
}
