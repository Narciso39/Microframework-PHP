<?php


use App\Controllers\AuthController;
use App\Controllers\UserController;
use Core\Middlewares\SecurityMiddleware;
Router::middleware(SecurityMiddleware::class, [
    '/user',
    '/user/{id}',
    '/users'
]);

// Router::add('GET', '/users', UserController::class, 'getAllUsers');
// Router::add('GET', '/users/{id}', UserController::class, 'getUserById');
// Router::add('POST', '/users', UserController::class, 'createUser');
// Router::add('GET', '/users', UserController::class, 'get');  
// Router::add('GET', '/user/{id}', UserController::class, 'show');
// Router::add('POST', '/user', UserController::class, 'post');   
Router::add('GET', '/users', UserController::class); 
Router::add('GET', '/user/{id}', UserController::class);
// Router::add('GET', '/', function (Request $request, Response $response) {
//     $response->json([
//         'status' => 'online',
//         'message' => 'API funcionando',
//         'endpoints' => [
//             '/user' => 'GET/POST',
//             '/user/{id}' => 'GET'
//         ]
//     ]);
// });
