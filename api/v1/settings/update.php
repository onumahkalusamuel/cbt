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
include_once '../../models/Exam.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate exam object
$exam = new Exam($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

$exam->student_id = $data->student_id;
$exam->session_id = $data->session_id;
$exam->term_id = $data->term_id;
$exam->class_id = $data->class_id;
$exam->subject_id = $data->subject_id;
$exam->value = $data->value;
$exam->id = $data->id;
@$exam->status = $data->status;

//update exam
if($exam->update()) {
    //exam
    http_response_code($statuscode->ok);
    echo json_encode(
        array(
            'code'=> $statuscode->ok,
            'message'=> 'exam updated'
            )
    );
} else {
    //no exam
    http_response_code($statuscode->not_found);
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'exam not found'
            )
    );
}