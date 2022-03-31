<?php
include_once('Functions.php');

class Paper
{
    //DB stuff
    private $conn;
    private $table = 'paper';
    private $exam_table = 'exam';
    private $subject_table = 'subject';
    private $class_table = 'class';

    //Exam Properties
    public $id;
    public $exam_id;
    public $subject_id;
    public $class_id;
    public $duration;
    public $start_date;
    public $status;

    // CREATE TABLE `cbtexams`.`paper` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `exam_id` INT(11) NULL , `subject_id` INT(11) NULL , `class_id` INT(11) NULL , `duration` VARCHAR(10) NULL , `start_date` VARCHAR(30) NULL , `status` TINYINT(1) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 


    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Exam
    public function read()
    {
        $this->id = Functions::sanitize($this->id);
        $this->exam_id = Functions::sanitize($this->exam_id);
        $this->subject_id = Functions::sanitize($this->subject_id);
        $this->class_id = Functions::sanitize($this->class_id);
        //Create Query
        $query =
            'SELECT 
            p.*, 
            e.title AS exam,
            s.title AS subject,
            c.title AS class
        FROM ' . $this->table . ' AS p ' .
            'JOIN 
            (' . $this->exam_table . ' AS e, ' .
            $this->subject_table . ' AS s, ' .
            $this->class_table . ' AS c) ' .
            'ON p.exam_id = e.id ' .
            'AND p.subject_id = s.id ' .
            'AND p.class_id = c.id ' .
            'WHERE 1 ' .
            (!empty($this->exam_id) ? ' AND p.exam_id = ' . $this->exam_id : '') .
            (!empty($this->subject_id) ? ' AND p.subject_id = ' . $this->subject_id : '') .
            (!empty($this->class_id) ? ' AND p.class_id = ' . $this->class_id : '') .
            (!empty($this->id) ? ' AND p.id = ' . $this->id : '');

        //Prepared statement
        $stmt = $this->conn->prepare($query);
        //Execute the query
        $stmt->execute();
        return $stmt;
    }

    //Create paper
    public function create()
    {
        $this->exam_id = Functions::sanitize($this->exam_id);
        $this->subject_id = Functions::sanitize($this->subject_id);
        $this->class_id = Functions::sanitize($this->class_id);
        $this->duration = Functions::sanitize($this->duration);
        $this->start_date = Functions::sanitize($this->start_date);

        // check if its existing first        
        $query = 'SELECT * FROM ' . $this->table . ' WHERE exam_id = "' . $this->exam_id . '" AND subject_id = "' . $this->subject_id . '" AND class_id = "' . $this->class_id . '"';
        $stmt = $this->conn->prepare($query);
        //Execute the query

        $stmt->execute();

        if ($stmt->rowCount() !== 0) return false;

        //continue if it doesnt exist
        //Create query
        $query = 'INSERT INTO ' . $this->table . ' SET
            exam_id = :exam_id,
            subject_id = :subject_id,
            class_id = :class_id,
            duration = :duration,
            start_date = :start_date,
            status = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('exam_id', $this->exam_id);
        $stmt->bindParam('subject_id', $this->subject_id);
        $stmt->bindParam('class_id', $this->class_id);
        $stmt->bindParam('duration', $this->duration);
        $stmt->bindParam('start_date', $this->start_date);
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
        //Create query
        $query = 'UPDATE ' .
            $this->table .
            ' SET 
            student_id = :student_id,
            session_id = :session_id,
            term_id = :term_id,
            class_id = :class_id,
            subject_id = :subject_id,
            value = :value,
            status = :status
        WHERE id = :id';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data
        $this->id = htmlspecialchars(strip_tags(trim($this->id)));
        $this->student_id = htmlspecialchars(strip_tags(trim($this->student_id)));
        $this->session_id = htmlspecialchars(strip_tags(trim($this->session_id)));
        $this->term_id = htmlspecialchars(strip_tags(trim($this->term_id)));
        $this->class_id = htmlspecialchars(strip_tags(trim($this->class_id)));
        $this->subject_id = htmlspecialchars(strip_tags(trim($this->subject_id)));
        $this->value = htmlspecialchars(strip_tags(trim($this->value)));
        $this->status = htmlspecialchars(strip_tags(trim($this->status)));

        //bind data
        $stmt->bindParam('id', $this->id);
        $stmt->bindParam('student_id', $this->student_id);
        $stmt->bindParam('session_id', $this->session_id);
        $stmt->bindParam('term_id', $this->term_id);
        $stmt->bindParam('class_id', $this->class_id);
        $stmt->bindParam('subject_id', $this->subject_id);
        $stmt->bindParam('value', $this->value);
        $stmt->bindParam('status', $this->status);

        //execute query
        if ($stmt->execute()) {
            return true;
        }

        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }

    //delete Exam
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->id = htmlspecialchars(strip_tags(trim($this->id)));
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
