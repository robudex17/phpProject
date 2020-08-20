
var report = document.getElementById('metrics_summary_export').addEventListener('click', getMetricsExport)

var querystring = window.location.search.substring(1);
var options = {};
var split_the_querystring = querystring.split('&');
var group = split_the_querystring[0];
var start_date_and_time = split_the_querystring[1];

options.option_metrics = split_the_querystring[3].split("=")[1];
options.duration_weight = split_the_querystring[4].split("=")[1];
options.callcount_weight = split_the_querystring[5].split("=")[1];

function getMetricsExport(){
var url = `${HTTPADDR}api/get_metrics.php?${querystring}`;
if(options.option_metrics =='tag'){
   //alert('Option metric tag is selected')
   var dataobj = ''
   fetch(url).then(response => {
     return response.json()
   }).then(data => {
    dataobj = data;
    return fetch(`${HTTPADDR}json/metrics.json`);
   }).then(res => {
    return res.json()
   }).then(jsonobj => {
      //THIS SECTION IS GENERATING EXCEL FILE FOR TAGS METRICS
      var array_tags = []
      var record_tags = {} 
      var array_months = []
      var tags_options = []

      for (const [key, value] of Object.entries(dataobj)) {
        // for (var t=0; t<tags_options.length ; t++){
        //   if(key === tags_options[t]){
        //     tags_options.splice(t,1)
        //   }
        //  }
        if (typeof value === 'object'){
           
            for(const [key1,value1] of Object.entries(value)){
          
             if(array_months.includes(key1) === false){
               array_months.push(key1)
             }
             
          }
          
        }
        
      }
       //The first Row of the file
      var head = [];
      var group = {text:`${dataobj.option.toString().toUpperCase()} TAGS`}
      head.push(group)
       for (var i=0;i<array_months.length;i++){
            var heading = {text: array_months[i]}
            head.push(heading)
        
       }
       var total = {text: 'Total'}
       head.push(total)
       jsonobj.tableData[0].data.push(head)

       //construct the value to be put on the table
       for (const [key, value] of Object.entries(dataobj)){
          var array_tag = []
          if (typeof value === 'object'){
            array_tag.push(key)
            for (const [key1,value1] of Object.entries(value)){
              if (value1 === 0){
                value1 === '';
              }
              array_tag.push(value1)
            }
             array_tags.push(array_tag)
          }

       }

         //populate or put the value  in the table
      for(var i= 0; i<array_tags.length ;i++){ 
        var data_array = [];
        for(var j=0 ; j<array_tags[i].length; j++){
            if(array_tags[i][j] == "0" || array_tags[i][j] == 0){
              var data = {text : ""}
            }else {
               var data = {text : array_tags[i][j]}
            }
           
           
           data_array.push(data)
           
        }
        //filter out the non number element and get the total value of all number element in filtered array
        var total = array_tags[i].filter(el => {
           if(typeof el === 'number'){
            return el
           }
        }).reduce((subtotal , nextVal) => {
          return subtotal + nextVal
        },0)
         var t = {text : total}
         data_array.push(t)
        jsonobj.tableData[0].data.push(data_array)
      }

       jsonobj.options.fileName = `${dataobj.option}-${dataobj.dateRange}-tag`;
       Jhxlsx.export(jsonobj.tableData, jsonobj.options);

      //END SECTION OF TAGS METRICS EXPORT
   }).catch(err => {
     console.log(err)
   })
}else{
  var dataobj ='';
    fetch(url).then(response => {
      return response.json();
    }).then(data => 
      {
       
        dataobj = data;
       return fetch(`${HTTPADDR}json/metrics.json`);
    }).then(res => {
      return res.json();
    }).then(jsonobj => {
      var metrics = [];
      let blank = [];
      var name = {text:  'NAME'};
      var extension = {text :"EXTENSION"};
      metrics.push(name);
      metrics.push(extension);
      if(dataobj[0].duration_weight != 0 && dataobj[0].callcount_weight != 0 ){
        
        var total_counts = {text : "Total Counts(#)"};
        var total_call_duration = {text : "Total Calls Durations(HH:MM:SS)"};
        var call_counts_percentage = {text :  `CallCounts Percentage(${dataobj[0].callcount_weight})`};
        var call_duration_percentage = {text : `Call Duration Percentage(${dataobj[0].duration_weight})`};
        var total_percent = {text : "Total(100%)"};
        metrics.push(total_counts)
        metrics.push(total_call_duration)
        metrics.push(call_counts_percentage)
        metrics.push(call_duration_percentage)
        metrics.push(total_percent)
        jsonobj.tableData[0].data.push(metrics)
        for(var i=0;i<dataobj[1].length; i++){
          let metrics = [];
          
          let name = {text: `${dataobj[1][i].name}` };
          let extension = {text : dataobj[1][i].extension};
          let total_counts = {text :dataobj[1][i].total_answered };
          let total_call_duration = {text : dataobj[1][i].total_duration };
          let c_percentage = (Number(dataobj[1][i].total_answered /dataobj[0].grand_total_counts)* Number(dataobj[0].callcount_weight)).toFixed(3)
          let d_percentage =(Number(dataobj[1][i].total_sec /dataobj[0].grand_total_duration_sec) * Number(dataobj[0].duration_weight)).toFixed(3)
          let call_counts_percentage = {text : c_percentage + "%"};
          let call_duration_percentage = {text : d_percentage + "%"};
          let total = (Number(c_percentage) + Number(d_percentage)).toFixed(4)
          let total_percent = {text :  `${total}%` };
          metrics.push(name);
          metrics.push(extension);
          metrics.push(total_counts)
          metrics.push(total_call_duration)
          metrics.push(call_counts_percentage)
          metrics.push(call_duration_percentage)
          metrics.push(total_percent)
          jsonobj.tableData[0].data.push(metrics)
          
        }
        let grandata = [];
        let grand_total_counts = {text: 'Grand Total Counts: '+ dataobj[0].grand_total_counts};
        let grand_total_duration = {text: 'Grand Total Call Duration(HH:MM:SS): ' + dataobj[0].grand_total_duration };
        let date_time_range = {text: 'Date Range: ' + dataobj[0].datetimeRange}
        jsonobj.tableData[0].data.push(blank);
        grandata.push(date_time_range);
        grandata.push(grand_total_counts);
        grandata.push(grand_total_duration);
        jsonobj.tableData[0].data.push(grandata);
        console.log(JSON.stringify(jsonobj));
        jsonobj.options.fileName = `${dataobj[0].option}-${dataobj[0].datetimeRange}-blended`;
        Jhxlsx.export(jsonobj.tableData, jsonobj.options);
      }
      //generate callcounts metrics
      if(dataobj[0].duration_weight == 0){
        var total_counts = {text : "Total Counts(#)"};
        var call_counts_percentage = {text :  `CallCounts Percentage(${dataobj[0].callcount_weight})`};
        var total_percent = {text : "Total(100%)"};
        metrics.push(total_counts)
        metrics.push(call_counts_percentage)
        metrics.push(total_percent)
        jsonobj.tableData[0].data.push(metrics)
         for(var i=0;i<dataobj[1].length; i++){
             let metrics = [];
            let blank = [];
            let name = {text: `${dataobj[1][i].name}` };
            let extension = {text : dataobj[1][i].extension};
            let total_counts = {text :dataobj[1][i].total_answered };
            let c_percentage = (Number(dataobj[1][i].total_answered /dataobj[0].grand_total_counts)* Number(dataobj[0].callcount_weight)).toFixed(3)
            let call_counts_percentage = {text : c_percentage + "%"};
            let total = c_percentage
            let total_percent = {text :  `${total}%` };
             metrics.push(name);
            metrics.push(extension);
            metrics.push(total_counts)
            metrics.push(call_counts_percentage)
            metrics.push(total_percent)
           jsonobj.tableData[0].data.push(metrics)
          

        }
         let grandata = [];
          let grand_total_counts = {text: 'Grand Total Counts: '+ dataobj[0].grand_total_counts};
          let date_time_range = {text: 'Date Range: ' + dataobj[0].datetimeRange}
           jsonobj.tableData[0].data.push(blank);
          grandata.push(date_time_range);
         grandata.push(grand_total_counts);
          jsonobj.tableData[0].data.push(grandata);
           jsonobj.options.fileName = `${dataobj[0].option}-${dataobj[0].datetimeRange}-callcounts`;
        Jhxlsx.export(jsonobj.tableData, jsonobj.options);



      }
      //generate callduration metrics
      if(dataobj[0].callcount_weight == 0){
        var total_call_duration = {text : "Total Calls Durations(HH:MM:SS)"};
        var call_duration_percentage = {text : `Call Duration Percentage(${dataobj[0].duration_weight})`};
        var total_percent = {text : "Total(100%)"};
         metrics.push(total_call_duration)
         metrics.push(call_duration_percentage)
        metrics.push(total_percent)
        jsonobj.tableData[0].data.push(metrics)
        console.log(JSON.stringify(jsonobj));
         for(var i=0;i<dataobj[1].length; i++){
            let metrics = [];
            let blank = [];
            let name = {text: `${dataobj[1][i].name}` };
            let extension = {text : dataobj[1][i].extension};
            let total_call_duration = {text : dataobj[1][i].total_duration };
            let d_percentage =(Number(dataobj[1][i].total_sec /dataobj[0].grand_total_duration_sec) * Number(dataobj[0].duration_weight)).toFixed(3)
            let call_duration_percentage = {text : d_percentage + "%"};
            let total = d_percentage
           let total_percent = {text :  `${total}%` };
           metrics.push(name);
           metrics.push(extension);
           metrics.push(total_call_duration)
           metrics.push(call_duration_percentage)
           metrics.push(total_percent)
           jsonobj.tableData[0].data.push(metrics)
          


         }
         let grandata = [];
          let grand_total_duration = {text: 'Grand Total Call Duration(HH:MM:SS): ' + dataobj[0].grand_total_duration };
         let date_time_range = {text: 'Date Range: ' + dataobj[0].datetimeRange}
        jsonobj.tableData[0].data.push(blank);
        grandata.push(date_time_range);
         grandata.push(grand_total_duration);
        jsonobj.tableData[0].data.push(grandata);
        jsonobj.options.fileName = `${dataobj[0].option}-${dataobj[0].datetimeRange}-callduration`;
        Jhxlsx.export(jsonobj.tableData, jsonobj.options);

      }
    })
    .catch(err =>{
      console.log(err)
    })
   }
}

