<?php

class Csd {

	//CSD class properties
	private $csdinbound_table = "csdinbound";
	private $inbound_callstatus_table = "inbound_callstatus";
    private $csdoutbound = "outbound";
	private $logs_table = "logs";
	private $conn;
	public $extension;
	public $callerid;
	public $username;
	public $receive_calls;

   //create database connection  when this class instantiated
    public function __construct($db){
    	$this->conn = $db;
    }

    public function getAll() {
        // build query
        $query = "SELECT * FROM ".$this->csdinbound_table."";

        //prepare the query

        $stmnt = $this->conn->prepare($query);
        //execute
        $stmnt->execute();
        return $stmnt;
    }

    public function getSingle($extension) {
        // build query
        $query = "SELECT * FROM ".$this->csdinbound_table." WHERE extension=?";

        //prepare the query

        $stmnt = $this->conn->prepare($query);

        //bind values 
        
        $stmnt->bindParam(1,$extension);

        //execute
        $stmnt->execute();
        return $stmnt;
    }

	public function changeExten($exten){
		$query = "`csdinbound` SET `extension`='9000' WHERE `username`='ROG'";
		
	}
    public function getActiveChannels($extension){
        $query = "SELECT * FROM `sip_channels` WHERE `extension`=?";
        $stmnt = $this->conn->prepare($query);
        $stmnt->bindParam(1,$extension);
        $stmnt->execute();
        return $stmnt->fetch();
   
    }  
    public function active_inactive($receive_calls){
    	// build query
    	$query = "SELECT * FROM ".$this->csdinbound_table." WHERE receive_calls=?";

    	//prepare the query

    	$stmnt = $this->conn->prepare($query);

    	//bind values 
    	$this->receive_calls = $receive_calls;
    	$stmnt->bindParam(1,$this->receive_calls);

    	//execute
    	$stmnt->execute();
    	return $stmnt;
    }
    public function loginLogoutDetails(){
    		$query = "SELECT * FROM  ".$this->logs_table." WHERE extension=?  ORDER BY timestamp DESC";

    		//prepare query
    		$stmnt = $this->conn->prepare($query);

    		//bind values 
    		$stmnt->bindParam(1, $this->extension);

    		$stmnt->execute();
    		return $stmnt;
    }

    public function login_logout_duration ($log,$extension){
        $query = "SELECT * FROM ".$this->logs_table." WHERE log=? AND extension=? ORDER by timestamp DESC LIMIT 1;"; // the question mark(?) is a place holder

        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values from question mark (?) place holder.
        $stmnt->bindParam(1,$log);
        $stmnt->bindParam(2,$extension);

        $stmnt->execute();

        $row = $stmnt->fetch(PDO::FETCH_ASSOC);
         $currenttimestamp = time();
                                      
         $duration =  ($currenttimestamp - strtotime($row['timestamp']));

         //make H:m:s time format
         $duration = $this->secToHR($duration);

         return $duration;
    }

   
    public function call_summary($getdate){
         $currentdate = date('Y-m-d');

        if(strtotime($getdate) > strtotime($currentdate)){
            echo json_encode(array ("message" => "No Records Found"));
            exit();
        }
    	//build query
    	$query = "SELECT * FROM ".$this->csdinbound_table."  ";

    	//prepare the query

    	$stmnt = $this->conn->prepare($query);
       

 
    	if($stmnt->execute()){
            $calls_summary = array();
    		while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
                $totalAgentTimeStamp = $this->getTotalAgentTimeStamp($getdate,$row['extension']);

                //total answer calls of each agent
                $totalanswered = $totalAgentTimeStamp->rowCount();

                //This section calculate the total call duration of each agents..
                 $total=0;
                 while($row_calls = $totalAgentTimeStamp->fetch(PDO::FETCH_ASSOC)) {
                 	$endtime = explode("-", $row_calls['EndTimeStamp']);
                    $startime = explode("-", $row_calls['StartTimeStamp']);
					$total = $total + ( (strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );  
				 } 
				 //make H:m:s time format
                 $total_duration = $this->secToHR($total);

            $calls_agent_summary = array(
            	"extension" => $row['extension'],
            	"name" => $row['username'],
            	"total_answered" => $totalanswered,
            	"total_duration" => $total_duration,
            	"getdate" => $getdate,
                "call_details" => "agent_call_details.php?extension=" .$row['extension'] . "&username=" .$row['username'] . "&getdate=" .$getdate
                
            );
            array_push($calls_summary, $calls_agent_summary);
    		}
            
    		echo json_encode($calls_summary);
    	}else {
    		echo json_encode(array ("message" => "No Records Found"));
    	}


    }

