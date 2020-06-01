<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);


$get_seq = $_POST['seq'];
$get_user_seq = $_POST['user_seq'];
$get_status = $_POST['status'];


		$ajax_check=0;
		
		$like_check=0;

		$sqlInsert = "SELECT count(distinct(like_user_seq)) as count FROM jamong_chat_like WHERE like_status = '$get_status' AND like_seq = '$get_seq' AND like_user_seq = '$get_user_seq'";
		$res = mysql_query($sqlInsert,$conn);

		if( !$res ) {
		echo "Failed to list like_status_ajax.php";
		$isSuccess = FALSE;
		}else{
			$ajax_check=1;
		}

		while( $row = mysql_fetch_array($res) ) {
			$like_check = $row['count'];
		}


if($res) {
	echo ($like_check);
} else {
	echo "처리하지 못했습니다.";
}
mysql_close();
?>