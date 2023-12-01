<?php
//check HTTP request method

if($_SERVER ['REQUEST_METHOD'] !== 'PUT') {
    header('ALLOW: PUT');
    http_response_code(405);
    echo json_encode(
        array('message'=>'Method not allowed')
    );
    return;
}

//set http response headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');

include_once '../db/Database.php';
include_once '../models/Todo.php';

//instantiate a DB object and connect

$database = new Database();
$dbConnection = $database->connect();
//instantiate todo object
$todo = new Todo($dbConnection);

//Get the HTTP PUT request JSON body
$data = json_decode(file_get_contents('php://input'));
if(!$data || !$data->id || !$data->done) {
    http_response_code(422);;
    echo json_encode(
        array('message'=> 'Error:missing required parameters id and done in the JSON body'));
        return;
    }
    $todo->setId($data->id);
    $todo->setDone($data->done);
    if($todo->update()) {
        echo json_encode(
            array('message'=> 'The todo item was updated'));
    } else {
        echo json_encode(
            array('message' => 'no todo item was updated'
            ));
        }