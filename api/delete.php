<?php

// Set common HTTP response headers
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

//check HTTP request method

if($_SERVER ['REQUEST_METHOD'] !== 'DELETE') {
    header('ALLOW: DELETE');
    http_response_code(405);
    echo json_encode(
        array('message'=>'Method not allowed')
    );
    return;
}

//set http response headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');

include_once '../db/Database.php';
include_once '../models/Todo.php';

//instantiate a DB object and connect

$database = new Database();
$dbConnection = $database->connect();
//instantiate todo object
$todo = new Todo($dbConnection);

//Get the HTTP DELETE request JSON body
$data = json_decode(file_get_contents('php://input'));
if(!$data || !$data->id) {
    http_response_code(422);;
    echo json_encode(
        array('message'=> 'Error:missing required parameters id in the JSON body'));
        return;
    }
    //DELETE todo item
    $todo->setId($data->id);
    if($todo->delete()) {
        echo json_encode(
            array('message'=> 'The todo item was deleted'));
    } else {
        echo json_encode(
            array('message' => 'no todo item was deleted'
            ));
        }