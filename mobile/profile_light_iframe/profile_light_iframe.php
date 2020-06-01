<?php

include "../../dbcon.php";
include "../../domain_security.php";
include "../common.php";

session_start(); // 세션을 시작헌다.

//메모리사용 무한
ini_set('memory_limit', -1); 

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

}else{
	$people = 'guest';
}


$get_seq = $_GET['seq'];
//$get_pa_height = $_GET['pa_height'];
$get_toppx = $_GET['toppx'];



$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";
$profile_domain_name2 = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/";


		$query="SELECT *, DATE_FORMAT(date, '%Y-%m-%d') as date2 FROM jamong_chat_user WHERE seq = '$get_seq'"; // SQL 쿼리문
		$result=mysql_query($query, $conn) or die (mysql_error()); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


		if( !$result ) {
			echo "Failed to list query profile_light_box.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['name'] = $row['name'];
			$board['profile_way'] = $row['profile_way'];
			$board['profile'] = $row['profile'];
			$board['date'] = $row['date2'];
			$board['point'] = $row['point'];
			$board['rating'] = $row['rating'];

			//프로필경로가 없을경우
			if($board['profile_way'] == '')
			{
				$board['profile_way'] = $profile_domain_name."btn_youtb.png";
			}

			//프로필이 없을경우
			if($board['profile'] == '')
			{
				$board['profile'] = "btn_youtb.png";
			}


			$get_profile_way = explode("/",$board['profile_way']);

			if( $get_profile_way[2] == 'graph.facebook.com') {
				//페이스북 프로필 이미지
				$board['profile_way_original'] = $board['profile_way'];
				$board['profile_way'] = $board['profile_way'];
			}else{
				//자몽챗 프로필
				$board['profile_way_original'] = $profile_domain_name2.$board['profile'];
				$board['profile_way'] = $profile_domain_name."800_".$board['profile'];
			}

			array_push($boardList, $board);
		}

	
?>


<!DOCTYPE html >
<html lang="ko">
	<head>
<?
	//include "../head.php";
?>

<?
	include "../analytics.php";
?>
<title>프로필 보기</title>
<script src="js/jquery-1.11.0.min.js"></script>
	<!-- light_box_jamongsoft -->
	<link rel="stylesheet" href="css/style.css">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">


	<!-- icon -->
	<link rel="icon" href="../../images/icon_512_ico.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../../images/icon_512_ico.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../../images/icon_512_ico.ico" type="image/vnd.microsoft.icon"><!--The 2010 IANA standard but not supported in IE-->
	<link rel="apple-touch-icon" href="../../images/icon_512.png">
	<link rel="apple-touch-icon-precomposed" href="../../images/icon_512.png"><!--prevents rendering-->

<?
	//include "../jquery_common.php";
?>

<script>
		var login_check="<?=$_SESSION['user_email']?>";
	
		$(window).resize(center);
		$(window).scroll(center);


		function center()
		{
		}

		


		$(window).load(function(){
				start_load();
		});

		function start_load()
		{
			setTimeout(loading,1200);
		}

		//로딩
		function loading()
		{
			$(".pace-progress").fadeOut(500);
			$(".pace-activity").fadeOut(500, function(){
				$(".pace").hide();
			});

			//$(".content_load").animate({'opacity':'1.0'},500);;
			//$(".bg").fadeOut(500);
		}

</script>

	</head>

	<body>


<? 
	foreach($boardList as $board) {	
?>
			

			
			

<div class="all-wrap">
			
			<!-- button -->
				<img class="light_box_btn_close" src='../../images/btn_close_gray.png' width='20px' title="끄기" onclick="history.go(-1);">

				<div class="light_box_group">
					

					<div class="text_div">

						<div class="profile_view_div">

							<h1 class="profile_view">프로필 보기</h1>

							<div class="line"></div>

							<table class="profile_detail">
								<tr>
									<td class="profile_name">닉네임</td>
									<td>: <?=$board['name']?></td>
								</tr>
								<tr>
									<td class="profile_date">회원가입</td>
									<td>: <?=$board['date']?></td>
								</tr>
								<tr>
									<td class="profile_point"><img src="images/logo_normal.svg" class="like_logo" height="14px" title="자몽"></td>
									<td>: <?=$board['point']?>개</td>
								</tr>
								<tr>
									<td class="profile_rating">등급</td>
									<td>: <?=$board['rating']?></td>
								</tr>
							</table>
						
						</div>

					</div>

						<!-- images -->
						<div class="img_div">
							<a href="<?=$board['profile_way_original']?>" target="_blank" title="원본 보기">		
								<img class='light_box_img' src='<?=$board['profile_way']?>'>								
							</a>
						</div>


			</div>

</div>
					
<? 
	}
?>
					
			

	</body>

</html>
