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
include_once '../../models/Paper.php';
include_once '../../models/Exam.php';
include_once '../../models/Subject.php';
include_once '../../models/ClassRoom.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$Paper = new Paper($db);
$Exam = new Exam($db);
$Subject = new Subject($db);
$ClassRoom = new ClassRoom($db);
//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;
$return['paper'] = [];
$return['exam'] = [];
$return['subject'] = [];
$return['class'] = [];

// get existing papers
$result = $Paper->read();
//get row count
$num = $result->rowCount();
if( $num > 0 ) {
    //initialize array
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $return['paper'][] = [
            'id' => $id,
            'exam' => $exam,
            'subject' => $subject,
            'class' => $class
        ];
    }
    //turn to json and output
    
}
$return['exam'] = $Exam->read()->fetchAll(PDO::FETCH_ASSOC);
$return['subject'] = $Subject->read()->fetchAll(PDO::FETCH_ASSOC);
$return['class'] = $ClassRoom->read()->fetchAll(PDO::FETCH_ASSOC);

die(json_encode($return));