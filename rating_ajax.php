<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);

session_start(); // 세션을 시작헌다.


if(isset($_SESSION['user_seq']))
{
}else{ //세션이 없을때는 에러처리
	echo "잘못된 접근입니다.";
	exit;
}





?>