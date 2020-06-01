<?php
include "../dbcon.php";

include "../web_check.php";

include "common.php";

session_start(); // 세션을 시작헌다.


/*
개발자 :  이누리
2014~2015

-- 아직 만들어야할것 --
//스크롤 내릴때 자동 more
//크게보기 문구 바꾸기
//프로필 사진 바꾸기
//페이지 상세보기
//비밀번호 저장

//라이트박스 남은시간 만들기

//썸네일 저장 기능
//게시물 삭제기능
//좋아요 자몽로고 일러스트로
게시물 수정
프로필 크게보기
//자동 로그인
//핫이슈
//검색기능
//게시물 카운트다운
//토탈 및 투데이 방문자수
공지사항 게시판
피드백 보내기

모바일페이지

-- 아직 만들어야할것 --

*/

/*
 $chExt_Name = "ffmpeg";
 // load extension
 if (!extension_loaded($chExt_Name)) {
 //echo "<script>alert('Can not load extension ffmpeg_php.');</script>";
  //exit;
 }

*/
//메모리사용 무한
ini_set('memory_limit', -1);



//$test = explode("#",$_SERVER["HTTP_REFERER"]);

//$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; 


$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);




if($_GET['logout'] ==1)
{
	$logout = 1;
}

//모바일 판독
$mobile = !!(FALSE !== strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'));


if(isset($_SESSION['user_email']))
{
	if($_SESSION['user_email'] == 'admin')
	{
		$people = 'admin';
		
	}else{
		$people = 'member';
	}	

	$user_seq=$_SESSION['user_seq'];
	$user_name=$_SESSION['user_name'];
	$user_email=$_SESSION['user_email'];

	//프로필 사진 새로고침
	//게시글 쿼리
	$query33="SELECT seq, profile_way, profile FROM jamong_chat_user WHERE email='$user_email'";
	$result33=mysql_query($query33, $conn); // 쿼리문을 실행 결과

	if( !$result33 ) {
		echo "Failed to profile_index.php";
		$isSuccess = FALSE;
	}

	while( $row = mysql_fetch_array($result33) ) {
		$user_profile_way = $row['profile_way'];		
	}

}else{
	$people = 'guest';
}

$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";

//프로필이 없을경우
if($user_profile_way == '')
{
	$user_profile_way = $profile_domain_name."btn_youtb.png";
}


		//게시글 쿼리
		$query="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM $board_type boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE boo.board_type = '$board_type2' ORDER BY boo.seq DESC limit 0,3"; // SQL 쿼리문
		$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query index";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result2) ) {
			$board['seq'] = $row['seq'];
			$board['user_seq'] = $row['user_seq'];
			$board['name'] = strip_tags($row['uoo_name']);
			$board['profile_way'] = $row['uoo_profile_way'];
			$board['profile'] = $row['uoo_profile'];
			$board['body'] = nl2br(strip_tags($row['body']));
			$board['like_c'] = $row['like_c'];
			$board['photo_way'] = $row['photo_way'];
			$board['photo'] = $row['photo'];
			$board['date'] = $row['date'];
			$board['finish_date'] = $row['finish_date'];
			$board['ip'] = $row['ip'];

			//프로필이 없을경우
			if($board['profile_way'] == '')
			{
				$board['profile_way'] = $profile_domain_name."btn_youtb.png";
			}

			//시간
			$date_time = explode(" ",$board['date']);
			$date = $date_time[0];
			$time = $date_time[1];

			$date_array =  explode("-",$date);
			$board['year'] = intval($date_array[0]);
			$board['month'] = intval($date_array[1]);
			$board['day'] = intval($date_array[2]);

			$time_array =  explode(":",$time);
			$board['hour'] = intval($time_array[0]);
			$board['minute'] = intval($time_array[1]);
			$board['second'] = intval($time_array[2]);

			if( $board['hour'] > 12)
			{
				$board['ampm'] = "오후";
				$board['hour'] = $board['hour'] - 12;
			}else{
				$board['ampm'] = "오전";
			}

			if( $board['minute'] < 10)
			{
				$board['minute'] = "0".$board['minute'];
			}
	
			$date1 = new DateTime($board['date']);
			$date2 = new DateTime($get_date);

			$date3 = date_diff($date1, $date2);
			
			$diff = $date3->days * 1440 + $date3->h * 60 + $date3->i;

			if($diff == 0)
			{
				$board['diff_check'] = 'now';
			}else if( floor($diff/60) == 0 )
			{
				$board['diff'] = $diff;
				$board['diff_check'] = 'minute';
			}else if (floor($diff/60) < 11)
			{
				$board['diff'] = floor($diff/60);
				$board['diff_check'] = 'hour';
			}else{
				$board['diff_check'] = 'none';
			}


			//자동 폭파시간
			$today_date = new DateTime($get_date);
			$finish_date = new DateTime($board['finish_date']);

			//오늘시간과 폭파시간 차이 구하기
			$finish_date_diff = date_diff($today_date, $finish_date);

			$finish_date_diff_minute = $finish_date_diff->days * 1440 + $finish_date_diff->h * 60 + $finish_date_diff->i;

			$finish_date_diff_second = $finish_date_diff->days * 86400 + $finish_date_diff->h * 3600 + $finish_date_diff->i * 60 + $finish_date_diff->s;
			
			//시간 차이
			$board['finish_date_diff_time_minute'] = $finish_date_diff_minute;
			$board['finish_date_diff_time_second'] = $finish_date_diff_second;

			$board['finish_date_diff_day'] = floor($finish_date_diff_second/86400);
			if($board['finish_date_diff_day'] == 0)
			{
				$board['finish_date_diff_day'] = '';
			}
			$board['finish_date_diff_hour'] = floor($finish_date_diff_second%86400/3600);
			$board['finish_date_diff_minute'] = floor($finish_date_diff_second%86400%3600/60);
			$board['finish_date_diff_second'] = floor($finish_date_diff_second%86400%3600%60);


			//줄수
			$board['body_dot']='';
			$board['line_cnt'] =0; # 총 라인수
			$set_str = 76; # 한라인에 들어가는 바이트수
			$tmp = explode("\n", $board['body']); # 3줄

			$tmp_int = count($tmp);
			for( $i =0; $i < $tmp_int; ++$i ){
			$board['line_cnt'] += ceil( strlen($tmp[$i]) / $set_str ); # 한줄을 한줄에 들어갈수 있는 바이트로 나누면 되겠어요 ^^
			}
			mb_internal_encoding ( 'UTF-8' );
			
			if ($board['line_cnt'] > 5)
			{
				if($tmp > 3) //엔터로 친것이 3줄 이상일 경우
				{
					//$board['body'] = mb_strcut ( $board['body'] , 0 ,20,'utf-8'); //100바이트까지 표시
					$board['body'] = mb_substr( $board['body'] , 0 ,100,'utf-8'); //170자까지 표시
					$board['body_dot'] = "...";
				}else{
					//$board['body'] = mb_strcut ( $board['body'] , 0 ,76,'utf-8'); //100바이트까지 표시
					$board['body'] = mb_substr( $board['body'] , 0 ,200,'utf-8'); //170자까지 표시
					$board['body_dot'] = "...";
				}
			}
						
			$temp_seq = $board['seq'];

			

			array_push($boardList, $board);
		}



