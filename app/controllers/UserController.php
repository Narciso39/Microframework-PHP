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
                    'error' => 'sem usuarios no sistema',
                    'data' => []
                ]);
            }

            $response->json(['data' => $users]);
        } catch (\Exception $e) {
            $response->status(500)->json([
                'error' => 'falha ao retornar usuarios',
                'details' => $e->getMessage()
            ]);
        }
    }


    public function show(Request $request, Response $response)
    {
        try {
            $id = $request->getParams()['id'] ?? null;

            if (!$id) {
                return $response->status(400)->json(['error' => 'id de usuario faltante']);
            }

            if (!is_numeric($id)) {
                return $response->status(400)->json(['error' => 'id de usuario invalida']);
            }

            $user = UserModel::find($id);

            if (!$user) {
                return $response->status(404)->json(['error' => 'usuario nao existe']);
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

        if (empty($data['name']) || empty($data['email'] ) || empty($data['password'])) {
            return $response->status(400)->json(['error' => 'dados faltantes ou invalidos']);
        }

        $id = UserModel::create($data);
        $response->status(201)->json([
            'message' => 'Usuário criado com sucesso',
            'id' => $id
        ]);
    }
}
