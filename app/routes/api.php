<?php
use App\Controllers\UserController;
use App\Controllers\AuthController;

Router::middleware('SecurityMiddleware', [
    '/user',
    // '/users',
    // '/auth'
]);

Router::add('POST', '/user', 'UserController@store');


Router::add('GET', '/user/{id}', 'UserController@show');
