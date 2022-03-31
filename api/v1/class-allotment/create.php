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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code($statuscode->method_not_allowed);
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}

//then continue
include_once '../../config/Database.php';
include_once '../../models/ClassAllotment.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate class object
$class_allotment = new ClassAllotment($db);


//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

if (empty($data->session_id))
{
    http_response_code($statuscode->bad_request);
    die(json_encode(array('code'=> $statuscode->bad_request, 'message'=> 'Bad Request')));
}

$class_allotment->session_id = $data->session_id;
$class_allotment->teacher_id = $data->teacher_id;
$class_allotment->class_id = $data->class_id;

//create subject
if($class_allotment->create()) {
    //subject
    http_response_code($statuscode->created);
    echo json_encode(
        array(
            'code'=> $statuscode->created,
            'message'=> 'class allotment created'
            )
    );
} else {
    //no class
    http_response_code($statuscode->not_modified);
    echo json_encode(
        array(
            'code'=> $statuscode->not_modified,
            'message'=> 'class allotment not created'
            )
    );
}