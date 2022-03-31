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
include_once '../../models/Question.php';
include_once '../../models/Paper.php';
include_once '../../models/Score.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate Question object
$Question = new Question($db);

//check if there are ids attached
$Question->exam_body = isset($_GET['exam_body']) ? $_GET['exam_body'] : -1;
$Question->exam_year = isset($_GET['exam_year']) ? $_GET['exam_year'] : -1;
$Question->cat_id = isset($_GET['cat_id']) ? (int) $_GET['cat_id'] : -1;
$Question->type = isset($_GET['type']) ? $_GET['type'] : -1;

//initialize array
$_arr = array();
$_arr['code'] = $statuscode->ok;
$_arr['questions'] = [];
$question_ids = [];

//question query
$result = $Question->read();

//get row count
$num = $result->rowCount();

if( $num > 0 ) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $_item = [];
        $_item['_id'] = $_id;
        $_item['question'] = $question;
        $_item['option_a'] = $option_a;
        $_item['option_b'] = $option_b;
        $_item['option_c'] = $option_c;
        $_item['option_d'] = $option_d;
        $_item['option_e'] = $option_e;
        $_item['correct_answer'] = $correct_answer;
        $_item['photo'] = $photo;

        //let's collect the question ids too
        $question_ids[] = $_id;

        //push to  data
        array_push($_arr['questions'], $_item);
    }
    $_arr['subject'] = $category;
    $_arr['exam_body'] = strtoupper($exam_body);
    $_arr['exam_year'] = $exam_year;
}

shuffle($_arr['questions']);

$_arr['question_ids'] = implode(',',$question_ids);
//turn to json and output

echo json_encode($_arr);
