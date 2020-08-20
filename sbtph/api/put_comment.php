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
include_once '../objects/csd.php';

$database = new Database();
$db = $database->getConnection();

$csd = new Csd($db);
 // get posted data
  $data = json_decode(file_get_contents("php://input"));

$startimestamp = htmlspecialchars($data->startimestamp) ; 
$getdate =  htmlspecialchars($data->getdate);
$whoansweredcall = htmlspecialchars($data->whoansweredcall);
$comment = htmlspecialchars($data->comment);
$tag= htmlspecialchars($data->tag);

$stmnt = $csd->putComment($startimestamp, $getdate, $whoansweredcall, $comment,$tag);
//$stmnt = $csd->putComment("20200602-000009", "2020-06-02", "6308", "", "");
// echo json_encode($data);
//echo $startimestamp;