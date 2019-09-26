<?php

class Collection {

	//CSD class properties
	private $collectionteam = "collectionteam";
	private $collectionteam_callsummary = "collectionteam_callsummary";
	private $conn;
	public $extension;
	public $name;
	public $email;
	
   //create database connection  when this class instantiated
    public function __construct($db){
    	$this->conn = $db;
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
    	$query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary." WHERE getDate=? AND CallStatus='ANSWER'  AND Caller =?";
    	
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
    	$query = "SELECT * FROM " . $this->collectionteam_callsummary . " WHERE Caller=? AND CallStatus='ANSWER' AND getDate=?";
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
					"getDate" => $row['getDate']
				);
				array_push($collection_calls_details, $agent);
			}
			//http_response_code(201);
			echo json_encode($collection_calls_details);
    	}else{
    		echo json_encode(array ("message" => "No Records Found"));
    	}

    	
     }

    public function test(){
    	echo "test";

    }



    private function secToHR($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
         return "$hours:$minutes:$seconds";
    }

}

?>