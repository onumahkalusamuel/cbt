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
include_once '../../models/Settings.php';

//instantiate Db and Connect
$database = new Database();
$db = $database->connect();

//Instantiate Settings object
$Settings = new Settings($db);

//check if there are ids attached
$Settings->id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$Settings->setting = isset($_GET['setting']) ? $_GET['setting'] : null;

//Settings query
$result = $Settings->read();

//get row count
$num = $result->rowCount();

//check if any subject
if( $num > 0 ) {
    //initialize array
    $Settings_arr = array();
    $Settings_arr['code'] = $statuscode->ok;
    $Settings_arr['data'] = array();

    while($row = $result->fetchAll(PDO::FETCH_ASSOC)) {
        $Settings_item = array();
        foreach($row as $value) {
            // print_r($value); die();
            $Settings_item[$value['setting']] = $value['value']; 
        }
        //push to Settings data
        array_push($Settings_arr['data'], $Settings_item);
    }
    //turn to json and output
    echo json_encode($Settings_arr);
} else {
    //no Settings
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'Settings not found'
            )
    );
}