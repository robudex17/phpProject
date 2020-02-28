
// function getLoginUser() {
//   var token = {};
//   token.jwt = getCookie('jwt');
//   var xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//       if (this.readyState == 4 && this.status == 200) {
//         //alert(this.responseText);
//          let result = JSON.parse(this.responseText);
//          document.getElementById('user').textContent = result.data.name;
         
//       }

//     };
//     xhttp.open("POST", "api/validate_token.php", true);

  
//     xhttp.setRequestHeader('Content-Type', 'application/json');
//     xhttp.send(JSON.stringify(token));  
// }

function logout() {
  alert('logout');
  document.cookie = "jwt=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"; //delete the jwt cookie
  window.location.reload();
}

function alreadyLogin() {
  
  var jwt = getCookie('jwt')
  if (jwt != ''){
    window.location.href = "http://192.168.70.250/sbtph-api-style/";
  }
  
    
}
         
function getLoginUser() {
  
  var jwt = getCookie('jwt');
  
  if (jwt === ''){
    window.location.href = "http://192.168.70.250/sbtph-api-style/login.php";
  }else{
    var token = JSON.parse(decodeToken(jwt));
    document.getElementById('user').textContent = token.data.name;
  }
}

// function to set cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// get or read cookie
function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' '){
            c = c.substring(1);
        }
 
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function decodeToken(token){
  var playload = atob(token.split('.')[1]);
    return playload;
    
};
