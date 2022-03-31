<?php
class ClassAllotment
{
    //DB stuff
    private $conn;
    private $table = 'class_allotment';
    private $session_table = 'session';
    private $teacher_table = 'teacher';
    private $class_table = 'class';

    //ClassAllotment Properties
    public $id;
    public $session_id;
    public $teacher_id;
    public $class_id;
    public $status;

    /*
     * sql for creation
     * 
     * CREATE TABLE `cbtexams`.`class_allotment` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `session_id` INT(11) NULL , `teacher_id` INT(11) NULL , `class_id` INT(11) NULL , `status` TINYINT(1) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
     */

    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get ClassAllotment
    public function read()
    {
        //Create Query
        $query =
            'SELECT 
            ca.*, 
            s.title AS session,
            t.name AS teacher,
            c.title AS class  
        FROM ' . $this->table . ' AS ca ' .
            'JOIN 
            (' . $this->session_table . ' AS s, ' .
            $this->teacher_table . ' AS t, ' .
            $this->class_table . ' AS c) ' .
            'ON ca.session_id = s.id ' .
            'AND ca.teacher_id = t.id ' .
            'AND ca.class_id = c.id ' .
            'WHERE 1 ' .
            (!empty($this->session_id) ? ' AND ca.session_id = ' . $this->session_id : '') .
            (!empty($this->class_id) ? ' AND ca.class_id = ' . $this->class_id : '') .
            (!empty($this->teacher_id) ? ' AND ca.teacher_id = ' . $this->teacher_id : '') .
            (!empty($this->id) ? ' AND ca.id = ' . $this->id : '');

        //Prepared statement
        $stmt = $this->conn->prepare($query);

        //Execute the query
        $stmt->execute();

        return $stmt;
    }

    //Create ClassAllotment
    public function create()
    {
        //Create query
        $query = 'INSERT INTO ' .
            $this->table .
            ' SET 
            session_id = :session_id,
            teacher_id = :teacher_id,
            class_id = :class_id,
            status = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data
        $this->session_id = htmlspecialchars(strip_tags(trim($this->session_id)));
        $this->teacher_id = htmlspecialchars(strip_tags(trim($this->teacher_id)));
        $this->class_id = htmlspecialchars(strip_tags(trim($this->class_id)));

        //bind data
        $stmt->bindParam('session_id', $this->session_id);
        $stmt->bindParam('teacher_id', $this->teacher_id);
        $stmt->bindParam('class_id', $this->class_id);

        //execute query
        if ($stmt->execute()) {
            return true;
        }

        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }


    //Update ClassAllotment
    public function update()
    {
        //Create query
        $query = 'UPDATE ' .
            $this->table .
            ' SET 
            session_id = :session_id, 
            teacher_id = :teacher_id, 
            class_id = :class_id,
            status = :status
            WHERE id = :id';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data
        $this->id = htmlspecialchars(strip_tags(trim($this->id)));
        $this->session_id = htmlspecialchars(strip_tags(trim($this->session_id)));
        $this->teacher_id = htmlspecialchars(strip_tags(trim($this->teacher_id)));
        $this->class_id = htmlspecialchars(strip_tags(trim($this->class_id)));
        $this->status = htmlspecialchars(strip_tags(trim($this->status)));

        //bind data
        $stmt->bindParam('id', $this->id);
        $stmt->bindParam('session_id', $this->session_id);
        $stmt->bindParam('teacher_id', $this->teacher_id);
        $stmt->bindParam('class_id', $this->class_id);
        $stmt->bindParam('status', $this->status);

        //execute query
        if ($stmt->execute()) {
            return true;
        }

        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }


    //delete ClassAllotment
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
