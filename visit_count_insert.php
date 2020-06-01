<?php

include "dbcon.php";

require_once "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);


//저장
$sqlInsert = "INSERT INTO visit_count(ip, date) VALUES ('$get_ip', '$get_date')";
$res = mysql_query($sqlInsert,$conn);

if( !$res ) {
echo "Failed to list visit_count.php";
$isSuccess = FALSE;
}else{
	echo "1";
}



?>