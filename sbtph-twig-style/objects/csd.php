<?php

class Csd {

	//CSD class properties
	private $csdinbound_table = "csdinbound";
	private $inbound_callstatus_table = "inbound_callstatus";
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
					"getDate" => $row['getDate']
				);
				array_push($agent_calls_details, $agent);
			}
			//http_response_code(201);
			echo json_encode($agent_calls_details);
    	}else{
    		echo json_encode(array ("message" => "No Records Found"));
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

     private function secToHR($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
         return "$hours:$minutes:$seconds";
    }

    
}