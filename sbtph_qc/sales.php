
<?php include ('header.php');?>

<body class="bg-light" onload="getSalesCallSummary()" >

     <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <a class="navbar-brand mr-auto mr-lg-0 " href="#">SBTPHILIPPINES CALLS MONITORING</a>
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
       
        <a class="nav-link btn btn-primary btn-lg active" href="sales.php">SALES-TEAM</a>   
        <a class="nav-link" href="sales_manage.php">MANAGE SALE AGENTS</a> 
       

       
      </nav>
</div>

    <main role="main" class="container">

          <div>
            <button class="btn btn-primary btn-lg btn-block" id="summary_export" hidden> EXPORT <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
            <button class="btn btn-primary btn-lg btn-block" id="report">EXPORT <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">EXTENSION</th>
                      <th scope="col">NAME</th>
                      <th scope="col">TEAM LEADER</th>
                      <th scope="col" class="text-center">Total Made Calls </th>
                      <th scope="col">Total Calls Duration</th>
                      <th scope="col">
                        <form action="sales.php" id="form_date">
                            <input type="date" name="getdate" id="datePicker">  
                              <input class="btn" type="submit"   value="Select_Date">
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#mySearch" dataset-backdrop="static" dataset-keyboard="false">SEARCH</button>
                              
                          </form>
                      </th>
                      
                  </tr>
                </thead>
                <tbody id="sales_summary">
                
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
                        <form method="GET" id="search_number" name="search_number" action="search_number.php">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Search</label>
                              <input type="text" class="form-control" id="callednumber" name="callednumber" aria-describedby="callednumber" placeholder="Type or Paste number here!" required="true">
                            
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
 <script src="js/sales_summary.js"></script>  
    </main>


 <?php include ('footer.php');?>