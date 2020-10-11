<?php
$request = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

require __DIR__ . '/views/header.html';

switch ($requestMethod) {
    case 'GET':
        switch ($request) {
            case '/' :
                require __DIR__ . '/views/register.phtml';
                break;
            default:
                http_response_code(404);
                require __DIR__ . '/views/404.html';
                break;
        }
        break;
}
require __DIR__ . '/views/footer.html';