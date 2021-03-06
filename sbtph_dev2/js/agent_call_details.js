var report = document.getElementById('export')
   report.addEventListener('click', csdinboundCallDetailsExport)

document.getElementById('clickdate').addEventListener('submit', agentCallDetails)

function agentCallDetails() {

    getLoginUser()
    var querystring = window.location.search.substring(1);

    //get username and extension from the query
    var split = querystring.split('&');
    var extension = split[0].split('=');
    var username = split[1].split('=');
    
   document.getElementById('extension').value = extension[1];
   document.getElementById('username').value = username[1];
   document.getElementById('agentusername').innerHTML = username[1];


    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var tbody = 'agent_call_details_tbody'
        agentCallDetailsTable(this.responseText,tbody);
        
      }
    };
   
      var apiquery = `api/agent_call_details.php?${querystring}`;
      xhttp.open("GET", apiquery  , true);
      xhttp.send();
     
}

function csdinboundCallDetailsExport() {

    let querystring = window.location.search.substring(1);
    let split = querystring.split('&');
    let extension = split[0].split('=');
    let username = split[1].split('=');
    let getdate = split[2].split('=');
    let url = `${HTTPADDR}api/csd_inbound_details_export.php?${querystring}`
    fetch(url).then(response => {
        return response.json();
    }).then(data => {
        data.options.fileName = `${username[1]}-${getdate[1]}-inbound-calldetails`
        Jhxlsx.export(data.tableData, data.options);
    })
}


function agentCallDetailsTable(res,tbody) {
   var response = JSON.parse(res);
  
  var active_tbody = document.getElementById(tbody);
  var i;
  for(i=0; i< response.length ; i++){
    //create elements
    var tr =  document.createElement('tr');
    var tdi = document.createElement('td');
    var tdextension = document.createElement('td');
    var tdcallednumber = document.createElement('td');
    var tdcaller = document.createElement('td');
    var tdcallstatus = document.createElement('td');
    var tdstarttime = document.createElement('td');
    var tdendtime = document.createElement('td');
    var tdcallduration = document.createElement('td');
    var tdcallrecording = document.createElement('td');
    var linkrecording = document.createElement('AUDIO');
    var tdgetdate = document.createElement('td');
    var btncomment = document.createElement('button');


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
        modalTitle.textContent = "YOUR COMMENT";
        var modalBtn = document.createElement('button');
        modalBtn.className= "close";
        modalBtn.dataset.dismiss = "modal";
        // modalBtn.textContent = "&times;";
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalBtn);
        modalContent.appendChild(modalHeader);


        var modalBody = document.createElement('div');
        modalBody.className = "modal-body";
        var textAreaBody = document.createElement('textarea');
        textAreaBody.id = i + "message";
        textAreaBody.cols = "62";
        textAreaBody.placeholder = "Put Your Comment Here."
        textAreaBody.textContent = response[i].comment;
        //textAreaBody.textContent = response[i].extension + " Channel is Currently Active";
        modalBody.appendChild(textAreaBody)
        modalContent.appendChild(modalBody);

        var modalFooter = document.createElement('div');
        modalFooter.id = response[i].extension + "modalfooter";
        modalFooter.className = "modal-footer";

        var updateBtn = document.createElement('button');
        updateBtn.id = i ;
        updateBtn.className = "btn btn-primary";

       
       // listenBtn.dataset.dismiss = "modal";
        updateBtn.textContent = "Update";
        updateBtn.addEventListener('click', function(e){
            e.preventDefault() 
           var id = e.path[0].id
            var getExistingComment = document.getElementById(id + "message")

           var data = {};
            data.startimestamp = response[id].startimestamp;
            data.getdate = response[id].getDate
           data.whoansweredcall =response[id].extension
           data.comment = getExistingComment.value
           

             fetch(`${HTTPADDR}api/put_comment.php`, {method:'post', body:JSON.stringify(data)})
             .then(response => {
                 return response.json()
             }).then(res => {
                
                  alert(res.message)
                 let params = `extension=${response[id].extension}&getdate=${response[id].getDate}&startimestamp=${response[id].startimestamp}`
                 let url = `${HTTPADDR}api/get_comment.php?${params}`
                 return fetch(url)
             }).then(response => 
                {return response.json()}
             ).then(data => {
                  console.log(data)
                let btncomment = document.getElementById(response[id].extension + id);
                
                console.log(btncomment.id)
                if (data.comment == ''){
                     btncomment.textContent = "Add Comment";
                     btncomment.className  = "btn btn-outline-info btn-sm text-justify ";
                }else {
                    btncomment.textContent = "View Comment";
                    btncomment.className  = "btn btn-info btn-sm text-justify red"
                }
             }).catch(err =>{
                 console.log(err)
             })
        })

        modalFooter.appendChild(updateBtn);

        var closeBtn = document.createElement('button');
        closeBtn.id = response[i].extension + "cancel";
        closeBtn.className = "btn btn-danger";
        closeBtn.dataset.dismiss = "modal";
        closeBtn.textContent = "Close";
        closeBtn.addEventListener('click', function(e){
            e.preventDefault() ;
        })
        modalFooter.appendChild(closeBtn)
        modalContent.appendChild(modalFooter);
        document.getElementById('main').appendChild(parentModal);
    
    
    //put values on the elements
    tdi.textContent = i+1;
    tdextension.textContent = response[i].extension;
    tdcallednumber.textContent = response[i].calledNumber;
    tdcaller.textContent = response[i].caller;
    tdcallstatus.textContent = response[i].callStatus
    tdstarttime.textContent = response[i].startime;
    tdendtime.textContent = response[i].endtime;
    tdcallduration.textContent = response[i].callDuration;
   // linkrecording.textContent = "Call Recording";
    //linkrecording.href = response[i].callrecording;
    linkrecording.setAttribute("src",response[i].callrecording);
    linkrecording.setAttribute("controls","controls");
    linkrecording.style.width = "130px";
    tdcallrecording.appendChild(linkrecording);
    tdgetdate.textContent = response[i].getDate;
    btncomment.id = response[i].extension + i;
    //Check if commeent is blank
    if(response[i].comment === ""){
        btncomment.textContent = "Add Comment";
        btncomment.className  = "btn btn-outline-info btn-sm text-justify ";
    }else{
        btncomment.textContent = "View Comment";
        btncomment.className  = "btn btn-info btn-sm text-justify red"
    }

    
    btncomment.style.margin = "5px";
    btncomment.dataset.toggle = "modal";
    btncomment.dataset.target =  "#myModal" + i;
    btncomment.dataset.backdrop = "static";
    btncomment.dataset.keyboard = "false";



    //tds to tr

    tr.appendChild(tdi);
    tr.appendChild(tdextension);
    tr.appendChild(tdcallednumber);
    tr.appendChild(tdcaller);
    tr.appendChild(tdcallstatus)
    tr.appendChild(tdstarttime);
    tr.appendChild(tdendtime);
    tr.appendChild(tdcallduration)
    tr.appendChild(tdcallrecording);
    tr.appendChild(tdgetdate);
    tr.appendChild(btncomment);

    //append tr to tbody
    active_tbody.appendChild(tr);
  }
}