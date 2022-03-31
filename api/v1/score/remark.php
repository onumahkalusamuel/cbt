<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));

//then continue
include_once '../../config/Database.php';
include_once '../../models/Score.php';
include_once '../../models/Question.php';

//instantiate Db and Connect

$database = new Database();
$db = $database->connect();

//Instantiate Score object
$Score = new Score($db);
$Question = new Question($db);

if(empty($_GET['id'])) return false;

$Score->id = $id = $_GET['id'];
$scores = $Score->read();
$result = $scores->fetch(PDO::FETCH_ASSOC);

if(empty($result)) return false;


$Score->student_id  =   $student_id     =   $result['student_id'];
$Score->exam_body  =   $exam_body     =   $result['exam_body'];
$Score->exam_year  =   $exam_year     =   $result['exam_year'];
$Score->subject  =   $subject     =   $result['subject'];
$question_answer       =   json_decode($result['question_answer']);
// print_r($question_answer);

//process the submitted data
$correct_answer = 0;
$answered_questions = [];

//get from the exam ids
foreach($question_answer as $key => $qa) {
    $Question->_id = $key;
    $answer = $Question->read()->fetch()['correct_answer'];
    $answer = strtoupper($answer);
    $choice = $qa->cho;
    //enter the choice of student and correct answer
    $question_answer->$key = ['cho'=>$choice, 'ans'=>$answer];

    if ($answer == $choice) {
        $correct_answer += 1;
    }
}

$correct_answer;
$Score->score = $correct_answer;
$Score->status = 1;
$Score->question_answer = json_encode($question_answer);
$Score->id = $result['id'];
//create Score
if($Score->update()) {
    echo json_encode(
        array(
            'code'=> $statuscode->created,
            'message'=> 'marking successful'
            )
    );
} else {
    //no Score
    echo json_encode(
        array(
            'code'=> $statuscode->not_modified,
            'message'=> 'an error occured'
            )
    );
}