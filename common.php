<?php

$get_ip = $_SERVER['REMOTE_ADDR'];


//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}


//new DateTime 사용하려면 이거 필수 삽입
date_default_timezone_set('Asia/Seoul');


//절대 url 값 구하기 ex)http://samplusil.cafe24.com/
$url_abs = "http://".$_SERVER['HTTP_HOST']."/";

//현재 url 값 구하기 ex)http://samplusil.cafe24.com/bbs/
$url_temp = explode("/","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$url_count = count($url_temp);

$url = '';
for($i = 0; $i < $url_count -1 ; $i++)
{
	$url .= $url_temp[$i]."/";
}




$mobile = !!(FALSE !== strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'));



//모바일 판독 모바일 접속하기
$mobile_check2 = content($_GET['mobile']);


//게시판 종류
$board_type2 = content($_GET['board']);
$board_type = $board_type2;
if($board_type == '')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'free')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'fashion')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'selfie')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'food')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'photo')
{
	$board_type = "jamong_chat_freeboard";
}else{
	echo "잘못된 경로입니다";
	exit;
}


session_start(); // 세션을 시작헌다.

//토큰 만들기
$_SESSION['token'] = '';
$_SESSION['tokensave'] = '';
$_SESSION['fake'] = '';

$_SESSION['token']=date("YmdHis"); //시간 저장
$_SESSION['tokensave']=$_SESSION['token']; //토큰 시간 저장

$token_text = "d!s1afjl23ks2%3adjfq#fjlkjf@423oiw4$ehf";
for($i = 0 ; $i <= 30; $i++)
{
	$rand=mt_rand(0,strlen($token_text)-1);
	$_SESSION['fake'] .= substr($token_text, $rand, 1); 
}

//echo "token : ". $_SESSION['fake'] . "<br>";


//ip차단
$query_blockip="SELECT count(*) as cnt FROM blockip WHERE '$get_ip' LIKE ip"; // SQL 쿼리문
$result_blockip=mysql_query($query_blockip, $conn); 

if( !$result_blockip ) {
	echo "Failed to list query blockip";
	$isSuccess = FALSE;
}

while( $row = mysql_fetch_array($result_blockip) ) {
	if($row['cnt'] > 0)
	{
		exit;
	}
}




/////////////////////////////////////// 시간 지난 게시물은 삭제 시작 /////////////////////////////////////
$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);


//삭제할 대상 조회
$query="SELECT seq, finish_date FROM jamong_chat_freeboard WHERE finish_date <= '$get_date'"; // SQL 쿼리문
$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query common content_delete.php";
	$isSuccess = FALSE;
}

$boardList = array();

//시간 지난 게시글 seq검사
while( $row = mysql_fetch_array($result) ) {
	$board_delete['seq'] = $row['seq'];

	array_push($boardList, $board_delete);
}


