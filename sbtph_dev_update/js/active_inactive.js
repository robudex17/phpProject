setInterval(function() {
    var tb_body = document.getElementById('active_tbody');
    tb_body.innerHTML = '';
   getActiveAgents()
  },5000)

function getActiveAgents(){

   getLoginUser()

   fetch(`${HTTPADDR}api/active.php`).then(response => {
    return response = response.json();
  }).then(data =>{
    var tbody = 'active_tbody';
    logInOutTable(data,tbody)
  })
}

function getInactiveAgents(){
   getLoginUser()
  fetch(`${HTTPADDR}api/inactive.php`).then(response => {
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

    //for active channels and counter greater or equal than 10 only
    if(response[i].channelstat == 1 && response[i].counter >= 10 ){

        //Creating Modal Form
          
        var parentModal = document.createElement('div');
        document.getElementById('main').appendChild(parentModal);
        parentModal.id =  "myModal" + i;
        parentModal.className = "modal";
        var modalDialog = document.createElement('div');
        modalDialog.className = "modal-dialog";
        parentModal.appendChild(modalDialog);

        var modalContent = document.createElement('div');
        modalContent.className = "modal-content";
        modalDialog.appendChild(modalContent);

        var modalHeader = document.createElement('div');
        modalHeader.className = "modal-header";
        var modalTitle = document.createElement('h4');
        modalTitle.className = "modal-title";
        modalTitle.textContent = "CALL BARGING";
        var modalBtn = document.createElement('button');
        modalBtn.className= "close";
        modalBtn.dataset.dismiss = "modal";
        // modalBtn.textContent = "&times;";
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalBtn);
        modalContent.appendChild(modalHeader);


        var modalBody = document.createElement('div');
        modalBody.className = "modal-body";
        var pBody = document.createElement('p');
        pBody.id = response[i].extension + "message";
        pBody.textContent = response[i].extension + " Channel is Currently Active";
        modalBody.appendChild(pBody)
        modalContent.appendChild(modalBody);

        var modalFooter = document.createElement('div');
        modalFooter.id = response[i].extension + "modalfooter";
        modalFooter.className = "modal-footer";

        var listenBtn = document.createElement('button');
        listenBtn.id = response[i].extension ;
        listenBtn.className = "btn btn-primary";

       // listenBtn.dataset.dismiss = "modal";
        listenBtn.textContent = "Listen Now";
        listenBtn.addEventListener('click', function(e){
             console.log(e);
             e.path[0].hidden = true;
             var user_extension = document.getElementById('hidden_extension').value;
           
            var params = {}
            params.channel = user_extension
            params.channel_to_spy = e.path[0].id;
            var querystring = JSON.stringify(params);
        

            fetch(`${HTTPADDR}utils/chanspy.php?querystring=${querystring}`).then(response =>{
             return response = response.json()
            }).then(data => 
              console.log(data)
            ).catch(err =>{
              console.log(err)
            })

        })

        modalFooter.appendChild(listenBtn);

        var cancelBtn = document.createElement('button');
        cancelBtn.id = response[i].extension + "cancel";
        cancelBtn.className = "btn btn-danger";
        cancelBtn.dataset.dismiss = "modal";
        cancelBtn.textContent = "Close";
        cancelBtn.addEventListener('click', function(e){
            location.reload();
        })
        modalFooter.appendChild(cancelBtn)
        modalContent.appendChild(modalFooter);
        document.getElementById('main').appendChild(parentModal);

         
        //Creating Channel Button per Itiration
        channelbtn.textContent = 'Active:  ' + response[i].activecalltime;
        channelbtn.className ="btn btn-primary btn-sm";
        var icon = document.createElement('i');
        icon.className = "fa fa-phone";
        icon.style.padding = "3px";
        channelbtn.appendChild(icon);
        channelbtn.style.margin = "5px";
        channelbtn.id = response[i].extension;
        channelbtn.dataset.toggle = "modal";
        channelbtn.dataset.target =  "#myModal" + i;
        channelbtn.dataset.backdrop = "static";
        channelbtn.dataset.keyboard = "false";
        tr.appendChild(channelbtn);
        
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

