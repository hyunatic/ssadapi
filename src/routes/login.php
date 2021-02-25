<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/fblogin', function(Request $request, Response $response){
    echo '{"notice": {"fb": "Logged in"}';
});


$app->get('/api/fblcallback', function(Request $request, Response $response){
  
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

        echo '{"notice": {"text": "Score Added"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});



?>