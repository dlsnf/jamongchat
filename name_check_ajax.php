<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];
$get_date = date("Y-m-d H:i:s");

$get_name = $_POST['name'];
$login_check = 0;
/*
session_start(); // 세션을 시작헌다.


//토큰
$second="600"; //시간초 지정 60 = 1분 저는 넉넉히 10분으로 하겠습니다. 
$time=date("YmdHis") -$_SESSION['tokensave'];
if(!$_SESSION['token'] or !$_SESSION['tokensave'] or !$_SESSION['fake'] or !$_POST['l_token']){echo "토큰이 유효하지 않습니다. 페이지를 새로고침 해주세요."; exit;}

//if($time<$second && $_SESSION['fake'] == $_POST['l_token']){ } //시간제한토큰

if($_SESSION['fake'] == $_POST['l_token']){
//echo $_POST['l_token'];
//echo "성공";

}else{echo "토큰이 유효하지 않습니다.  페이지를 새로고침 해주세요.2"; exit;}
*/

		$query="SELECT * FROM jamong_chat_user WHERE name='$get_name'"; // SQL 쿼리문
		$result=mysql_query($query, $conn) ; // 쿼리문을 실행 결과

		if( !$result ) {
			echo "Failed to list query name_check_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['name'] = $row['name'];
			$board['email'] = $row['email'];
			$board['password'] = $row['password'];

			array_push($boardList, $board);
		}

		foreach($boardList as $board) {
			if($get_name == $board['name'])
			{
				$login_check = 1;
			}
		}

if($result) {
	echo ($login_check);
} else {
	echo "처리하지 못했습니다.";
}
mysql_close();
?>