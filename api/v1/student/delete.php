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
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code($statuscode->method_not_allowed);
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}

//then continue
include_once '../../config/Database.php';
include_once '../../models/Student.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate student object
$student = new Student($db);


//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

$student->id = $data->id;

//Delete student

if($student->delete()) {
    //no subject
    http_response_code($statuscode->ok);
    echo json_encode(
        array(
            'code'=> $statuscode->ok,
            'message'=> 'student deleted'
            )
    );
} else {
    //no subject
    http_response_code($statuscode->not_found);
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'student not found'
            )
    );
}