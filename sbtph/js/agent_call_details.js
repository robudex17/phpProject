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
       
       //tag 
        var inbound_tags =['DROPPED CALL','SALES ASSISTANCE','CAR INQUIRY (REGISTERED)','CAR INQUIRY (UNREGISTERED)',
        					'PAYMENT UPDATE','PAYMENT PROBLEM','INVOICE','CONSIGNEE','COURIER','SHIPMENT','DOCUMENTS',
        					'RELEASE','AMENDMENT','COMPLAINTS','NO TAG'
        				  ] 
       var select_tag = document.createElement('SELECT')
       select_tag.setAttribute("id", i+" inbound_select_tag")
       select_tag.style.width = "450px"
       var ptag = document.createElement('p')
       ptag.textContent = "SELECT TAG:"
       ptag.style.padding ="0"
       ptag.style.margin = "0"
       ptag.style.fontWeight = "500"

       for(var t=0;t<inbound_tags.length;t++){
       	var option_el = document.createElement('option')
       	option_el.setAttribute("value",inbound_tags[t])
       	var text_node = document.createTextNode(inbound_tags[t])
       	option_el.appendChild(text_node)
       	select_tag.appendChild(option_el)
       }
       if(response[i].tag == '' || response[i].tag == 'NO TAG' ){
       		var option_el = document.createElement('option')
	       	 option_el.setAttribute("value","")
	       	 option_el.disabled = true
	       	 option_el.selected = true
	       	 var text_node = document.createTextNode("Select Tag")
	       	 option_el.appendChild(text_node)
	       	 select_tag.appendChild(option_el)
       }else{
       	select_tag.value = response[i].tag
       }
        
        modalBody.appendChild(textAreaBody)
        modalBody.appendChild(ptag)
        modalBody.appendChild(select_tag)
        modalContent.appendChild(modalBody)

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
            var getExistingTag = document.getElementById(id+ " inbound_select_tag")
            console.log(getExistingTag.value)
           var data = {};
           data.startimestamp = response[id].startimestamp;
           data.getdate = response[id].getDate
           data.whoansweredcall =response[id].extension
           data.comment = getExistingComment.value
           data.tag =  getExistingTag.value
           
            console.log(data)
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
                if (data.comment == '' && (data.tag == "" || data.tag == "NO TAG")){
                     btncomment.textContent = "Add Comment/Tag";
                     btncomment.className  = "btn btn-outline-info btn-sm text-justify ";
                }else {
                    btncomment.textContent = "View Comment/Tag";
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
    if(response[i].comment === "" && (response[i].tag == "" || response[i].tag == "NO TAG")){
        btncomment.textContent = "Add Comment/Tag";
        btncomment.className  = "btn btn-outline-info btn-sm text-justify ";
    }else{
        btncomment.textContent = "View Comment/Tag";
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


/*

const object1 = {"August-2020":{"tag1":1,"tag3":1},"September-2020":{"tag2":2},"December-2020":{"tag2":1}}

for (const [key, value] of Object.entries(object1)) {
  for(const [key1,value1] of Object.entries(value)){
  	console.log(` ${key} - ${key1}: ${value1}`);
  }
  
}


*/