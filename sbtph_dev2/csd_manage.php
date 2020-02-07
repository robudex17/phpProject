
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.ico">

    <title>CSD PHILIPPINES CALLS MONITORING</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <link href="css/offcanvas.css" rel="stylesheet">
  </head>

  <body class="bg-light" onload="getAllAgents()">

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
           
                 <a class="nav-link" href="active.php">ACTIVE</a>
                 <a class="nav-link" href="inactive.php">INACTIVE</a>
                 <a class="nav-link" href="csd_inbound.php">CSD-INBOUND</a>
                 <a class="nav-link" href="csd_outbound.php">CSD-OUTBOUND</a>
                 <a class="nav-link" href="collection.php">COLLECTION-TEAM</a>
                 <a class="nav-link btn btn-primary btn-lg active" href="csd_manage.php">MANAGE CSD AGENTS</a> 
                <a class="nav-link" href="collection_manage.php">MANAGE COLLECTION AGENTS</a>                 
          </nav>
    </div>
  
    <main role="main" class="container" id="main">
     
    

 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title">Add Agent</h4>
        </div>
        <div class="modal-body">
              <form method="POST" id="addAgent">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Agent Name" required="true">
                  </div>
                   <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required="true">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Extension</label>
                    <input type="text" class="form-control" id="exten" name="extension" placeholder="Enter Extension" required="true">
                  </div> 
                  <hr>
                  <div class="text-right mb-3">
                      <button type="button" class="btn btn-primary ml-auto" data-dismiss="modal" id="addbtn" >Submit</button>
                    <button type="button" class="btn btn-danger ml-auto"  data-dismiss="modal" >Close</button>   
                  </div>
                            
          </form>
        </div>
        
    
      </div>
      
    </div>
  </div>
  <?php include ('metrics.php');?>
</div>
        <table class="table table-striped " style="padding-top: 30px; padding-bottom:30px; margin-top: 30px;">
          <thead>
            <tr>
              <th scope="col"><h3>#</h3></th>
              <th scope="col"><h3>Extension</h3></th>
              <th scope="col"><h3>Name</h3></th>
              <th scope="col"><h3>Email</h3></th>
              <th scope="col"><h3>Action</h3></th>
            </tr>
          </thead>
          <tbody id="csd_tbody">
           
               
          </tbody>
        </table>
        <div class="row justify-content-md-center pb-5">
            <div class="col">
               
           </div> 
           
          
            <div class="col-6">
               <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#myModal" dataset-backdrop="static" dataset-keyboard="false" id="add_agent">ADD AGENT</button>
              <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#myMetrics" dataset-backdrop="static" dataset-keyboard="false" id="add_agent">GENERATE RANKING METRICS</button>
            </div>
              <div class="col">
               
           </div>
             
        </div>

      <script src="js/script.js"></script>
    <script src="js/csd_manage.js"></script>
    </main>

 <?php include ('footer.php');?>