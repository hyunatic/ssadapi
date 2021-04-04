<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/add/tutorial', function(Request $request, Response $response){

    $tutname = $request->getParam('tutname');
    $tutgrp = $request->getParam('tutgrp');
    $createdby = $request->getParam('createdby');
    $coins = $request->getParam('coins');

    $sql = "INSERT INTO tutorial (tutname, tutgrp, createdby,coins) VALUES
    (:tutname,:tutgrp,:createdby,:coins)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':tutname', $tutname);
        $stmt->bindParam(':tutgrp',  $tutgrp);
        $stmt->bindParam(':createdby',   $createdby);
        $stmt->bindParam(':coins',   $coins);

        $stmt->execute();

        $tsql1 = "SELECT * From tutorial ORDER BY tutid DESC LIMIT 1";
        $stmt = $db->prepare($tsql1);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->execute();
       
        echo json_encode($results);

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->post('/api/delete/tutorial', function(Request $request, Response $response){
    $tutid = $request->getParam('tutid');
    $sql = "DELETE from tutorial WHERE tutid = :tutid";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':tutid', $tutid);
        $stmt->execute();

        echo '[{"response": "Tutorial Deleted"}]';

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->get('/api/tutlist', function(Request $request, Response $response){
    $tsql = "SELECT * From tutorial";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/user/tutlist', function(Request $request, Response $response){
    $tutgrp = $request->getParam('tutgrp');
    $tsql = "SELECT * From tutorial WHERE tutgrp = '$tutgrp' AND createdby LIKE 'Prof%'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/prof/tutlist', function(Request $request, Response $response){
    $name = $request->getParam('name');
    $tutgrp = $request->getParam('tutgrp');
    $tsql = "SELECT * From tutorial WHERE tutgrp = '$tutgrp' AND createdby = '$name'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/student/tutlist', function(Request $request, Response $response){
    $createdby = $request->getParam('createdby');
    $tsql = "SELECT * From tutorial WHERE createdby = '$createdby'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});


?>