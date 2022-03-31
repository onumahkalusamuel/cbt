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

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate Question object
$Question = new Question($db);
$Paper = new Paper($db);

//check if there are ids attached
$Question->id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$Question->paper_id = isset($_GET['paper_id']) ? (int) $_GET['paper_id'] : null;

//initialize array
$_arr = array();
$_arr['code'] = $statuscode->ok;
$_arr['questions'] = [];
$_arr['related'] = [];

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

//fetch related
$_arr['related'] = $Question->related();
$_arr['paper_id'] = $Question->paper_id;
//turn to json and output

echo json_encode($_arr);
