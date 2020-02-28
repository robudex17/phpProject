<?php
//required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Tokyo');

// // database connection will be here...

// //include database and object files
include_once '../config/database.php';
include_once '../objects/csd.php';

$database = new Database();
$db = $database->getConnection();

$csd = new Csd($db);

if( isset($_GET['extension']) && isset($_GET['username']) && isset($_GET['getdate']) ){

	$extension = $_GET['extension'];
	$username = $_GET['username'];
	$getdate = $_GET['getdate'];

	$stmnt = $csd->csdOutboundCallDetails($extension,$username,$getdate);


}else{

	echo json_encode(array("message" => "Each Field must not empty"));
}

