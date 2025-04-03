<?php

namespace App\Controllers;

use App\Models\UserModel;
use Core\Auth;
use Request;
use Response;

class AuthController
{
    public function authenticate(Request $request, Response $response)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (!$password) {
            return $response->status(400)->json(['error' => 'senha faltante']);
        }

        $user = UserModel::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $token = Auth::generateToken($user['id']);
            $response->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]
            ]);
        } else {
            $response->status(401)->json([
                'success' => false,
                'error' => 'Credenciais inválidas'
            ]);
        }
    }
}