//삭제 시작
foreach($boardList as $board_delete) {

	$get_seq = $board_delete['seq'];

	$query="SELECT * FROM jamong_chat_freeboard WHERE seq = '$get_seq'"; // SQL 쿼리문
	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
	echo "Failed to list query common content_delete2.php";
	$isSuccess = FALSE;
	}

	$boardList = array();

	while( $row = mysql_fetch_array($result) ) {
	$board_delete['seq'] = $row['seq'];
	$board_delete['user_seq'] = $row['user_seq'];
	$board_delete['photo'] = $row['photo'];
	}


	//삭제
	$temp_date = explode(" ",$board_delete['photo']);
	$photo_date = explode("_",$temp_date[0]); //$photo_date[1] 사진 파일명에서 날짜뽑아내기 ex)2015-04-15

	//폴더 찾아 파일 삭제 (썸네일 파일 포함)
	$directory = "upload/up_img/"; // 이건 님의 디렉토리
	//동일파일 삭제
	if(is_file($directory.$photo_date[1]."/".$board_delete['photo'])){
		//echo "파일삭제";
		unlink($directory.$photo_date[1]."/".$board_delete['photo']);
	}
	//동일파일 삭제 (썸네일)
	if(is_file($directory.$photo_date[1]."/thumbnail/".$board_delete['photo'])){
		//echo "파일삭제";
		unlink($directory.$photo_date[1]."/thumbnail/".$board_delete['photo']);
	}
	//동일파일 삭제 (썸네일) 400px
	if(is_file($directory.$photo_date[1]."/thumbnail/400_".$board_delete['photo'])){
		//echo "파일삭제";
		unlink($directory.$photo_date[1]."/thumbnail/400_".$board_delete['photo']);
	}

	//게시글 삭제쿼리
	$sql_delete = "DELETE FROM jamong_chat_freeboard WHERE seq = '$get_seq'";
	$res = mysql_query($sql_delete,$conn);

	if(!$res)
	{
		echo "게시글 삭제 에러";
	}

	//게시글 좋아요 삭제쿼리
	$sql_delete = "DELETE FROM jamong_chat_like WHERE like_status = 'content' AND like_seq = '$get_seq'";
	$res = mysql_query($sql_delete,$conn);

	if(!$res)
	{
		echo "좋아요 삭제 에러";
	}	


	//게시글에 달린 댓글 조회
	$sql_delete = "SELECT seq, board_seq FROM jamong_chat_comment WHERE board_seq = '$get_seq'";
	$res2 = mysql_query($sql_delete,$conn);

	if(!$res2)
	{
		echo "댓글 조회 에러";
	}

	$boardList2 = array();

	while( $row = mysql_fetch_array($res2) ) {
		$board_delete['seq'] = $row['seq'];
		$board_delete['board_seq'] = $row['board_seq'];


		array_push($boardList2, $board_delete);
	}

	foreach($boardList2 as $board_delete) {

		$temp_seq = $board_delete['seq'];
		

		//게시글에 달린 댓글의 사진 삭제 조회
		$query="SELECT * FROM jamong_chat_comment WHERE seq = '$temp_seq'"; // SQL 쿼리문
		$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result ) {
		echo "Failed to list query common comment_img_delete.php";
		$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
		$board_delete['seq'] = $row['seq'];
		$board_delete['user_seq'] = $row['user_seq'];
		$board_delete['photo'] = $row['photo'];
		}
	
		//삭제할 데이터가 있을때만 삭제
		if( $board_delete['photo'] != '')
		{
			//삭제
			$temp_date = explode(" ",$board_delete['photo']);
			$photo_date = explode("_",$temp_date[0]); //$photo_date[1] 사진 파일명에서 날짜뽑아내기 ex)2015-04-15

			//폴더 찾아 파일 삭제 (썸네일 파일 포함)
			$directory = "upload/up_img/"; // 이건 님의 디렉토리
			//동일파일 삭제
			if(is_file($directory.$photo_date[1]."/".$board_delete['photo'])){
				//echo "파일삭제";
				unlink($directory.$photo_date[1]."/".$board_delete['photo']);
			}
			//동일파일 삭제 (썸네일)
			if(is_file($directory.$photo_date[1]."/thumbnail/".$board_delete['photo'])){
				//echo "파일삭제";
				unlink($directory.$photo_date[1]."/thumbnail/".$board_delete['photo']);
			}
			//동일파일 삭제 (썸네일) 400px
			if(is_file($directory.$photo_date[1]."/thumbnail/400_".$board_delete['photo'])){
				//echo "파일삭제";
				unlink($directory.$photo_date[1]."/thumbnail/400_".$board_delete['photo']);
			}
		}



		//댓글 좋아요 삭제쿼리
		$sql_delete = "DELETE FROM jamong_chat_like WHERE like_status = 'comment' AND like_seq = '$temp_seq'";
		$res = mysql_query($sql_delete,$conn);
		
		if(!$res)
		{
			echo "댓글 좋아요 삭제 에러";
			
		}

	}

	//댓글 삭제쿼리
	$sql_delete = "DELETE FROM jamong_chat_comment WHERE board_seq = '$get_seq'";
	$res = mysql_query($sql_delete,$conn);

	if(!$res)
	{
		echo "댓글 삭제 에러";
	}

}
/////////////////////////////////////// 시간 지난 게시물은 삭제 끝 /////////////////////////////////////



?>