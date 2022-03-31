<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code($statuscode->method_not_allowed);
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
    

//then continue
include_once '../../config/Database.php';
include_once '../../models/ClassAllotment.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate class_allotment object
$class_allotment = new ClassAllotment($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

$class_allotment->session_id = $data->session_id;
$class_allotment->teacher_id = $data->teacher_id;
$class_allotment->class_id = $data->class_id;
$class_allotment->id = $data->id;
$class_allotment->status = 1;

//update class_allotment
if($class_allotment->update()) {
    //class_allotment
    http_response_code($statuscode->ok);
    echo json_encode(
        array(
            'code'=> $statuscode->ok,
            'message'=> 'Class allotment updated'
            )
    );
} else {
    //no class_allotment
    http_response_code($statuscode->not_found);
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'Class allotment not found'
            )
    );
}