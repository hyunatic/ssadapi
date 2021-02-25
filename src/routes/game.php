<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/test', function(Request $request, Response $response){
    $param1 = $request->getParam('param1');
    $param2 = $request->getParam('param2');

    echo "This API routes accept 2 params and return what user has posted \n";
    echo '[{ "param1" : '. $param1 .' , "param2": '. $param2. ' }]';
});

?>