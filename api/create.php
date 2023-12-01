<?php

// Set common HTTP response headers
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
//THE ABOVE CODE IS SOMEHOW MAKING MY CODE WORK!


// Check HTTP request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('ALLOW: POST');
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed'));
    return;
}



include_once '../db/Database.php';
include_once '../models/Todo.php';

//instantiate a DB object and connect

$database = new Database();
$dbConnection = $database->connect();
//instantiate todo object
$todo = new Todo($dbConnection);
//get teh http post request JSON body
$data = json_decode(file_get_contents('php://input'), true);
//if no task is included in the json body, return an error
if(!$data || !isset($data['task'])) {
    http_response_code(422);
    echo json_encode(
        array('message'=> 'Error missing required parameter task in the JSON body')
    );
    return;
}
//Create a todo item
$todo->setTask($data['task']);
if($todo->create()) {
    echo json_encode(
        array('message'=>'A todo item was created')
    );
}
else{
    echo json_encode(
        array('message'=> 'Error: No todo Item was created')
    );

}