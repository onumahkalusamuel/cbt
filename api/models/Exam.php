<?php
include_once('Functions.php');

class Exam
{
    //DB stuff
    private $conn;
    private $table = 'exam';

    //Exam Properties
    public $id;
    public $title;
    public $slug;
    public $status;

    /*CREATE TABLE `cbtexams`.`exam` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `title` VARCHAR(191) NULL , `slug` VARCHAR(191) NULL , `status` TINYINT(1) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;  */


    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Exam
    public function read()
    {
        $this->id = Functions::sanitize($this->id);
        //Create Query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE 1 ' . (!empty($this->id) ? ' AND id = ' . $this->id : '');
        //Prepared statement
        $stmt = $this->conn->prepare($query);
        //Execute the query
        $stmt->execute();
        return $stmt;
    }

    //Create Exam
    public function create()
    {

        $this->title = Functions::sanitize($this->title);
        $this->slug = Functions::make_slug($this->title);

        // check if its existing first        
        $query = 'SELECT * FROM ' . $this->table . ' WHERE title = "' . $this->title . '"';
        $stmt = $this->conn->prepare($query);
        //Execute the query

        $stmt->execute();

        if ($stmt->rowCount() !== 0) return false;

        //continue if it doesnt exist
        //Create query
        $query = 'INSERT INTO ' . $this->table . ' SET
            title = :title,
            slug = :slug,
            status = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('title', $this->title);
        $stmt->bindParam('slug', $this->slug);

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

        // print error if something goes wrong
        // printf("Error: %s, \n", $stmt->error);

        return false;
    }
}
