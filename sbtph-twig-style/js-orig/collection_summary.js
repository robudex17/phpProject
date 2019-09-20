document.getElementById('form_date').addEventListener("submit", getCollectionCallSummary);

function getCollectionCallSummary(){
  getLoginUser()
  var querystring = window.location.search.substring(1)
  var querydate = document.getElementById('form_date').getdate.value;
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var tbody = 'collection_summary'
     callCollectionSummaryTable(this.responseText,tbody);
      
    }
  };
  if(querystring !== ''){
    var apiquery = `api/collection_summary.php?${querystring}`;
    xhttp.open("GET", apiquery  , true);
    xhttp.send();
  }else {
    xhttp.open("GET", "api/collection_summary.php", true);
    xhttp.send();
  }
 
}

function callCollectionSummaryTable(res,tbody) {
  var response = JSON.parse(res);
  var collection_tbody = document.getElementById(tbody);   
    if(response.message === "No Records Found"){
      //create elements
      var tr =  document.createElement('tr');
      var tdmessage = document.createElement('td');

      tdmessage.textContent = response.message;
      tr.appendChild(tdmessage);

      //append tr to tbody
      collection_tbody.appendChild(tr);

    }else{
    
    var i;
    for(i=0; i< response.length ; i++){
      //create elements
      var tr =  document.createElement('tr');
      var tdi = document.createElement('td');
      var tdname = document.createElement('td');
      var tdextension = document.createElement('td');
      var tdtotalmadecalls = document.createElement('td');
      var tdtotal_duration = document.createElement('td');
      var linkdate = document.createElement('a');
      var tddate = document.createElement('td');
      
      
      //put values on the elements
      tdi.textContent = i+1;
      tdextension.textContent = response[i].extension;
      tdname.textContent = response[i].name;
      tdtotalmadecalls.textContent = response[i].tdtotalmadecalls;
      tdtotal_duration.textContent = response[i].totalduration;
     linkdate.href = response[i].calldetails;
      linkdate.textContent = response[i].getdate;
      tddate.appendChild(linkdate)
      
      //tds to tr

      tr.appendChild(tdi);
      tr.appendChild(tdextension);
      tr.appendChild(tdname);
      tr.appendChild(tdtotalmadecalls);
      tr.appendChild(tdtotal_duration);
      tr.appendChild(tddate);

      //append tr to tbody
      collection_tbody.appendChild(tr);
    }
  }
}