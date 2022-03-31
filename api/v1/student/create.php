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
include_once '../../models/Student.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate student object
$student = new Student($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

if (empty($data->lastname))
{
    die(json_encode(array('code'=> $statuscode->bad_request, 'message'=> 'Bad Request')));
}

foreach($data as $key => $value) {
    if (!empty($value)) $student->$key = $value;
}

//create student
if($student->create()) {
    //student
    echo json_encode(
        array(
            'code'=> $statuscode->created,
            'message'=> 'student created'
            )
    );
} else {
    //no student
    echo json_encode(
        array(
            'code'=> $statuscode->not_modified,
            'message'=> 'student not created'
            )
    );
}