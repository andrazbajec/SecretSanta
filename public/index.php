<?php
$request = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

require __DIR__ . '/views/header.html';

switch ($requestMethod) {
    case 'GET':
        switch ($request) {
            case '/' :
                require __DIR__ . '/views/home.phtml';
                break;
            case '/register' :
                require __DIR__ . '/views/register.phtml';
                break;
            case '/login' :
                require __DIR__ . '/views/login.phtml';
                break;
            case '/joinRoom':
                require __DIR__ . '/views/joinRoom.phtml';
                break;
            case '/createRoom':
                require __DIR__ . '/views/createRoom.phtml';
                break;
            case '/room':
                require __DIR__ . '/views/room.phtml';
                break;
            case '/roomOptions':
                require __DIR__ . '/views/room_options.phtml';
                break;
            case '/user':
                require __DIR__ . '/views/user.phtml';
                break;
            default:
                http_response_code(404);
                require __DIR__ . '/views/404.html';
                break;
        }
        break;
}
require __DIR__ . '/views/footer.html';