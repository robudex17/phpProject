<?php

class Sales {

	//CSD class properties
	private $salesteam = "salesteam";
	private $salesteam_callsummary_table = "outbound";
	private $conn;
	public $extension;
	public $name;
	public $email;
    public $teamlead;
	
   //create database connection  when this class instantiated
    public function __construct($db){
    	$this->conn = $db;
    }
    public function getAll() {
        // build query
        $query = "SELECT * FROM ".$this->salesteam."";

        //prepare the query

        $stmnt = $this->conn->prepare($query);
        //execute
        $stmnt->execute();
        return $stmnt;
    }
    public function getSingle($extension) {
        // build query
        $query = "SELECT * FROM ".$this->salesteam." WHERE extension=?";

        //prepare the query

        $stmnt = $this->conn->prepare($query);

        //bind values 
        
        $stmnt->bindParam(1,$extension);

        //execute
        $stmnt->execute();
        return $stmnt;
    }
     public function getComment($caller,$getdate,$startimestamp) {
        //build query
        $query = "SELECT * FROM  ".$this->salesteam_callsummary_table." WHERE Caller=? AND getDate=? AND StartTimeStamp=?";

        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values
        $stmnt->bindParam(1,$caller);
        $stmnt->bindParam(2,$getdate);
        $stmnt->bindParam(3,$startimestamp);

        $stmnt->execute();

        $num = $stmnt->rowCount();
   
        if ($num != 0 ){
              $row = $stmnt->fetch(PDO::FETCH_ASSOC);
             $sales_comment = array("comment" => $row['comment']);
             echo json_encode($sales_comment);
            
        }else{
            echo json_encode(array ("comment" => "No comment"));
        }
    }
    public function sales_summary($getdate){
    	$currentdate = date('Y-m-d');

        if(strtotime($getdate) > strtotime($currentdate)){
            echo json_encode(array ("message" => "No Records Found"));
            exit();
        }

        // build the query
        $query = "SELECT * FROM ".$this->salesteam." ORDER BY teamlead ";
        
        //prepare the query
    	$stmnt = $this->conn->prepare($query);
    
    	if($stmnt->execute()){
    		$sales_summary = array();
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
                 


                 $sales_agent_summary = array(
                	"extension" => $row['extension'],
                 	"name" => $row['name'],
                    "teamlead" => $row['teamlead'],
                 	"totalmadecalls" => $totalMadeCalls,
                 	"totalduration" => $total_duration,
                 	"getdate" => $getdate,
                 	"calldetails" => "sales_agent_call_details.php?extension=" . $row['extension'] . "&name=" . $row['name'] . "&getdate=" . $getdate
                    
                 );

                
                 array_push($sales_summary, $sales_agent_summary);
    		 }
    		echo json_encode($sales_summary);
                 
    	}
    

    }

     public function sales_summary_export($getdate){
        $currentdate = date('Y-m-d');

        if(strtotime($getdate) > strtotime($currentdate)){
            echo json_encode(array ("message" => "No Records Found"));
            exit();
        }

        // build the query
        $query = "SELECT * FROM ".$this->salesteam." ORDER BY teamlead ";
        
        //prepare the query
        $stmnt = $this->conn->prepare($query);
    
        if($stmnt->execute()){
            
             $sales_agent_summary_template_json = file_get_contents("/var/www/html/sbtph_qc_pub/json/sales_call_summary.json");
            //make an object 
            $sales_summary_call_details_obj = json_decode($sales_agent_summary_template_json, FALSE);
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
                 
                 $sales_agent_summary = array();
                 //put each field to each array
                 $array_extension = array("text" => $row['extension']);
                 $array_name = array("text" => $row['name']);
                 $array_teamlead = array("text" => $row['teamlead']);
                 $array_totalmadecalls = array("text" =>  $totalMadeCalls);
                 $array_totalduration = array("text" => $total_duration);
                 $array_getdate = array("text" => $getdate);

                 //push it one by one
                 array_push($sales_agent_summary,$array_extension);
                 array_push($sales_agent_summary, $array_name);
                 array_push($sales_agent_summary, $array_teamlead);
                 array_push($sales_agent_summary, $array_totalmadecalls);
                 array_push($sales_agent_summary, $array_totalduration);
                 array_push($sales_agent_summary, $array_getdate);
               
                 array_push($sales_summary_call_details_obj->tableData[0]->data, $sales_agent_summary);
                
                 
             }
            //echo as json
            echo json_encode($sales_summary_call_details_obj);
                 
        }
    

    }

    public function getAgentTotalRecords($extension, $getdate) {

    	//build query
    	$query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->salesteam_callsummary_table." WHERE getDate=? AND CallStatus='ANSWER'  AND Caller =?";
    	
        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values from question mark (?) place holder.
         $stmnt->bindParam(1,$getdate);
         $stmnt->bindParam(2,$extension);

        //execute 

       $stmnt->execute();
  
       return $stmnt;
     

    }

     public function searchCallDetails($calledNumber){

      //  build query
        $query = "SELECT * FROM " . $this->salesteam_callsummary_table . " WHERE CalledNumber=? AND CallStatus='ANSWER'";
        // $query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary." WHERE getDate='2019-09-13' AND CallStatus='ANSWER'  AND Caller ='6340'";
        // //prepare the query
         $stmnt = $this->conn->prepare($query);
          
         //bind values from question mark (?) place holder
         $stmnt->bindParam(1, $calledNumber);
         

         $stmnt->execute();

         $num = $stmnt->rowCount();

        
        if ($num != 0 ){

            $sales_calls_details = array();

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
                
                $get_single_agent =  $this->getSingle($row['Caller']);
                $agent_row = $get_single_agent->fetch(PDO::FETCH_ASSOC);

                 $agent = array(
                    "agent" => $agent_row['name'],
                    "extension" => $agent_row['extension'],
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
                array_push($sales_calls_details, $agent);
            }
            //http_response_code(201); 
           
            echo json_encode($sales_calls_details);
        }else{
            echo json_encode(array ("message" => "No Records Found"));
        }

        
     }


    public function salesCallDetails($extension,$name,$getdate){

      //  build query
    	$query = "SELECT * FROM " . $this->salesteam_callsummary_table . " WHERE Caller=? AND CallStatus='ANSWER' AND getDate=?";
    	// $query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary." WHERE getDate='2019-09-13' AND CallStatus='ANSWER'  AND Caller ='6340'";
    	// //prepare the query
    	 $stmnt = $this->conn->prepare($query);
          
    	 //bind values from question mark (?) place holder
    	 $stmnt->bindParam(1, $extension);
    	 $stmnt->bindParam(2, $getdate);

    	 $stmnt->execute();

    	 $num = $stmnt->rowCount();


    	if ($num != 0 ){

    		$sales_calls_details = array();

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
				array_push($sales_calls_details, $agent);
			}
			//http_response_code(201);
			echo json_encode($sales_calls_details);
    	}else{
    		echo json_encode(array ("message" => "No Records Found"));
    	}

    	
     }

      public function salesCallDetailsExport($extension,$name,$getdate){

      //  build query
        $query = "SELECT * FROM " . $this->salesteam_callsummary_table . " WHERE Caller=? AND CallStatus='ANSWER' AND getDate=?";
        // $query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary." WHERE getDate='2019-09-13' AND CallStatus='ANSWER'  AND Caller ='6340'";
        // //prepare the query
         $stmnt = $this->conn->prepare($query);
          
         //bind values from question mark (?) place holder
         $stmnt->bindParam(1, $extension);
         $stmnt->bindParam(2, $getdate);

         $stmnt->execute();

         $num = $stmnt->rowCount();


        if ($num != 0 ){

            $sales_agent_call_details_template_json = file_get_contents("/var/www/html/sbtph_qc/json/sales_agent_call_details.json");
            //make an object 
            $sales_agent_call_details_obj = json_decode($sales_agent_call_details_template_json, FALSE);

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

                 $agent = array();
                //put each field to each array
                $array_name = array("text" => $name);
                $array_extension = array("text" => $extension);
                $array_calledNumber = array("text" => $row['CalledNumber']);
                $array_caller = array("text" => $row['Caller'] );
                $array_callStatus = array("text" => $row['CallStatus']);
                $array_startime = array("text" => date( "h:i:s a",$StartTime));
                $array_endtime = array("text" => date("h:i:s a",$EndTime));
                $array_callDuration = array("text" => $duration );
                $array_callrecording = array("text" => $full_url);
                $array_getDate = array("text" => $row['getDate']);
                $array_comment = array("text" =>  $row['comment']);
                
                //push it 
                array_push($agent,$array_name);
                array_push($agent, $array_extension);
                array_push($agent,$array_calledNumber);
                array_push($agent,$array_caller);
                array_push($agent, $array_callStatus);
                array_push($agent,$array_startime);
                array_push($agent, $array_endtime);
                array_push($agent, $array_callDuration);
                array_push($agent,$array_callrecording);
                array_push($agent, $array_getDate);
                array_push($agent,$array_comment);

               
                array_push($sales_agent_call_details_obj->tableData[0]->data, $agent);
            }
            //http_response_code(201);
            echo json_encode($sales_agent_call_details_obj);
        }else{
            echo json_encode(array ("message" => "No Records Found"));
        }

        
     }


     public function putsalesComment($startimestamp, $getdate, $caller, $comment) {
        //build query
    
       $query = "UPDATE `outbound` SET `comment`='$comment' WHERE `StartTimeStamp`='$startimestamp' AND `getDate`='$getdate' AND `Caller`='$caller'";
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

      public function updateSalesAgent() {
      
      	 $query = "UPDATE `salesteam` SET `extension`='$this->extension',`name`='$this->name',`email`='$this->email', `teamlead`='$this->teamlead'  WHERE `extension`='$this->extension'";
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

        $query = " INSERT INTO " . $this->salesteam . " SET  extension = :extension, name = :name, email = :email, teamlead = :teamlead";

        // prepare queery
        $stmnt = $this->conn->prepare($query);

        // sanitize
        $this->extension = htmlspecialchars(strip_tags($this->extension));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->teamlead = htmlspecialchars(strip_tags($this->teamlead));

        //bind values
        $stmnt->bindParam(":extension", $this->extension);
        $stmnt->bindParam(":name", $this->name);
        $stmnt->bindParam(":email", $this->email);
        $stmnt->bindParam(":teamlead",$this->teamlead);

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
    $query = "DELETE FROM `salesteam` WHERE `extension`='$this->extension'";
 
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
            $query = "DELETE FROM `outbound` WHERE `Caller`='$extension'";

            $stmnt = $this->conn->prepare($query);

            $stmnt->execute();
            $count = $stmnt->rowCount();
           
     }



}

?>