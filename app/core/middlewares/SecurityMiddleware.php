<?php
namespace Core\Middlewares;

use Core\Middleware;
use Request;
use Response;

class SecurityMiddleware extends Middleware
{
    public function handle(Request $request, Response $response): bool
    {
       
        if (false && $request->getMethod() === 'POST' && !$this->checkCsrfToken($request)) {
            $response->status(403)->json(['error' => 'Invalid CSRF token']);
            return false;
        }

        $response->header('X-Content-Type-Options', 'nosniff')
               ->header('X-Frame-Options', 'DENY')
               ->header('X-XSS-Protection', '1; mode=block');

        return true;
    }

    private function checkCsrfToken($request)
    {
        $token = $request->input('_token') ?? $request->header('X-CSRF-TOKEN');
        return $token === ($_SESSION['csrf_token'] ?? false);
    }
}