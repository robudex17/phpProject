
document.getElementById('addbtn').addEventListener('click', function(e){
   
   addsalesAgent()
});


function addsalesAgent() {
    var data = {};
    data.name = document.getElementById('addAgent').name.value ;
    data.extension = document.getElementById('addAgent').extension.value;
    data.email = document.getElementById('addAgent').email.value;
    data.teamlead = document.getElementById('teamleader').value
    
    //call fetch function

    fetch('http://103.5.6.2/sbtph_qc_pub/api/create_sales_agent.php', { method: 'post', body:JSON.stringify(data)} )
    .then(response => {
        response = response.json();
        console.log(response)
        return response;
    }).then(data => {
        alert(JSON.stringify(data.message))
        location.reload();

    }).catch(err => alert("Cannot Add extension that already added."))

}


function getAllAgents() {
	getLoginUser();
    var position = document.getElementById('position').value;
     if(position != 1) {
        document.getElementById('add_agent').disabled = true;
    }

	fetch('http://103.5.6.2/sbtph_qc_pub/api/sales_manage.php')
	.then(data => {
		data = data.json();
		return data
	}).then(data =>
        {
            console.log(data)
            putToTable(data)
        }
		
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
            var tdtl = document.createElement('td')
			var tdextension = document.createElement('td')
			var actUpdate = document.createElement('button')
			var actDelete = document.createElement('button')
			var tdAction = document.createElement('td')

			thi.textContent = i+1;
			tdname.textContent = data[i].name 
            tdtl.textContent = data[i].teamlead
			tdextension.textContent = data[i].extension
			tdemail.textContent = data[i].email
			tdname.className = " lead text-justify"
			tdemail.className ="text-primary lead text-justify"
			tdextension.className ="lead text-justify"


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
        var teamleadlabel = document.createElement('label')
        var select = document.createElement('select')
        var br = document.createElement('br')


        nameBody.id = i + "name"
        extensionBody.id = i + "extension"
        emailBody.id = i + "email"
        namelabel.textContent = "Name:"
        extensionlabel.textContent = "Extension:"
        emaillabel.textContent = "Email:"
        teamleadlabel.textContent = "TeamLeader:"

        nameBody.setAttribute('type', 'text');
        emailBody.setAttribute('type', 'email')
        nameBody.className = "form-control lead"
        extensionBody.className = "form-control lead bg-dark text-success"
        emailBody.className = "form-control lead"
        
        //Select
        select.id = i + "teamleader";
        select.className = " custom-select"
        
        //create teamleader option
        var bry = document.createElement('option')
        var bry_textNode = document.createTextNode('BRY')
        bry.setAttribute("value", "BRY")
        bry.appendChild(bry_textNode)

        var chel = document.createElement('option')
        var chel_textNode = document.createTextNode('CHEL')
        chel.setAttribute("value", "CHEL")
        chel.appendChild(chel_textNode)

        var don = document.createElement('option')
        var don_textNode = document.createTextNode('DON')
        don.setAttribute("value", "DON")
        don.appendChild(don_textNode)

        var ivan = document.createElement('option')
        var ivan_textNode = document.createTextNode('IVAN')
        ivan.setAttribute("value", "IVAN")
        ivan.appendChild(ivan_textNode)

        var selectted = document.createElement('option');
        selectted.setAttribute('value', data[i].teamlead)
        var selectted_textNode = document.createTextNode(data[i].teamlead)
        selectted.appendChild(selectted_textNode)

        var joman = document.createElement('option')
        var joman_textNode = document.createTextNode('JOMAN')
        joman.setAttribute("value", "JOMAN")
        joman.appendChild(joman_textNode)

        var jhun = document.createElement('option')
        var jhun_textNode = document.createTextNode('JHUN')
        jhun.setAttribute("value", "JHUN")
        jhun.appendChild(jhun_textNode)

        var ken = document.createElement('option')
        var ken_textNode = document.createTextNode('KEN')
        ken.setAttribute("value", "KEN")
        ken.appendChild(ken_textNode)

        var mhel = document.createElement('option')
        var mhel_textNode = document.createTextNode('MHEL')
        mhel.setAttribute("value", "MHEL")
        mhel.appendChild(mhel_textNode)

        var paula = document.createElement('option')
        var paula_textNode = document.createTextNode('PAULA')
        paula.setAttribute("value", "PAULA")
        paula.appendChild(paula_textNode)

        var rustan = document.createElement('option')
        var rustan_textNode = document.createTextNode('RUSTAN')
        rustan.setAttribute("value", "RUSTAN")
        rustan.appendChild(rustan_textNode)

        var sally = document.createElement('option')
        var sally_textNode = document.createTextNode('SALLY')
        sally.setAttribute("value", "SALLY")
        sally.appendChild(sally_textNode)

        var zarwin = document.createElement('option')
        var zarwin_textNode = document.createTextNode('ZARWIN')
        zarwin.setAttribute("value", "ZARWIN")
        zarwin.appendChild(zarwin_textNode)

        //add teamleader option to select tag
        select.appendChild(selectted)
        select.appendChild(bry)
        select.appendChild(chel)
        select.appendChild(don)
        select.appendChild(ivan)
        select.appendChild(joman)
        select.appendChild(jhun)
        select.appendChild(ken)
        select.appendChild(mhel)
        select.appendChild(paula)
        select.appendChild(rustan)
        select.appendChild(sally)
        select.appendChild(zarwin)

        
        nameBody.value = data[i].name 
        extensionBody.textContent = data[i].extension
        emailBody.value = data[i].email

        modalBody.appendChild(namelabel)
        modalBody.appendChild(nameBody)
        modalBody.appendChild(extensionlabel)
        modalBody.appendChild(extensionBody)
        modalBody.appendChild(emaillabel)
        modalBody.appendChild(emailBody)
        modalBody.appendChild(br)
        modalBody.appendChild(teamleadlabel)
        modalBody.appendChild(select)
        modalContent.appendChild(modalBody);

        var modalFooter = document.createElement('div');
        modalFooter.id = data[i].extension + "modalfooter";
        modalFooter.className = "modal-footer";

        var updateBtn = document.createElement('button');
        updateBtn.id = i;
        updateBtn.className = "btn btn-primary";

       // updateBtn.dataset.dismiss = "modal";
        updateBtn.textContent = "Update";
         if(position != 1) {
             updateBtn.disabled = true;
        }
        updateBtn.addEventListener('click', function(e){
            console.log(e)
            var id = e.path[0].id
            var getUpdateName = document.getElementById(id + "name")
            var getUpdateExtension = document.getElementById(id + "extension")
            var getUpdateEmail = document.getElementById(id + "email")
            var getupdateSelect = document.getElementById(id + "teamleader")


           var params = {};
            params.extension = getUpdateExtension.textContent
            params.name = getUpdateName.value
            params.email = getUpdateEmail.value
            params.teamlead = getupdateSelect.value
            console.log(params)
            // alert(JSON.stringify(params))

             fetch('http://103.5.6.2/sbtph_qc_pub/api/updatesales.php', {method:'post', body:JSON.stringify(params)})
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
        actUpdate.addEventListener('click', function(e){
            console.log(this)
        })
       
     
        actDelete.textContent = 'Delete'
        actDelete.className ="btn btn-danger btn-sm font-weight-normal lead";
       // actDelete.style.margin = "4px";
        actDelete.id = data[i].extension;
        actDelete.dataset.toggle = "modal";
        // actDelete.dataset.target =  "#myModal" + i;
        // actDelete.dataset.backdrop = "static";
        // actDelete.dataset.keyboard = "false";
         if(position != 1) {
             actDelete.disabled = true;
        }
        actDelete.addEventListener('click',function(e){
            
            var params  = {};
            params.extension = this.id;
            if(confirm(`Are you sure you want delete ${this.id} Agent? Deleting Agent will automatically delete Agent Records as well`)){
                 fetch('http://103.5.6.2/sbtph_qc_pub/api/delete_sales.php', {method:'post', body:JSON.stringify(params)})
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
        tr.appendChild(tdtl)
		tr.appendChild(tdemail)
		tr.appendChild(tdAction)
        document.getElementById('sales_tbody').appendChild(tr)
        
    }




}
