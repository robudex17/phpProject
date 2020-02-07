<?php
//required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('UTC');

// // database connection will be here...

// //include database and object files
// // database connection will be here...

// //include database and object files

$datetime = new DateTime('2020-01-30 05:01:05');
echo $datetime->format('Y-m-d H:i:s') . "\n";
$jp_time = new DateTimeZone('Asia/Tokyo');
$datetime->setTimezone($jp_time);
$gettime =  $datetime->format('Y-m-d H:i:s');

//echo  $gettime;

echo date ( 'Y-m-d H:i:s', strtotime($gettime) - 1);
