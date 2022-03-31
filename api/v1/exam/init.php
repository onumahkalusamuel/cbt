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
include_once '../../models/SubjectAllotment.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$subject_allotment = new SubjectAllotment($db);
//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;

//check if there are ids attached
$subject_allotment->teacher_id = isset($_GET['teacher']) ? (int) $_GET['teacher'] : null;
//subject allotment query
$result = $subject_allotment->read();
//get row count
$num = $result->rowCount();
//check if any subject
if( $num > 0 ) {
    //initialize array
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $return['data'][] = [
            'id' => $class_id . '-' . $subject_id,
            'value' => $class . ' (' . $subject .')'
        ];
    }
    //turn to json and output
    echo json_encode($return);
} else {
    //no assignment
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'Test not found'
            )
    );
}