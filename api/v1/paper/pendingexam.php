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
include_once '../../models/Paper.php';
include_once '../../models/Score.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate object
$Paper = new Paper($db);
$Score = new Score($db);

// initialize return
$return = [];
$return['code'] = $statuscode->ok;
$return['paper'] = [];

//check if there are ids attached
$student_id = $_GET['student_id'];
$class_id = $_GET['class_id'];

$Score->student_id = $student_id;
//get all the papers for that class
$Paper->class_id = $class_id;
$res = $Paper->read()->fetchAll(PDO::FETCH_ASSOC);

if(!empty($res)) {

    foreach($res as $r)
    //remove the ones the student has written
    $Score->paper_id = $r['id'];
    $result = $Score->read();
    if(!$result->rowCount()) {
        $return['paper'][] = $r;
    }
}

//return the pending ones
echo json_encode($return);
