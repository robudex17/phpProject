

document.getElementById('clickdate').addEventListener('submit', agentCallDetails)

function collectionCallDetails() {

    getLoginUser()
    var querystring = window.location.search.substring(1);

    //get username and extension from the query
    var split = querystring.split('&');
    var extension = split[0].split('=');
    var username = split[1].split('=');
    
   document.getElementById('extension').value = extension[1];
   document.getElementById('name').value = username[1];
   document.getElementById('agentusername').innerHTML = username[1];


    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var tbody = 'collection_call_details_tbody'
        collectionCallDetailsTable(this.responseText,tbody);
        
      }
    };
   
      var apiquery = `api/collection_call_details.php?${querystring}`;
      xhttp.open("GET", apiquery  , true);
      xhttp.send();
     
}

function collectionCallDetailsTable(res,tbody) {
   var response = JSON.parse(res);
  
  var active_tbody = document.getElementById(tbody);
  var i;
  for(i=0; i< response.length ; i++){
    //create elements
    var tr =  document.createElement('tr');
    var tdi = document.createElement('td');
    var tdcallednumber = document.createElement('td');
    var tdcaller = document.createElement('td');
    var tdcallstatus = document.createElement('td');
    var tdstarttime = document.createElement('td');
    var tdendtime = document.createElement('td');
    var tdcallduration = document.createElement('td');
    var tdcallrecording = document.createElement('td');
    var linkrecording = document.createElement('a');
    var tdgetdate = document.createElement('td');
    
    
    //put values on the elements
    tdi.textContent = i+1;
    tdcallednumber.textContent = response[i].calledNumber;
    tdcaller.textContent = response[i].caller;
    tdcallstatus.textContent = response[i].callStatus;
    tdstarttime.textContent = response[i].startime;
    tdendtime.textContent = response[i].endtime;
    tdcallduration.textContent = response[i].callDuration;
    linkrecording.textContent = "Call Recording";
    linkrecording.href = response[i].callrecording;
    tdcallrecording.appendChild(linkrecording);
    tdgetdate.textContent = response[i].getDate;

    //tds to tr

    tr.appendChild(tdi);
    tr.appendChild(tdcallednumber);
    tr.appendChild(tdcaller);
    tr.appendChild(tdcallstatus);
    tr.appendChild(tdstarttime);
    tr.appendChild(tdendtime);
    tr.appendChild(tdcallduration)
    tr.appendChild(tdcallrecording);
    tr.appendChild(tdgetdate);

    //append tr to tbody
    active_tbody.appendChild(tr);
  }
}