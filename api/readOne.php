<?php
//check HTTP request method

if($_SERVER ['REQUEST_METHOD'] !== 'GET') {
    header('ALLOW: GET');
    http_response_code(405);
    echo json_encode(
        array('message'=>'Method not allowed')
    );
    return;
}

//set http response headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

include_once '../db/Database.php';
include_once '../models/Todo.php';

//instantiate a DB object and connect

$database = new Database();
$dbConnection = $database->connect();
//instantiate todo object
$bookamrk = new Bookmark($dbConnection);

//Get the http GET request query parameter (e.g. ?id=140)
if(!isset($_GET['id'])){
    http_response_code(422);
    echo json_encode(array('message'=> 'Error missing required query parameter id.'));
    return;
}
//Read todo details
$bookamrk->setId($_GET['id']);
if($bookamrk->readOne()) {
    $result = array(
        'id' => $bookamrk->getId(),
        'title' =>$bookamrk->getTitle(),
        'dateAdded' => $bookamrk->getDateAdded(),
        'URL' => $bookamrk->getURL()
    );
    echo json_encode($result);
} else {
    http_response_code(404);
    echo json_encode(array('message'=> 'Error: no such item'));
}