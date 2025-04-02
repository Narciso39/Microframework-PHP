<?php
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Request.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Middleware.php';
require_once __DIR__ . '/../app/core/Auth.php';


$config = require __DIR__ . '/../config/app.php';


$dbConfig = require __DIR__ . '/../config/database.php';
Database::init($dbConfig);


require __DIR__ . '/../app/routes/api.php';


// Router::execute();
