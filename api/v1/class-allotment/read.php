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
    http_response_code($statuscode->method_not_allowed);
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
    

//continue
include_once '../../config/Database.php';
include_once '../../models/ClassAllotment.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate class_allotment object
$class_allotment = new ClassAllotment($db);

//check if there's an id attached
$class_allotment->id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$class_allotment->session_id = isset($_GET['session']) ? (int) $_GET['session'] : null;
$class_allotment->teacher_id = isset($_GET['teacher']) ? (int) $_GET['teacher'] : null;
$class_allotment->class_id = isset($_GET['class']) ? (int) $_GET['class'] : null;

//class_allotment query
$result = $class_allotment->read();

//get row count
$num = $result->rowCount();

//check if any class

if( $num > 0 ) {
    //initialize array
    $class_allotment_arr = array();
    $class_allotment_arr['code'] = $statuscode->ok;
    $class_allotment_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $class_allotment_item = array(
            'id' => $id,
            'session_id' => $session_id,
            'session' => $session,
            'teacher_id' => $teacher_id,
            'teacher' => $teacher,
            'class_id' => $class_id,
            'class' => $class,
            'status' => $status
        );

        //push to class_allotment data
        array_push($class_allotment_arr['data'], $class_allotment_item);
    }
    
    //turn to json and output
    http_response_code($statuscode->ok);
    echo json_encode($class_allotment_arr);

} else {
    //no subject
    http_response_code($statuscode->not_found);
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'class allotment not found'
            )
    );
}