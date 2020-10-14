<?php

namespace SecretSanta;

use Controllers\Authentication;
use Controllers\Database;
use Controllers\Migrations;

require __DIR__ . '/vendor/autoload.php';

$request = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$auth = new Authentication();
$db = new Database();

function display404(): void
{
    require __DIR__ . '/Views/404.html';
    http_response_code(404);
}

/**
 * @param string $page
 */
function displayResponse(string $page): void
{
    require __DIR__ . '/Views/header.html';
    require __DIR__ . $page;
    require __DIR__ . '/Views/footer.html';
}

switch ($requestMethod) {
    case 'GET':
        switch ($request) {
            case '/' :
                displayResponse('/Views/register.phtml');
                break;
            case '/migrations':
                displayResponse('/Views/migrations.phtml');
                break;
            default:
                display404();
                break;
        }
        break;
    case 'POST':
        switch ($request) {
            case '/migration':
                $migrationID = $_POST['migrationID'] ?? null;
                $up = $_POST['up'] ?? null;
                if ($migrationID != null && $up != null) {
                    $migrations = new Migrations();
                    $sql = $migrations->migrations[$migrationID][sprintf('SQL_%s', $up)];
                    $db->connect();
                    try {
                        $db->executeRawQuery($sql);
                        try {
                            $sql = $up == 'up' ?
                                sprintf('insert into migrations(description, status) values(%s, 1)', $migrationID) :
                                sprintf('delete from migrations where description = %s', $migrationID);
                            $db->executeRawQuery($sql);
                        } catch (\PDOException $e) {
                        }
                        echo json_encode(['status' => 'success']);
                    } catch (\PDOException $e) {
                        return;
                    }
                }
                break;
            case '/get-migrations':
                $db->connect();
                $sql = 'select description from migrations;';
                $data = $db->executeRawQuery($sql);
                echo json_encode(['status' => 'success', 'data' => $data]);
                break;
            case '/register':
                $name = $_POST['name'] ?? null;
                $username = $_POST['username'] ?? null;
                $password = $_POST['password'] ?? null;
                $auth->register($name, $username, $password);
                break;
            default:
                display404();
                break;
        }
        break;
    default:
        display404();
        break;
}

