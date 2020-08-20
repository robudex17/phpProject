
var position = document.getElementById('position').value;
document.getElementById('addbtn').addEventListener('click', function(e){

        addAgent()
     
     
});


function addAgent() {
    var data = {};
    data.name = document.getElementById('addAgent').name.value ;
    data.extension = document.getElementById('addAgent').extension.value;
    data.email = document.getElementById('addAgent').email.value;
    
    //call fetch function

    fetch(`${HTTPADDR}api/create_collection_agent.php`, { method: 'post', body:JSON.stringify(data)} )
    .then(response => {
        response = response.json();
        return response;
    }).then(data => {
        alert(JSON.stringify(data.message))
        location.reload();

    }).catch(err => alert("Cannot Add extension that already added."))

}



function getAllCollectionAgents() {
	getLoginUser();
    var position = document.getElementById('position').value;
    if(position != 1) {
        document.getElementById('add_agent').disabled = true;
    }
	fetch(`${HTTPADDR}api/collection_manage.php`)
	.then(data => {
		data = data.json();
		return data
	}).then(data =>
		putToTable(data)
        
	)
    .catch(err => console.log(err))
}

function putToTable(data){
    var position = document.getElementById('position').value;
	for (var i= 0; i< data.length; i++) {
			//creat element
			var tr = document.createElement('tr')
			var thi = document.createElement('th');
			var tdname = document.createElement('td')
			var tdemail = document.createElement('td')
			var tdextension = document.createElement('td')
			var actUpdate = document.createElement('button')
			var actDelete = document.createElement('button')
			var tdAction = document.createElement('td')

			thi.textContent = i+1;
			tdname.textContent = data[i].name 
			tdextension.textContent = data[i].extension
			tdemail.textContent = data[i].email
			tdname.className = "  text-justify lead "
			tdemail.className ="text-primary  text-justify lead"
			tdextension.className ="text-justify lead"


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
        modalTitle.textContent = "Update Agent Info";
        var modalBtn = document.createElement('button');
        modalBtn.className= "close";
        modalBtn.dataset.dismiss = "modal";
       
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalBtn);
        modalContent.appendChild(modalHeader);


        var modalBody = document.createElement('div');
        modalBody.className = "modal-body";
        var nameBody = document.createElement('input');
        var extensionBody = document.createElement('p');
        var emailBody = document.createElement('input')
        var namelabel = document.createElement('label')
        var extensionlabel = document.createElement('label')
        var emaillabel = document.createElement('label')
         
        nameBody.id = i + "name"
        extensionBody.id = i + "extension"
        emailBody.id = i + "email"
        namelabel.textContent = "Name:"
        extensionlabel.textContent = "Extension:"
        emaillabel.textContent = "Email:"

        
        emailBody.setAttribute('type', 'email')
        nameBody.className = "form-control lead"
        extensionBody.className = "form-control lead bg-dark text-success "
        emailBody.className = "form-control lead"

        nameBody.value = data[i].name 
        extensionBody.textContent = data[i].extension
        emailBody.value = data[i].email

        modalBody.appendChild(namelabel)
        modalBody.appendChild(nameBody)
        modalBody.appendChild(extensionlabel)
        modalBody.appendChild(extensionBody)
        modalBody.appendChild(emaillabel)
        modalBody.appendChild(emailBody)
        modalContent.appendChild(modalBody);

        var modalFooter = document.createElement('div');
        modalFooter.id = data[i].extension + "modalfooter";
        modalFooter.className = "modal-footer";

        var updateBtn = document.createElement('button');
        updateBtn.id = i;
        updateBtn.className = "btn btn-primary";

       // updateBtn.dataset.dismiss = "modal";
        updateBtn.textContent = "Update";

            //disabled if not allowed
            if(position != 1) {
             updateBtn.disabled = true;
             }
        updateBtn.addEventListener('click', function(e){
            var id = e.path[0].id
            var getUpdateName = document.getElementById(id + "name")
            var getUpdateExtension = document.getElementById(id + "extension")
            var getUpdateEmail = document.getElementById(id + "email")

           var params = {};
            params.extension = getUpdateExtension.textContent
            params.name = getUpdateName.value
            params.email = getUpdateEmail.value
            // alert(JSON.stringify(params))

             fetch(`${HTTPADDR}updatecollection.php`, {method:'post', body:JSON.stringify(params)})
             .then(response => {
                 return response.json()
             }).then(data => {
                 console.log(data)
                 alert(data.message)
             }).catch(err =>{
                 console.log(err)
             })

        })

        modalFooter.appendChild(updateBtn);

        var cancelBtn = document.createElement('button');
        cancelBtn.id = data[i].extension + "cancel";
        cancelBtn.className = "btn btn-danger";
        cancelBtn.dataset.dismiss = "modal";
        cancelBtn.textContent = "Close";
        cancelBtn.addEventListener('click', function(e){
            location.reload();
        })
        modalFooter.appendChild(cancelBtn)
        modalContent.appendChild(modalFooter);
        document.getElementById('main').appendChild(parentModal);

         //create action buttons
         var actdiv = document.createElement('div')

         var actUpdate = document.createElement('button');
         var actDelete = document.createElement('button');
         actdiv.appendChild(actUpdate)
         actdiv.appendChild(actDelete)
       

     
        actUpdate.textContent = 'Update'
        actUpdate.className ="btn btn-primary btn-sm font-weight-normal lead";
        actUpdate.style.margin = "0 5px 0";
        actUpdate.id = data[i].extension;
        actUpdate.dataset.toggle = "modal";
        actUpdate.dataset.target =  "#myModal" + i;
        actUpdate.dataset.backdrop = "static";
        actUpdate.dataset.keyboard = "false";
       
     
        actDelete.textContent = 'Delete'
        actDelete.className ="btn btn-danger btn-sm font-weight-normal lead";
       // actDelete.style.margin = "4px";
        actDelete.id =  + data[i].extension;
        actDelete.dataset.toggle = "modal";
        // actDelete.dataset.target =  "#myModal" + i;
        // actDelete.dataset.backdrop = "static";
        // actDelete.dataset.keyboard = "false";

        //disabled if not allowed
            if(position != 1) {
                actDelete.disabled = true;
            }
        actDelete.addEventListener('click',function(e){
            
            var params  = {};
            params.extension = this.id;
            if(confirm(`Are you sure you want delete ${this.id} Agent? Deleting Agent will automatically delete Agent Records as well `)){
                 fetch(`${HTTPADDR}api/delete_collection.php`, {method:'post', body:JSON.stringify(params)})
             .then(response => {
                 return response.json()
             }).then(data => {
                 
                 alert(JSON.stringify(data.message))
                 location.reload();
             }).catch(err =>{
                 console.log(err)
                 location.reload();
             })
            }
            
           
        })



        tdAction.appendChild(actUpdate)
        tdAction.appendChild(actDelete)

    
        tr.appendChild(thi);
		tr.appendChild(tdextension)
		tr.appendChild(tdname)
		tr.appendChild(tdemail)
		tr.appendChild(tdAction)
        document.getElementById('csd_tbody').appendChild(tr)
        
    }




}
