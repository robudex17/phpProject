<?php

require_once ('smartyHeader.php');


$msg = "Hello smary";
$title = "Smarty demo";


$smarty->assign('title', $title);
$smarty->assign('message', $msg);

$smarty->display('test.tpl');
?>