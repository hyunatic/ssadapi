<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/test', function(Request $request, Response $response){
    $param1 = $request->getParam('param1');
    $param2 = $request->getParam('param2');

    echo '[{ "param1" : '. $param1 .' , "param2": '. $param2. ' }]';
});

$app->get('/api/leaderboard', function(Request $request, Response $response){
    $tsql = "SELECT * From leaderboard";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/tut/student', function(Request $request, Response $response){
    $tutgrp = $request->getParam('tutgrp');

    $tsql = "SELECT * From leaderboard WHERE tutgrp = '$tutgrp'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});

?>