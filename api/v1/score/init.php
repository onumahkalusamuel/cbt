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

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$Paper = new Paper($db);

//initialize the return variable
$return = array();
$return['code'] = $statuscode->ok;
$return['paper'] = [];

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
            'title' => $class . ' - ' . $subject . ' - ' . $exam 
        ];
    }
    
}

die(json_encode($return));