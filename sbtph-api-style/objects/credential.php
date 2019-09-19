<?php

class Credential {

	//Credential Properties

	private $logintable = "login";
	private $conn;
	public $extension;
	public $secret;
	public $name;
	public $position;


	//create database connection  when this class instantiated
    public function __construct($db){
    	$this->conn = $db;
    }


    public function checkUser (){
    	$query = "SELECT *
            FROM " . $this->logintable . "
            WHERE extension = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        //sanitize
      //  $this->extension = htmlspecialchars(strip_tags($this->extension));

        //bind params

        
        
        $stmt->bindParam(1, $this->extension);

        //execute

        $stmt->execute();


        //get the number of rows

        $num = $stmt->rowCount();
 
        if($num>0){
        	//get record details
        	$row = $stmt->fetch(PDO::FETCH_ASSOC);

        	$this->extension = $row['extension'];
        	$this->secret = $row['secret'];
        	$this->name = $row['name'];
        	$this->position = $row['position'];

            return true;
        } else{
        	return false;
        }
	

	}




	public function test() {
		echo $this->extension;
	}









}
?>