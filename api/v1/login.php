<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

//include and instantiate statuscodes
include_once '../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
    
//continue
include_once '../config/Database.php';
include_once '../models/Student.php';
include_once '../models/Settings.php';
include_once 'functions.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$Student = new Student($db);
$Settings = new Settings($db);

//extract the login parameters
extract($_GET);

//be sure they are set and not empty
if ( empty($loginusername) || empty($loginpassword) ) die(json_encode(false));

//get the Student id
$Student_id = getID('student', 'username', $loginusername);

//if username doesn't exist, return false
if (empty($Student_id)) die(json_encode(false));

//continue
$Student->id = $Student_id;

//Student query
$result = $Student->read();

$row = $result->fetch(PDO::FETCH_ASSOC);
extract($row);

//check if account is active
if($status !== '1') die(json_encode(false));

// compare the two password hashes
if ($password !== md5($loginpassword)) die(json_encode(false));

//initialize the return array and add some data
$return = array(
    'student_id' => $id,
    'student_name' => $lastname . ', ' . $firstname . ' ' . $middlename,
    'class_id' => $class_id,
    'class' => $class,
    'adm_no' => $adm_no,
    'phone' => $phone,
    'email' => $email,
    'gender' => $gender,
    'photo' => $photo
    );

//get school settings
$set = $Settings->read()->fetchAll(PDO::FETCH_ASSOC);

foreach($set as $value) {
    $return[$value['setting']] = $value['value'];
}

//finally return 
die(json_encode($return));