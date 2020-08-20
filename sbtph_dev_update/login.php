<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="images/favicon.ico">
    <title>Login Page</title>
</head>
<body class="bg-light" onload="alreadyLogin()">
  
    <div class="login-box">
        <img src="images/sbtlogo.png" alt="Logo Here" class="avatar">
        <h1>Login Here</h1>
        <form id="login_form" method="POST">
             <p>Extension</p>
             <input type="text" name="extension" id="extension" placeholder="Enter Extension">
             <p>Secret</p>
             <input type="password" name="secret" id="secret" placeholder="Enter Secret">
			 
             <input type="submit" name="submit" id="" value="Login">
             <a href="#">CSD MONITORING APP</a>
        </form>
        

    </div>
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.min.js"></script>
    <script src="js/offcanvas.js"></script>
    <script src="js/login.js"></script>
    <script src="js/script.js"></script>   
</body>
</html>