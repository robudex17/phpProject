
<?php include ('header.php');?>

<body class="bg-light" onload="getActiveAgents()" >

     <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <a class="navbar-brand mr-auto mr-lg-0 " id="index_menu" href="index.php">CSD PHILIPPINES CALLS MONITORING</a>
       <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" id="user"></a>
                <input type="hidden" name="hidden_extension" id="hidden_extension">
                 <input type="hidden" name="position" id="position">
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
     <style> 
       #index_menu:hover {
          color: magenta;
       }
     </style>
<div class="nav-scroller bg-blue shadow-sm">
      <nav class="nav nav-underline">
       
        <a class="nav-link mx-0 px-2 btn btn-primary btn-lg active " href="active.php">ACTIVE</a>
        <a class="nav-link mx-0 px-2" href="inactive.php">INACTIVE</a>
        <a class="nav-link mx-0 px-2" href="csd_inbound.php">CSD-INBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_outbound.php">CSD-OUTBOUND</a>
         <a class="nav-link mx-0 px-2" href="csd_missedcalls.php">CSD-MISSED-CALLS</a>
          <a class="nav-link mx-0 px-2" href="parked_calls.php">PARKED-CALLS</a>
        <a class="nav-link mx-0 px-2" href="collection.php">COLLECTION-TEAM</a>
        <a class="nav-link mx-0 px-2" href="csd_manage.php">MANAGE CSD AGENTS</a> 
        <a class="nav-link mx-0 px-2" href="collection_manage.php">MANAGE COLLECTION AGENTS</a> 
         <a class="nav-link mx-0 px-2" href="metrics.php">GEN METRICS</a> 
        
       
      </nav>
</div>
     
    <main role="main" class="container" id="main" >
          <!-- The Modal -->
        <div class="modal" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
            
              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">CALL BARGING</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              
              <!-- Modal body -->
              <div class="modal-body">
               <p id='message'></p>
              </div>
              
              <!-- Modal footer -->
              <div class="modal-footer" id='modal_footer'>
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal" id='listen'>Listen Now</button> -->
               <!--  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
              </div>
              
            </div>
          </div>
        </div>
        
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
                  
             
                </tbody>
            </table>
          </div>
       
    </main>


 <script src="js/active_inactive.js"></script>
 <?php include ('footer.php');?>