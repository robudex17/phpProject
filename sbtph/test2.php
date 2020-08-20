<?php
// $array = array('apple', 'orange', 'strawberry', 'blueberry', 'kiwi', 'strawberry'); //throw in another 'strawberry' to demonstrate that it removes multiple instances of the string
// $array_without_strawberries = array_diff($array, array('strawberry'));
// print_r($array_without_strawberries);

$marks = array(100, 65, 70, 87); 
  
if (in_array("100", $marks)) 
  { 
  echo "found"; 
  } 
else
  { 
  echo "not found"; 
  } 

  