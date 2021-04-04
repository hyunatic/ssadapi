<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

function sqlGetConnection()
{    
    $servername = 'asedb.mysql.database.azure.com';
    $username = 'aseadmin@asedb';
    $password = 'Pa$$w0rd';
    $dbname = 'ssad';
    
    try{
    $conn = new mysqli($servername,$username,$password,$dbname);
    }
    catch(Exception $e)
    {
        die(print_r($e->getMessage() ) );
    }
	return $conn;
}	
$app->post('/api/show/unity/tutquest', function(Request $request, Response $response){
    $tutid = $request->getParam('tutid');

    $tsql = "SELECT questid, question, tutgrp, tutid, solution, hint, level, section From quest WHERE tutid = $tutid";

    $conn = sqlGetConnection();
    $result = mysqli_query($conn,$tsql);
    $totalrecord = mysqli_num_rows($result);
    $counter = 0;

    // | in the back the JSON is special for this function only
    $output = '[';
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
           if(++$counter == $totalrecord){
                $output .= '{'. '"questid":'. '"'.$row['questid'] . '"' .',"question":'. '"'.$row['question'] . '"' .',"level":'. '"'.$row['level'] . '"' .',"section":'. '"'.$row['section'] . '"' .',"tutgrp":'. '"'.$row['tutgrp'] . '"' .',"tutid":'. '"'.$row['tutid'] . '"' . ',"solution":'. $row['solution'] .',"hint":' .$row['hint'] .'}|]';
           }
           else{
                $output .= '{'. '"questid":'. '"'.$row['questid'] . '"' .',"question":'. '"'.$row['question'] . '"' .',"level":'. '"'.$row['level'] . '"' .',"section":'. '"'.$row['section'] . '"' .',"tutgrp":'. '"'.$row['tutgrp'] . '"' .',"tutid":'. '"'.$row['tutid'] . '"' . ',"solution":' .$row['solution'] .',"hint":' .$row['hint'] .'}|,';
           }
        }
    }
    echo $output;
});

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

?>