<?php

include "../../dbcon.php";

include "../../domain_security.php";

include "../common.php";

session_start(); // 세션을 시작헌다.



//메모리사용 무한
ini_set('memory_limit', -1); 

//모바일 판독
$mobile = !!(FALSE !== strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'));

$get_seq = $_GET['seq'];
//$get_pa_height = $_GET['pa_height'];
$get_toppx = $_GET['toppx'];
$get_status = $_GET['status'];

$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";


		$query="SELECT loo.*, uoo.seq uoo_seq, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile, uoo.name uoo_name, uoo.rating uoo_rating  FROM jamong_chat_like loo LEFT JOIN jamong_chat_user uoo ON loo.like_user_seq = uoo.seq WHERE loo.like_seq = '$get_seq' AND loo.like_status = '$get_status' group by uoo.seq ORDER BY loo.date ASC"; // SQL 쿼리문
		$result=mysql_query($query, $conn) or die (mysql_error()); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


		if( !$result ) {
			echo "Failed to list query light_box_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['like_status'] = $row['like_status'];
			$board['like_seq'] = $row['like_seq'];
			$board['rating'] = $row['uoo_rating'];

			$board['like_user_seq'] = $row['like_user_seq'];
			$board['like_count'] = $row['like_count'];
			$board['date'] = $row['date'];
			$board['ip'] = $row['ip'];
			$board['uoo_seq'] = $row['uoo_seq'];
			$board['uoo_email'] = $row['uoo_email'];
			$board['uoo_profile_way'] = $row['uoo_profile_way'];
			$board['uoo_profile'] = $row['uoo_profile'];
			$board['uoo_name'] = $row['uoo_name'];

			//프로필이 없을경우
			if($board['uoo_profile_way'] == '')
			{
				$board['uoo_profile_way'] = $profile_domain_name."btn_youtb.png";
			}

			array_push($boardList, $board);
		}

		//echo "<br><br><br>";
		//echo $board['seq'];
		//echo "<br><br><br>";
		//echo $board['name'];
		//echo "<br><br><br>";
		//echo $board['email'];


	
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
<title>이 게시글을 좋아한 사람</title>
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

	

<script>
var iframe_check = "<?=$get_iframe?>";	
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

	
	$(window).bind('orientationchange', function() {

	});



		$(window).ready(function(){
	
		});

	
</script>
<script src="js/jquery.form.js"></script> 
	</head>

	<body>


	
			

<div class="all-wrap">
				
				<!-- button -->
				<img class="light_box_btn_close" src='../../images/btn_close_gray.png' width='20px' title="끄기" onclick="history.go(-1);">

				<div class="light_box_group" style="width: 100%; height: 100%;">

					<div class="light_box_title">
					<?
					if ($get_status == "content")
					{
					?>
						이 게시글을 좋아한 사람
					<?
					}else{
					?>
						이 댓글을 좋아한 사람
					<?
					}
					?>
					</div>

					<div class="line"></div>

					<div class="light_box_body">
<? 
	foreach($boardList as $board) {	
?>
						<div class="like_people">
							<img class="profile_img" src="<?=$board['uoo_profile_way']?>" style="background:white;" width="34px" height="34px">
							<div class="name user_name mouse_over_underline" ><?=$board['uoo_name']?></div><br>
							<div class="level"><?=$board['rating']?></div>
						</div>
<? 
	}
?>
					</div><!-- light_box_body -->

				
					
			</div>

</div>		
			

	</body>

</html>
