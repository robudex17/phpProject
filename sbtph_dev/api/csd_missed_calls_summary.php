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

if(isset($_GET['getdate']) && isset($_GET['option']) ){

	$getdate = $_GET['getdate'];
	$option =  $_GET['option'];
}else{
	$getdate = date('Y-m-d');
	$option = 'summary';
}
$stmnt = $csd->csdMissedCalls($getdate,$option);