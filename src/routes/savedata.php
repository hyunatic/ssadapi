<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/get/savedata', function(Request $request, Response $response){
    $userid = $request->getParam('userid');

    $tsql = "SELECT * From saveddata WHERE userid = $userid";

    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
});

$app->post('/api/insert/savedata', function(Request $request, Response $response){
    $userid = $request->getParam('userid');
    $level = $request->getParam('level');
    $section = $request->getParam('section');
    $tutid = $request->getParam('tutid');
    $threshold = $request->getParam('threshold');
    

    $sql = "INSERT INTO saveddata (userid, level, section,tutid, threshold) VALUES
    (:userid, :level, :section, :tutid, :threshold)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':userid',  $userid);
        $stmt->bindParam(':level',   $level);
        $stmt->bindParam(':section',   $section);
        $stmt->bindParam(':tutid',   $tutid);
        $stmt->bindParam(':threshold',  $threshold);

        $stmt->execute();

        $tsql1 = "SELECT * From saveddata WHERE userid = '$userid' AND tutid = '$tutid' ORDER BY saveid DESC LIMIT 1";
        $stmt = $db->prepare($tsql1);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->execute();
       
        echo json_encode($results);

    } catch(PDOException $e){
        echo '[{"error": {"text": '.$e->getMessage().'}]';
    }
});

$app->post('/api/update/savedata', function(Request $request, Response $response){
    $userid = $request->getParam('userid');
    $level = $request->getParam('level');
    $section = $request->getParam('section');
    $tutid = $request->getParam('tutid');
    $threshold = $request->getParam('threshold');

    $tsql = "UPDATE saveddata SET level = '$level', section = '$section', threshold = '$threshold' WHERE userid = '$userid' AND tutid = '$tutid'";
       
    $db = new db();
    // Connect
    $db = $db->connect();
    $stmt = $db->prepare($tsql);
    $stmt->execute();

    echo '[{"response": "Update Saved Data Successfully"}]';

});


?>