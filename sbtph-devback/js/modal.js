 

   for (var i = 0; i < 3; i++) {
       var  btn = document.createElement('button')
       btn.id = "btn" + i;
       btn.className = "btn btn-primary";
       btn.textContent = "button" + i;
       btn.dataset.target = "#myModal" + i;
       btn.dataset.toggle = "modal";
       btn.dataset.backdrop = "static";
       btn.dataset.keyboard = "false";
       btn.style.margin = "5px";
      document.getElementById('main').appendChild(btn);


       //Creating Modal Form
          
        var parentModal = document.createElement('div');
        parentModal.id =   "myModal" + i;
        parentModal.className = "modal";
        alert(parentModal.id);
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
        modalTitle.textContent = "Title of button" + i;
        var modalBtn = document.createElement('button');
        modalBtn.className= "close";
        modalBtn.dataset.dismiss = "modal";
        modalBtn.value = "&times;";
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalBtn);
        modalContent.appendChild(modalHeader);


        var modalBody = document.createElement('div');
        modalBody.className = "modal-body";
        var pBody = document.createElement('p');
        pBody.id = "message";
        pBody.textContent = "Channel Are Currently Active";
        modalBody.appendChild(pBody)
        modalContent.appendChild(modalBody);

        var modalFooter = document.createElement('div');
        modalFooter.id ="modalfooter";
        modalFooter.className = "modal-footer";

        var listenBtn = document.createElement('button');
        listenBtn.id =  "listen";
        listenBtn.className = "btn btn-primary";
        listenBtn.dataset.dismiss = "modal";
        listenBtn.textContent = "Listen Now";
      

        modalFooter.appendChild(listenBtn);

        var cancelBtn = document.createElement('button');
        cancelBtn.id = "cancel";
        cancelBtn.className = "btn btn-danger";
        cancelBtn.dataset.dismiss = "modal";
        cancelBtn.textContent = "Close";
        modalFooter.appendChild(cancelBtn)
        modalContent.appendChild(modalFooter);
       document.getElementById('main').appendChild(parentModal);
     
   }


   

    
        