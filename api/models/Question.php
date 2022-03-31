<?php
include_once('Functions.php');

class Question
{
    //DB stuff
    private $conn;
    private $questions = 'questions';
    private $comm_info = 'comm_info';
    private $course_info = 'course_info';
    private $faculty_info = 'faculty_info';
    private $question_categories = 'question_categories';
    private $question_ref = 'question_ref';
    private $sqlite_sequence = 'sqlite_sequence';

    //Exam Properties
    public $_id;
    public $cat_id;
    public $ref_id;
    public $exam_body;
    public $exam_year;
    public $question;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $option_e;
    public $correct_answer;
    public $explanation;
    public $photo;
    public $answer_photo;
    public $is_hidden;
    public $date_added;
    public $date_updated;
    public $type;
    public $editor_images;

    // CREATE TABLE `cbtexams`.`questions` ( `_id` INT(11) NULL , `cat_id` INT(11) NULL , `ref_id` INT(11) NULL , `exam_body` VARCHAR(20) NULL , `exam_year` VARCHAR(20) NULL , `question` TEXT NULL , `opt_a` TEXT NULL , `opt_b` TEXT NULL , `opt_c` TEXT NULL , `opt_d` TEXT NULL , `opt_e` TEXT NULL , `answer` VARCHAR(5) NULL , `image` VARCHAR(191) NULL , `status` TINYINT(1) NULL ) ENGINE = InnoDB; 


    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Exam
    public function read()
    {
        $this->_id = Functions::sanitize($this->_id);
        @$this->cat_id = Functions::sanitize($this->cat_id);
        @$this->ref_id = Functions::sanitize($this->ref_id);
        @$this->exam_body = Functions::sanitize($this->exam_body);
        @$this->exam_year = Functions::sanitize($this->exam_year);
        @$this->type = Functions::sanitize($this->type);
        //Create Query
        $query =
            'SELECT q.*, qc.title AS category FROM ' . $this->questions . ' AS q ' .
            'JOIN ' . $this->question_categories . ' AS qc ' .
            'ON q.cat_id = qc._id ' .
            'WHERE 1 ' .
            (!empty($this->cat_id) ? ' AND q.cat_id = "' . $this->cat_id . '"' : '') .
            (!empty($this->ref_id) ? ' AND q.ref_id = "' . $this->ref_id . '"' : '') .
            (!empty($this->exam_body) ? ' AND q.exam_body = "' . $this->exam_body . '"' : '') .
            (!empty($this->exam_year) ? ' AND q.exam_year = "' . $this->exam_year . '"' : '') .
            (!empty($this->type) ? ' AND q.type = "' . $this->type . '"' : '') .
            (!empty($this->_id) ? ' AND q._id = ' . $this->_id : '') .
            ' LIMIT 100 ';

        //Prepared statement
        $stmt = $this->conn->prepare($query);
        //Execute the query
        $stmt->execute();
        return $stmt;
    }

    //Create Exam
    public function create()
    {
        $this->paper_id = Functions::sanitize($this->paper_id);
        $this->question = Functions::sanitize($this->question);
        $this->opt_a = Functions::sanitize($this->opt_a);
        $this->opt_b = Functions::sanitize($this->opt_b);
        $this->opt_c = Functions::sanitize($this->opt_c);
        $this->opt_d = Functions::sanitize($this->opt_d);
        $this->opt_e = Functions::sanitize($this->opt_e);
        $this->answer = Functions::sanitize($this->answer);
        $this->image = Functions::sanitize($this->image);

        // check if its existing first        
        $query = 'SELECT * FROM ' . $this->table . ' WHERE paper_id = "' . $this->paper_id . '" AND question = "' . $this->question . '" AND opt_a = "' . $this->opt_a . '"';
        $stmt = $this->conn->prepare($query);
        //Execute the query

        $stmt->execute();

        if ($stmt->rowCount() !== 0) return false;

        //continue if it doesnt exist
        //Create query
        $query = 'INSERT INTO ' . $this->table . ' SET
        paper_id = :paper_id,
        question = :question,
        opt_a = :opt_a,
        opt_b = :opt_b,
        opt_c = :opt_c,
        opt_d = :opt_d,
        opt_e = :opt_e,
        answer = :answer,
        image = :image,
        status = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('paper_id', $this->paper_id);
        $stmt->bindParam('question', $this->question);
        $stmt->bindParam('opt_a', $this->opt_a);
        $stmt->bindParam('opt_b', $this->opt_b);
        $stmt->bindParam('opt_c', $this->opt_c);
        $stmt->bindParam('opt_d', $this->opt_d);
        $stmt->bindParam('opt_e', $this->opt_e);
        $stmt->bindParam('answer', $this->answer);
        $stmt->bindParam('image', $this->image);

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
        $this->paper_id = Functions::sanitize($this->paper_id);
        $this->question = Functions::sanitize($this->question);
        $this->opt_a = Functions::sanitize($this->opt_a);
        $this->opt_b = Functions::sanitize($this->opt_b);
        $this->opt_c = Functions::sanitize($this->opt_c);
        $this->opt_d = Functions::sanitize($this->opt_d);
        $this->opt_e = Functions::sanitize($this->opt_e);
        $this->answer = Functions::sanitize($this->answer);
        $this->image = Functions::sanitize($this->image);
        //Create query
        $query = 'UPDATE ' .
            $this->table .
            ' SET 
            paper_id = :paper_id,
            question = :question,
            opt_a = :opt_a,
            opt_b = :opt_b,
            opt_c = :opt_c,
            opt_d = :opt_d,
            opt_e = :opt_e,
            answer = :answer,
            image = :image,
            status = 1
        WHERE id = :id';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('id', $this->id);
        $stmt->bindParam('paper_id', $this->paper_id);
        $stmt->bindParam('question', $this->question);
        $stmt->bindParam('opt_a', $this->opt_a);
        $stmt->bindParam('opt_b', $this->opt_b);
        $stmt->bindParam('opt_c', $this->opt_c);
        $stmt->bindParam('opt_d', $this->opt_d);
        $stmt->bindParam('opt_e', $this->opt_e);
        $stmt->bindParam('answer', $this->answer);
        $stmt->bindParam('image', $this->image);

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
