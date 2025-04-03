<?php

namespace App\Controllers;

use App\Models\UserModel;
use Request;
use Response;

class UserController
{

    public function index(Request $request, Response $response)
    {
        try {
            $users = UserModel::getAll();

            if (empty($users)) {
                return $response->status(404)->json([
                    'error' => 'No users found',
                    'data' => []
                ]);
            }

            $response->json(['data' => $users]);
        } catch (\Exception $e) {
            $response->status(500)->json([
                'error' => 'Failed to retrieve users',
                'details' => $e->getMessage()
            ]);
        }
    }


    public function show(Request $request, Response $response)
    {
        try {
            $id = $request->getParams()['id'] ?? null;

            if (!$id) {
                return $response->status(400)->json(['error' => 'User ID is required']);
            }

            if (!is_numeric($id)) {
                return $response->status(400)->json(['error' => 'Invalid user ID']);
            }

            $user = UserModel::find($id);

            if (!$user) {
                return $response->status(404)->json(['error' => 'User not found']);
            }

            $response->json($user);
        } catch (\Exception $e) {
            $response->status(500)->json([
                'error' => 'Failed to retrieve user',
                'details' => $e->getMessage()
            ]);
        }
    }


    public function store(Request $request, Response $response)
    {
        $data = $request->all();

        if (empty($data['name']) || empty($data['email'])) {
            return $response->status(400)->json(['error' => 'Name and email are required']);
        }

        $id = UserModel::create($data);
        $response->status(201)->json([
            'message' => 'Usuário criado com sucesso',
            'id' => $id
        ]);
    }
}
