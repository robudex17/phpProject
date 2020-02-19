


setInterval(function(){ 
    var active_tbody = document.getElementById('parked_calls_tbody');
    active_tbody.innerHTML = " ";
    parked_calls() } , 3000);

function parked_calls() {
      
    getLoginUser()
    
     let url = `${HTTPADDR}api/get_parked_calls.php`
     
     fetch(url).then(response => {
        
        return response.json();
       
     }).then(data => {
        console.log(data)
        parked_calls_table(data);
     }).catch(err => {
        console.log(err)
     })
     
}



function parked_calls_table(response) {
   //var response = JSON.parse(res);
  
  var active_tbody = document.getElementById('parked_calls_tbody');
 var tr =  document.createElement('tr');
  if(!response.hasOwnProperty("message")){
      var i;
      for(i=0; i< response.length ; i++){
        //create elements
        var active_tbody = document.getElementById('parked_calls_tbody');
        var tr =  document.createElement('tr');
        var tdi = document.createElement('td');
        var tdcaller = document.createElement('td');
        var tdstarttime = document.createElement('td');
        var tdgetdate = document.createElement('td');
      

        //put values on the elements
        tdi.textContent = i+1;
     
        tdcaller.textContent = response[i].caller;
        tdstarttime.textContent = response[i].time;
        tdgetdate.textContent = response[i].getdate;
       
       
        //tds to tr

        tr.appendChild(tdi);
        tr.appendChild(tdcaller);
        tr.appendChild(tdstarttime);
        tr.appendChild(tdgetdate);
         active_tbody.appendChild(tr); 
      } 
    }else{
        
        td_noparked_calls = document.createElement('td')
        td_noparked_calls.textContent = response.message;
        tr.appendChild(td_noparked_calls);
        active_tbody.appendChild(tr);
  
        
  }

    //append tr to tbody
   


}