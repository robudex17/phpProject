

<?php include ('header.php');?>

<body class="bg-light" onload="parked_calls()">

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
       
        <a class="nav-link mx-0 px-2 " href="active.php">ACTIVE</a>
        <a class="nav-link mx-0 px-2" href="inactive.php">INACTIVE</a>
        <a class="nav-link mx-0 px-2" href="csd_inbound.php">CSD-INBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_outbound.php">CSD-OUTBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_missedcalls.php">CSD-MISSED-CALLS</a>
        <a class="nav-link mx-0 px-2 btn btn-primary btn-lg active " href="parked_calls.php">PARKED-CALLS</a>
        <a class="nav-link mx-0 px-2" href="voicemails.php">VOICE-MAILS </a> 
        <a class="nav-link mx-0 px-2" href="collection.php">COLLECTION-TEAM</a>
        <a class="nav-link mx-0 px-2" href="csd_manage.php">MANAGE CSD AGENTS</a> 
        <a class="nav-link mx-0 px-2" href="collection_manage.php">MANAGE COLLECTION AGENTS</a> 

       
      </nav>
</div>

    <main role="main" id="main" >
      
          <div>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">Caller</th>
                      <th scope="col">Waiting Time</th>
                      <th scope="col">Date</th>
                      
                  </tr>
                </thead>
                <tbody id="parked_calls_tbody">
                
                </tbody>
            </table>
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
        <script src="js/parked_calls.js"></script>  
    </main>

 <?php include ('footer.php');?>

 