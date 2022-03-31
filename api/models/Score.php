<?php
include_once('Functions.php');

class Score
{
    //DB stuff
    private $conn;
    private $table = 'score';
    private $student_table = 'student';
    private $subject_table = 'subject';
    private $class_table = 'class';
    private $exam_table = 'exam';

    //Score Properties
    public $id;
    public $student_id;
    public $exam_body;
    public $exam_year;
    public $subject;
    public $score;
    public $status;


    // CREATE TABLE `cbtexams`.`score` ( `id` INT(11) NOT NULL AUTO_INCREMENT ,  `student_id` INT(11) NULL ,  `exam_body` VARCHAR(20) NULL ,  `exam_year` VARCHAR(20) NULL ,  `subject` VARCHAR(191) NULL ,  `score` DOUBLE(10,2) NULL ,  `status` TINYINT(1) NULL ,    PRIMARY KEY  (`id`)) ENGINE = InnoDB;


    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Score
    public function read()
    {
        $this->id = Functions::sanitize($this->id);
        @$this->student_id = Functions::sanitize($this->student_id);
        @$this->exam_body = Functions::sanitize($this->exam_body);
        @$this->exam_year = Functions::sanitize($this->exam_year);
        @$this->subject = Functions::sanitize($this->subject);
        //Create Query
        $query =
            'SELECT 
            s.*, 
            CONCAT(st.lastname," ", st.firstname) AS student
        FROM ' . $this->table . ' AS s ' .
            'JOIN ' . $this->student_table . ' AS st ' .
            ' ON s.student_id = st.id ' .
            'WHERE 1 ' .
            (!empty($this->student_id) ? ' AND s.student_id = ' . $this->student_id : '') .
            (!empty($this->exam_body) ? ' AND s.exam_body = ' . $this->exam_body : '') .
            (!empty($this->exam_year) ? ' AND s.exam_year = ' . $this->exam_year : '') .
            (!empty($this->subject) ? ' AND s.subject = ' . $this->subject : '') .
            (!empty($this->id) ? ' AND s.id = ' . $this->id : '');

        //Prepared statement
        $stmt = $this->conn->prepare($query);
        //Execute the query
        $stmt->execute();
        return $stmt;
    }

    //Create Score
    public function create()
    {
        $this->student_id = Functions::sanitize($this->student_id);
        $this->exam_body = Functions::sanitize($this->exam_body);
        $this->exam_year = Functions::sanitize($this->exam_year);
        $this->subject = Functions::sanitize($this->subject);
        $this->score = Functions::sanitize($this->score);
        $this->question_answer = $this->question_answer;

        //Create query
        $query = 'INSERT INTO ' . $this->table . ' SET
        student_id = :student_id,
        exam_body = :exam_body,
        exam_year = :exam_year,
        subject = :subject,
        score = :score,
        question_answer = :question_answer,
        status = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('student_id', $this->student_id);
        $stmt->bindParam('exam_body', $this->exam_body);
        $stmt->bindParam('exam_year', $this->exam_year);
        $stmt->bindParam('subject', $this->subject);
        $stmt->bindParam('score', $this->score);
        $stmt->bindParam('question_answer', $this->question_answer);

        //execute query
        if ($stmt->execute()) {
            return true;
        }

        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }

    //Update Exam
    public function update()
    {
        //sanitize
        $this->id = Functions::sanitize($this->id);
        $this->student_id = Functions::sanitize($this->student_id);
        $this->exam_body = Functions::sanitize($this->exam_body);
        $this->exam_year = Functions::sanitize($this->exam_year);
        $this->subject = Functions::sanitize($this->subject);
        $this->score = Functions::sanitize($this->score);
        $this->question_answer = $this->question_answer;

        //Create query
        $query = 'UPDATE ' .
            $this->table .
            ' SET 
            student_id = :student_id,
            exam_body = :exam_body,
            exam_year = :exam_year,
            subject = :subject,
            score = :score,
            question_answer = :question_answer,
            status = 1
        WHERE id = :id';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('id', $this->id);
        $stmt->bindParam('student_id', $this->student_id);
        $stmt->bindParam('exam_body', $this->exam_body);
        $stmt->bindParam('exam_year', $this->exam_year);
        $stmt->bindParam('subject', $this->subject);
        $stmt->bindParam('score', $this->score);
        $stmt->bindParam('question_answer', $this->question_answer);

        //execute query
        if ($stmt->execute()) {
            return true;
        }

        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }

    //delete Score
    public function delete()
    {
        $this->id = Functions::sanitize($this->id);
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //bind data
        $stmt->bindParam('id', $this->id);
        //execute query
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
}
