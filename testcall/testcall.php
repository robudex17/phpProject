<?php

$exten = $_GET['querystring'];

 $socket = fsockopen("192.168.70.250","5038", $errno, $errstr, $timeout);
 fputs($socket, "Action: Login\r\n");
 fputs($socket, "UserName: sbtc2c\r\n");
 fputs($socket, "Secret: sbtrading\r\n\r\n");

 
 fputs($socket, "Action: Originate\r\n");
 fputs($socket, "Channel: SIP/$exten\r\n");
 fputs($socket, "Context:sbt-internal-ext\r\n");
 fputs($socket, "Exten: 911\r\n");
 fputs($socket, "Priority: 1\r\n");
 fputs($socket, "Callerid: Notification\r\n");
 fputs($socket, "Action: Logoff\r\n\r\n");
 
 
$wrets=fgets($socket,128);


	while (!feof($socket)) {
		
		$wrets .= fread($socket, 8192);
	}	
	//echo $wrets;


fclose($socket);


?>

<h1>Calling</h1>
