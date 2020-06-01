<?php

include "dbcon.php";

include "web_check.php";

include "common.php";



session_start(); // 세션을 시작헌다.

/*
개발자 :  이누리
2014~2015

게시판 증설시에 고쳐야 할곳 board_type
login.php
common.php
content_more_ajax.php
write_iframe/file_db.php
jquery_common.php -> navigate()

mobile/top.php
mobile/index.php
mobile/common.php
mobile/write_iframe/file_db.php
mobile/jquery_common.php -> navigate()
mobile/write_iframe/write_iframe.php


-- 아직 만들어야할것 --
//스크롤 내릴때 자동 more
//크게보기 문구 바꾸기
//프로필 사진 바꾸기
//페이지 상세보기
//비밀번호 저장

//라이트박스 남은시간 만들기

댓글 사진 첨부 기능

//썸네일 저장 기능
//게시물 삭제기능
//좋아요 자몽로고 일러스트로
게시물 수정
//프로필 크게보기
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



/*남은용량

echo "<br><br><br><br><br><br><br><br>";
$dir = '/var/www';
$free = disk_free_space("/var/www");
$total = disk_total_space("/var/www");
$free_to_mbs = round( $free / ((1024*1024)*1024), 1);
$total_to_mbs = round( $total / ((1024*1024)*1024), 1);
echo 'You have '.$free_to_mbs.' GBs from '.$total_to_mbs.' total GBs';
*/
//메모리사용 무한
ini_set('memory_limit', -1);



//$test = explode("#",$_SERVER["HTTP_REFERER"]);

//$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; 


//////////////////////////////////// 업로드 빈 폴더 삭제하기 시작 ///////////////////////////////////
$stamp = mktime();
$get_date2 = date('Y-m-d', $stamp);

//폴더속 빈것 찾기
$directory = "upload/up_img/"; // 이건 님의 디렉토리

$dir_array = array();

//날짜별 폴더 검색
$data_list = opendir($directory); // 폴더내에 존재하는 파일 리스트 목록을 받아옴

$j=0;
$i = 1;
while(false !== ($file = readdir($data_list))){
	if($file != "." && $file != ".."){		
		if($get_date2 != $file ) //오늘날짜를 제외한것만 검색
		{
			//echo "<br>$i - $file";
			$i++;
			$dir_array[$j] = $file;
			$j++;
		}
	}
}

//opendir로 열어놓은 메모리는 closedir로 닫아줘야 한다.
closedir($data_list);



//날짜별 검색된 하위폴더 검색하기 ex) 2015-05-29 ...
for($i = 0 ; $i < count($dir_array); $i++)
{
	$dir_name = $directory . $dir_array[$i];

	//echo "<br>" . $dir_name.  "<br>" .del_dir($dir_name);
	
	//해당 디렉토리에 thumbnail 폴더만 있을경우 1 반환, 여러파일들이 있으면 0 반환
	if(del_dir($dir_name) == 1)
	{
		//echo "1";
		rmdir($dir_name . "/thumbnail"); //썸네일폴더 먼저 삭제
		rmdir($dir_name); //날짜폴더 삭제
	}else if(del_dir($dir_name) == ''){ //썸네일폴더도 없고 아무 파일도 없으면 폴더 삭제
		//echo "0";
		rmdir($dir_name); //날짜폴더 삭제
	}else{
		//echo "0";
	}

}

//해당 디렉토리에 thumbnail 폴더만 있을경우 1 반환, 여러파일들이 있으면 0 반환
function del_dir($name){
 
	 if(is_dir($name)){ //폴더가 존재 하는지 안하는지 여부
	  
	  $data_list = opendir($name); // 폴더내에 존재하는 파일 리스트 목록을 받아옴

		while(false !== ($file = readdir($data_list))){
			if($file != "." && $file != ".."){
				//echo "<br>$file"; //검색된 폴더 및 파일들		

				if($file == "thumbnail") //thumbnail 폴더 의외에 다른파일이 있는지 감지
				{
					$dir_file_check = 1;
				}else{
					$dir_file_check = 0;
					break;
				}
			}
		}

		//opendir로 열어놓은 메모리는 closedir로 닫아줘야 한다.
		closedir($data_list);
		return $dir_file_check;
	 }else{
		$dir_file_check = 0;
		return $dir_file_check;
	 }

}


//////////////////////////////////// 업로드 빈 폴더 삭제하기 끝 ///////////////////////////////////


//echo "<br><br><br><br><br><br><br><br><br><br>".$mobile;

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);


if($_GET['logout'] ==1)
{
	$logout = 1;
}



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


?>

<!DOCTYPE html>
<html lang="ko">
	<head>
	<title>자몽챗</title>
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

<img class="loading_img" src="images/loading_img/loading_img.GIF" style="position:absolute; left:0px;right:0px; margin:auto; display:none; z-index:999999999999999;" width="60px">

			<div class="center">
				
				

					<div class="content_div_body">

<?
	include "login.php";
?>	


										
											

<div class="home_info">
	<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">

	<div class="home_div">
		<div class="home_text content_load">
<?
if($version<=9)
{
?>
			<img src="images/icon_512_ico.png" class="home_main_logo" width="140px">
<?
}else{
?>
			<div class="loading_logo">
				<div class="loading_logo_top">
				</div>
				<div class="loading_logo_bottom">
				</div>
			</div> <!-- logo -->
<?
}
?>	
			<br>
			<span class="home_info_label">환영합니다</span><br><br><br><br>
			<span class="home_ex">- 자몽챗 -</span><br><br><br>
			<span class="home_ex2">· 모든 게시글은 24시간 뒤에 자동 폭파됩니다.<br><br>· 좋아요 1개당 1시간 연장됩니다.<br><br>· 삭제하거나 폭파된 게시물의 글과 사진은 즉각 폐기됩니다.<br><br>· 자몽챗은 여러분의 개인 정보를 안전하게 관리합니다.</span>
		</div>
	</div>
</div><!-- home_info -->

<?
	//include "content.php";
?>						
						
<?
	//include "hot_issue.php";
?>						

					</div><!-- content_div_body -->


			</div><!-- center -->

<?
	include "footer.php";
?>
			

		</div><!-- all-wrap -->
	</body>





</html>
