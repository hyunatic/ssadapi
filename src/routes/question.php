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

function sqlQuery($conn, $sql, $params = [])
{
    $getResults = $conn->prepare($sql);
    $getResults->execute($params);
    $results = $getResults->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}


function sqlExecute($conn, $sql, $params = [])
{
    $getResults = $conn->prepare($sql);
    $getResults->execute($params);
}

$app->post('/api/add/tutquest', function(Request $request, Response $response){

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
        // Get DB Object
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

        $tsql1 = "SELECT * From quest ORDER BY questid DESC LIMIT 1";
        $stmt = $db->prepare($tsql1);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->execute();
       
        echo json_encode($results);

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

$app->post('/api/show/unity/tutquest', function(Request $request, Response $response){
    // $tutid = $request->getParam('tutid');

    // $tsql = "SELECT questid, question, tutgrp, tutid, solution From quest WHERE tutid = $tutid";;

    // $conn = sqlGetConnection();
    // $result = mysqli_query($conn,$tsql);
    // $totalrecord = mysqli_num_rows($result);
    // $counter = 0;

    // $output = '[';
    // if(mysqli_num_rows($result) > 0){
    //     while($row = mysqli_fetch_assoc($result)){
    //        if(++$counter == $totalrecord){
    //            //Last Record
    //             $output .= '{'. '"questid":'. '"'.$row['questid'] . '"' .',"question":'. '"'.$row['question'] . '"' .',"tutgrp":'. '"'.$row['tutgrp'] . '"' .',"tutid":'. '"'.$row['tutid'] . '"' . ',"solution":'. '"'.json_decode($row['solution']) . '"' .'}]';
    //        }
    //        else{
    //             $output .= '{'. '"questid":'. '"'.$row['questid'] . '"' .',"question":'. '"'.$row['question'] . '"' .',"tutgrp":'. '"'.$row['tutgrp'] . '"' .',"tutid":'. '"'.$row['tutid'] . '"' . ',"solution":'. '"'.json_decode($row['solution']) . '"' .'},';
    //        }
    //     }
    // }
    // echo $output;

    $tutid = $request->getParam('tutid');

    $tsql = "SELECT questid, question, tutgrp, tutid, solution From quest WHERE tutid = $tutid";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});


?>