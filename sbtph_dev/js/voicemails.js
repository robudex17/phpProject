




function voicemails() {
      
    getLoginUser()
    
     let url = `${HTTPADDR}api/get_voicemail.php`
     
     fetch(url).then(response => {
        
        return response.json();
       
     }).then(data => {
        console.log(data)
        voicemail_table(data);
     }).catch(err => {
        console.log(err)
     })
     
}



function voicemail_table(response) {
   //var response = JSON.parse(res);
  
  var active_tbody = document.getElementById('voicemails_tbody');
 var tr =  document.createElement('tr');
  if(!response.hasOwnProperty("message")){
      var i;
      for(i=0; i< response.length ; i++){
        //create elements
        var active_tbody = document.getElementById('voicemails_tbody');
        var tr =  document.createElement('tr');
        var tdi = document.createElement('td');
        var tdcaller = document.createElement('td');
        var tdstarttime = document.createElement('td');
        var tdgetdate = document.createElement('td');
         var tdvoicemail = document.createElement('td');
        var linkvoicemail = document.createElement('AUDIO');
        var tddeletebtn = document.createElement('button');
      

        //put values on the elements
        tdi.textContent = i+1;
     
        tdcaller.textContent = response[i].caller;
        tdstarttime.textContent = response[i].time;
        tdgetdate.textContent = response[i].date;
        linkvoicemail.setAttribute("src",response[i].voicemail);
        linkvoicemail.setAttribute("controls","controls");
        linkvoicemail.style.width = "130px";
        tdvoicemail.appendChild(linkvoicemail);
        tddeletebtn.textContent = 'Delete'
        tddeletebtn.className ="btn btn-danger font-weight-normal lead mt-3";
        tddeletebtn.id = response[i].timestamp;

        tddeletebtn.addEventListener('click', function(e){
             e.preventDefault();
             var querystring = `timestamp=${this.id}`;
             var url = `${HTTPADDR}api/delete_voicemail.php?${querystring}`
             if(confirm(`Are You sure you want to delete is voicemail ${this.id}?`)){
              fetch(url).then(res => {
                return res.json()
              }).then(data => {
                alert(`${data.message}`)
                  var active_tbody = document.getElementById('voicemails_tbody');
                  active_tbody.innerHTML = '';
                voicemails();
              }).catch(err => {
                console.log(err)
              })
             }
        })
       
        //tds to tr

        tr.appendChild(tdi);
        tr.appendChild(tdcaller);
        tr.appendChild(tdgetdate);
        tr.appendChild(tdstarttime);
        tr.appendChild(tdvoicemail);
        tr.appendChild(tddeletebtn);
         active_tbody.appendChild(tr); 
      } 
    }else{
        
        td_novoicemail = document.createElement('td')
         td_novoicemail.textContent = response.message;
        tr.appendChild(td_novoicemail);
        active_tbody.appendChild(tr);
  
        
  }


   


}