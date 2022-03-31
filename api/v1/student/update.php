<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//include and instantiate statuscodes
include_once '../../config/Status.php';
$statuscode = new Status();

// double check to make sure request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    die(json_encode(array('code'=> $statuscode->method_not_allowed, 'message'=> 'Method not Allowed')));
}
//then continue
include_once '../../config/Database.php';
include_once '../../models/Student.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate student object
$student = new Student($db);
//get the raw posted data 
$data = json_decode(file_get_contents('php://input'));

if(empty($data)) die(json_encode(array('code'=> $statuscode->forbidden, 'message'=> 'Empty data detected')));
//if(!empty($data->password)) $data->password = md5($data->password);
//check if user exists
$student->id = $data->id;
if( md5($data->curpass) !== $student->read()->fetch(PDO::FETCH_ASSOC)['password'] )
    die(json_encode(array('code'=> $statuscode->forbidden, 'message'=> 'Current password entered is invalid.')));
//remove the password check before looping
unset($data->curpass);

foreach($data as $key => $dat ) $student->$key = $dat;

//update student
if($student->update()) {
    //student
    echo json_encode(
        array(
            'code'=> $statuscode->ok,
            'message'=> 'student updated'
            )
    );
} else {
    //no student
    echo json_encode(
        array(
            'code'=> $statuscode->not_found,
            'message'=> 'student not found'
            )
    );
}