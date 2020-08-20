
<?php include ('header.php');?>
<script type="text/javascript" src="js/anytime.js"></script>
    <link rel="stylesheet" type="text/css" href="css/anytime.css">
<body class="bg-light" onload="getLoginUser()" >

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

    <main role="main" class="container" id="main" >
       <br><br><br><br>
       <h2 class="text-center">Generate Metrics</h2>
       <hr>
              <div>
                      <form method="GET" id="genMentrics" action="generate_metrics.php">

                             <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Select </label>
                                  </div>

                                <select class="custom-select" name="group" form="genMentrics" required id="group">
                                   <option value="csdinbound">CSD-INBOUND</option>
                                   <option value="csdoutbound">CSD-OUTBOUND</option>
                                    <option value="collection">COLLECTION</option>

                                </select>
                            </div>
                         <div class="form-group">
                            <label for="start_date_and_time">Start Date & Time</label>
                            <input type="text" class="form-control" id="start_date_and_time" name="start_date_and_time" aria-describedby="start_date_and_time" placeholder="Enter Date & Time" required>
                        </div>
                         <div class="form-group">
                            <label for="end_date_and_time">End Date & Time</label>
                            <input type="text" class="form-control" id="end_date_and_time" name="end_date_and_time" aria-describedby="end_date_and_time" placeholder="Enter Date & Time" >
                        </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <label class="input-group-text" for="inputGroupSelect01">Select Metrics </label>
                        </div>

                        <select class="custom-select" name="option_metrics" form="genMentrics" required id="option_metrics" onchange="chooseMetrics()" >
                          <option option value="" disabled selected></option>
                           <option value="callduration">Call Durations</option>
                           <option value="callcounts">Call Counts</option>
                            <option value="blended">Blended Durations/Counts</option>
                            <option value="tag">Tags</option>

                        </select>
                   </div>
                      <div class="border" style="display: none;" id="div_blended">
                      <p>Choose Your Desired Weight Percentage(%):</p>
                          <div class="row">
                            <div class="cl">

                            </div>
                             <div class="col-2 pr-0 mr-0 ">
                                 <label class="pr-0 mr-0 ">Select CallDuration Weight: </label>
                             </div>
                              <div class="col-2 pl-0 ml-0">


                                <select class="custom-select"  id="duration_weight" name="duration_weight" onchange="metrics_duration()">
                                  <option value="0" selected>0</option>
                                </select>
                            </div>

                          </div>
                            <br>

                          <div class="row">
                            <div class="col-2 pr-0 mr-0">
                               <label class="pr-0 mr-0 ">Select CallCounts Weight: </label>
                            </div>
                              <div class="col-2 pl-0 ml-0">
                                <select class="custom-select" id="callcount_weight" name="callcount_weight" onchange="metrics_callcounts()">
                                  <option value="0" selected>0</option>
                                </select>
                            </div>
                          </div>

                 </div>
                 <br>
                 <div class="text-left mb-3">
                       <input type="submit" name="genbtn"  class="btn btn-primary ml-auto"value="Generate" id="genbtn">
                  </div>
                </form>


            </div>

    </main>

 <script>
  AnyTime.picker( "start_date_and_time",
    { format: "%Y-%m-%d %H:%i:%s ",
      formatUtcOffset: "%: (%@)",
      theme:'start'

     }
      );
   AnyTime.picker( "end_date_and_time",
    { format: "%Y-%m-%d %H:%i:%s ",
      formatUtcOffset: "%: (%@)"

     }
      );
  function chooseMetrics(){
      var option_metrics = document.getElementById('option_metrics');

      if(option_metrics.value == 'blended'){
        var div_blended = document.getElementById('div_blended').style.display = 'block';
        var duration_weight = document.getElementById('duration_weight');
        var callcount_weight = document.getElementById('callcount_weight');
        putValue(duration_weight);
        putValue(callcount_weight);
      }else{
          var div_blended = document.getElementById('div_blended').style.display = 'none';
          if(option_metrics.value == 'callduration'){
            var duration_weight = document.getElementById('duration_weight')
            putValue(duration_weight);
          }

          if(option_metrics.value == 'callcounts'){
            var callcount_weight = document.getElementById('callcount_weight')
            putValue(callcount_weight);
          }
      }
  }
var hundred_percent = 100;
var duration_weight = document.getElementById('duration_weight');
var callcount_weight = document.getElementById('callcount_weight');

 function putValue(select){
   for(var i=1;i<=100;i++){
     var option = document.createElement('option')
     option.textContent = i;
     option.value = i;
     select.appendChild(option);
   }
 }
 function metrics_duration() {
    callcount_weight.value = hundred_percent - duration_weight.value;
 }
 function metrics_callcounts() {
    duration_weight.value = hundred_percent - callcount_weight.value;
 }
 document.getElementById('genbtn').addEventListener("click",function(e){

     var start_date_and_time = document.getElementById('start_date_and_time');
     var end_date_and_time = document.getElementById('end_date_and_time');
     var option_metrics = document.getElementById('option_metrics');

    var start_timestamp = new Date(start_date_and_time.value).getTime()
    var  end_timestamp = new Date(end_date_and_time.value).getTime()
    if(start_timestamp > end_timestamp){
      alert('End Date must be greater Than Start Date')
        start_date_and_time.value = '' ;
        end_date_and_time.value = '';
      e.preventDefault();
    }
    if(start_date_and_time.value == '' || end_date_and_time.value == ''){
        alert('All fields must not empty');
          e.preventDefault();
    }
    if(option_metrics.value == 'blended'){
       var duration_weight = document.getElementById('duration_weight').value;
       var callcount_weight = document.getElementById('callcount_weight').value;
      if(duration_weight == 0 && callcount_weight == 0){
        alert('Zero weight Percentage is not allowed');
        e.preventDefault()
      }
    }
    if(option_metrics.value == 'callduration'){
      var duration_weight = document.getElementById('duration_weight').value = 100
      var callcount_weight = document.getElementById('callcount_weight').value = 0
      putValue(duration_weight);
    }

    if(option_metrics.value == 'callcounts'){
    var callcount_weight = document.getElementById('callcount_weight').value = 100
      var duration_weight = document.getElementById('duration_weight').value = 0
    }


 })
  </script>


  <style>
  #start_date_and_time {
    border:1px solid #FFC030;color:#3090C1;font-weight:bold}

   #end_date_and_time {
    border:1px solid #FFC030;color:#3090C1;font-weight:bold}

  </style>

 <?php include ('footer.php');?>
