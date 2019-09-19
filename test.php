<?php
date_default_timezone_set('Asia/Tokyo');

function secToHR($seconds) {
  $hours = floor($seconds / 3600);
  $minutes = floor(($seconds / 60) % 60);
  $seconds = $seconds % 60;
  return "$hours:$minutes:$seconds";
}

 $currenttimestamp = time();
// //echo $currenttimestamp;
//  $logindate = "2016-03-22 09:27:52";
//  $dt = new DateTime($logindate);

//$dateinseconds = strtotime("2019-09-05 ");

$current_date = date("Y-m-d");
$current_time = date("H:i:s");

// $logintime = $dt->getTimestamp();
// echo $logintime;
//  $duration =   $currenttimestamp - $logintime;

// //$duration = secToHR($duration);

// echo $duration;

$time = '09:27:52';
$parsed = date_parse($time);
$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
$seconds = strtotime("1970-01-01 $time PST");
//echo $seconds;
//echo $seconds; 



$date = new DateTime();
$result = $date->format('Y-m-d H:i:s');

echo $current_time;
$duration = strtotime($result)  - strtotime("2019-09-05 10:00:00") ;
$duration = secToHR($duration);

//echo $duration;

?>