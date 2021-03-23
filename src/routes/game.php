<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/test', function(Request $request, Response $response){
    $param1 = $request->getParam('param1');
    $param2 = $request->getParam('param2');

    echo '[{ "param1" : '. $param1 .' , "param2": '. $param2. ' }]';
});

$app->get('/api/leaderboard', function(Request $request, Response $response){
    $tsql = "SELECT id, name, date, score, tutgrp, tutid, comment, studid From leaderboard WHERE score != ''";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/tut/student', function(Request $request, Response $response){
    $tutid = $request->getParam('tutid');

    $tsql = "SELECT * From leaderboard WHERE tutid = '$tutid'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});

$app->post('/api/student/submission', function(Request $request, Response $response){
    $id = $request->getParam('id');

    $tsql = "SELECT * From leaderboard WHERE id = '$id'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});


$app->post('/api/submission', function(Request $request, Response $response){
    $name = $request->getParam('name');
    $image = $request->getParam('image');
    $date = $request->getParam('date');
    $tutgrp = $request->getParam('tutgrp');
    $tutid = $request->getParam('tutid');
    $studid = $request->getParam('studid');
    $score = "No score";
    $comment = "No comment";

    $sql = "INSERT INTO leaderboard (name, image, date, score, tutgrp, tutid, comment, studid) VALUES
    (:name,:image,:date,:score,:tutgrp,:tutid,:comment,:studid)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image',  $image);
        $stmt->bindParam(':date',   $date);
        $stmt->bindParam(':score',   $score);
        $stmt->bindParam(':tutgrp',   $tutgrp);
        $stmt->bindParam(':tutid',   $tutid);
        $stmt->bindParam(':comment',   $comment);
        $stmt->bindParam(':studid',   $studid);

        $stmt->execute();

        echo '[{"response": "Student Submission is Successful"}]';

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->post('/api/marking/update', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $score = $request->getParam('score');
    $comment = $request->getParam('comment');

    $tsql = "UPDATE leaderboard SET score = '$score', comment = '$comment' WHERE id = '$id'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();

    echo '[{"response": "Update Scores Successfully"}]';
    
});

$app->post('/api/student/completed/tut', function(Request $request, Response $response){
    $studid = $request->getParam('studid');

    $tsql = "Select id, tutname, score, date, name, leaderboard.tutid from leaderboard, tutorial WHERE leaderboard.tutid = tutorial.tutid AND leaderboard.studid = '$studid' AND score != '' GROUP BY tutname ORDER BY score DESC";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});

$app->post('/api/start/compete', function(Request $request, Response $response){
    $requestorid = $request->getParam('requestorid');
    $competitorid = $request->getParam('competitorid');
    $leaderboardid = $request->getParam('leaderboardid');
    $tutid = $request->getParam('tutid');
    $msg = '';

    $sql = "INSERT INTO competiton (requestorid, competitorid, leaderboardid, competitormsg, tutid) VALUES
    (:requestorid,:competitorid,:leaderboardid,:msg, :tutid)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':requestorid', $requestorid);
        $stmt->bindParam(':competitorid',  $competitorid);
        $stmt->bindParam(':leaderboardid',   $leaderboardid);
        $stmt->bindParam(':msg',   $msg);
        $stmt->bindParam(':tutid',   $tutid);

        $stmt->execute();

        echo '[{"response": "Compete Request is Successful"}]';

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
    
});

$app->post('/api/get/challengers', function(Request $request, Response $response){
    $competitorid = $request->getParam("competitorid");

    $tsql = "Select userlogin.id, userlogin.name, leaderboard.score, tutorial.tutname, competiton.competitionid from competiton, userlogin, tutorial, leaderboard Where competiton.competitorid = '$competitorid' AND competiton.requestorid = userlogin.id AND competiton.tutid = tutorial.tutid AND competiton.leaderboardid = leaderboard.id";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});

$app->post('/api/get/competitors', function(Request $request, Response $response){
    $requestorid = $request->getParam("requestorid");
    $tsql = "Select userlogin.id, userlogin.name, leaderboard.score, tutorial.tutname, competiton.competitionid from competiton, userlogin, tutorial, leaderboard Where competiton.requestorid = '$requestorid' AND competiton.competitorid = userlogin.id AND competiton.tutid = tutorial.tutid AND competiton.leaderboardid = leaderboard.id";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});

$app->post('/api/send/comment', function(Request $request, Response $response){
    $compid = $request->getParam('compid');
    $comment = $request->getParam('comment');

    $tsql = "UPDATE competiton SET comment = '$comment' WHERE competitionid = '$compid'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();

    echo '[{"response": "Update Comment Successfully"}]';
    
});

?>