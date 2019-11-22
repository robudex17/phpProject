<?php
//required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Tokyo');

// // database connection will be here...

// //include database and object files
include_once '../config/database.php';
include_once '../objects/collection.php';

$database = new Database();
$db = $database->getConnection();

$collection = new Collection($db);

if( isset($_GET['extension']) && isset($_GET['name']) && isset($_GET['getdate']) ){

	$extension = $_GET['extension'];
	$name = $_GET['name'];
	$getdate = $_GET['getdate'];

	$stmnt = $collection->collectionCallDetails($extension,$name,$getdate);


}else{

	echo json_encode(array("message" => "Each Field must not empty"));
}



