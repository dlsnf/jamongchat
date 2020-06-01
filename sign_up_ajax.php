<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);

$get_name = content(addslashes($_POST['name']));
$get_email = $_POST['email'];
$get_password = $_POST['password'];

$login_check = 0;

$sign_check=0;

//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}
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
if( $get_email == "admin" ) //관리자 계정 생성 금지
{
	exit;
}

//email 중복체크
$query="SELECT * FROM jamong_chat_user WHERE email='$get_email'"; // SQL 쿼리문
$result=mysql_query($query, $conn) ; // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query email_check_ajax.php";
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
	if($get_email == $board['email'])
	{
		$login_check = 1;
	}
}


//별명 중복체크
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

//별명 중복이 없을때만 등록
if($login_check != 1)
{
		$sqlInsert = "INSERT INTO jamong_chat_user(email, name, password, ip, date) VALUES ('$get_email','$get_name','$get_password','$get_ip','$get_date')";
		$res = mysql_query($sqlInsert,$conn);

		if( !$res ) {
		echo "Failed to list sign_up_ajax.php";
		$isSuccess = FALSE;
		}else{
			$sign_check=1;
		}
}

if($res) {
	echo ($sign_check);
} else {
	echo "처리하지 못했습니다.";
}
mysql_close();
?>