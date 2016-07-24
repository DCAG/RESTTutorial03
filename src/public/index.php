<?php

/*
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}
*/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "user";
$config['db']['pass']   = "password";
$config['db']['dbname'] = "exampleapp";

require __DIR__ . '/../vendor/autoload.php';

//go to ../classes/ all my classes are there
spl_autoload_register(function ($classname) {
    require ("../classes/" . $classname . ".php");
});

$app = new \Slim\App(["settings"=> $config]);

//for dependency injection
$container = $app->getContainer();
//1st service is Monolog (a php logger)
$container['logger'] = function($c){
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $formatter = new \Monolog\Formatter\LineFormatter("[%datetime%][%channel%][%level_name%] : %message% %context% \r\n", 'Y-m-d H:i:s');
    $file_handler->setFormatter($formatter);
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function($c){
  return new \MongoDB\Driver\Manager("mongodb://localhost:27017"); // connect
};

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    $this->logger->addWarning("Something interesting has happened");
    //$this->db->foo; // get the database named "foo"
    var_dump($this->db);
    $response->getBody()->write("<br />Hello");
    return $response;
});

$app->run();