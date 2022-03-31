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
include_once '../../models/ClassAllotment.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate objects
$class_alllocation = new ClassAllotment($db);

//initialize return
$return['code'] = $statuscode->ok;

//get the raw posted data 
$putdata = json_decode(file_get_contents('php://input'));

//check if there are ids attached
$class_alllocation->session_id = $putdata->session;
$class_alllocation->class_id = $putdata->class;

// check if there is an existing allocation for that class 
$result = $class_alllocation->read()->fetch(PDO::FETCH_ASSOC);

//bring in the teacher id now.
$class_alllocation->teacher_id = $putdata->teacher;

if (empty($result)) {
    //insert record
    $class_alllocation->create();
} else {
    //update record
    $class_alllocation->id = $result['id'];
    $class_alllocation->status = 1;
    $class_alllocation->update();
}

$return['message'] = 'update successful';

//operation successful
die( json_encode($return) );