mysql_close();

?>

<!DOCTYPE html>
<html lang="ko">
	<head>
	<title>자몽챗 - 모바일</title>
<?
	include "head.php";
?>

<?
	include "jquery_common.php";
?>

<?
	include "jquery.php";
?>



<script type="text/javascript">
  document.createElement('video');document.createElement('audio');document.createElement('track');
</script>
	</head>

	<body>
<!-- 토큰 -->
<form>
	<input type='hidden' class="l_token" name='l_token' value='<?=$_SESSION['fake']?>'>
</form>
<!--
	<div class="test" style="z-index:99999;position:absolute; width:200px; height:200px; background:red;">
	</div>

<div class="tooltip" style="margin:60px;" title="fdgdsfsdfsdsdfdfsdfg"> 
    This div has a tooltip when you hover over it!
</div>

-->

		
		<div class="all-wrap">

			<?
				include "top.php";
			?>
<!--
			<video id="MY_VIDEO_1" class="video-js vjs-default-skin" controls	preload="auto" width="640" height="264" poster="write_iframe/upload/up_video/up_sumnail/Untitled.jpg"
			data-setup="{}">
				 <source src="dlsn0.mp4" type='video/mp4' />
				<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			</video>
-->

<img class="loading_img" src="../images/loading_img/loading_img.GIF" style="position:absolute; left:0px;right:0px; margin:auto; display:none; z-index:999999999999999;" width="60px">

			<div class="center">
				
				

					<div class="content_div_body">


<?
	include "content.php";
?>						
						
<?
	include "hot_issue.php";
?>						

					</div><!-- content_div_body -->


			</div><!-- center -->

<?
	include "footer.php";
?>
			

		</div><!-- all-wrap -->
	</body>





</html>
