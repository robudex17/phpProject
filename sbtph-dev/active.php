
<?php
  include ('header.php');
  // header("Access-Control-Allow-Origin: * ");
  // header("Content-Type: application/json; charset=UTF-8");
  date_default_timezone_set('Asia/Tokyo');


      // //include database and object files
    include_once 'config/database.php';
    include_once 'objects/csd.php';

    $database = new Database();
    $db = $database->getConnection();

    $csd = new Csd($db);

    $canreceive_calls = 1;

    $stmnt = $csd->active_inactive($canreceive_calls);
    $num = $stmnt->rowCount();
    $csd_active = array();
    $log = "IN";

     function secToHR($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
         return "$hours:$minutes:$seconds";
    }
?>

<body class="bg-light" onload="loadme()" >

     <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <a class="navbar-brand mr-auto mr-lg-0 " href="#">CSD PHILIPPINES INBOUND MONITORING</a>
       <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" id="user"></a>
                <input type="hidden" name="hidden_extension" id="hidden_extension">
            </li>
            <li class="nav-item">
                <button type="button" class="btn btn-primary btn-small btn-nav" id="logout" onclick="logout()">Logout</button>
            </li>
        </ul>
    </div>
      <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
      </button>
    </nav>

<div class="nav-scroller bg-blue shadow-sm">
      <nav class="nav nav-underline">
       
        <a class="nav-link btn btn-primary btn-lg active " href="active.php">ACTIVE</a>
        <a class="nav-link " href="inactive.php">INACTIVE</a>
        <a class="nav-link" href="call-summary.php">CALL-SUMMARY</a>
        <a class="nav-link" href="collection.php">COLLECTION-TEAM</a>
        <a class="nav-link" href="csd_manage.php">MANAGE CSD AGENTS</a> 
        <a class="nav-link" href="collection_manage.php">MANAGE COLLECTION AGENTS</a> 

       
      </nav>
</div>
     
    <main role="main" class="container" id="main" >
        
          <div >
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">EXTENSION</th>
                      <th scope="col">NAME</th>
                      <th scope="col">LOGIN/LOGOUT</th>
                      <th scope="col">LOGIN DURATION</th>
                      <th scope="col">CHANNEL STAT</th>
                  </tr>
                </thead>
                <tbody id="active_tbody">
                  <?php
                    $id=2;  // start with number 2 to exclude the two buttons above
                    if($num !=0){
                        
                       while($row = $stmnt->fetch(PDO::FETCH_ASSOC)){
                        $login_duration = $csd->login_logout_duration($log,$row['extension']);
                        $getchannelStat = $csd->getActiveChannels($row['extension']);
                        
                        $csd_active_agent = array(
                          "extension" => $row['extension'],
                          "username" => $row['username'],
                          "loginlogout" => "login_logout_details.php?extension=" .$row['extension'] . "&username=" .$row['username'],
                          "loginduration" => $login_duration,
                          "channelstat" => $getchannelStat['status'],
                          "counter" => $getchannelStat['counter'],
                          "activecalltime" => secToHR($getchannelStat['counter'])
                        );
                      echo "<tr>";
                          echo "<th scope='row'>" . ($id -1). "</th>";
                          echo "<td>" . $csd_active_agent['extension'] . "</td>";
                          echo "<td>" . $csd_active_agent['username'] . "</td>";
                          echo "<td> " ;?>
                            <a href="<?php echo $csd_active_agent['loginlogout']; ?>">Click Details</a>
                            <?php
                          echo "</td>";
                          echo "<td>" . $csd_active_agent['loginduration'] . "</td>";
                   
                     if($csd_active_agent['channelstat'] == 1 && $csd_active_agent['counter'] >=10 ){
                      echo "<td>"?>
                        
                        <button class="btn btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" 
                                data-keyboard="false" data-target= " <?php echo '#myModal'. $id;?>" id="<?php echo $csd_active_agent['extension'] ;?>" 
                                style="margin:5px"><?php echo "ACTIVE" . $csd_active_agent['activecalltime'] ;?><i class="fa fa-phone" style="padding: 3px" ></i>
                        </button>
                    <?php
                      echo "</td>";
                      $id = $id+1;
                     }


                    }

                  }
                  ?>
             
                </tbody>
            </table>
          </div>
       
    </main>


 <script src="js/active_inactive.js"></script>
 <?php include ('footer.php');?>