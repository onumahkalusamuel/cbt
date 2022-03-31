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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));

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

//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));
$Score->student_id  =   $student_id     =   $data->student_id; unset($data->student_id);
$Score->exam_body  =   $exam_body     =   $data->exam_body; unset($data->exam_body);
$Score->exam_year  =   $exam_year     =   $data->exam_year; unset($data->exam_year);
$Score->subject  =   $subject     =   $data->subject; unset($data->subject);
$question_ids       =   $data->question_ids; unset($data->question_ids);

//process the question_ids
$exp = explode(',',$question_ids);
foreach($exp as $anId) {
    $_ids[$anId] = ['cho'=>"", 'ans'=>""];
}

//process the submitted data
$correct_answer = 0;
$answered_questions = [];

foreach($data as $key => $value) {
    $a = explode('-', $key);
    $answered_questions[$a[1]] = $value;
}

//get from the exam ids
foreach($_ids as $key => $value) {
    $Question->_id = $key;
    $answer = $Question->read()->fetch()['correct_answer'];
    $answer = strtoupper($answer);
    $choice = !empty($answered_questions[$key]) ? strtoupper($answered_questions[$key]) : "";
    //enter the choice of student and correct answer
    $_ids[$key] = ['cho'=>$choice, 'ans'=>$answer];

    if ($answer == $choice) {
        $correct_answer += 1;
    }
}

// foreach($data as $key => $value) {
//     $a = explode('-', $key);
//     $Question->_id = $a[1];
    
//     $answer = $Question->read()->fetch()['correct_answer'];
//     $answer = strtoupper($answer);
//     $value = strtoupper($value);
//     //enter the choice of student and correct answer
//     $_ids[$a[1]] = ['cho'=>$value, 'ans'=>$answer];

//     if ($answer == $value) {
//         $correct_answer += 1;
//     }
// }

$Score->score = $correct_answer;
$Score->status = 1;
$Score->question_answer = json_encode($_ids);

//create Score
if($Score->create()) {
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