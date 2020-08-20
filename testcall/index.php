<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>TEST CALL</title>
  </head>
  <body>
    <h1 class="text-center">CHOOSE YOUR EXTENSION BELOW AND CLICK CALL</h1>
    <div class="container">
    	
    	<div>
    		<form method="GET" id="genTestcall" >
    			
                             <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Select </label>
                                  </div>

                                <select class="custom-select" name="group" form="genTestcall" required id="extension">
                                   <option value="0">0</option>
                                   
                                </select>
                            </div>
                            <div class="row">
                            	<div class="col-2">
                            		 	<button class="class btn btn-lg btn-primary" id="call">CALL</button>
                            	</div>
                            	 <div class="col-2">
                            		<button class="class btn btn-lg btn-secondary" id="reload">RELOAD</button>
                           		 </div>
                           
                            </div>
                            
    		</form>
    	</div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript">
        document.getElementById('reload').disabled = true
         
    	function putValue(select){
		   for(var i=6300;i<=6499;i++){
		     var option = document.createElement('option')
		     option.textContent = i;
		     option.value = i;
		     select.appendChild(option);
   		}
 	 }
 	 var getExtension = document.getElementById('extension')
 	 putValue(getExtension)

     var call = document.getElementById('call').addEventListener('click', function(e){
     	e.preventDefault()
          document.getElementById('reload').disabled = false
           document.getElementById('call').disabled = true
     	var select = document.getElementById("extension");
		var selected_extension = select.options[select.selectedIndex].text;
     	
        

            fetch(`http://103.5.6.2/testcall/testcall.php?querystring=${selected_extension}`).then(response =>{
             return response = response.json()
            }).then(data => 
              console.log(data)
            ).catch(err =>{
              console.log(err)
            })

     })

     document.getElementById('reload').addEventListener('click', function(){
     	location.reload(); 
     })

    </script>
  </body>
</html>
