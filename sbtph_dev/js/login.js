
document.getElementById('login_form').addEventListener("submit", userlogin);

function userlogin () {
	let extension = document.getElementById("login_form").extension.value;
	let secret = document.getElementById("login_form").secret.value;
	let credentials = {};
	credentials.extension = extension
	credentials.secret = secret
	
    if(extension === '' && secret === ''){
    	alert('User is not exist or Invalid Password');
    	document.getElementById("login_form").extension.value = '';
	    document.getElementById('login_form').secret.value = '';
    }else{
		var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		       let result = JSON.parse(this.responseText);
		       setCookie("jwt",result.jwt,1);
		       window.location.href = HTTPADDR;	
		    }

		    if(this.readyState == 4 && this.status != 200) {
		    	alert('User is not exist or Invalid Password');
		    	document.getElementById("login_form").extension.value = '';
		    	document.getElementById('login_form').secret.value = '';
		    }
		  };
		  xhttp.open("POST", "api/login.php", true);

		
		  xhttp.setRequestHeader('Content-Type', 'application/json');
		  xhttp.send(JSON.stringify(credentials));
    }
	 
}


