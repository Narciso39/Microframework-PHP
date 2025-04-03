<?php
abstract class Middleware {
    abstract public function handle(Request $request, Response $response);
}