var querystring = window.location.search.substring(1);
var options = {};
function getMetrics(){
  getLoginUser()

   var split_the_querystring = querystring.split('&');
   var group = split_the_querystring[0];
   var start_date_and_time = split_the_querystring[1];

   options.option_metrics = split_the_querystring[3].split("=")[1];
   options.duration_weight = split_the_querystring[4].split("=")[1];
   options.callcount_weight = split_the_querystring[5].split("=")[1];

   //hide the duration and callcounts table and make the tag metrics table visible
   
  //console.log(split_the_querystring);
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    //   var tbody = 'generate_metrics_table'
    // getMetricsTable(this.responseText,tbody,);
      if(options.option_metrics == 'tag'){
        document.getElementById('count-duration-metrics').style.display = 'none';
        document.getElementById('tag-metrics').style.display = 'block';
         var tbody = 'generate_tag_metrics_table'
         getMetricsTagTable(this.responseText,tbody)

      }else{
        document.getElementById('count-duration-metrics').style.display = 'block';
        document.getElementById('tag-metrics').style.display = 'none';
       var tbody = 'generate_metrics_table'
       getMetricsTable(this.responseText,tbody,);
      }
    }
  };
  if(querystring !== ''){
    var apiquery = `api/get_metrics.php?${querystring}`;
    xhttp.open("GET", apiquery  , true);
    xhttp.send();
  }else {
    xhttp.open("GET", "api/get_metrics.php", true);
    xhttp.send();
  }

}
function getMetricsTagTable(res){
  var response = JSON.parse(res)
  var table_tag_tr = document.getElementById('table-tag-tr')
  var table_tag = document.getElementById('generate_tag_metrics_table')
  var total_th = document.createElement('th')
  total_th.setAttribute("scope","col")
  var array_tags = []
  var record_tags = {} 
  var array_months = []
  var tags_options = []
  
  document.getElementById('options-tag').textContent = `${response.option.toString().toUpperCase()} TAGS`;
    
    for (const [key, value] of Object.entries(response)) {
      for (var t=0; t<tags_options.length ; t++){
        if(key === tags_options[t]){
          tags_options.splice(t,1)
        }
      }
      if (typeof value === 'object'){
         
          for(const [key1,value1] of Object.entries(value)){
        
           if(array_months.includes(key1) === false){
             array_months.push(key1)
           }
           
        }
        
      }
      
    }
    
   for (var i=0;i<array_months.length;i++){

    var th_month = document.createElement('th')
        th_month.setAttribute("scope","col")
        th_month.textContent = array_months[i]
        table_tag_tr.appendChild(th_month)
   }
   total_th.textContent = "Total"
   table_tag_tr.appendChild(total_th)

   //construct the value to be put on the table
   for (const [key, value] of Object.entries(response)){
    var array_tag = []
    if (typeof value === 'object'){
      array_tag.push(key)
      for (const [key1,value1] of Object.entries(value)){
        if (value1 === 0){
          value1 === '';
        }
        array_tag.push(value1)
      }
       array_tags.push(array_tag)
    }

   }
    //populate or put the value  in the table
    for(var i= 0; i<array_tags.length ;i++){ 
      var tr = document.createElement('tr')
      var tdtotal = document.createElement('td')
      for(var j=0 ; j<array_tags[i].length; j++){
       var td = document.createElement('td')
       td.textContent = array_tags[i][j]
       if(td.textContent == '0' || td.textContent == 0){
        td.textContent= ''
       }
       tr.appendChild(td)
       table_tag.appendChild(tr)
      }
      //filter out the non number element and get the total value of all number element in filtered array
      var total = array_tags[i].filter(el => {
         if(typeof el === 'number'){
          return el
         }
      }).reduce((subtotal , nextVal) => {
        return subtotal + nextVal
      },0)

      tdtotal.textContent = total
      tr.appendChild(tdtotal)
      table_tag.appendChild(tr)
    }
    
   
}
function getMetricsTable(res,tbody) {
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
    document.getElementById('options').textContent = `${response[0].option.toString().toUpperCase()}#`;
    document.getElementById('call_count_percentage').textContent = `Call Counts Percentage(${options.callcount_weight}%)`
   document.getElementById('call_duration_percentage').textContent = `Call Duration Percentage(${options.duration_weight}%)`
   document.getElementById('date_time_range').textContent = response[0].datetimeRange;
   document.getElementById('grand_total_counts').innerHTML = `Grand Total Counts:<span class="font-weight-bold text-danger">${response[0].grand_total_counts}</span>`;
   document.getElementById('grand_total_call_duration').innerHTML = `Grand Total Call Duration(HH:MM:SS): <span class="font-weight-bold text-danger">${response[0].grand_total_duration}</span>` ;

   if(response[0].callcount_weight == 0){
       document.getElementById('call_count_percentage').style.display = 'none';
       document.getElementById('grand_total_counts').style.display = 'none';
       document.getElementById('total_counts').style.display = 'none';
   }
   if(response[0].duration_weight == 0){
       document.getElementById('call_duration_percentage').style.display = 'none';
       document.getElementById('grand_total_call_duration').style.display = 'none';
       document.getElementById('total_duration').style.display = 'none';
   }
    var i;
    for(i=0; i< response[1].length ; i++){
      //create elements
      var tr =  document.createElement('tr');
      var tdi = document.createElement('td');
      var tdname = document.createElement('td');
      var tdextension = document.createElement('td');
      var tdtotalcallcounts = document.createElement('td');
      var tdtotalduration = document.createElement('td');
      var call_count_percentage = document.createElement('td');
      var call_duration_percentage = document.createElement('td');
      var total_percentage = document.createElement('td');


      if(response[0].callcount_weight == 0){
       call_count_percentage.style.display = 'none';
       tdtotalcallcounts.style.display = 'none';
   }
   if(response[0].duration_weight == 0){
      tdtotalduration.style.display = 'none';
      call_duration_percentage.style.display = 'none';
   }

      //put values on the elements
      tdi.textContent = i+1;
      tdextension.textContent = response[1][i].extension;
      tdname.textContent = response[1][i].name;
      tdtotalcallcounts.textContent = response[1][i].total_answered;
      tdtotalduration.textContent = response[1][i].total_duration;
      var c_percentage = (Number(response[1][i].total_answered /response[0].grand_total_counts)* Number(options.callcount_weight)).toFixed(3)
      var d_percentage =(Number(response[1][i].total_sec /response[0].grand_total_duration_sec) * Number(options.duration_weight)).toFixed(3)
      call_count_percentage.textContent = c_percentage + '%'
      call_duration_percentage.textContent = d_percentage + '%'
      total_percentage.textContent = (Number(c_percentage) + Number(d_percentage)).toFixed(4) + '%';

      //tds to tr

      tr.appendChild(tdi);
      tr.appendChild(tdname);
      tr.appendChild(tdextension);

      tr.appendChild(tdtotalcallcounts);
      tr.appendChild(tdtotalduration);
      tr.appendChild(call_count_percentage);
       tr.appendChild(call_duration_percentage);
       tr.appendChild(total_percentage);
      //append tr to tbody
      collection_tbody.appendChild(tr);
    }
  }
}
