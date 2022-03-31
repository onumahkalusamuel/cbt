<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));

//then continue
include_once '../../config/Database.php';
include_once '../../models/Settings.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate Settings object
$Settings = new Settings($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

if (empty($data->setting)) die(json_encode(array('code'=> $statuscode->bad_request, 'message'=> 'Bad Request')));

$Settings->setting = $data->setting;
$Settings->value = $data->value;

//create Settings
if($Settings->create()) {
    echo json_encode(
        array(
            'code'=> $statuscode->created,
            'message'=> 'Settings created'
            )
    );
} else {
    //no Settings
    echo json_encode(
        array(
            'code'=> $statuscode->not_modified,
            'message'=> 'Settings not created'
            )
    );
}