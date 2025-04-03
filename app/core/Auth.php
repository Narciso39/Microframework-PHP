<?php
class Auth extends Middleware
{
    public function handle(Request $request, Response $response)
    {
        $token = $request->input('token');

        if (!$token) {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);
        }

        if (!$token) {
            $response->status(401)->json(['error' => 'Token not provided']);
            return false;
        }

        try {
            $payload = $this->decodeToken($token);
            $request->setParams(['user_id' => $payload['user_id']]);
            return true;
        } catch (Exception $e) {
            $response->status(401)->json(['error' => 'Invalid token']);
            return false;
        }
    }

    private function decodeToken($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new Exception('Invalid token format');
        }

        $payload = json_decode(base64_decode($parts[1]), true);
        if (!$payload || !isset($payload['user_id'])) {
            throw new Exception('Invalid token payload');
        }

        return $payload;
    }

    public static function generateToken($userId)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['user_id' => $userId, 'exp' => time() + 3600]);

        $base64Header = base64_encode($header);
        $base64Payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", 'your-secret-key');

        return "$base64Header.$base64Payload.$signature";
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
