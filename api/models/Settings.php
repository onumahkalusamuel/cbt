<?php
include_once('Functions.php');

class Settings
{
    //DB stuff
    private $conn;
    private $table = 'settings';

    //Exam Properties
    public $id;
    public $setting;
    public $value;


    // CREATE TABLE `cbtexams`.`settings` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `setting` VARCHAR(191) NULL , `value` TEXT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 


    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Exam
    public function read()
    {
        $this->id = Functions::sanitize($this->id);
        $this->setting = Functions::sanitize($this->setting);
        //Create Query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE 1 '
            . (!empty($this->id) ? ' AND id = ' . $this->id : '')
            . (!empty($this->setting) ? ' AND setting = "' . $this->setting . '"' : '');
        //Prepared statement
        $stmt = $this->conn->prepare($query);
        //Execute the query
        $stmt->execute();
        return $stmt;
    }

    //Create Exam
    public function create()
    {

        $this->setting = Functions::sanitize($this->setting);
        $this->value = Functions::sanitize($this->value);

        // check if its existing first        
        $query = 'SELECT * FROM ' . $this->table . ' WHERE setting = "' . $this->setting . '"';
        $stmt = $this->conn->prepare($query);
        //Execute the query

        $stmt->execute();

        if ($stmt->rowCount() !== 0) return false;

        //continue if it doesnt exist
        //Create query
        $query = 'INSERT INTO ' . $this->table . ' SET
            setting = :setting,
            value = :value';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('setting', $this->setting);
        $stmt->bindParam('value', $this->value);

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
        $this->id = Functions::sanitize($this->id);
        $this->setting = Functions::sanitize($this->setting);
        $this->value = Functions::sanitize($this->value);
        //Create query
        $query = 'UPDATE ' .
            $this->table .
            ' SET 
            value = :value
        WHERE id = :id 
        OR setting = :setting';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('id', $this->id);
        $stmt->bindParam('setting', $this->setting);
        $stmt->bindParam('value', $this->value);

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
        $this->setting = Functions::sanitize($this->setting);
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id OR setting = :setting';

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
