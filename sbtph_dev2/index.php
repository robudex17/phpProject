
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.ico">

    <title>CSD PHILIPPINES CALLS MONITORING</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/offcanvas.css" rel="stylesheet">
  </head>

  <body class="bg-light" onload="getLoginUser()">

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
          <nav class="nav nav-underline ">
           
                 <a class="nav-link mx-0 px-2" href="active.php">ACTIVE</a>
                 <a class="nav-link mx-0 px-2" href="inactive.php">INACTIVE</a>
                 <a class="nav-link mx-0 px-2" href="csd_inbound.php">CSD-INBOUND</a>
                 <a class="nav-link mx-0 px-2" href="csd_outbound.php">CSD-OUTBOUND</a>
                  <a class="nav-link mx-0 px-2" href="csd_missedcalls.php">CSD-MISSED-CALLS <span id="miss_calls_counts"></span></a>
                   <a class="nav-link mx-0 px-2" href="parked_calls.php">PARKED-CALLS <span id="parked_calls_counts"></a>
                   <a class="nav-link mx-0 px-2" href="voicemails.php">VOICE-MAILS <span id="voicemail_counts"></a> 
                 <a class="nav-link mx-0 px-2" href="collection.php">COLLECTION-TEAM</a>   
                 <a class="nav-link mx-0 px-2" href="csd_manage.php">MANAGE CSD AGENTS</a> 
                 <a class="nav-link mx-0 px-2" href="collection_manage.php">MANAGE COLLECTION AGENTS</a> 
          </nav>
    </div>

    <main role="main" class="container">
       
    </main>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.min.js"></script>
    <script src="js/offcanvas.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>
