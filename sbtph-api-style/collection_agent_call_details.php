

<?php include ('header.php');?>

<body class="bg-light" onload="collectionCallDetails()">

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
       
        <a class="nav-link" href="active.php">ACTIVE</a>
        <a class="nav-link" href="inactive.php">INACTIVE</a>
        <a class="nav-link btn " href="call-summary.php">CALL-SUMMARY</a>
        <a class="nav-link" href="collection.php">COLLECTION-TEAM</a>

       
      </nav>
</div>

    <main role="main" >
      <h2 class="text-center font-weight-bold text-primary"><span  id="agentusername"></span><span class='text-danger'> CALLS DETAILS</span></h2>
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
                      <th scope="col">Call-Recordings</th> 
                      <th scope="col">
                          <form>
                            <input type="hidden" name="extension" id="extension">
                            <input type="hidden" name="name" id="name">
                            <input type="date" name="getdate" id="datePicker"> 
                            <input class="btn" type="submit"  id=clickdate" value="Select_Date"></form>

                      </th>
                  </tr>
                </thead>
                <tbody id="collection_call_details_tbody">
                
                </tbody>
            </table>
          </div>
        <script src="js/collection_call_details.js"></script>  
    </main>

 <?php include ('footer.php');?>