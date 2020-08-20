<?php include ('header.php');?>

    <div class="nav-scroller fixed-top bg-blue shadow-sm">
      <nav class="nav nav-underline navbar-expand-lg  fixed-top">
       
        <a class="nav-link mx-0 px-2" href="active.php">ACTIVE</a>
        <a class="nav-link mx-0 px-2" href="inactive.php">INACTIVE</a>
        <a class="nav-link mx-0 px-2" href="csd_inbound.php">CSD-INBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_outbound.php">CSD-OUTBOUND</a>
        <a class="nav-link mx-0 px-2" href="csd_manage.php">MANAGE CSD AGENTS</a> 
        <a class="nav-link mx-0 px-2" href="collection_manage.php">MANAGE COLLECTION AGENTS</a> 
         <a class="nav-link mx-0 px-2" href="metrics.php">GEN METRICS</a>
       
      </nav>
    </div>

    <main role="main" class="container">
          <div>
              <h2 class="text-center font-weight-bold text-primary"><span class='text-danger'> CALLS STATISTICS</span></h2>
              <table class="table">
                <thead class="thead-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">Total Calls Answered</th>
                      <th scope="col">Total Calls Duration</th>
                      <th scope="col">Current Date</th>
                      <th scope="col">Details</th>
                      <th scope="col"></th>
                      <th scope="col"><input type="date" name=""><input type="submit" name="" value="SEARCHD DATE"></th>
                  </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
          </div>
       
    </main>

<?php include ('footer.php');?>
