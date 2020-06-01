<?php
session_start(); // 세션을 시작헌다.

include "dbcon.php";

include "domain_security.php";


$id = $_POST['login_id'];

$password = $_POST['password_hidden'];


if( $password == "" ) //아무내용 없으면 로그인 실패
{
	exit();
}

$login_check= 0;


		$query="SELECT * FROM jamong_chat_user WHERE email='$id'"; // SQL 쿼리문
		$result=mysql_query($query, $conn) ; // 쿼리문을 실행 결과

		if( !$result ) {
			echo "Failed to list query login_form.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['name'] = $row['name'];
			$board['email'] = $row['email'];
			$board['password'] = $row['password'];
			$board['profile'] = $row['profile'];

			//프로필이 없을경우
			if($board['profile'] == '')
			{
				$board['profile'] = "btn_youtb.png";
			}

			array_push($boardList, $board);
		}

		foreach($boardList as $board) {
			if($id == $board['email'])
			{
				if($password == $board['password'])
				{
					$login_check = 1;

					$_SESSION['user_seq'] = '';
					$_SESSION['user_name'] = '';
					$_SESSION['user_email'] = '';
					$_SESSION['user_profile'] = '';

					$_SESSION['user_seq'] = $board['seq'];
					$_SESSION['user_name'] = $board['name'];
					$_SESSION['user_email'] = $id;	
					$_SESSION['user_profile'] = $board['profile'];


				}else{
					$login_check = 2;
				}				
			}
		}

if(isset($_POST['login_id']))
{
}else{
	$login_check= 10;
}

if( $login_check == 1) {

//성공
echo $login_check;

}else if($login_check == 2){

//비밀번호 틀림
echo $login_check;

}else if($login_check == 0){

//계정 없음
echo $login_check;

}else{
?>
<script>
alert("잘못된 접근입니다.");
history.go(-1);
</script>
<?
}
?>