<?php
$request = $_SERVER['REQUEST_URI'];

require __DIR__ . '/views/header.html';

switch ($request) {
    case '/' :
        require __DIR__ . '/views/home.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}

require __DIR__ . '/views/footer.html';