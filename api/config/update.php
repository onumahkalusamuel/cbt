<?php
set_time_limit(0);
$host = 'localhost';
$db_name = 'cbtexams';
$username = 'root';
$password = 'root';
$db_file = 'cbtexams.db';
$conn1 = new PDO('sqlite:' . $db_file);
$conn2 = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $username, $password);
$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$table = "questions";
$stmt1 = $conn1->prepare("SELECT * FROM {$table}");
$stmt1->execute();
$res = $stmt1->fetchAll(PDO::FETCH_ASSOC);
if (empty($res)) die("no data found");

$query = 'INSERT INTO questions SET _id=:_id, cat_id=:cat_id, ref_id=:ref_id, exam_body=:exam_body, exam_year=:exam_year, question=:question, opt_a=:option_a, opt_b=:option_b, opt_c=:option_c, opt_d=:option_d, opt_e=:option_e, answer=:correct_answer, image=:photo';
$stmt2 = $conn2->prepare($query);

foreach ($res as $f) {
    $stmt2->bindParam('_id', $f['_id']);
    $stmt2->bindParam('cat_id', $f['cat_id']);
    $stmt2->bindParam('ref_id', $f['ref_id']);
    $stmt2->bindParam('exam_body', $f['exam_body']);
    $stmt2->bindParam('exam_year', $f['exam_year']);
    $stmt2->bindParam('question', $f['question']);
    $stmt2->bindParam('option_a', $f['option_a']);
    $stmt2->bindParam('option_b', $f['option_b']);
    $stmt2->bindParam('option_c', $f['option_c']);
    $stmt2->bindParam('option_d', $f['option_d']);
    $stmt2->bindParam('option_e', $f['option_e']);
    $stmt2->bindParam('correct_answer', $f['correct_answer']);
    // $stmt2->bindParam('explanation', $f['explanation']);
    $stmt2->bindParam('photo', $f['photo']);
    // $stmt2->bindParam('answer_photo', $f['answer_photo']);
    // $stmt2->bindParam('is_hidden', $f['is_hidden']);
    // $stmt2->bindParam('date_added', $f['date_added']);
    // $stmt2->bindParam('date_updated', $f['date_updated']);
    // $stmt2->bindParam('type', $f['type']);
    // $stmt2->bindParam('editor_images', $f['editor_images']);
    if ($stmt2->execute());
}
echo "success";
