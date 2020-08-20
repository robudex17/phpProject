<?php
//required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// // database connection will be here...

//include database and object files
include_once '../config/database.php';
include_once '../objects/sales.php';

$database = new Database();
$db = $database->getConnection();

$sales = new Sales($db);
 // get posted data
  $data = json_decode(file_get_contents("php://input"));
  	$sales->name = $data->name;
	$sales->extension = $data->extension;
 	$sales->email = $data->email;
 	$sales->teamlead = $data->teamlead;



$sales->updateSalesAgent();
// if (!empty($data->name) && !empty($data->extension) && !empty($data->email) ){
// 	$collection->name = $data->name;
// 	$collection->extension = $data->extension;
// 	$collection->email = $data->email;
   
//     if($collection->updateCollectionAgent()){
//     	//set response code ok
//     	http_response_code(200);

//     	echo json_encode(array("message" => "Update was Successful"));
//     }else{
//     	http_response_code(503);
//     	echo json_encode(array("message" => "Unable to update"));
//     }
// }


