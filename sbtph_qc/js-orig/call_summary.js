document.getElementById('date_form').addEventListener("submit", getCallSummary);

function getCallSummary(){
  getLoginUser()
  var querystring = window.location.search.substring(1)
  var querydate = document.getElementById('date_form').getdate.value;
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var tbody = 'call-summary-body'
      callSummaryTable(this.responseText,tbody);
      
    }
  };
  if(querystring !== ''){
    var apiquery = `api/call_summary.php?${querystring}`;
    xhttp.open("GET", apiquery  , true);
    xhttp.send();
  }else {
    xhttp.open("GET", "api/call_summary.php", true);
    xhttp.send();
  }
 
}

function callSummaryTable(res,tbody) {
  var response = JSON.parse(res);
  var active_tbody = document.getElementById(tbody);   
    if(response.message === "No Records Found"){
      //create elements
      var tr =  document.createElement('tr');
      var tdmessage = document.createElement('td');

      tdmessage.textContent = response.message;
      tr.appendChild(tdmessage);

      //append tr to tbody
      active_tbody.appendChild(tr);

    }else{
    
    var i;
    for(i=0; i< response.length ; i++){
      //create elements
      var tr =  document.createElement('tr');
      var tdi = document.createElement('td');
      var tdname = document.createElement('td');
      var tdextension = document.createElement('td');
      var tdtotal_answered = document.createElement('td');
      var tdtotal_duration = document.createElement('td');
      var linkdate = document.createElement('a');
      var tddate = document.createElement('td');
      
      
      //put values on the elements
      tdi.textContent = i+1;
      tdextension.textContent = response[i].extension;
      tdname.textContent = response[i].name;
      tdtotal_answered.textContent = response[i].total_answered;
      tdtotal_duration.textContent = response[i].total_duration;
      linkdate.href = response[i].call_details;
      linkdate.textContent = response[i].getdate
      tddate.appendChild(linkdate)

      //tds to tr

      tr.appendChild(tdi);
      tr.appendChild(tdextension);
      tr.appendChild(tdname);
      tr.appendChild(tdtotal_answered);
      tr.appendChild(tdtotal_duration);
      tr.appendChild(tddate);

      //append tr to tbody
      active_tbody.appendChild(tr);
    }
  }
}