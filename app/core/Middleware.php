<?php
namespace Core;

use Request;
use Response;

abstract class Middleware
{
    abstract public function handle(Request $request, Response $response): bool;
}