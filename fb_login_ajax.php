<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);

$get_id = content($_POST['id']);
$get_name = content($_POST['name']);
$get_fb = $_POST['fb'];
$get_profile_way = "http://graph.facebook.com/".$get_id."/picture";


//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}

if( $get_id == "admin" ) //관리자 계정 생성 금지
{
	exit();
}

//회원가입? 체크
$sign_up_check = 0;
$ajax_check = 0;

//email 중복체크
$query="SELECT * FROM jamong_chat_user WHERE email = '$get_id'"; // SQL 쿼리문
$result=mysql_query($query, $conn) ; // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query email_check_ajax.php";
	$isSuccess = FALSE;
}

$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['name'] = $row['name'];

	array_push($boardList, $board);
}

//계정이 존재
if(isset($board))
{
	$sign_up_check = 1;
}else{
	$sign_up_check = 0;
}


//회원가입 필요
if($sign_up_check == 0)
{
		$sqlInsert = "INSERT INTO jamong_chat_user(email, name, profile_way, ip, date) VALUES ('$get_id','$get_name', '$get_profile_way', '$get_ip','$get_date')";
		$res = mysql_query($sqlInsert,$conn);

		if( !$res ) {
		echo "Failed to list fb_login_ajax_sign_up.php";
		$isSuccess = FALSE;
		}else{
			$ajax_check = login($conn,$get_id,$get_fb);
		}

}

//회원가입 불필요
if($sign_up_check == 1)
{
	$ajax_check = login($conn,$get_id,$get_fb);
}


//로그인
function login($conn2,$id,$fb)
{
	$sqlInsert = "SELECT * FROM jamong_chat_user WHERE email = '$id'";
	$res = mysql_query($sqlInsert,$conn2);


	if( !$res ) {
	echo "Failed to list fb_login_ajax_login.php";
	$isSuccess = FALSE;
	}else{
		$ajax_check2=1;
	}
	
	
	while( $row = mysql_fetch_array($res) ) {
		$board['seq'] = $row['seq'];
		$board['name'] = $row['name'];
		$board['email'] = $row['email'];
		$board['profile'] = $row['profile'];

		session_start(); // 세션을 시작헌다.

		$_SESSION['user_seq'] = '';
		$_SESSION['user_name'] = '';
		$_SESSION['user_email'] = '';
		$_SESSION['user_profile'] = '';
		$_SESSION['fb'] = '';

		$_SESSION['user_seq'] = $board['seq'];
		$_SESSION['user_name'] = $board['name'];
		$_SESSION['user_email'] = $board['email'];
		$_SESSION['user_profile'] = $board['profile'];
		$_SESSION['fb'] = $fb;
	
	}

	return $ajax_check2;
}


if($ajax_check) {
	echo ($ajax_check);
} else {
	echo "처리하지 못했습니다.";
}

mysql_close();
?>