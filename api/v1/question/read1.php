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

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate Question object
$Question = new Question($db);

//check if there are ids attached
$Question->_id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$Question->exam_year = isset($_GET['exam_year']) ? (int) $_GET['exam_year'] : null;
$Question->exam_body = isset($_GET['exam_body']) ? (int) $_GET['exam_body'] : null;
$Question->cat_id = isset($_GET['cat_id']) ? (int) $_GET['cat_id'] : null;
$Question->ref_id = isset($_GET['ref_id']) ? (int) $_GET['ref_id'] : null;

//initialize array
$_arr = array();
$_arr['code'] = $statuscode->ok;
$_arr['questions'] = [];

//exam query
$result = $Question->read();

//get row count
$num = $result->rowCount();

if( $num > 0 ) {

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $_item = array();

        foreach($row as $key => $value) {
            $_item[$key] = $value; 
        }

        //push to  data
        array_push($_arr['questions'], $_item);
    }
}
echo json_encode($_arr);
