<?php

include "dbcon.php";
include "domain_security.php";


session_start(); // 세션을 시작헌다.


$ajax_check = 0;

$get_seq = $_POST['seq'];
$get_user_seq = $_POST['user_seq'];
$get_user_eamil = $_POST['user_eamil'];

$get_seq = explode(" ", $_POST['seq']);
$get_seq = $get_seq[0];

//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}

$get_seq = content($get_seq);//특수문자 제거


$query="SELECT * FROM jamong_chat_comment WHERE seq = '$get_seq'"; // SQL 쿼리문
$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query content_delete_ajax.php";
	$isSuccess = FALSE;
}

$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row['seq'];
	$board['user_seq'] = $row['user_seq'];
	$board['photo'] = $row['photo'];
}

if($board['user_seq'] == $_SESSION['user_seq'] || $_SESSION['user_email'] == "admin") //세션과 지우려는 해당게시글 맞는지 확인
{
	if($board['photo'] != '')
	{
		//사진삭제
		$temp_date = explode(" ",$board['photo']);
		$photo_date = explode("_",$temp_date[0]); //$photo_date[1] 사진 파일명에서 날짜뽑아내기 ex)2015-04-15

		//폴더 찾아 파일 삭제 (썸네일 파일 포함)
		$directory = "upload/up_img/"; // 이건 님의 디렉토리
		//동일파일 삭제
		if(is_file($directory.$photo_date[1]."/".$board['photo'])){
			//echo "파일삭제";
			unlink($directory.$photo_date[1]."/".$board['photo']);
		}
		//동일파일 삭제 (썸네일)
		if(is_file($directory.$photo_date[1]."/thumbnail/".$board['photo'])){
			//echo "파일삭제";
			unlink($directory.$photo_date[1]."/thumbnail/".$board['photo']);
		}

		//동일파일 삭제 (썸네일) 400px
		if(is_file($directory.$photo_date[1]."/thumbnail/400_".$board['photo'])){
			//echo "파일삭제";
			unlink($directory.$photo_date[1]."/thumbnail/400_".$board['photo']);
		}
	
	}


	//댓글 좋아요 삭제쿼리
	$sql_delete = "DELETE FROM jamong_chat_like WHERE like_status = 'comment' AND like_seq = '$get_seq'";
	$res = mysql_query($sql_delete,$conn);
	
	if(!$res)
	{
		echo "좋아요 삭제 에러";
	}	


	//댓글 삭제쿼리
	$sql_delete = "DELETE FROM jamong_chat_comment WHERE seq = '$get_seq'";
	$res = mysql_query($sql_delete,$conn);
	
	if(!$res)
	{
		echo "댓글 삭제 에러";
	}

	
	

		//최종 끝
	if($res)
	{
		$ajax_check = 3;
	}



}else{

	//에러
	echo  "에러";

}


if($res) {
	echo ($ajax_check);
} else {
	echo "처리하지 못했습니다.";
}
?>