    public function agentCallDetails($extension,$username,$getdate){

    	//build query
    	$query = "SELECT * FROM  ".$this->inbound_callstatus_table." WHERE WhoAnsweredCall=? AND getDate=?";

    	//prepare the query
    	$stmnt = $this->conn->prepare($query);

    	//bind values
    	$stmnt->bindParam(1,$extension);
    	$stmnt->bindParam(2,$getdate);

    	$stmnt->execute();

    	$num = $stmnt->rowCount();

    	if ($num != 0 ){

    		$agent_calls_details = array();

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
                $base_url = "http://211.0.128.110/callrecording/incoming/";
                $date_folder = str_replace('-',"", $row['getDate']);
                $filename = $row['Caller'] .'-'. $row['CalledNumber'] .'-' .$row['StartTimeStamp']. ".mp3";
                $full_url = $base_url . $date_folder .'/'.$filename;

				 $agent = array(
                    "name" => $username,
					"extension" => $extension,
					"calledNumber" => $row['CalledNumber'],
					"caller" => $row['Caller'],
					"callStatus" => $row['CallStatus'],
                    "startime" => date( "h:i:s a",$StartTime),
                    "endtime" =>  date("h:i:s a",$EndTime),
					"callDuration" => $duration,
                    "callrecording" => $full_url,
					"getDate" => $row['getDate'],
                    "comment" => $row['comment'],
                    "startimestamp" => $row['StartTimeStamp']
				);
				array_push($agent_calls_details, $agent);
			}
			//http_response_code(201);
			echo json_encode($agent_calls_details);
    	}else{
    		echo json_encode(array ("message" => "No Records Found"));
    	}
    }
    
    public function putComment($startimestamp, $getdate, $whoansweredcall, $comment) {
      
       $query = "UPDATE `inbound_callstatus` SET `comment`='$comment' WHERE `StartTimeStamp`='$startimestamp' AND `getDate`='$getdate' AND `WhoAnsweredCall`='$whoansweredcall'";
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

     public function putoutboundComment($startimestamp, $getdate, $caller, $comment) {
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

    public function updateCSDAgent($extension,$name,$email) {
      
      // $query = "UPDATE `csdinbound` SET `comment`='$comment' WHERE `StartTimeStamp`='$startimestamp' AND `getDate`='$getdate' AND `WhoAnsweredCall`='$whoansweredcall'";
       $query = "UPDATE `csdinbound` SET `extension`='$extension',`username`='$name',`email`='$email' WHERE `extension`='$extension'";
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
    public function getTotalAgentTimeStamp($getdate, $extension){

    	$query = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->inbound_callstatus_table." WHERE getDate=? AND CallStatus='ANSWER'  AND WhoAnsweredCall=?";

    	$stmnt = $this->conn->prepare($query);

    	//bind values
    	$stmnt->bindParam(1,$getdate);
    	$stmnt->bindParam(2,$extension);

    	//execute
    	$stmnt->execute();
    	return $stmnt;

    }

    public function getCsdOutboundSummary($getdate){
        $currentdate = date('Y-m-d');

        if(strtotime($getdate) > strtotime($currentdate)){
            echo json_encode(array ("message" => "No Records Found"));
            exit();
        }

        // build the query
        $query = "SELECT * FROM ".$this->csdinbound_table."  ";
        
        //prepare the query
        $stmnt = $this->conn->prepare($query);
    
        if($stmnt->execute()){
            $outbound_summary = array();
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
                 


                 $outbound_agent_summary = array(
                    "extension" => $row['extension'],
                    "username" => $row['username'],
                    "totalmadecalls" => $totalMadeCalls,
                    "totalduration" => $total_duration,
                    "getdate" => $getdate,
                    "calldetails" => "outbound_agent_call_details.php?extension=" . $row['extension'] . "&username=" . $row['username'] . "&getdate=" . $getdate
                    
                 );

                
                 array_push($outbound_summary, $outbound_agent_summary);
             }
            echo json_encode($outbound_summary);
                 
        }
    
    }

     public function csdOutboundCallDetails($extension,$username,$getdate){
         //  build query
        $query = "SELECT * FROM " . $this->csdoutbound . " WHERE Caller=? AND CallStatus='ANSWER' AND getDate=?";
        // $query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->collectionteam_callsummary." WHERE getDate='2019-09-13' AND CallStatus='ANSWER'  AND Caller ='6340'";
        // //prepare the query
         $stmnt = $this->conn->prepare($query);
          
         //bind values from question mark (?) place holder
         $stmnt->bindParam(1, $extension);
         $stmnt->bindParam(2, $getdate);

         $stmnt->execute();

         $num = $stmnt->rowCount();


        if ($num != 0 ){

            $csdoutbound_calls_details = array();

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
                    "username" => $username,
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
                array_push($csdoutbound_calls_details, $agent);
            }
            //http_response_code(201);
            echo json_encode($csdoutbound_calls_details);
        }else{
            echo json_encode(array ("message" => "No Records Found"));
        }
     }

    public function getAgentTotalRecords($extension, $getdate) {

        //build query
        $query  = "SELECT StartTimeStamp,EndTimeStamp FROM ".$this->csdoutbound." WHERE getDate=? AND CallStatus='ANSWER'  AND Caller =?";
        
        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values from question mark (?) place holder.
         $stmnt->bindParam(1,$getdate);
         $stmnt->bindParam(2,$extension);

        //execute 

       $stmnt->execute();
  
       return $stmnt;
     

    }

     public function createAgent() {
        //create query

        $query = " INSERT INTO " . $this->csdinbound_table . " SET  extension = :extension, username = :name, email = :email";

        // prepare queery
        $stmnt = $this->conn->prepare($query);

        // sanitize
        $this->extension = htmlspecialchars(strip_tags($this->extension));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        //bind values
        $stmnt->bindParam(":extension", $this->extension);
        $stmnt->bindParam(":name", $this->username);
        $stmnt->bindParam(":email", $this->email);

        //execute query
        if($stmnt->execute()){
            return true;

        }
        return false;
    }
    public function deleteAgent() {
    // sanitize
    $this->extension=htmlspecialchars(strip_tags($this->extension));

    
    //delete query
    $query = "DELETE FROM `csdinbound` WHERE `extension`='$this->extension'";
 
    // prepare query
    $stmnt = $this->conn->prepare($query);
 
    $stmnt->execute();
 
     $count = $stmnt->rowCount();
        if($count !=0){
                 //delete the agent records  if there are.
                 $this->deleteAgentRecordings($this->extension);
                 $this->deleteAgentLogs($this->extension);
                 echo json_encode(array("message" => "Agent Successfully Deleted"));
        }else{
             echo json_encode(array("message" => "Agent Cannot be Deleted"));
        }
     }
     private function deleteAgentRecordings($extension){
            $query = "DELETE FROM `inbound_callstatus` WHERE `WhoAnsweredCall`='$extension'";

            $stmnt = $this->conn->prepare($query);

            $stmnt->execute();
            $count = $stmnt->rowCount();
           
     }
     private function deleteAgentLogs($extension){
        $query = "DELETE FROM `logs` WHERE `extension`='$extension'";

            $stmnt = $this->conn->prepare($query);

            $stmnt->execute();
            $count = $stmnt->rowCount();
     }

     

     private function secToHR($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
         return "$hours:$minutes:$seconds";
    }

    
}