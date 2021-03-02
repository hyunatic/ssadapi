<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/add/tutorial', function(Request $request, Response $response){

    $tutname = $request->getParam('tutname');
    $tutgrp = $request->getParam('tutgrp');
    $createdby = $request->getParam('createdby');
    $difficulty = $request->getParam('difficulty');
    $coins = $request->getParam('coins');

    $sql = "INSERT INTO tutorial (tutname, tutgrp, createdby,difficulty,coins) VALUES
    (:tutname,:tutgrp,:createdby,:difficulty,:coins)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':tutname', $tutname);
        $stmt->bindParam(':tutgrp',  $tutgrp);
        $stmt->bindParam(':createdby',   $createdby);
        $stmt->bindParam(':difficulty',   $difficulty);
        $stmt->bindParam(':coins',   $coins);


        $stmt->execute();

        echo '[{"response": "Tutorial Registered"}]';

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

$app->post('/api/add/tutquest', function(Request $request, Response $response){

    $tutname = $request->getParam('tutname');
    $tutgrp = $request->getParam('tutgrp');
    $createdby = $request->getParam('createdby');

    $sql = "INSERT INTO tutorial (tutname, tutgrp, createdby) VALUES
    (:tutname,:tutgrp,:createdby)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':tutname', $tutname);
        $stmt->bindParam(':tutgrp',  $tutgrp);
        $stmt->bindParam(':createdby',   $createdby);


        $stmt->execute();

        echo '[{"response": "Tutorial Question Registered"}]';

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->post('/api/delete/tutquest', function(Request $request, Response $response){
    $questid = $request->getParam('questid');
    $sql = "DELETE from quest WHERE questid = :questid";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':questid', $questid);
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

?>