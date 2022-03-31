<?php
function getID($table, $condition, $value) {
    //global declaration of 
    global $db;
    //make sure the three parameters and $db are inserted, else quit
    if(empty($table) || empty($condition) || empty($value) || (!$db instanceof PDO)) return null;
    $query = 'SELECT id FROM ' . $table . ' WHERE ' . $condition . '="' . $value .'"';
    $stmt = $db->prepare($query);
    if ($stmt->execute()) {
        $return = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    } else {
        $return = null;
    }
    return $return;
}

function getIDs($table, $condition, $value = null) {
    //global declaration of 
    global $db;
    //make sure the three parameters and $db are inserted, else quit
    if(empty($table) || empty($condition) || empty($value) || (!$db instanceof PDO)) return null;
    $final = [];
    if (is_array($value)) foreach ($value as $v) $final[] = '"' . $v . '"';
    if(is_string($value)) $final[] = '"' . $value . '"';
    $query = 'SELECT id FROM ' . $table . ' WHERE ' . $condition . ' IN (' . implode(",", $final) . ')';
    $stmt = $db->prepare($query);
    if ($stmt->execute()) {
        $a = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($a as $b) $return[] = $b['id'];
    } else {
        $return = [];
    }
    return $return;
}

function processFIleUpload($file) {
    if (($file['size']) > 15360) return false;
    $imageData = base64_encode(file_get_contents($file['tmp_name']));
    $type = $file['type'];
    return $formated = 'data:'.$type.';base64,'.$imageData;
}

function calculate($heading, $value) {
    global $scoring;
    extract($scoring);
    if ($heading == 'assignment' || $heading == 'test' || $heading == 'project') {
        //get total obtainable
        $obtainable = $$heading['count'] * $$heading['unit_score_max'];
        //get total obtained
        $obtained = $value;
        //get percentage based on total_percentage
        $percentage = $obtained / $obtainable * $$heading['total_percentage'];
        return round($percentage, 2);
    }

    if ($heading == 'exam') {
        //get total obtainable
        $obtainable = $$heading['unit_score_max'];
        //get total obtained
        $obtained = $value;
        //get percentage based on total_percentage
        $percentage = $obtained / $obtainable * $$heading['total_percentage'];
        return round($percentage, 2);
    }

    if ($heading == 'psycho-motor') {

        $return = [];
        //get total obtainable
        $return['obtainable'] = $scoring['psycho-motor']['count'] * $scoring['psycho-motor']['unit_score_max'];
        $return['obtainable'] = round($return['obtainable'], 2);
        
        //get total obtained
        $return['obtained'] = round($value, 2);

        $return['obtainable_percentage'] = round($scoring['psycho-motor']['total_percentage'], 2);

        //get percentage based on total_percentage
        $return['obtained_percentage'] = $return['obtained'] / $return['obtainable'] * $return['obtainable_percentage'];
        
        $return['obtained_percentage'] = round($return['obtained_percentage'], 2);

        return $return;
    }

}

function getGradingSystem($class_id) {
    global $db;
    if(empty($class_id) || (!$db instanceof PDO)) return null;

    $query = 'SELECT GI.ordering, GI.grade, GI.min, GI.max, GI.details 	
            FROM grading_allotment AS GA
            JOIN (grading AS G, grading_item AS GI) 
            ON GA.grading_id = G.id  
            AND GI.grading_id = G.id 
            WHERE GA.class_id= :class_id
            ORDER BY GI.ordering';

    $stmt = $db->prepare($query);
    $stmt->bindParam('class_id', $class_id);
    if ($stmt->execute()) {
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $return = null;
    }
    return $return;
}

function getGrade($total, $grading_system) {
    foreach( $grading_system as $grader ) if ( ($total >= $grader['min']) && ($total < ($grader['max']+1)) ) return $grader['grade'];
}

function getTeacherSignature($subject_id, $class_id) {
    
    //global declaration of 
    global $db;
    //make sure the three parameters and $db are inserted, else quit
    if(empty($subject_id) || empty($class_id) || (!$db instanceof PDO)) return null;
    $query = 'SELECT teacher_id FROM subject_allotment WHERE class_id = :class_id AND subject_id = :subject_id';
    $stmt = $db->prepare($query);
    
    $stmt->bindParam('class_id', $class_id);
    $stmt->bindParam('subject_id', $subject_id);

     if ($stmt->execute()) {
         $teacher_id = $stmt->fetch(PDO::FETCH_ASSOC)['teacher_id'];
         if (empty($teacher_id)) return null;
        $query = "SELECT signature FROM teacher WHERE id = :teacher_id";
        $stmt = $db->prepare($query);    
        $stmt->bindParam('teacher_id', $teacher_id);
        if ($stmt->execute()) {
            $signature = $stmt->fetch(PDO::FETCH_ASSOC)['signature'];
            return $signature;   
        }
    }
}