<?php
include_once('Functions.php');

class Student
{
    //DB stuff
    private $conn;
    private $table = 'student';
    private $class_table = 'class';

    //Student Properties
    public $id;
    public $lastname;
    public $firstname;
    public $middlename;
    public $gender;
    public $phone;
    public $username;
    public $password;
    public $guardian_phone;
    public $address;
    public $guardian_name;
    public $adm_no;
    public $class_id;
    public $house_id;
    public $photo;
    public $status;



    // CREATE TABLE `cbtexams`.`student` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `lastname` VARCHAR(50) NULL , `firstname` VARCHAR(50) NULL , `middlename` INT(50) NULL , `gender` VARCHAR(10) NULL , `phone` VARCHAR(20) NULL , `username` VARCHAR(50) NULL , `password` VARCHAR(50) NULL , `guardian_phone` VARCHAR(20) NULL , `address` VARCHAR(191) NULL , `guardian_name` VARCHAR(191) NULL , `adm_no` VARCHAR(50) NULL , `class_id` INT(11) NULL , `house_id` INT(11) NULL , `photo` VARCHAR(191) NULL , `status` TINYINT(1) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 



    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Student
    public function read()
    {
        $this->id = Functions::sanitize($this->id);
        $this->gender = Functions::sanitize($this->gender);
        $this->house_id = Functions::sanitize($this->house_id);
        $this->class_id = Functions::sanitize($this->class_id);
        //Create Query
        $query =
            'SELECT 
            s.*, 
            c.title AS class
        FROM ' . $this->table . ' AS s ' .
            ' JOIN ' .  $this->class_table . ' AS c ' .
            ' ON s.class_id = c.id ' .
            'WHERE 1 ' .
            (!empty($this->class_id) ? ' AND s.class_id = ' . $this->class_id : '') .
            (!empty($this->gender) ? ' AND s.gender = "' . $this->gender . '"' : '') .
            (!empty($this->id) ? ' AND s.id = ' . $this->id : '') .
            ' ORDER BY class ASC ';
        //Prepared statement
        $stmt = $this->conn->prepare($query);
        //Execute the query
        $stmt->execute();
        return $stmt;
    }

    //Create Student
    public function create()
    {
        $this->lastname = Functions::sanitize($this->lastname);
        $this->firstname = Functions::sanitize($this->firstname);
        $this->middlename = Functions::sanitize($this->middlename);
        $this->gender = Functions::sanitize($this->gender);
        $this->phone = Functions::sanitize($this->phone);
        $this->username = Functions::sanitize($this->username);
        $this->password = Functions::sanitize($this->password);
        $this->guardian_phone = Functions::sanitize($this->guardian_phone);
        $this->address = Functions::sanitize($this->address);
        $this->guardian_name = Functions::sanitize($this->guardian_name);
        $this->adm_no = Functions::sanitize($this->adm_no);
        $this->class_id = Functions::sanitize($this->class_id);
        $this->house_id = Functions::sanitize($this->house_id);
        $this->photo = Functions::sanitize($this->photo);

        //Create query
        $query = 'INSERT INTO ' .
            $this->table .
            ' SET
            lastname = :lastname,
            firstname = :firstname,
            middlename = :middlename,
            gender = :gender,
            phone = :phone,
            guardian_phone = :guardian_phone,
            address = :address,
            guardian_name = :guardian_name,
            adm_no = :adm_no,
            class_id = :class_id,
            house_id = :house_id,
            photo = :photo,
            status = 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam('lastname', $this->lastname);
        $stmt->bindParam('firstname', $this->firstname);
        $stmt->bindParam('middlename', $this->middlename);
        $stmt->bindParam('gender', $this->gender);
        $stmt->bindParam('phone', $this->phone);
        $stmt->bindParam('guardian_phone', $this->guardian_phone);
        $stmt->bindParam('address', $this->address);
        $stmt->bindParam('guardian_name', $this->guardian_name);
        $stmt->bindParam('adm_no', $this->adm_no);
        $stmt->bindParam('class_id', $this->class_id);
        $stmt->bindParam('house_id', $this->house_id);
        $stmt->bindParam('photo', $this->photo);

        //execute query
        if ($stmt->execute()) {
            return true;
        }

        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }


    //Update Student
    public function update()
    {
        $this->id = Functions::sanitize($this->id);
        $this->lastname = Functions::sanitize($this->lastname);
        $this->firstname = Functions::sanitize($this->firstname);
        $this->middlename = Functions::sanitize($this->middlename);
        $this->gender = Functions::sanitize($this->gender);
        $this->phone = Functions::sanitize($this->phone);
        $this->password = !empty($this->password) ? md5(Functions::sanitize($this->password)) : null;
        $this->guardian_phone = Functions::sanitize($this->guardian_phone);
        $this->address = Functions::sanitize($this->address);
        $this->guardian_name = Functions::sanitize($this->guardian_name);
        $this->adm_no = Functions::sanitize($this->adm_no);
        $this->class_id = Functions::sanitize($this->class_id);
        $this->house_id = Functions::sanitize($this->house_id);
        $this->photo = Functions::sanitize($this->photo);
        $this->status = 1;
        //Create query
        $query = 'UPDATE ' . $this->table . ' SET ' . Functions::prepareUpdateData($this) . ' WHERE id = ' . $this->id;
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //execute query
        if ($stmt->execute()) {
            return true;
        }
        // print error if something goes wrong
        printf("Error: %s, \n", json_encode($stmt->errorInfo()));

        return false;
    }


    //delete Student
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
