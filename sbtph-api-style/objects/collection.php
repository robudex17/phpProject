<?php

class Collection {

	//CSD class properties
	private $collectionteam = "collectionteam";
	private $collectionteam_callsummary_table = "collectionteam_callsummary";
	private $conn;
	public $extension;
	public $name;
	public $email;
	
   //create database connection  when this class instantiated
    public function __construct($db){
    	$this->conn = $db;
    }
    public function getAll() {
        // build query
        $query = "SELECT * FROM ".$this->collectionteam."";

        //prepare the query

        $stmnt = $this->conn->prepare($query);
        //execute
        $stmnt->execute();
        return $stmnt;
    }
    public function collection_summary($getdate){
    	$currentdate = date('Y-m-d');

        if(strtotime($getdate) > strtotime($currentdate)){
            echo json_encode(array ("message" => "No Records Found"));
            exit();
        }

        // build the query
        $query = "SELECT * FROM ".$this->collectionteam."  ";
        
        //prepare the query
    	$stmnt = $this->conn->prepare($query);
    
    	if($stmnt->execute()){
    		$collection_summary = array();
    		while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
    			$getAgentTotalRecords = $this->getAgentTotalRecords($row['extension'],$getdate);

    			 //total answer calls of each agent
    			$totalMadeCalls = $getAgentTotalRecords->rowCount();
    			//This section calculate the total call duration of each agents..
                 $total=0;
                 while($row_calls = $getAgentTotalRecords->fetch(PDO::FETCH_ASSOC)) {
                 	$endtime = explode("-", $row_calls['EndTimeStamp']);
                    $startime = explode("-", $row_calls['StartTimeStamp']);
					$total = $total + ( (strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );  
				 } 
				// make H:m:s time format
                 $total_duration = $this->secToHR($total);
                 


                 $collection_agent_summary = array(
                	"extension" => $row['extension'],
                 	"name" => $row['name'],
                 	"totalmadecalls" => $totalMadeCalls,
                 	"totalduration" => $total_duration,
                 	"getdate" => $getdate,
                 	"calldetails" => "collection_agent_call_details.php?extension=" . $row['extension'] . "&name=" . $row['name'] . "&getdate=" . $getdate
                    
                 );

                
                 array_push($collection_summary, $collection_agent_summary);
    		 }
    		echo json_encode($collection_summary);
                 
    	}
    

    }

    public function getAgentTotalRecords($extension, $getdate) {

    	//build query
    	$query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary_table." WHERE getDate=? AND CallStatus='ANSWER'  AND Caller =?";
    	
        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values from question mark (?) place holder.
         $stmnt->bindParam(1,$getdate);
         $stmnt->bindParam(2,$extension);

        //execute 

       $stmnt->execute();
  
       return $stmnt;
     

    }

    public function collectionCallDetails($extension,$name,$getdate){

      //  build query
    	$query = "SELECT * FROM " . $this->collectionteam_callsummary_table . " WHERE Caller=? AND CallStatus='ANSWER' AND getDate=?";
    	// $query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary." WHERE getDate='2019-09-13' AND CallStatus='ANSWER'  AND Caller ='6340'";
    	// //prepare the query
    	 $stmnt = $this->conn->prepare($query);
          
    	 //bind values from question mark (?) place holder
    	 $stmnt->bindParam(1, $extension);
    	 $stmnt->bindParam(2, $getdate);

    	 $stmnt->execute();

    	 $num = $stmnt->rowCount();


    	if ($num != 0 ){

    		$collection_calls_details = array();

			while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
				$total=0;
                 echo $row['EndtimeStamp'];
                 $endtime = explode("-", $row['EndTimeStamp']);
                 $startime = explode("-", $row['StartTimeStamp']);
                 $total = $total + ((strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );
                 $duration = $this->secToHR($total);
                 //get start and end calltime
                $StartTime = str_replace("-", " ", $row['StartTimeStamp']);
                $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                $StartTime = strtotime($StartTime);
                $EndTime = strtotime($EndTime);

                //get recordings url
                $base_url = "http://211.0.128.110/callrecording/outgoing/";
                $date_folder = str_replace('-',"", $row['getDate']);
                $filename = $row['Caller'] .'-'. $row['CalledNumber'] .'-' .$row['StartTimeStamp']. ".mp3";
                $full_url = $base_url . $date_folder .'/'.$filename;

				 $agent = array(
                    "name" => $name,
					"caller" => $extension,
					"calledNumber" => $row['CalledNumber'],
					"callStatus" => $row['CallStatus'],
                    "startime" => date( "h:i:s a",$StartTime),
                    "endtime" =>  date("h:i:s a",$EndTime),
					"callDuration" => $duration,
                    "callrecording" => $full_url,
					"getDate" => $row['getDate'],
                    "comment" => $row['comment'],
                    "starttimestamp" => $row['StartTimeStamp']
				);
				array_push($collection_calls_details, $agent);
			}
			//http_response_code(201);
			echo json_encode($collection_calls_details);
    	}else{
    		echo json_encode(array ("message" => "No Records Found"));
    	}

    	
     }

