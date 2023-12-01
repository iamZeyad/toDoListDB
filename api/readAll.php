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
$todo = new Todo($dbConnection);

//Get the http GET request for all the todo items
$result = $todo->readAll();
if(!empty($result)) {
    echo json_encode($result);
}
else {
    echo json_encode(array('message'=> 'No todo items were found'));
}