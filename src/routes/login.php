<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/login', function(Request $request, Response $response){
    $email = $request->getParam('email');
    $password = $request->getParam('password');

    $tsql = "SELECT id From userlogin WHERE email = '$email' AND password = '$password'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '[{ "userExists" : '. count($results) .' }]';
    
});


$app->post('/api/fblogin', function(Request $request, Response $response){
    $fbid = $request->getParam('fbid');

    $tsql = "SELECT fbid From userlogin WHERE fbid = '$fbid'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '[{ "userExists" : '. count($results) .' }]';
});


$app->post('/api/numbergame/add', function(Request $request, Response $response){
    $age = $request->getParam('age');
    $time = $request->getParam('time');
    $score = $request->getParam('score');
    $picture = $request->getParam('picture');
    $mode = $request->getParam('mode');

    $sql = "INSERT INTO numbergame (age,time,score,picture,mode) VALUES
    (:age,:time,:score,:picture,:mode)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':time',  $time);
        $stmt->bindParam(':score',   $score);
        $stmt->bindParam(':picture',  $picture);
        $stmt->bindParam(':mode',    $mode);


        $stmt->execute();

        echo '{"notice": }';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});



?>