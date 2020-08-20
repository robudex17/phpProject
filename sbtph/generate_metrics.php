
<?php include ('header.php');?>

<body class="bg-light" onload="getMetrics()" >

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
        <a class="nav-link mx-0 px-2" href="csd_inbound.php">CSD-INBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_outbound.php">CSD-OUTBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_missedcalls.php">CSD-MISSED-CALLS</a>
        <a class="nav-link mx-0 px-2" href="parked_calls.php">PARKED-CALLS </a>
        <a class="nav-link mx-0 px-2" href="voicemails.php">VOICE-MAILS </a>
        <a class="nav-link mx-0 px-2 " href="collection.php">COLLECTION-TEAM</a>
        <a class="nav-link mx-0 px-2" href="csd_manage.php">MANAGE CSD AGENTS</a>
        <a class="nav-link mx-0 px-2" href="collection_manage.php">MANAGE COLLECTION AGENTS</a>
         <a class="nav-link mx-0 px-2" href="metrics.php">GEN METRICS</a>

    </nav>
</div>

    <main role="main" class="container-fluid" >
          <div >
              <button class="btn btn-primary btn-lg btn-block" id="metrics_summary_export" > EXPORT <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
              <div id="count-duration-metrics" style="display: none">
                  <table class="table" >
                    <thead class="thead-dark">
                       <tr>
                          <th scope="col" id='options'></th>
                          <th scope="col">NAME</th>
                          <th scope="col">EXTENSION</th>
                          <th scope="col" class="text-center" id="total_counts">Total Call Counts(#)</th>
                          <th scope="col" id="total_duration">Total Calls Duration(HH:MM:SS)</th>
                          <th scope="col" id="call_count_percentage"></th>
                          <th scope="col" id="call_duration_percentage"></th>
                          <th scope="col" id="total_percentage">Total(100%)</th>

                      </tr>
                    </thead>
                    <tbody id="generate_metrics_table">

                    </tbody>
                </table>
                 <div class="row pb-5 mt-3 border">
                   <div class="col-6">Date Range: <span id= "date_time_range"class="font-weight-bold text-danger"></span></div>
                   <div class="col-2" id= "grand_total_counts"></div>
                   <div class="col-4" id= "grand_total_call_duration"></span></div>
                 </div>
           </div>
            <div id="tag-metrics" style="display: none">
              <table class="table" id='table-tag'>
                  <thead class="thead-dark">
                     <tr id='table-tag-tr'>
                        <th scope="col" id='options-tag'></th>
                       
                    </tr>
                  </thead>
                  <tbody id="generate_tag_metrics_table">

                  </tbody>
              </table>
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
    </main>


 <script src="js/generate_metrics.js"></script>
 <?php include ('footer.php');?>
