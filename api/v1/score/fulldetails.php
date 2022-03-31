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
include_once '../../models/Score.php';
include_once '../../models/Question.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$Score = new Score($db);
$Question = new Question($db);

//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;
$return['data'] = [];

$Score->id = !empty($_GET['id']) ? $_GET['id'] : -1;

// get existing Scores
$row = $Score->read()->fetch(PDO::FETCH_ASSOC);
if(empty($row)) return false;
extract($row); //to get $question_answer
$return['score'] = $score;
$return['exam_body'] = $exam_body;
$return['exam_year'] = $exam_year;
$return['subject'] = $subject;
$questions = json_decode($question_answer);

foreach($questions as $key => $ques) {
    $question_item = [];
    $question_item['id'] = $Question->_id = $key;
    $question_item['choice'] = @$ques->cho;
    $question_item['status'] = (!empty(@$ques->cho) && @$ques->cho === @$ques->ans) ? 'success' : 'danger';
    
    @$res = $Question->read()->fetch(PDO::FETCH_ASSOC);

    if (!empty($res)) $question_item['details'] = $res;
    
    $return['data'][] = $question_item;
}

die(json_encode($return));