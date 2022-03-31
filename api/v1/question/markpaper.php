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
include_once '../../models/Question.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate Paper object
$Paper = new Paper($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

$Paper->exam_id = $data->exam_id;
$Paper->subject_id = $data->subject_id;
$Paper->class_id = $data->class_id;

if( empty($Paper->exam_id) || empty($Paper->subject_id) || empty($Paper->class_id) ) 
    die(json_encode(array('code'=> $statuscode->bad_request, 'message'=> 'Bad Request')));
    
//create Paper
if($Paper->create()) {
    echo json_encode(
        array(
            'code'=> $statuscode->created,
            'message'=> 'Paper created'
            )
    );
} else {
    //no Paper
    echo json_encode(
        array(
            'code'=> $statuscode->not_modified,
            'message'=> 'Paper not created'
            )
    );
}