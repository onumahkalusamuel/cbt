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
include_once '../../models/Teacher.php';
include_once '../../models/ClassRoom.php';
include_once '../functions.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$class_allotment = new ClassAllotment($db);
$teacher = new Teacher($db);
$classroom = new ClassRoom($db);
//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;
$return['teacher'] = [];
$return['class'] = [];
$return['class_allotment'] = [];
//get Teachers
$result = $teacher->read();
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $return['teacher'][] = [
        'id' => $id,
        'name' => $name,
        'role' => $role
    ];
}
//get classes
$result = $classroom->read();
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);    
    $return['class'][] = [
        'id' => $id,
        'title' => $title
    ];
}
//get existing alottment
//get Subjects
$result = $class_allotment->read();
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);    
    $return['class_allotment'][] = [
        'id'        => $id,
        'session'   => $session,
        'teacher'   => $teacher,
        'class'     => $class
    ];
}
die( json_encode($return) );