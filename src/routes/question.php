<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->post('/api/add/tutquest', function(Request $request, Response $response){
    $tutid = $request->getParam('tutid');
    $tutgrp = $request->getParam('tutgrp');
    $question = $request->getParam('question');
    $solution = $request->getParam('solution');
    $level = $request->getParam('level');
    $section = $request->getParam('section');
    $hint = $request->getParam('hint');

    $cleansol = json_encode($solution);
    $cleanhint = json_encode($hint);

    $sql = "INSERT INTO quest (question, tutgrp, tutid, solution,level,section, hint) VALUES
    (:question,:tutgrp,:tutid, :solution, :level, :section, :hint)";

    try{
        //Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':tutgrp',  $tutgrp);
        $stmt->bindParam(':tutid',   $tutid);
        $stmt->bindParam(':solution',   $cleansol);
        $stmt->bindParam(':level',   $level);
        $stmt->bindParam(':section',   $section);
        $stmt->bindParam(':hint',   $cleanhint);

        $stmt->execute();

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

        echo '[{"response": "Tutorial Question Deleted"}]';

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->post('/api/show/tutquest', function(Request $request, Response $response){
    $tutid = $request->getParam('tutid');

    $tsql = "SELECT * From quest WHERE tutid = $tutid";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/add/web/tutquest', function(Request $request, Response $response){
    $tutid = $request->getParam('tutid');
    $tutgrp = $request->getParam('tutgrp');
    $question = $request->getParam('question');
    $solution = $request->getParam('solution');
    $level = $request->getParam('level');
    $section = $request->getParam('section');
    $hint = $request->getParam('hint');

    $cleansol = json_encode($solution);
    $cleanhint = json_encode($hint);

    $sql = "INSERT INTO quest (question, tutgrp, tutid, solution,level,section, hint) VALUES
    (:question,:tutgrp,:tutid, :solution, :level, :section, :hint)";

    try{
        //Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':tutgrp',  $tutgrp);
        $stmt->bindParam(':tutid',   $tutid);
        $stmt->bindParam(':solution',   $cleansol);
        $stmt->bindParam(':level',   $level);
        $stmt->bindParam(':section',   $section);
        $stmt->bindParam(':hint',   $cleanhint);

        $stmt->execute();
       
        $tsql1 = "SELECT * From quest ORDER BY tutid DESC LIMIT 1";
        $stmt = $db->prepare($tsql1);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->execute();
       
        echo json_encode($results);

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});



?>