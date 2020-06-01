		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Oxygen:700,400' rel='stylesheet' type='text/css'>
		

		<!-- 641px ~  -->
		<link rel="stylesheet" href="css/style.css">


		

		<!-- loadding bar / 최상단으로 / http://github.hubspot.com/pace/docs/welcome/ 
		<link rel="stylesheet" href="css/dataurl.css" type="text/css" charset="utf-8"/>
		<script src="js/pace.min.js"></script>-->
		
		
		<!-- <meta name="viewport" content="initial-scale=0.4, maximum-scale=2.0, user-scalable=yes">-->
		<meta name="format-detection" content="telephone=no" />
		<!-- <meta name="viewport" content="width=1280"> -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 

		<!-- icon -->
		<link rel="icon" href="../images/icon_512_ico.ico" type="image/x-icon">
		<link rel="shortcut icon" href="../images/icon_512_ico.ico" type="image/x-icon">
		<link rel="shortcut icon" href="../images/icon_512_ico.ico" type="image/vnd.microsoft.icon"><!--The 2010 IANA standard but not supported in IE-->
		<link rel="apple-touch-icon" href="../images/icon_512.png">
		<link rel="apple-touch-icon-precomposed" href="../images/icon_512.png"><!--prevents rendering-->
		
		 

		<!-- 필수 제이쿼리 -->
		<script src="js/jquery-1.11.0.min.js"></script>
		<script src="js/jquery-ui.js"></script>
		<link rel="stylesheet" href="css/jquery-ui.css">
		<script src="js/url.min.js"></script>

		<!-- ajaxForm -->
		<script src="js/jquery.form.js"></script> 

		<!-- 특정부분 스크롤 -->
		<script src="js/iscroll.js"></script> 

		

		<!-- alert -->
		<script src="js/jquery.noty.packaged.js"></script>
		
		<!-- swipe -->
		<script src="js/swipe.js"></script>
		<script src="js/swipe_auto.js"></script>
		<script src="js/ready.js"></script>	
		<script src="js/jquery.dotdotdot.js"></script>

		
		<!--custom scrollbar css-->
		<link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
		<!-- custom scrollbar plugin -->
		<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>

		<!--<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script> -->
		<!--<script src="js/jquery.colorbox.js"></script>-->

		<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />    
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

		<!-- 캐시저장 방지
		<META http-equiv="Expires" content="-1"> 
		<META http-equiv="Pragma" content="no-cache"> 
		<META http-equiv="Cache-Control" content="No-Cache"> 
		 -->


		<!-- 이미지 흑백 -->
		<script src="js/jquery.gray.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/gray.css" />

		<!-- video js 
		<script src="js/video.js"></script>
		<link rel="stylesheet" type="text/css" href="css/video-js.css" />   
		-->


		<!-- smooth_scroll plugin 
		<script src="js/smooth_scroll/TweenMax.min.js"></script>
		<script src="js/smooth_scroll/ScrollToPlugin.min.js"></script>
		-->




<script>
		
//메뉴관련 함수들
function menu_on()
{

	//$('body').bind('touchmove', function(e){e.preventDefault()});

	//$(".menu").css({'top':'0px'});
	$(".menu_black").css({'top':'0px', 'background-color':'rgba(0,0,0,0.7)'});
	$(".menu_body").addClass("menu_on");

}

function menu_off()
{
	//$('body').unbind('touchmove');

	$(".menu_body").removeClass("menu_on");

		$(".menu").css({'top':'-100%'});
		$(".menu_black").css({'top':'-100%', 'background-color':'rgba(0,0,0,0.0)'});
}

		</script>
<body onload="">


					<div class="menu">
						
						<div class="menu_black" onclick="menu_off();">	
							<div class="menu_black_block"></div>
						</div>

						<div class="menu_body">	

							<div class="menu_title">MENU</div>

							<div class="menu_board" >

								<div class="board_menu">
									<div class="board_menu_info">< 게시판 ></div>
									<a href="board.php?board=free&mobile=<?=$mobile_check2?>" class="btn_board free_board">자유게시판 <?=$free_board_count?></a>
									<a href="board.php?board=fashion&mobile=<?=$mobile_check2?>" class="btn_board fashion_board">패션 <?=$fashion_board_count?></a>

									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="#" class="btn_board ">-</a>
									<a href="javascript:;" class="btn_board" onclick="pc_link();">피씨버전 보기</a>
								</div><!-- board_menu -->

							</div><!-- menu_board -->

							<span class="company">Copyright © 2020 <a href="http://nuricarrot.com" class="company_a_2" target="blank">NuriCarrot</a> All rights reserved.</span>

						</div><!-- menu_body -->

					</div><!-- menu -->
</body>