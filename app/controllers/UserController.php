<?php

namespace App\Controllers;

use App\Models\UserModel;
use Request;
use Response;


class UserController
{
    public function index(Request $request, Response $response)
    {
        $users = UserModel::getAll();
        $response->json(['data' => $users]);
    }

    public function show(Request $request, Response $response)
    {
        $id = $request->getParams()['id'];
        $user = UserModel::find($id);

        if ($user) {
            $response->json($user);
        } else {
            $response->status(404)->json(['error' => 'User not found']);
        }
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->all();

        if (!isset($data['name']) || !isset($data['email'])) {
            $response->status(400)->json(['error' => 'Name and email are required']);
            return;
        }

        $id = UserModel::create($data);
        $response->status(201)->json([
            'message' => 'Usuário criado com sucesso',
            'id' => $id
        ]);
    }
}
