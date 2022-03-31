<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));

//then continue
include_once '../../config/Database.php';
include_once '../../models/Paper.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate Paper object
$Paper = new Paper($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

$Paper->id = $data->id;
//Delete Paper
if($Paper->delete()) {
    echo json_encode(
        array(
            'code'=> $statuscode->ok,
            'message'=> 'Paper deleted'
            )
    );
} else {
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'Paper not found'
            )
    );
}