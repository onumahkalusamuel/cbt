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
include_once '../../models/House.php';
include_once '../../models/ClassRoom.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$student = new Student($db);
$house_list = new House($db);
$class_room = new ClassRoom($db);
//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;
$return['student'] = [];
$return['house'] = [];
$return['class'] = [];

//get students
$result = $student->read();
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);    
    $return['student'][] = [
        'id' => $id,
        'name' => $lastname . ', ' . $firstname . ' ' . $middlename,
        'photo' => $photo,
        'adm_no' => $adm_no,
        'guardian_contact' => $guardian_phone . (!empty($guardian_name) ? ' ('. $guardian_name . ')' : ''),
        'class' => $class,
        'gender' => $gender
    ];
}

//get classes
$result = $class_room->read();
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);    
    $return['class'][] = [
        'id' => $id,
        'title' => $title
    ];
}

//get houses
$result = $house_list->read();
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);    
    $return['house'][] = [
        'id' => $id,
        'title' => $title
    ];
}

die( json_encode($return) );