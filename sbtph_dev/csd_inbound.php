
<?php include ('header.php');?>

<body class="bg-light" onload="getCallSummary()">

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
        <a class="nav-link mx-0 px-2" href="active.php">ACTIVE</a>
        <a class="nav-link mx-0 px-2" href="inactive.php">INACTIVE</a>
        <a class="nav-link btn btn-primary btn-lg active" href="csd_inbound.php">CSD-INBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_outbound.php">CSD-OUTBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_missedcalls.php">CSD-MISSED-CALLS </a>
        <a class="nav-link mx-0 px-2" href="parked_calls.php">PARKED-CALLS </a>
        <a class="nav-link mx-0 px-2" href="voicemails.php">VOICE-MAILS </a> 
        <a class="nav-link mx-0 px-2" href="collection.php">COLLECTION-TEAM</a>
        <a class="nav-link mx-0 px-2" href="csd_manage.php">MANAGE CSD AGENTS</a> 
        <a class="nav-link mx-0 px-2" href="collection_manage.php">MANAGE COLLECTION AGENTS</a> 
         <a class="nav-link mx-0 px-2" href="metrics.php">GEN METRICS</a>
          
      </nav>
</div>

    <main role="main" class="container">
          <div>
              <button class="btn btn-primary btn-lg btn-block" id="inbound_summary_export" > EXPORT <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">EXTENSION</th>
                      <th scope="col">NAME</th>
                      <th scope="col" class="text-center">Total Calls Answered</th>
                      <th scope="col">Total Calls Duration</th>
                      <th scope="col">
                      	<form id="date_form" >
                      		  <input type="date" name="getdate" id="getdate" > 	
                              <input class="btn" type="submit"  id="clickdate" value="Select_Date">
                               <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#mySearch" dataset-backdrop="static" dataset-keyboard="false">SEARCH</button>
                          </form>
                      </th>
                  </tr>
                </thead>
                <tbody id="call-summary-body">
                  
                </tbody>
            </table>
          </div>
          <div class="modal fade" id="mySearch" role="dialog">
              <div class="modal-dialog">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                  
                    <h4 class="modal-title">SEARCH PHONE NUMBER</h4>
                   </div>
                   <div class="modal-body">
                        <form method="GET" id="search_number" name="search_number" action="search_inbound_number.php">
                            <div class="form-group">
                              <label for="search_caller">Search</label>
                              <input type="text" class="form-control" id="caller" name="caller" aria-describedby="callednumber" placeholder="Type or Paste number here!" required="true">
                            
                            </div> 
                            <hr>
                            <div class="text-right mb-3">
                                <input type="submit" class="btn btn-primary ml-auto">
                                <!-- <button type="submit" class="btn btn-primary ml-auto" data-dismiss="modal" id="search" >Search</button> -->
                              <button type="button" class="btn btn-danger ml-auto"  data-dismiss="modal" >Close</button>   
                            </div>
                                      
                        </form>
                    </div>
                  
              
                 </div>
      
               </div>
           </div>
           <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-36251023-1']);
          _gaq.push(['_setDomainName', 'jqueryscript.net']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

      </script>
         <script src="js/call_summary.js"></script>  
    </main>
 <?php include ('footer.php');?>
 