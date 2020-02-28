
document.getElementById('date_form').addEventListener("submit", getMissedCallSummary);

function getMissedCallSummary(){
  getLoginUser()
  var option = '&option=summary';
  var querystring = window.location.search.substring(1) + option;
    var querydate = document.getElementById('date_form').getdate.value;
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var tbody = 'missed_calls_summary-body'
     
      missedCallSummaryTable(this.responseText,tbody);
      
    }
  };
  if(querystring !== ''){
    var apiquery = `api/csd_missed_calls_summary.php?${querystring}`;
    xhttp.open("GET", apiquery  , true);
    xhttp.send();
  }else {
    xhttp.open("GET", "api/csd_missed_calls_summary.php", true);
    xhttp.send();
  }
 
}

function missedCallSummaryTable(res,tbody) {
  var response = JSON.parse(res);
  console.log(response.message )
  var active_tbody = document.getElementById(tbody);   
    if(response.message === "No Records Found" ){
      //create elements
      var tr =  document.createElement('tr');
      var tdmessage = document.createElement('td');

      tdmessage.textContent = 'No Missed Calls';
      tr.appendChild(tdmessage);

      //append tr to tbody
      active_tbody.appendChild(tr);

    }else{
    
    var i;
    for(i=0; i< response.length ; i++){
      //create elements
      var tr =  document.createElement('tr');
      var tdi = document.createElement('td');
      var tdtotal_missed_calls = document.createElement('td');
      var linkdate = document.createElement('a');
      var tddate = document.createElement('td');
      
      
      //put values on the elements
      tdi.textContent = i+1;
      tdtotal_missed_calls.textContent = response[i].total_missed_calls;
      linkdate.href = response[i].misscalls_details;
      linkdate.textContent = response[i].getdate;
      tddate.appendChild(linkdate)

      //tds to tr

      tr.appendChild(tdtotal_missed_calls);
      tr.appendChild(tddate);

      //append tr to tbody
      active_tbody.appendChild(tr);
    }
  }
}