<?php

header('Content-Type: application/json');


require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Request.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Database.php';


$config = require __DIR__ . '/../config/app.php';
$dbConfig = require __DIR__ . '/../config/database.php';


Database::init($dbConfig);


Router::add('GET', '/', function () {
    echo json_encode([
        'status' => 'online',
        'message' => 'API funcionando',
        'timestamp' => time()
    ]);
});


require __DIR__ . '/../app/routes/api.php';


Router::execute();
