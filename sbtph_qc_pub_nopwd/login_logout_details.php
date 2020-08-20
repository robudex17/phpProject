<?php include ('header.php');?>

<body class="bg-light" onload="getLogInOutDetails()">

     <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <a class="navbar-brand mr-auto mr-lg-0 " href="#">CSD PHILIPPINES CALLS MONITORING</a>
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


<div class="nav-scroller bg-blue shadow-sm">
      <nav class="nav nav-underline">
       
        <a class="nav-link btn btn-primary btn-lg active " href="active.php">ACTIVE</a>
        <a class="nav-link " href="inactive.php">INACTIVE</a>
        <a class="nav-link" href="csd_inbound.php">CSD-INBOUND</a>
        <a class="nav-link" href="csd_outbound.php">CSD-OUTBOUND</a>
        <a class="nav-link" href="collection.php">COLLECTION-TEAM</a>

       
      </nav>
</div>

    <main role="main" class="container">
          <div >
            <h2  class="text-center font-weight-bold text-primary"> <span id="agentname"></span><span class='text-danger'> LOGIN/LOGOUT STATISTICS</span></h2>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">LOG</th>
                      <th scope="col">DATA</th>
                      <th scope="col">TIME</th>
                      
                  </tr>
                </thead>
                <tbody id="details_tbody">
                  
             
                </tbody>
            </table>
          </div>
       
    </main>


<script src="js/active_inactive.js"></script>
 <?php include ('footer.php');?>