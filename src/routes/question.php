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
        $stmt->bindParam(':solution',   $solution);
        $stmt->bindParam(':level',   $level);
        $stmt->bindParam(':section',   $section);
        $stmt->bindParam(':hint',   $hint);

        $stmt->execute();
       
        $tsql1 = "SELECT * From quest ORDER BY tutid DESC LIMIT 1";

        $conn = sqlGetConnection();
        $result = mysqli_query($conn,$tsql1);
        $totalrecord = mysqli_num_rows($result);
        $counter = 0;

        // | in the back the JSON is special for this function only
        $output = '[';
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
            if(++$counter == $totalrecord){
                    $output .= '{'. '"questid":'. '"'.$row['questid'] . '"' .',"question":'. '"'.$row['question'] . '"' .',"level":'. '"'.$row['level'] . '"' .',"section":'. '"'.$row['section'] . '"' .',"tutgrp":'. '"'.$row['tutgrp'] . '"' .',"tutid":'. '"'.$row['tutid'] . '"' . ',"solution":'. $row['solution'] .',"hint":' .$row['hint'] .'}]';
            }
            else{
                    $output .= '{'. '"questid":'. '"'.$row['questid'] . '"' .',"question":'. '"'.$row['question'] . '"' .',"level":'. '"'.$row['level'] . '"' .',"section":'. '"'.$row['section'] . '"' .',"tutgrp":'. '"'.$row['tutgrp'] . '"' .',"tutid":'. '"'.$row['tutid'] . '"' . ',"solution":' .$row['solution'] .',"hint":' .$row['hint'] .'},';
            }
            }
        }
        echo $output;
        } 
        catch(PDOException $e){
            echo '[{"error": {"text": '.$e->getMessage().'}]';
        }
});



?>