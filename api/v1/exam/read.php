<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');


//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
    

//continue
include_once '../../config/Database.php';
include_once '../../models/Exam.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate Exam object
$exam = new Exam($db);

//check if there are ids attached
$exam->id = isset($_GET['id']) ? (int) $_GET['id'] : null;

//exam query
$result = $exam->read();

//get row count
$num = $result->rowCount();

//check if any subject

if( $num > 0 ) {
    //initialize array
    $exam_arr = array();
    $exam_arr['code'] = $statuscode->ok;
    $exam_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $exam_item = array();

        foreach($row as $key => $value) {
            $exam_item[$key] = $value; 
        }

        //push to exam data
        array_push($exam_arr['data'], $exam_item);
    }
    
    //turn to json and output
    echo json_encode($exam_arr);

} else {
    //no exam
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'exam not found'
            )
    );
}