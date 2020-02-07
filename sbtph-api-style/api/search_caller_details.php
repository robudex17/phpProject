<?php
//required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");

// // database connection will be here...

// //include database and object files
include_once '../config/database.php';
include_once '../objects/csd.php';

$database = new Database();
$db = $database->getConnection();

$csd = new Csd($db);

 if(isset($_GET['caller'])){

 	$caller = $_GET['caller'];
	

 	$stmnt = $csd->searchCallerDetails($caller);

 }else{

 	echo json_encode(array("message" => "Each Field must not empty"));
 }

