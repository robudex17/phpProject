<?php

class Collection {

    //CSD class properties
    private $collectionteam = "collectionteam";
    private $collectionteam_callsummary_table = "collectionteam_callsummary";
    private $conn;
    public $extension;
    public $name;
    public $email;
    private $json_addr = "/var/www/html/sbtph_dev/json/";

   //create database connection  when this class instantiated
    public function __construct($db){
        $this->conn = $db;
    }

     public function genMetrics($option,$startDateAndTime,$endDateAndTime,$origstarttime,$orgendtime,$duration_weight,$callcount_weight) {
          // build the query
         $query = "SELECT * FROM ".$this->collectionteam."  ";


        //prepare the query
        $stmnt = $this->conn->prepare($query);

        if($stmnt->execute()){
            $collection_summary = array();
            while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
                $getAgentTotalRecords = $this->getCollectionAgentTotalRecords($startDateAndTime,$endDateAndTime,$row['extension']);

                 //total answer calls of each agent
                $totalMadeCalls = $getAgentTotalRecords->rowCount();

                /*This section calculate the total call duration of each agents..
                  Duration field is newly added  on the table.
                  On the  old records that Duration field is empty, Duraiton is calculated using the End and start timestamp
                */
                 $total_sec=0;
                 while($row_calls = $getAgentTotalRecords->fetch(PDO::FETCH_ASSOC)) {
                   if($row_calls['Duration'] !=0){
                      $total_sec = $total_sec +  $row_calls['Duration'];
                   }else{
                     $endtime = explode("-", $row_calls['EndTimeStamp']);
                     $startime = explode("-", $row_calls['StartTimeStamp']);
                     $total_sec = $total_sec + ( (strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );
                   }

                 }
                // make H:m:s time format
                  $grand_total_duration_sec = $grand_total_duration_sec + $total_sec;
                 $total_duration = $this->secToHR($total_sec);
                 $grand_total_counts = $grand_total_counts + $totalMadeCalls;

                 $collection_agent_summary = array(
                   "extension" => $row['extension'],
                  "name" => $row['name'],
                  "total_answered" => $totalMadeCalls,
                  "total_duration" => $total_duration,
                  "total_sec" => $total_sec
                 );

              array_push($collection_summary, $collection_agent_summary);
             }
            $grand_total_duration = $this->secToHR($grand_total_duration_sec);
            $grand_total = array('option' => $option, 'duration_weight' => $duration_weight,'callcount_weight' =>$callcount_weight,'grand_total_duration_sec' => $grand_total_duration_sec,'grand_total_duration' => $grand_total_duration, 'grand_total_counts' => $grand_total_counts,'datetimeRange' => $origstarttime . ' To ' . $orgendtime);
           $final_results = array();
           array_push($final_results, $grand_total);
           array_push($final_results, $collection_summary);

          echo json_encode($final_results);

        }

    }
      public function getCollectionAgentTotalRecords($startDateAndTime, $endDateAndTime, $extension) {

        //build query
        $query  = "SELECT * FROM ".$this->collectionteam_callsummary_table." WHERE  CallStatus='ANSWER'  AND Caller =? AND StartTimeStamp BETWEEN ? AND ? ORDER BY StartTimeStamp DESC ";

        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values from question mark (?) place holder.

         $stmnt->bindParam(1,$extension);
         $stmnt->bindParam(2,$startDateAndTime);
         $stmnt->bindParam(3,$endDateAndTime);
        //execute

       $stmnt->execute();

       return $stmnt;


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
     public function getSingle($extension) {
        // build query
        $query = "SELECT * FROM ".$this->collectionteam." WHERE extension=?";

        //prepare the query

        $stmnt = $this->conn->prepare($query);
        $stmnt->bindParam(1,$extension);
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

                /*This section calculate the total call duration of each agents..
                  Duration field is newly added  on the table.
                  On the  old records that Duration field is empty, Duraiton is calculated using the End and start timestamp
                */
                 $total=0;
                 while($row_calls = $getAgentTotalRecords->fetch(PDO::FETCH_ASSOC)) {
                     if($row_calls['Duration'] != 0){
                       $total = $total +  $row_calls['Duration'];
                     }else{
                        $endtime = explode("-", $row_calls['EndTimeStamp']);
                        $startime = explode("-", $row_calls['StartTimeStamp']);
                        $total = $total + ( (strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );
                     }

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

    public function collection_summary_export($getdate){
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

             $collection_summary_template_json = file_get_contents($this->json_addr."collection_summary.json");
            //make an object
            $collection_summary_call_details_obj = json_decode($collection_summary_template_json, FALSE);
            while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
                $getAgentTotalRecords = $this->getAgentTotalRecords($row['extension'],$getdate);

                //total answer calls of each agent
               $totalMadeCalls = $getAgentTotalRecords->rowCount();

               /*This section calculate the total call duration of each agents..
                 Duration field is newly added  on the table.
                 On the  old records that Duration field is empty, Duraiton is calculated using the End and start timestamp
               */
                $total=0;
                while($row_calls = $getAgentTotalRecords->fetch(PDO::FETCH_ASSOC)) {
                    if($row_calls['Duration'] != 0){
                      $total = $total +  $row_calls['Duration'];
                    }else{
                       $endtime = explode("-", $row_calls['EndTimeStamp']);
                       $startime = explode("-", $row_calls['StartTimeStamp']);
                       $total = $total + ( (strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );
                    }

                }

                // make H:m:s time format
                 $total_duration = $this->secToHR($total);

                 $collection_summary = array();
                 //put each field to each array
                 $array_extension = array("text" => $row['extension']);
                 $array_name = array("text" => $row['name']);
                 $array_totalmadecalls = array("text" =>  $totalMadeCalls);
                 $array_totalduration = array("text" => $total_duration);
                 $array_getdate = array("text" => $getdate);

                 //push it one by one
                 array_push($collection_summary,$array_extension);
                 array_push($collection_summary, $array_name);
                 array_push($collection_summary, $array_totalmadecalls);
                 array_push($collection_summary, $array_totalduration);
                 array_push($collection_summary, $array_getdate);

                 array_push($collection_summary_call_details_obj->tableData[0]->data, $collection_summary);


             }
            //echo as json
            echo json_encode($collection_summary_call_details_obj);
        }
    }

    public function getAgentTotalRecords($extension, $getdate) {

        //build query
        $query  = "SELECT * FROM ".$this->collectionteam_callsummary_table." WHERE getDate=? AND CallStatus='ANSWER'  AND Caller =?";

        //prepare the query
        $stmnt = $this->conn->prepare($query);

        //bind values from question mark (?) place holder.
         $stmnt->bindParam(1,$getdate);
         $stmnt->bindParam(2,$extension);

        //execute

       $stmnt->execute();

       return $stmnt;

    }
      public function getCollectionComment($caller,$getdate,$startimestamp) {
          //build query
          $query = "SELECT * FROM  ".$this->collectionteam_callsummary_table." WHERE Caller=? AND getDate=? AND StartTimeStamp=?";

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
               $collection_comment = array("comment" => $row['comment']);
               echo json_encode($collection_comment);

          }else{
              echo json_encode(array ("comment" => "No comment"));
          }
    }
    public function searchCalledNumberCallDetails($callednumber){

        $query = "SELECT * FROM " . $this->collectionteam_callsummary_table . " WHERE CalledNumber=? ORDER BY getDate DESC";

         $stmnt = $this->conn->prepare($query);

         //bind values from question mark (?) place holder
         $stmnt->bindParam(1, $callednumber);


         $stmnt->execute();

         $num = $stmnt->rowCount();


        if ($num != 0 ){

            $collection_calls_details = array();

            while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
                 $total=0;
                //Duration Field is new added to the table and the old records has empty duration field so need to used start and end timestamp to compute the duration
                 if($row['Duration'] == ''){
                     $endtime = explode("-", $row['EndTimeStamp']);
                     $startime = explode("-", $row['StartTimeStamp']);
                    $total = $total + ((strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );

                   $StartTime = str_replace("-", " ", $row['StartTimeStamp']);
                   $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                   $StartTime = strtotime($StartTime);
                   $EndTime = strtotime($EndTime);
                 }
                 // this is where the duration is available so no need to compute the duration but need to compute the start timestamp.
                 else{
                     $total = $total + $row['Duration'];
                     $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                     $EndTime = strtotime($EndTime);
                     $StartTime =  $EndTime - $duration;

                 }
                  $duration = $this->secToHR($total);


                //get recordings url
                $base_url = "http://211.0.128.110/callrecording/outgoing/";
                $date_folder = str_replace('-',"", $row['getDate']);
                $filename = $row['Caller'] .'-'. $row['CalledNumber'] .'-' .$row['StartTimeStamp']. ".mp3";
                $full_url = $base_url . $date_folder .'/'.$filename;



                $get_single_agent =  $this->getSingle($row['Caller']);
                if($get_single_agent->rowCount() !=0) {
                    $agent_row = $get_single_agent->fetch(PDO::FETCH_ASSOC);
                    $agent_name = $agent_row['name'];
                }else{
                    $agent_name = "SaleAgent";
                }


                 $agent = array(
                    "caller" => $agent_name,
                     "extension" => $row['Caller'],
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
                //Duration Field is new added to the table and the old records has empty duration field so need to used start and end timestamp to compute the duration
                 if($row['Duration'] == ''){
                     $endtime = explode("-", $row['EndTimeStamp']);
                     $startime = explode("-", $row['StartTimeStamp']);
                    $total = $total + ((strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );

                   $StartTime = str_replace("-", " ", $row['StartTimeStamp']);
                   $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                   $StartTime = strtotime($StartTime);
                   $EndTime = strtotime($EndTime);
                 }
                 // this is where the duration is available so no need to compute the duration but need to compute the start timestamp.
                 else{
                     $total = $total + $row['Duration'];
                     $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                     $EndTime = strtotime($EndTime);
                     $StartTime =  $EndTime - $duration;

                 }
                  $duration = $this->secToHR($total);




                //get recordings url
                $base_url = "http://211.0.128.110/callrecording/outgoing/";
                $date_folder = str_replace('-',"", $row['getDate']);
                $filename = $row['Caller'] .'-'. $row['CalledNumber'] .'-' .$row['StartTimeStamp']. ".mp3";
                $full_url = $base_url . $date_folder .'/'.$filename;

                 // For Recordings Should Replace above
                    // $base_url = "http://211.0.128.110/callrecording/outgoing/";
                    // $date_folder = str_replace('-',"", $row['getDate']);
                    // $filename = $row['recording_link'];
                    // $filename = str_replace("wav" , "mp3", $filename);
                    // $full_url = $base_url . $date_folder .'/'.$filename;



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

     public function collectionCallDetailsExport($extension,$name,$getdate){

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

            $collection_call_details_template_json = file_get_contents($this->json_addr."collection_call_details.json");
            //make an object
            $collection_call_details_obj = json_decode($collection_call_details_template_json, FALSE);

            while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
                $total=0;

                //Duration Field is new added to the table and the old records has empty duration field so need to used start and end timestamp to compute the duration
                 if($row['Duration'] == ''){
                     $endtime = explode("-", $row['EndTimeStamp']);
                     $startime = explode("-", $row['StartTimeStamp']);
                    $total = $total + ((strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );

                   $StartTime = str_replace("-", " ", $row['StartTimeStamp']);
                   $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                   $StartTime = strtotime($StartTime);
                   $EndTime = strtotime($EndTime);
                 }
                 // this is where the duration is available so no need to compute the duration but need to compute the start timestamp.
                 else{
                     $total = $total + $row['Duration'];
                     $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                     $EndTime = strtotime($EndTime);
                     $StartTime =  $EndTime - $duration;

                 }
                  $duration = $this->secToHR($total);



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


                array_push($collection_call_details_obj->tableData[0]->data, $agent);
            }
            //http_response_code(201);

            echo json_encode($collection_call_details_obj);
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
