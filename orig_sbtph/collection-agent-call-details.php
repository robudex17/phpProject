<?php
require('database.php');

// $sql = "SELECT * FROM  csdinbound";
// $result = $conn->query($sql);




 if (isset($_GET['extension'])){
   $extension = $_GET['extension'];
 }
 if (isset($_GET['name'])){
   $name = $_GET['name'];
 }
 if (isset($_GET['getdate'])){
   $getdate = $_GET['getdate'];
 }
 
 $sql = "SELECT * FROM  collectionteam_callsummary WHERE Caller='$extension' AND CallStatus='ANSWER' AND getDate='$getdate' ";
$result = $conn->query($sql);

?>

<?php include ('header.php');?>


<div class="nav-scroller bg-blue shadow-sm">
      <nav class="nav nav-underline">
       
        <a class="nav-link" href="active.php">ACTIVE</a>
        <a class="nav-link" href="inactive.php">INACTIVE</a>
        <a class="nav-link btn " href="call-summary.php">CALL-SUMMARY</a>
        <a class="nav-link " href="collection.php">COLLECTION-TEAM</a>
        
      </nav>
</div>

    <main role="main" >
      <h2 class="text-center font-weight-bold text-primary"><?php echo $name . "<span class='text-danger'> CALLS DETAILS</span>"?></h2>
          <div>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">Caller</th>
                      <th scope="col">CalledNumber</th>
                      <th scope="col">CallStatus</th>
                      <th scope="col">StartTime</th>
                      <th scope="col">EndTime</th>
                      <th scope="col">CallDuration</th>
                      <th scope="col">Recordings</th>
                      <th scope="col">
                          <form action="collection-agent-call-details.php">
                            <input type="hidden" name="extension" value="<?php echo $extension;?>">
                            <input type="hidden" name="name" value="<?php echo $name;?>">
                            <input type="date" name="getdate" id="datePicker"> 
                            <input class="btn" type="submit"  id=clickdate" value="Select_Date"></form>

                      </th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    function secToHR($seconds) {
                       $hours = floor($seconds / 3600);
                        $minutes = floor(($seconds / 60) % 60);
                        $seconds = $seconds % 60;
                        return "$hours:$minutes:$seconds";
                    }
                       
                        if($result->num_rows > 0 ) {
                          $id=1;
                           while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                    echo "<th scope='row'>". $id. "</th>";
                                    echo "<td>" . $row['Caller'] . "</td>";
                                    echo "<td>" . $row['CalledNumber'] . "</td>";
                                    echo "<td>" . $row['CallStatus']. "</td>";
                                    $total=0;
                                    echo $row['EndtimeStamp'];
                                    $endtime = explode("-", $row['EndTimeStamp']);
                                    $startime = explode("-", $row['StartTimeStamp']);
                                    
                                    //calculating call duration
                                    $total = $total + ( (strtotime($endtime[0]) + strtotime($endtime[1])) - (strtotime($startime[0]) +strtotime($startime[1])) );
                                     $total_duration = secToHR($total);

                                    //get start and end calltime
                                    $StartTime = str_replace("-", " ", $row['StartTimeStamp']);
                                    $EndTime  = str_replace("-", " ", $row['EndTimeStamp']);
                                    $StartTime = strtotime($StartTime);
                                    $EndTime = strtotime($EndTime);

                                    //get recordings url
                                    $base_url = "http://211.0.128.110/callrecording/outgoing/";
                                    $date_folder = str_replace('-',"", $row['getDate']);
                                    $filename = $row['Caller'] .'-'. $row['CalledNumber'] .'-' .$row['StartTimeStamp']. ".mp3";
                                    $recordingfile = $base_url . $date_folder .'/'.$filename;


                                    echo "<td>" . date( "h:i:s a",$StartTime). "</td>";
                                    echo "<td>" . date("h:i:s a",$EndTime). "</td>";
            
                                    echo "<td class='text-justify'>" .$total_duration. "</td>";
                                    echo "<td>" ?>
                                       <a class="nav-link" href="<?php echo $recordingfile;?>">Call-Recordings</a>
                                    <?php "</td>";
                                     echo "<td>" .$row['getDate']. "</td>";
                                    
                                 echo "</tr>";

                                $id = $id+1;
                          }
                        }else{
                            echo "<tr>";
                            echo "<th scope='row'></th>";
                            echo "<td>NO CALL SUMMARY AVAILABLE</td>";
                            echo "<tr>";
                      } 
                  $conn->close();   
                  ?>
                </tbody>
            </table>
          </div>
       
    </main>



 <?php include ('footer.php');?>