<?php
//required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Tokyo');

// // database connection will be here...

// //include database and object files
include_once '../config/database.php';
include_once '../objects/sales.php';

$database = new Database();
$db = $database->getConnection();

$sales = new Sales($db);

if(isset($_GET['getdate'])){

	$getdate = $_GET['getdate'];
}else{
	$getdate = date('Y-m-d');
}

$stmnt = $sales->sales_summary_export($getdate);