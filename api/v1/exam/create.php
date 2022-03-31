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
include_once '../../models/Exam.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate exam object
$exam = new Exam($db);

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

if (empty($data->title)) die(json_encode(array('code'=> $statuscode->bad_request, 'message'=> 'Bad Request')));

$exam->title = $data->title;

//create exam
if($exam->create()) {
    echo json_encode(
        array(
            'code'=> $statuscode->created,
            'message'=> 'exam created'
            )
    );
} else {
    //no exam
    echo json_encode(
        array(
            'code'=> $statuscode->not_modified,
            'message'=> 'exam not created'
            )
    );
}