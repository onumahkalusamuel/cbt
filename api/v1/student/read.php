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
include_once '../../models/Student.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate student object
$student = new Student($db);

//check if there are ids attached
$student->id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$student->class_id = isset($_GET['class']) ? (int) $_GET['class'] : null;
$student->house_id = isset($_GET['house']) ? (int) $_GET['house'] : null;

//student query
$result = $student->read();

//get row count
$num = $result->rowCount();

//check if any subject

if( $num > 0 ) {
    //initialize array
    $student_arr = array();
    $student_arr['code'] = $statuscode->ok;
    $student_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $student_item = array();

        foreach($row as $key => $value) {
            $student_item[$key] = $value; 
        }

        //push to student data
        array_push($student_arr['data'], $student_item);
    }
    
    //turn to json and output
    http_response_code($statuscode->ok);
    echo json_encode($student_arr);

} else {
    //no student
    http_response_code($statuscode->not_found);
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'student not found'
            )
    );
}