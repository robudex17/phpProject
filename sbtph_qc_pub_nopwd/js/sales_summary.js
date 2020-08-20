
var report = document.getElementById('report').addEventListener('click', getSalesCallSummaryExport)
document.getElementById('form_date').addEventListener("submit",  getSalesCallSummary);

function getSalesCallSummaryExport(){
  var querystring = window.location.search.substring(1);
  var d = new Date();
  var months = ["01", "02", "03", "04","05", "06", "07", "08","09", "10", "11", "12" ];
   var getmonth = months[d.getMonth()];
   var getdate = `${d.getFullYear()}-${getmonth}-${d.getDate()}`;
   console.log(getdate)
  var url = 'http://103.5.6.2/sbtph_qc_pub/api/sales_summary_export.php';
  if (querystring !== ''){
       url = `http://103.5.6.2/sbtph_qc_pub/api/sales_summary_export.php?${querystring}`;
       getdate = querystring.split('=')[1];
  }
  fetch(url).then(response => {
        return response.json();
    }).then(data => {
        data.options.fileName = `sales_call_summary-${getdate}-calldetails`
        Jhxlsx.export(data.tableData, data.options);
    })
}

function getSalesCallSummary(){
 // getLoginUser()
  var querystring = window.location.search.substring(1);


  var querydate = document.getElementById('form_date').getdate.value;
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var tbody = 'sales_summary'
     callSalesSummaryTable(this.responseText,tbody);
      
    }
  };
  if(querystring !== ''){
    var apiquery = `api/sales_summary.php?${querystring}`;
    xhttp.open("GET", apiquery  , true);
    xhttp.send();
  }else {
    xhttp.open("GET", "api/sales_summary.php", true);
    xhttp.send();
  }
 
}

function callSalesSummaryTable(res,tbody) {
  var response = JSON.parse(res);
  var collection_tbody = document.getElementById(tbody);   
    if(response.message === "No Records Found"){
      //create elements
      var tr =  document.createElement('tr');
      var tdmessage = document.createElement('td');

      tdmessage.textContent = response.message;
      tr.appendChild(tdmessage);

      //append tr to tbody
      collection_tbody.appendChild(tr);

    }else{
    
    var i;
    for(i=0; i< response.length ; i++){
      //create elements
      var tr =  document.createElement('tr');
      var tdi = document.createElement('td');
      var tdname = document.createElement('td');
      var tdextension = document.createElement('td');
      var tdtl = document.createElement('td');
      var tdtotalmadecalls = document.createElement('td');
      var tdtotal_duration = document.createElement('td');
      var linkdate = document.createElement('a');
      var tddate = document.createElement('td');
      
      
      //put values on the elements
      tdi.textContent = i+1;
      tdextension.textContent = response[i].extension;
      tdname.textContent = response[i].name;
      tdtl.textContent = response[i].teamlead;
      tdtotalmadecalls.textContent = response[i].totalmadecalls;
      tdtotal_duration.textContent = response[i].totalduration;
     linkdate.href = response[i].calldetails;
      linkdate.textContent = response[i].getdate;
      tddate.appendChild(linkdate)
      
      //tds to tr

      tr.appendChild(tdi);
      tr.appendChild(tdextension);
      tr.appendChild(tdname);
      tr.appendChild(tdtl);
      tr.appendChild(tdtotalmadecalls);
      tr.appendChild(tdtotal_duration);
      tr.appendChild(tddate);

      //append tr to tbody
      collection_tbody.appendChild(tr);
    }
  }
}