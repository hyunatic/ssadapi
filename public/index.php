<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$config = ['settings' => ['displayErrorDetails' => true]];
$app = new Slim\App($config);

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With,X-Powered-By, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

//Routes
require '../src/routes/auth.php';
require '../src/routes/game.php';
require '../src/routes/tutorial.php';
require '../src/routes/question.php';
require '../src/routes/savedata.php';



$app->run();
?>