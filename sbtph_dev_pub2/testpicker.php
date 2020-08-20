 <?php include ('header.php');?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 4 DatePicker</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/anytime.js"></script>
    <link rel="stylesheet" type="text/css" href="css/anytime.css">
</head>
<body>

<main role="main" class="container" id="main" >
       
              
                <h2 class="text-center">Generate Metrics</h2>
              <div>
                      <form method="POST" id="genMentrics">
                        <div class="form-group">
                          <label for="start_date_and_time">Start Date & Time</label>
                          <input type="text" class="form-control" id="start_date_and_time" name="start_date_and_time" aria-describedby="start_date_and_time" placeholder="Enter Date & Time" required="true">
                        </div>
                         <div class="form-group">
                          <label for="end_date_and_time">End Date & Time</label>
                          <input type="text" class="form-control" id="enddatetime" name="enddatetime" aria-describedby="enddatetime" placeholder="Enter Date & Time" required="true">
                        </div>
                          <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Seclect Metrics </label>
                    </div>
                  
                    <select class="custom-select" name="option_metrics" form="genMentrics" required id="option_metrics">
                       <option value="callduration">Call Durations</option>
                       <option value="callcounts">Call Counts</option>
                        <option value="blended">Blended</option>
                       
                    </select>
                </div>
                        <hr>
                        <div class="text-right mb-3">
                            <button type="button" class="btn btn-primary ml-auto" data-dismiss="modal" id="genbtn" >Submit</button>
                          <button type="button" class="btn btn-danger ml-auto"  data-dismiss="modal" >Close</button>   
                        </div>
                                  
                </form>
                
              </div>
              
          
         
            
        
</main>
 <script>
  AnyTime.picker( "start_date_and_time",
    { format: "%Y-%m-%d %H:%i:%s ",
      formatUtcOffset: "%: (%@)"
   
     } );
   AnyTime.picker( "end_date_and_time",
    { format: "%Y-%m-%d %H:%i:%s ",
      formatUtcOffset: "%: (%@)"
   
     } );
  </script>


  <style>
  #start_date_and_time { background-image:url("clock.png");
    background-position:right center; background-repeat:no-repeat;
    border:1px solid #FFC030;color:#3090C1;font-weight:bold}
  
   #end_date_and_time { background-image:url("clock.png");
    background-position:right center; background-repeat:no-repeat;
    border:1px solid #FFC030;color:#3090C1;font-weight:bold}
  
  </style>
</body>
</html>