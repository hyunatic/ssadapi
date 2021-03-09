<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/login', function(Request $request, Response $response){
    $email = $request->getParam('email');
    $password = $request->getParam('password');

    $tsql = "SELECT fbid, email, name, usertype, tutgrp From userlogin WHERE email = '$email' AND password = '$password'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});


$app->post('/api/fblogin', function(Request $request, Response $response){
    $fbid = $request->getParam('fbid');

    $tsql = "SELECT fbid, email, name, usertype, tutgrp From userlogin WHERE fbid = '$fbid'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});


$app->post('/api/register', function(Request $request, Response $response){
    $fbid = $request->getParam('fbid');
    $email = $request->getParam('email');
    $password = $request->getParam('password');
    $name = $request->getParam('name');
    $usertype = $request->getParam('usertype');
    $tutgrp = $request->getParam('tutgrp');

    $sql = "INSERT INTO userlogin (fbid,email,password,name,usertype,tutgrp) VALUES
    (:fbid,:email,:password,:name,:usertype,:tutgrp)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':fbid', $fbid);
        $stmt->bindParam(':email',  $email);
        $stmt->bindParam(':password',   $password);
        $stmt->bindParam(':name',  $name);
        $stmt->bindParam(':usertype',    $usertype);
        $stmt->bindParam(':tutgrp',    $tutgrp);


        $stmt->execute();

        echo '[{"response": "User Registered"}]';

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->post('/api/studentinfo', function(Request $request, Response $response){
    $id = $request->getParam('id');

    $tsql = "SELECT fbid, email, name, usertype, tutgrp From userlogin WHERE id = '$id' AND usertype = 'Student'";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    
});


?>