     public function putcollComment($startimestamp, $getdate, $caller, $comment) {
        //build query
    
       $query = "UPDATE `collectionteam_callsummary` SET `comment`='$comment' WHERE `StartTimeStamp`='$startimestamp' AND `getDate`='$getdate' AND `Caller`='$caller'";
        //prepare query
        $stmnt = $this->conn->prepare($query);
       

        //excute
        $stmnt->execute();

        $count = $stmnt->rowCount();
        if($count !=0){
                 echo json_encode(array("message" => "Successfully Updated"));
        }else{
             echo json_encode(array("message" => "Update was not Successfull"));
        }


    }
    public function test(){
    	echo "test";

    }

      public function updateCollectionAgent() {
      
      	 $query = "UPDATE `collectionteam` SET `extension`='$this->extension',`name`='$this->name',`email`='$this->email' WHERE `extension`='$this->extension'";
        //prepare query
        $stmnt = $this->conn->prepare($query);

       
        $stmnt->execute();

       

        $count = $stmnt->rowCount();
        if($count !=0){
                 echo json_encode(array("message" => "Successfully Updated"));
        }else{
             echo json_encode(array("message" => "Update was not Successfull"));
        }
         

    }
    public function createAgent() {
        //create query

        $query = " INSERT INTO " . $this->collectionteam . " SET  extension = :extension, name = :name, email = :email";

        // prepare queery
        $stmnt = $this->conn->prepare($query);

        // sanitize
        $this->extension = htmlspecialchars(strip_tags($this->extension));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));

        //bind values
        $stmnt->bindParam(":extension", $this->extension);
        $stmnt->bindParam(":name", $this->name);
        $stmnt->bindParam(":email", $this->email);

        //execute query
        if($stmnt->execute()){
        	return true;

        }
        return false;
    }

    private function secToHR($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
         return "$hours:$minutes:$seconds";
    }


    public function deleteAgent() {
    // sanitize
    $this->extension=htmlspecialchars(strip_tags($this->extension));

    
    //delete query
    $query = "DELETE FROM `collectionteam` WHERE `extension`='$this->extension'";
 
    // prepare query
    $stmnt = $this->conn->prepare($query);
 
    $stmnt->execute();
 
     $count = $stmnt->rowCount();
        if($count !=0){
                 //delete the agent records  if there are.
                 $this->deleteAgentRecordings($this->extension);
                 
                 echo json_encode(array("message" => "Agent Successfully Deleted"));
        }else{
             echo json_encode(array("message" => "Agent Cannot be Deleted"));
        }
     }
     private function deleteAgentRecordings($extension){
            $query = "DELETE FROM `collectionteam_callsummary` WHERE `Caller`='$extension'";

            $stmnt = $this->conn->prepare($query);

            $stmnt->execute();
            $count = $stmnt->rowCount();
           
     }



}

?>