<?php

// load config and startup file
require 'config.php';
require SYSTEM . 'Startup.php';

// using
use Router\Router;

// create object of request and response class
$request = new Http\Request();
$response = new Http\Response();
$pagination = new Pagination\Pagination();


// Set CORS headers
$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
$response->setHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $response->sendStatus(200);
    $response->render();
    exit();
}

// set default header
$response->setHeader('Content-Type: application/json; charset=UTF-8');

// set request url and method
$router = new Router('/' . strtolower($request->getUrl()), $request->getMethod());

// check install 
$file = SCRIPT . 'SQL/library-sql.sql';
if (file_exists($file) && strtolower($request->getUrl()) !== 'install') {
    exit('Your System Not Installed please try with /install');
}

// import router file
require 'Router/Router.php';

// Router Run Request
$router->run();

// Response Render Content
$response->render();
