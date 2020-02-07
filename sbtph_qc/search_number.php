

<?php include ('header.php');?>

<body class="bg-light" onload="search_number()">

   <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <a class="navbar-brand mr-auto mr-lg-0 " href="#"> SBTPHILIPPINES CALLS MONITORING</a>
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
       
        <a class="nav-link" href="sales.php">SALES-TEAM</a>   
        <a class="nav-link" href="sales_manage.php">MANAGE SALE AGENTS</a> 
       
      </nav>
</div>

    <main role="main" id="main" >
     
          <div>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">Agent</th>
                      <th scope="col">Extension</th>
                      <th scope="col">CalledNumber</th>
                      <th scope="col">CallStatus</th>
                      <th scope="col">StartTime</th>
                      <th scope="col">EndTime</th>
                      <th scope="col">CallDuration</th>
                      <th scope="col">Call-Recordings</th> 
                      <th scope="col">Date</th>
                      <th scope="col">Comment</th>
                  </tr>
                </thead>
                <tbody id="sales_call_details_tbody">
                
                </tbody>
            </table>
          </div>
          <script src="js/script.js"></script>
        <script src="js/search_number.js"></script> 
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

 <?php include ('footer.php');?>