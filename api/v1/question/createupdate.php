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
//continue
include_once '../../config/Database.php';
include_once '../../models/Question.php';
//instantiate Db and Connect
$database = new Database();
$db = $database->connect();
//Instantiate objects
$Question = new Question($db);
//initialize return
$return['code'] = $statuscode->ok;
$return['error'] = 0;

//get the raw posted data 
$putdata = json_decode(file_get_contents('php://input'));
//check if there are ids attached
$paper_id = $Question->paper_id = isset($putdata->paper_id) ? (int) $putdata->paper_id : null; unset($putdata->paper_id);
$Question->status = 1;

//work on the remaining
foreach($putdata as $d => $val) {
    $d = explode('-', $d);
    @$data[$d['0']][$d['1']][$d['2']] = $val;
}

// verify that the $data array is not empty
if(empty($data)) {
    die( json_encode([
        'code' => $statuscode->bad_request,
        'message' => 'Something is not right. Contact administration.'
    ]) );
}

// process the old questions
if(!empty($data['old'])) {
    foreach($data['old'] as $id => $old) {

        if( empty($old['question']) ||  empty($old['opt_a']) || empty($old['opt_b']) ) {
            $return['error'] += 1; continue;
        }

        $Question->id = $id;
        $Question->question = $old['question'];
        $Question->opt_a = $old['opt_a'];
        $Question->opt_b = $old['opt_b'];
        $Question->opt_c = $old['opt_c'];
        $Question->opt_d = $old['opt_d'];
        $Question->opt_e = $old['opt_e'];
        $Question->answer = $old['answer'];
        $Question->image = $old['image'];
        if (!$Question->update()) $return['error'] += 1;
        unset($Question->id); unset($id);
    }
}

// process the new questions 
if(!empty($data['new'])) {
    foreach($data['new'] as $new) {

        if( empty($new['question']) ||  empty($new['opt_a']) || empty($new['opt_b']) ) {
            $return['error'] += 1; continue;
        }

        $Question->question = $new['question'];
        $Question->opt_a = $new['opt_a'];
        $Question->opt_b = $new['opt_b'];
        $Question->opt_c = @$new['opt_c'];
        $Question->opt_d = @$new['opt_d'];
        $Question->opt_e = @$new['opt_e'];
        $Question->answer = @$new['answer'];
        $Question->image = @$new['image'];
        if (!$Question->create()) $return['error'] += 1;
    }
}

// process the new questions 
if(!empty($data['related'])) {
    foreach($data['related'] as $key => $q) {
        //unset the paper Id for reading the question
        $Question->paper_id = null;
        $Question->id = $key;
        $res = $Question->read()->fetch(PDO::FETCH_ASSOC);
        if(empty($res)) continue;
        $Question->question = $res['question'];
        $Question->opt_a = $res['opt_a'];
        $Question->opt_b = $res['opt_b'];
        $Question->opt_c = $res['opt_c'];
        $Question->opt_d = $res['opt_d'];
        $Question->opt_e = $res['opt_e'];
        $Question->answer = $res['answer'];
        $Question->image = $res['image'];
        //set the paper id again
        $Question->paper_id = $paper_id;
        //add it now;
        if (!$Question->create()) $return['error'] += 1;
    }
}


$return['message'] = 'update successful';

//operation successful
die( json_encode( $return) );