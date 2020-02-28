function getActiveAgents(){
   getLoginUser()
   fetch('http://192.168.70.250/sbtph-api-style/api/active.php').then(response => {
    return response = response.json();
  }).then(data =>{
    var tbody = 'active_tbody';
    logInOutTable(data,tbody)
  })
 }

function getInactiveAgents(){
   getLoginUser()
  fetch('http://192.168.70.250/sbtph-api-style/api/inactive.php').then(response => {
    return response = response.json();
  }).then(data =>{
    var tbody = 'inactive_tbody';
    logInOutTable(data,tbody)
  })
}

function logInOutTable(data,tbody) {
 getLoginUser();
 var response = data;

  var tb_body = document.getElementById(tbody);
  var i;
  for(i=0; i< response.length ; i++){
    //create elements
    var tr =  document.createElement('tr');
    var tdi = document.createElement('td');
    var tdlextension = document.createElement('td');
    var tdusername = document.createElement('td');
    var tdloginlogout = document.createElement('td');
    var tdloginduration = document.createElement('td');
    var linkloginlogout = document.createElement('a');
    var channelbtn = document.createElement('button');
      
    //put values on the elements
    tdi.textContent = i+1;
    tdlextension.textContent = response[i].extension;
    tdusername.textContent = response[i].username;
    
    linkloginlogout.href = response[i].loginlogout;
    linkloginlogout.textContent = "Click Details";
    tdloginlogout.appendChild(linkloginlogout);
    tdloginduration.textContent = response[i].loginduration;
    
    //tds to tr

    tr.appendChild(tdi);
    tr.appendChild(tdlextension);
    tr.appendChild(tdusername);
    tr.appendChild(tdloginlogout);
    tr.appendChild(tdloginduration);

    //for active channels only
    if(response[i].channelstat == 1){
    channelbtn.textContent = 'Active'
    channelbtn.className ="btn btn-primary btn-sm";
    channelbtn.style.margin = "5px";
    channelbtn.id = response[i].extension;
    tr.appendChild(channelbtn);
    channelbtn.addEventListener('click', function(e){
      alert(e.path[0].id) // this is id of each button
    });  
    }
   

    //append tr to tbody
    tb_body.appendChild(tr);
}

}
function getLogInOutDetails() {
   getLoginUser()
   var querystring = window.location.search.substring(1); // get the query string from the URL of the current page.
   var xhttp = new XMLHttpRequest();
   var username = querystring.split('&');
   var valuepairname = username[1].split("=");
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var tbody = 'details_tbody'
      document.getElementById('agentname').textContent= valuepairname[1];
      logInOutDetailsTables(this.responseText,tbody);
    
    }
  };
  var csdquery = `api/login_logout_details.php?${querystring}`;
  xhttp.open("GET", csdquery , true);
  xhttp.send();
}

function logInOutDetailsTables(res,tbody) {
  var response = JSON.parse(res);
  var detailsbody = document.getElementById(tbody);
  var i;
  for(i=0; i< response.length ; i++){
    //create elements
    var tr =  document.createElement('tr');
    var tdi = document.createElement('td');
    var tdlog = document.createElement('td');
    var tddate = document.createElement('td');
    var tdtime = document.createElement('td');
   
    
    
    //put values on the elements
    tdi.textContent = i+1;
    tdlog.textContent = response[i].LOG;
    tddate.textContent = response[i].DATE;
    tdtime.textContent = response[i].TIME;
   

    //tds to tr

    tr.appendChild(tdi);
    tr.appendChild(tdlog);
    tr.appendChild(tddate);
    tr.appendChild(tdtime);
  

    //append tr to tbody
    detailsbody.appendChild(tr);
}

}

// document.getElementsByTagName('button').addEventListener('click', function(e){
//   console.log(e);
// });