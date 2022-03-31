<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
//continue
include_once '../../config/Database.php';
include_once '../../models/Score.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$Score = new Score($db);

//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;
$return['score'] = [];

$Score->student_id = !empty($_GET['student_id']) ? $_GET['student_id'] : -1;

// get existing Scores
$result = $Score->read();
//get row count
$num = $result->rowCount();
if( $num > 0 ) {
    //initialize array
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $return['score'][] =  [
            'id' => $id,
            'student_id' => $student_id,
            'exam_body' => $exam_body,
            'exam_year' => $exam_year,
            'subject' => $subject,
            'score' => $score
        ];
    }
}

die(json_encode($return));