<?php
header('Content-Type: application/json');


require_once __DIR__ . '/../app/core/Middleware.php';
require_once __DIR__ . '/../app/core/Request.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/middlewares/SecurityMiddleware.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__.'/../app/controllers/UserController.php';
require_once __DIR__.'/../app/core/Router.php';


$config = require __DIR__ . '/../config/app.php';
$dbConfig = require __DIR__ . '/../config/database.php';

Database::init($dbConfig);


// Router::add('GET', '/', function (Request $request, Response $response) {
//     $response->json([
//         'status' => 'online',
//         'message' => 'API funcionando',
//         'endpoints' => [
//             '/user' => 'POST',
//             '/user/{id}' => 'GET'
//         ]
//     ]);
// });


require __DIR__ . '/../app/routes/api.php';


Router::execute();
