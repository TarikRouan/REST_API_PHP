<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');


$parts = explode("/", $_SERVER["REQUEST_URI"]);

// print_r($parts);

if ($parts[1] != "php_rest_api") {
    http_response_code(404);
    exit;
}

$id = $parts[2] ?? null;

$database = new Database("localhost", "rest_api_php", "root", "");

// $database->getConnection();
$gateWay = new MovieGateWay($database);

// var_dump($id);
$controller = new Controller($gateWay);


// echo $_SERVER["REQUEST_METHOD"];

// exit(0);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
