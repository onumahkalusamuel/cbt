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
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
//continue
include_once '../../config/Database.php';
include_once '../../models/Exam.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$exam = new Exam($db);
//initialize return
$return['code'] = $statuscode->ok;

//get the raw posted data 
$putdata = json_decode(file_get_contents('php://input'));
//check if there are ids attached
$exam->session_id = isset($putdata->session_id) ? (int) $putdata->session_id : null; unset($putdata->session_id);
$exam->term_id = isset($putdata->term_id) ? (int) $putdata->term_id : null; unset($putdata->term_id);
$exam->class_id = isset($putdata->class_id) ? (int) $putdata->class_id : null; unset($putdata->class_id);
$exam->subject_id = isset($putdata->subject_id) ? (int) $putdata->subject_id : null; unset($putdata->subject_id);
$exam->status = 1;

//work on the remaining
foreach($putdata as $d => $val) {
    //some magical stuff should be going on here.
    //i'm picking the remaining putdata that are in this format: data-1-assignment_id = 4
    //I'm breaking down and converting it to array like data[1][assignment_id] = 4 
    $d = explode('-', $d);
    ${$d['0']}[$d['1']][$d['2']] = $val;
}

// verify that the $data array is not empty
if(empty($data)) {
    die( json_encode([
        'code' => $statuscode->bad_request,
        'message' => 'Something is not right. Contact administration.'
    ]) );
}

//it's not empty. let's continue inserting/updating
foreach( $data as $student_id => $details ) {
    //assign student id
    $exam->student_id = $student_id;
    //read the data to be sure the records exist
    if(!empty($details['exam_id'])) {
        //update
        $exam->id = $details['exam_id'];
        $exam->value = $details['value'];
        $exam->update();
    } else {
        //create new record
        $exam->value = $details['value'];
        $exam->create();
    }
}

$return['message'] = 'update successful';

//operation successful
die( json_encode( $return) );