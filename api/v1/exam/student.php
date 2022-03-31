<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();
// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
//continue
include_once '../../config/Database.php';
include_once '../../models/Exam.php';
include_once '../../models/Student.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$student = new Student($db);
$exam = new Exam($db);
//initialize return
$return['code'] = $statuscode->ok;
//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));
//check if there are ids attached
$exam->session_id = isset($data->session) ? (int) $data->session : null;
$exam->term_id = isset($data->term) ? (int) $data->term : null;
$exam->class_id = isset($data->class) ? (int) $data->class : null;
$exam->subject_id = isset($data->subject) ? (int) $data->subject : null;
//ok, lets get the students for this class
$student->class_id = isset($data->class) ? (int) $data->class : null;
$stu = $student->read();
while ($row = $stu->fetch(PDO::FETCH_ASSOC)) {
    //assign student id to the assignment object
    $exam->student_id = $row['id'];
    //get the score, if any, of the student
    $e = $exam->read();
    $rr = $e->fetch(PDO::FETCH_ASSOC);
    //then be sure if they have any exam scores already
    //if yes, add it to the return variable. if not, set the value to null or zero ( i prefer null )
    $return['data'][] = [
        'student_id'    => $row['id'],
        'student_name'  => $row['lastname'] . ' ' . $row['firstname'],
        'adm_no'        => $row['adm_no'],
        'exam_id'       => !empty($rr['id']) ? $rr['id'] : '',
        'value'         => !empty($rr['value']) ? $rr['value'] : ''
    ];
}

$return['ids'] = [
    'session_id'    => $data->session,
    'term_id'       => $data->term,
    'class_id'      => $data->class,
    'subject_id'    => $data->subject
];
die( json_encode($return) );