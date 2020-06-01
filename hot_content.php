<?php
include "dbcon.php";

include "web_check.php";

include "common.php";

session_start(); // 세션을 시작헌다.

/*
개발자 :  이누리
2014~2015

-- 아직 만들어야할것 --
//스크롤 내릴때 자동 more
프로필 사진 바꾸기
페이지 상세보기
비밀번호 저장
자동 로그인
핫이슈
검색기능
게시물 카운트다운

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
		//전체검색
	if( $board_type2 == '')
	{
		//게시글 쿼리
		$query="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM $board_type boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq ORDER BY boo.like_c DESC, boo.date ASC limit 0,3"; // SQL 쿼리문
	}else{
		//게시글 쿼리
		$query="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM $board_type boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE boo.board_type = '$board_type2' ORDER BY boo.like_c DESC, boo.date ASC limit 0,3"; // SQL 쿼리문
	}
		
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

<!--
The MIT License (MIT)

Copyright (c) <year> <copyright holders>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->
<!DOCTYPE html>
<html lang="ko">
	<head>
	<title>자몽챗 핫이슈</title>
<?
	include "head.php";
?>

<?
	include "jquery_common.php";
?>

<script>
	
		//피보나치 수열
		/*
		var f2 = 1;
		alert(fibo(10));
		function fibo(n)
		{
			if(n == 1 || n == 2)
			{
				return 1;
			}
			var a = (n-1);
			var b = (n-2);
			if(a == 2)
			{
				return f2+fibo(b);
			}else if(b == 2)
			{
				return fibo(a)+f2;
			}else{
				return fibo(a)+fibo(b);
			}
		}
		*/
		//스크롤 자동 more 전역 변수
		var auto_more = 0;
		var auto_more_count = 0;
	
		$(window).resize(center);
		$(window).scroll(center);


		function center()
		{
			var toppx = $(this).scrollTop();

			$(".loading_img").css({'top':$(window).height()/2 + toppx});

			top_setting();
			section_fixed();
			

			if($('.iframe_div').css("display") == "block")
			{
				<? //모바일
				if($mobile){
				?>
					
				<?
					}else{
				?>
					$(".iframe_div").css({'top':toppx});	
				<?
					}	
				?>	
				
			}

			//light_box_center();
			
			//스크롤 내릴시 자동으로 콘텐츠 로드 5번까지
			if ( toppx + $(window).height() >= $(document).height() - 300 )
			{
				if(auto_more == 0)
				{
					auto_more = 1;
					auto_more_count++;				
					
					if(auto_more_count < 5)
					{
						if("<?=$boardList[1]?>")
						{								
							//로딩이미지 추가
							var more_loading = '<img class="more_btn_loading" src="images/loading_img/loading_img.GIF" width="30px">';
							$(".content_board").append(more_loading);
						}

						setTimeout(function(){	
							if("<?=$boardList[1]?>")
							{								
								content_more();
							}
							
							if(auto_more_count >= 5)
							{
								auto_more_count = 0;
							}
						},200);	

					}
				}
			}
			
		}



var onload_check="off";

		$(window).load(function(){


		/*
		//바이트 구하기
		var str =strip_tags($(".con43 .content_load .text").text());
        var size = 0;

        for(var i=0;i<str.length;i++) {
            size++;
            if(44032 <str.charCodeAt(i) && str.charCodeAt(i) <=  55203) { // hangul Syliables
                size++;
            }
            if(12593 <= str.charCodeAt(i) && str.charCodeAt(i) <= 12686 ) {
                size++;
            }
        }
		*/

			//hash 출력방법
			//window.location.hash = '';

			//alert(window.location.hash);

				start_load();

				//setTimeout(function(){$(".test").animate({'top': '500px'}, 1000, 'easeOutExpo');},1000);
				//setTimeout(function(){$("body, html").animate({scrollTop:1500}, 1500, 'easeInOutQuint');},3000);
				
/*
			//on load errer
			setTimeout(function(){
			if (onload_check == "off")
			{
				location.reload();
			}
			},1000);*/



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

			$(".con_loading_img").hide();
			$(".content_load").animate({'opacity':'1.0'},500);
			//$(".bg").fadeOut(500);
		}



		function con_load(e,user_e)
		{	
			onload_check = "on";

			//자동폭파시간 설정
			finish_date(e);

			//삭제버튼 추가
			content_delete(e,user_e);	

			var temp_width = $(".con_img_"+e).width();
			var temp_height = $(".con_img_"+e).height();
			
			if (	temp_width  >= temp_height)
			{
				var ratio ="가로";
			}else{
				var ratio ="세로";
			}
			
			if ( ratio == "가로")
			{
				//사진 중앙정렬
				//$(".con_img_"+e).css({'top': $(".con"+e+" .photo").height()/2 - $(".con_img_"+e).height()/2 }); 
				//$(".con_img_"+e).css({'left': $(".con"+e+" .photo").width()/2 - $(".con_img_"+e).width()/2 });
				
				$(".photo_"+e).css({'width':$(".con_img_"+e).width(),'height':$(".con_img_"+e).height()});
			}else{
				$(".con_img_"+e).css({'width':'300px'});
				$(".photo_"+e).css({'width':$(".con_img_"+e).width(),'height':$(".con_img_"+e).height()});
				
				if ($(".con_img_"+e).height() > 800 )
				{
					$(".photo_"+e).css({'height':'400px'});
					$(".con_img_"+e).css({'top': $(".con"+e+" .photo").height()/2 - $(".con_img_"+e).height()/2 });
				}
				
				
			}
						
		}

		function con_more_load(e)
		{			
			$(".con_loading_img_"+e).hide();
			$(".content_load_"+e).animate({'opacity':'1.0'},300);
		}

		$(window).ready(function(){
			//네비게이트
			navigate();

//쿠키관련
<?
if(isset($_SESSION['user_email']))
{ 
}else{
?>
				if (getCookie("id")) { // getCookie함수로 id라는 이름의 쿠키를 불러와서 있을경우
					$(".login_id").val(getCookie("id"));
					$("#idsave_id").attr("checked", true);
					//document.login_form.login_id.value = getCookie("id"); //input 이름이 id인곳에 getCookie("id")값을 넣어줌
					//document.login_form.idsave.checked = true; // 체크는 체크됨으로
				}

				if (getCookie("pw")) { // getCookie함수로 id라는 이름의 쿠키를 불러와서 있을경우
					if (getCookie("id"))
					{
						$(".login_password").val(getCookie("pw"));
						$("#pwsave_id").attr("checked", true);
					}
					
				}
<?
}
?>		

	/*
	
	var $window = $(window);		//Window object
	
	var scrollTime = 0.4;			//Scroll time
	var scrollDistance = 200;		//Distance. Use smaller value for shorter scroll and greater value for longer scroll
		
	$window.on("mousewheel DOMMouseScroll", function(event){
		
		event.preventDefault();	
										
		var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
		var scrollTop = $window.scrollTop();
		var finalScroll = scrollTop - parseInt(delta*scrollDistance);
			
		TweenMax.to($window, scrollTime, {
			scrollTo : { y: finalScroll, autoKill:true },
				ease: Power1.easeOut,	//For more easing functions see http://api.greensock.com/js/com/greensock/easing/package-detail.html
				autoKill: true,
				overwrite: 5							
			});
					
	});
	*/

			<? //모바일
			if($mobile){
			?>
				
			<?
				}else{
			?>
				
			<?
				}
			?>
			tooltip_fun();
			
			
			var toppx = $(this).scrollTop();

			$(".loading_img").css({'top':$(window).height()/2 + toppx});

			



			top_setting();
			
		
		});


//content_more_ajax
var last_content_index = 0;
function content_more()
{
	var get_type = "hot";	
	var get_search = "";

	//콘텐츠 제일 아랫부분 인덱스값 얻어오기
	last_content_index = $(".content:eq(-1)").attr('class').split(' ')[2].split('con')[1];

	$(".content_more_btn").attr('onclick','alert("잠시만 기다려 주세요.");');
	//alert(last_content_index);
	/*
	SET @n=0;
SELECT foo.*, @n := @n+1 AS rownum from jamong_chat_freeboard foo ORDER BY  foo.seq DESC LIMIT 8,5*/

	
	$.ajax({
			type:"POST",
			url:"content_more_ajax.php",
			data:"last_seq="+last_content_index+"&get_type="+get_type+"&search="+get_search+"&board="+board_type,
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert(data[0].seq);
				$(".content_more_btn").remove( );
				
				for(var i = 0 ; i < data.length ; i ++)
				{		
					//alert(data[i].count);

					var content_more_append = '<div class="content mouse_over con'+data[i].seq+'"><img class="load_temp" src="images/icon_512.png" style="display: none;" onload="content_delete('+data[i].seq+','+data[i].user_seq+');" height="30px"><img class="con_loading_img con_loading_img_'+data[i].seq+'" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px"><div class="content_load content_load_div content_load_'+data[i].seq+'"><ul class="finish_date finish_date_'+data[i].seq+' tooltip2" title="게시글 폭파까지 남은시간"><li class="finish_day">'+data[i].finish_date_diff_day+'</li>													<li class="finish_nbsp">&nbsp;</li><li class="finish_hour">'+data[i].finish_date_diff_hour+'</li><li class="finish_twinkle">:</li><li class="finish_minute">'+data[i].finish_date_diff_minute+'</li><li class="finish_twinkle">:</li>	<li class="finish_second">'+data[i].finish_date_diff_second+'</li>													<input class="finish_time_second" type="hidden" value="'+data[i].finish_date_diff_time_second+'"></ul><div class="con_number"><a href="detail_content.php?seq='+data[i].seq+'&board='+board_type+'&mobile=<?=$mobile_check2?>" title="해당 게시물로 이동">No. '+data[i].seq+'</a></div><div class="info"><img class="profile_img" src="'+data[i].profile_way+'" style="background:white;"  width="40px" height="40px" onclick="profile_light_iframe_start('+data[i].user_seq+');" title="프로필 보기">													<span><div class="user_name mouse_over_underline" style="display: inline-block; font-weight:bold; width:470px; text-overflow: ellipsis; white-space: nowrap;overflow: hidden;">'+data[i].name+'</div><br><div class="date_time">';
					
					
					if (data[i].diff_check == "none")
					{
						content_more_append +=data[i].year+'년 '+data[i].month+'월'+data[i].day+'일 '+data[i].ampm+' '+data[i].hour+':'+data[i].minute;
					}else if(data[i].diff_check == "hour")
					{
						content_more_append +=data[i].diff+'시간 전';
					}else if(data[i].diff_check == "minute")
					{
						content_more_append +=data[i].diff+'분 전';
					}else if(data[i].diff_check == "now")
					{
						content_more_append +='방금 전';
					}
					content_more_append +='</div></span></div><div class="text">'+data[i].body+'<span>'+data[i].body_dot+'</span>';

					if(	data[i].line_cnt > 5 ){
						content_more_append +='<span class="content_enter" onclick="content_text_more('+data[i].seq+');" title="더 보기">더 보기</span>';
					}					
												
					content_more_append +='</div><div class="photo photo_'+data[i].seq+' tooltip" onclick="light_iframe_start('+data[i].seq+');" title="크게 보기">													<img  class="con_img_'+data[i].seq+'" src="'+data[i].photo_way+'thumbnail/'+data[i].photo+'" width="598px" onload="con_load('+data[i].seq+','+data[i].user_seq+'); con_more_load('+data[i].seq+'); like_status('+data[i].seq+',\'content\');"><!--<img src="'+data[i].photo_way+'thumbnail/'+data[i].photo+'" width="598px" onclick="light_time_out(0,\''+data[i].photo+'\',\'con'+data[i].seq+'\',\''+data[i].seq+'\');">--></div>												<div class="content_footer content_footer_'+data[i].seq+'">								<span class="content_footer_btn content_like_'+data[i].seq+'" title="좋아합니다"  onclick="like_ajax('+data[i].seq+',\'content\');">좋아요</span>														<span>·</span><span class="content_footer_btn" title="댓글 남기기" onclick="comment_btn_click('+data[i].seq+');"> 댓글 달기</span><div class="content_footer_like_count_'+data[i].seq+'" style="display:inline;">';
					
					if (data[i].like_c>0)
					{
						content_more_append +='<span class="like_temp_'+data[i].seq+'"> · <img src="images/logo_normal.svg" class="like_logo" height="14px"></span><span class="con'+data[i].seq+'_like_count like_temp_'+data[i].seq+'"> '+data[i].like_c+'</span>';
					}

					content_more_append +='</div><div class="content_footer_comment_count_'+data[i].seq+'" style="display:inline;"></div> </div>		<div class="comment">								<div class="comment_like_count comment_like_count_'+data[i].seq+'">';
					
					if (data[i].like_c>0)
					{
						content_more_append +='<div class="like_count_div2"><img src="images/logo_normal.svg" class="like_logo" height="14px">&nbsp;</div>														<div class="like_count_div tooltip" onclick="like_iframe_start('+data[i].seq+',\'content\');" title="누가 좋아했는지 보기">															<span class="like_count con'+data[i].seq+'_like_count">'+data[i].like_c+'</span><span>명</span>													</div>											<div>이 좋아합니다.</div>';
					}else{
						content_more_append +='<div>가장 먼저 좋아요를 눌러보세요!</div>';
					}
				
				if(login_check)
				{
					content_more_append +='</div><div class="comment_list">	<div class="comment_write comment_write_'+data[i].seq+'">														<form id="myForm_'+data[i].seq+'" name="myForm_'+data[i].seq+'" action="comment_write_ajax.php" method="post" accept-charset="utf-8" ENCTYPE="multipart/form-data"><img class="profile_img" src="<?=$user_profile_way?>" style="background:white;" width="30px" height="30px" onclick="profile_light_iframe_start(<?=$user_seq?>);" title="프로필 보기">															<input name="comment_input" class="comment_input comment_input_'+data[i].seq+'" placeholder="댓글을 입력하세요..." maxlength="140" autocomplete="off" required=""	onkeypress="if (event.keyCode==13){comment_write('+data[i].seq+',1);}">		<input type="text" style="display: none;">										<div class="image_add" onclick="image_add('+data[i].seq+')"></div>														<input type="hidden" class="seq" name="seq" value="'+data[i].seq+'">															<input type="hidden" class="user_seq" name="user_seq" value="<?=$user_seq?>"></form>	</div><div class="comment_reload_div comment_reload_div_'+data[i].seq+'">';
				}else{
					content_more_append +='</div><div class="comment_list"><div class="comment_write">													<img class="profile_img" src="<?=$user_profile_way?>" style="background:white;" width="30px" height="30px">														<input type="text" name="comment_input" class="comment_input comment_input_'+data[i].seq+' mouse_over" placeholder="로그인이 필요합니다..." maxlength="140" autocomplete="off" required="" onkeypress="if (event.keyCode==13) alert(\'로그인이 필요합니다.\');" disabled>												</div>				<div class="comment_reload_div comment_reload_div_'+data[i].seq+'">													</div><!-- comment_reload_div -->';
				}
				content_more_append +='</div><!-- comment_list -->		</div>										</div>					</div><!-- content -->';

					$(".content_board").append(content_more_append);

					comment_reload(data[i].seq);
				}
				
				//로딩이미지 삭제
				$(".more_btn_loading").remove();
				
				if(data.length == 0)
				{
					auto_more_count = 5;
					//alert("더이상 없어요...");
				}

				//스크롤 자동이 멈출때 버튼 다시추가하기
				if( auto_more_count == 4 )
				{
					var more_btn_append = '<div class="content_more_btn tooltip" onclick="content_more(); auto_more_count = 0;"  title="더 보기"><span oncontextmenu="return false" onselectstart="return false" ondragstart="return false">MORE</span></div>';
					$(".content_board").append(more_btn_append);
				}
				
				tooltip_fun();

				//스크롤 자동 more 변수 체크
				auto_more = 0;
			},
			error:function(e){
				//alert("에러");
				alert( e.responseText );
			}
		});
		
}


//like_ajax 좋아요 등록 및 취소
function like_ajax(e,status)
{
	var user_seq = "<?=$user_seq?>";
	var like_check = 0;

	if(user_seq)
	{
		if(status == 'content')
		{
			if($(".content_like_"+e).text() == "좋아요")
			{
				like_check = 1;
			}else if($(".content_like_"+e).text() == "좋아요 취소")
			{
				like_check = 2;
			}
		}else if(status == 'comment'){
			if($(".comment_like_"+e).text() == "좋아요")
			{
				like_check = 1;
			}else if($(".comment_like_"+e).text() == "좋아요 취소")
			{
				like_check = 2;
			}
		}
		
		if(like_check == 1 || like_check == 2)
		{
			$.ajax({
					type:"POST",
					url:"like_ajax.php",
					data:"seq="+e+"&user_seq="+user_seq+"&status="+status+"&like_check="+like_check+"&l_token="+encodeURIComponent($(".l_token").val()),
					dataType:"json",
					//traditional: true,
					//contentType: "application/x-www-form-urlencoded;charset=utf-8",
					success:function( data ){
						//alert(data);
						if(data == 0 || data == 2)
						{
							alert("등록 실패");
						}else{
							//성공
							like_status(e,status);
							
							if(status == 'content') //콘텐츠일때
							{
								//좋아요 카운트 바꾸기
								if(like_check ==1)
								{
									var like_count = $(".con"+e+"_like_count").eq(0).text();
									if(like_count == '')
									{
										like_count=0;
									}
									like_count = parseInt(like_count);
									like_count++;
									
									if(like_count == 1)
									{
										var like_btn_add_append = '<span class="like_temp_'+e+'">· <img src="images/logo_normal.svg" class="like_logo" height="14px"></span><span class="con'+e+'_like_count like_temp_'+e+'"></span>';
										$(".content_footer_like_count_"+e).append(like_btn_add_append);	
										
										$(".comment_like_count_"+e).empty();
										var like_btn_add_append2 = '<div><img src="images/logo_normal.svg" class="like_logo" height="14px" width="11px">&nbsp;</div>													<div class="like_count_div tooltip" onclick="like_iframe_start('+e+',\'content\');" title="누가 좋아했는지 보기">															<span class="like_count con'+e+'_like_count"></span><span>명</span></div>	<div>이 좋아합니다.</div>';
										$(".comment_like_count_"+e).append(like_btn_add_append2);	
									}
									
									$(".con"+e+"_like_count").text('');
									$(".con"+e+"_like_count").append(' '+like_count);

									//시간 연장
									finish_effect(e,1);
									
								}else if(like_check ==2){
									var like_count = $(".con"+e+"_like_count").eq(0).text();
									if(like_count == '')
									{
										like_count=0;
									}
									like_count = parseInt(like_count);
									if(like_count != 0)
									{
										like_count--;
									}									

									if(like_count == 0)
									{
										$(".content_footer_like_count_"+e).empty();

											
										$(".comment_like_count_"+e).empty();
										var like_btn_add_append2 = '<div>가장 먼저 좋아요를 눌러보세요!</div>';
										$(".comment_like_count_"+e).append(like_btn_add_append2);	
									}

									$(".con"+e+"_like_count").text('');
									$(".con"+e+"_like_count").append(' '+like_count);

									//시간 감소
									finish_effect(e,2);

								}
							}else if(status == 'comment'){ //댓글일때
								//comment_like_count_
								//좋아요 카운트 바꾸기
								if(like_check ==1)
								{
									var like_count = $(".comment_"+e+"_like_count").text();
									if(like_count == '')
									{
										like_count=0;
									}
									like_count = parseInt(like_count);
									like_count++;
									
									if(like_count == 1)
									{
										var like_btn_add_append = '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+e+'_like_count tooltip" onclick="like_iframe_start('+e+',\'comment\');" title="누가 좋아했는지 보기"></span></span>';
										$(".comment_like_count_"+e).append(like_btn_add_append);
									
									}
									
									$(".comment_"+e+"_like_count").text('');
									$(".comment_"+e+"_like_count").append(' '+like_count);
									
								}else if(like_check ==2){
									var like_count = $(".comment_"+e+"_like_count").text();
									if(like_count == '')
									{
										like_count=0;
									}
									like_count = parseInt(like_count);
									if(like_count != 0)
									{
										like_count--;
									}

									if(like_count == 0)
									{
										$(".comment_like_count_"+e).empty();
									}

									$(".comment_"+e+"_like_count").text('');
									$(".comment_"+e+"_like_count").append(' '+like_count);
								}
							}
							
						}

						tooltip_fun();
					},
					error:function(e){
						alert("에러");
						//alert( e.responseText );
					}
			});
		}

	}else{
		alert("로그인이 필요합니다.");
	}
}

//좋아요 눌렀는지 안눌렀는지 판독
function like_status(e,status)
{
	var user_seq = "<?=$user_seq?>";
	if(user_seq)
	{
		$.ajax({
			type:"POST",
			url:"like_status_ajax.php",
			data:"seq="+e+"&user_seq="+user_seq+"&status="+status,
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert(data);
				if(data == 0)
				{
					if(status == "content")
					{
						$(".content_like_"+e).text('좋아요');
						$(".content_like_"+e).attr('title','좋아요');
					}else{
						$(".comment_like_"+e).text('좋아요');
						$(".comment_like_"+e).attr('title','좋아요');
					}
				}else{
					if(status == "content")
					{
						$(".content_like_"+e).text('좋아요 취소');
						$(".content_like_"+e).attr('title','좋아요 취소');
					}else{
						$(".comment_like_"+e).text('좋아요 취소');
						$(".comment_like_"+e).attr('title','좋아요 취소');
					}
				}
			},
			error:function(e){
				alert("에러");
				//alert( e.responseText );
			}
		});
	}
}



//댓글 새로고침 함수
function comment_reload(e,g,a) //e = seq, g = get, a = all
{
	
	$.ajax({
			type:"POST",
			url:"comment_reload_ajax.php",
			data:"seq="+e,
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert(data);
				

				//$(".comment_reload_div_"+e).css({'opacity':'0.0'});

				$(".comment_reload_div_"+e).empty();
				$(".comment_reload_div_"+e).css({'display':'none'});
				if(g == 1 || a == 1) //사용자가 입력했을때 체크 및 전체출력
				{
					//전체출력
					for(var i = 0 ; i < data.length ; i ++)
					{					
						var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img class="profile_img" src="'+data[i].uoo_profile_way+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\'); comment_delete('+data[i].seq+','+data[i].user_seq+');comment_img('+data[i].seq+','+data[i].user_seq+',\''+data[i].photo_way+'\',\''+data[i].photo+'\');" onclick="profile_light_iframe_start('+data[i].user_seq+');" title="프로필 보기">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
								
						if(data[i].like_comment >0)
						{
							comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
						}
						
						comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

						$(".comment_reload_div_"+e).append(comment_reload_append);

						
						$(".comment_reload_div_"+e).fadeIn(300);
					}
					
					//글 입력시에 자동 스크롤
					if(g == 1)
					{
						<? //모바일
						if($mobile){
						?>
							
						<?
							}else{
						?>
							setTimeout(function(){
								$("body, html").stop().animate({scrollTop:$(".con"+e).offset().top + $(".con"+e).height() - $(window).height()/2 },500 , 'easeOutExpo');
							},100);
							//$("body, html").stop().animate({scrollTop:$(".comment_reload_div_"+e).offset().top - 200},500 , 'easeOutExpo');
						<?
							}	
						?>
					}

					//댓글더보기 클릭시 자동 스크롤
					if(a == 1)
					{
						<? //모바일
						if($mobile){
						?>
							
						<?
							}else{
						?>
							
							//$("body, html").stop().animate({scrollTop:$(".comment_reload_div_"+e).offset().top - 200},500 , 'easeOutExpo');
						<?
							}	
						?>
					}

				}else{

					//코멘트 갯수에 따른 출력
					if(data.length > 15)
					{
						//갯수가 16개 이상일때 1개만 출력
						for(var i = 0 ; i < 1 ; i ++)
						{					
							var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img class="profile_img" src="'+data[i].uoo_profile_way+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\'); comment_delete('+data[i].seq+','+data[i].user_seq+');comment_img('+data[i].seq+','+data[i].user_seq+',\''+data[i].photo_way+'\',\''+data[i].photo+'\');" onclick="profile_light_iframe_start('+data[i].user_seq+');" title="프로필 보기">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
									
							if(data[i].like_comment >0)
							{
								comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
							}
							
							comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

							$(".comment_reload_div_"+e).append(comment_reload_append);

							
							$(".comment_reload_div_"+e).fadeIn(300);
						}
					

						//댓글 더보기
						var comment_more_count = data.length - 1;
						var comment_more = '<div class="comment_more" onclick="comment_reload('+e+',0,1);"><img src="images/comment_icon.svg" height="14px" class="comment_icon"">&nbsp;<span>댓글 '+comment_more_count+'개 더 보기...</span></div>';
						$(".comment_reload_div_"+e).append(comment_more);
						
					}else if(data.length > 7)
					{
						//코멘트 갯수가 8~15개 사이일때
						//3개 출력
						for(var i = 0 ; i < 3 ; i ++)
						{					
							var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img class="profile_img" src="'+data[i].uoo_profile_way+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\'); comment_delete('+data[i].seq+','+data[i].user_seq+');comment_img('+data[i].seq+','+data[i].user_seq+',\''+data[i].photo_way+'\',\''+data[i].photo+'\');" onclick="profile_light_iframe_start('+data[i].user_seq+');" title="프로필 보기">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
									
							if(data[i].like_comment >0)
							{
								comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
							}
							
							comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

							$(".comment_reload_div_"+e).append(comment_reload_append);

							
							$(".comment_reload_div_"+e).fadeIn(300);
						}

						//댓글 더보기
						var comment_more_count = data.length - 3;
						var comment_more = '<div class="comment_more" onclick="comment_reload('+e+',0,1);"><img src="images/comment_icon.svg" height="14px" class="comment_icon"">&nbsp;<span>댓글 '+comment_more_count+'개 더 보기...</span></div>';
						$(".comment_reload_div_"+e).append(comment_more);

					}else if(data.length <= 7)
					{
							//전체출력
							for(var i = 0 ; i < data.length ; i ++)
							{					
								var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img class="profile_img" src="'+data[i].uoo_profile_way+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\'); comment_delete('+data[i].seq+','+data[i].user_seq+');comment_img('+data[i].seq+','+data[i].user_seq+',\''+data[i].photo_way+'\',\''+data[i].photo+'\');" onclick="profile_light_iframe_start('+data[i].user_seq+');" title="프로필 보기">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
										
								if(data[i].like_comment >0)
								{
									comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
								}
								
								comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

								$(".comment_reload_div_"+e).append(comment_reload_append);

								
								$(".comment_reload_div_"+e).fadeIn(300);
							}						
							
					}
				}

				
				//$(".comment_reload_div_"+e).css({'opacity':'1.0'});
				$(".comment_input_"+e).val('');
				$(".comment_input_"+e).removeAttr('readonly');

				//comment_count append
				$(".content_footer_comment_count_"+e).empty();
				if(data.length !=0)
				{
					var comment_count_append = '<span class="comment_temp_'+e+'"> · <img src="images/comment_icon.svg" height="14px" class="comment_icon"></span><span class="con_'+e+'_comment_count comment_temp_'+e+'"> '+data.length;

					$(".content_footer_comment_count_"+e).append(comment_count_append);
				}

				//input 포커스 해제
				$(".comment_input_"+e).blur();

				tooltip_fun();
				
			},
			error:function(e){
				alert("에러");
				//alert( e.responseText );
			}
		});
}

</script>



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

<div class="content_board">


<!-- 게시물이 존재하지 않을경우 -->
<?
if(isset($board))
{
}else{
?>


<div class="mouse_over" style="position: relative;
  background-color: #fff;
  width: 600px;
  height:720px;
  padding: 10px;
  margin: 10px auto;
  border: 1px solid;
  border-color: #e9eaed #dfe0e4 #d0d1d5;
  border-radius: 3px;
  -webkit-border-radius: 3px;
  text-align: center;">

	<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">

	<div class="content_load not_present_div">
		<span class="not_present">
			게시물이 삭제 되었거나, 존재하지 않습니다. <br>
			Posts have been deleted or does not exist.
		</span>
	 </div>

  </div>
<?
}
?>
	
	<!-- 존재 할 경우 -->
						<? 
							foreach($boardList as $board) {	
						?>
										<div class="content mouse_over con<?=$board['seq']?>">

										<img class="load_temp" src="images/icon_512.png" style="display: none;" onload="content_delete(<?=$board['seq']?>,<?=$board['user_seq']?>);" height="30px">

										
										<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">
										

											<div class="content_load content_load_div">

												<ul class="finish_date finish_date_<?=$board['seq']?> tooltip2" title="게시글 폭파까지 남은시간">
													<li class="finish_day"><?=$board['finish_date_diff_day']?></li>
													<li class="finish_nbsp">&nbsp;</li>
													<li class="finish_hour"><?=$board['finish_date_diff_hour']?></li>
													<li class="finish_twinkle">:</li>
													<li class="finish_minute"><?=$board['finish_date_diff_minute']?></li>
													<li class="finish_twinkle">:</li>
													<li class="finish_second"><?=$board['finish_date_diff_second']?></li>
													<input class="finish_time_second" type="hidden" value="<?=$board['finish_date_diff_time_second']?>">
												</ul>

												<div class="con_number"><a href="detail_content.php?seq=<?=$board['seq']?>&board=<?=$board_type2?>&mobile=<?=$mobile_check2?>" title="해당 게시물로 이동">No. <?=$board['seq']?></a></div>
											
												<div class="info">
													<img class="profile_img" src="<?=$board['profile_way']?>" style="background:white;"  width="40px" height="40px" onclick="profile_light_iframe_start(<?=$board['user_seq']?>);" title="프로필 보기">
													<span><div class="user_name mouse_over_underline" style="display: inline-block; font-weight:bold; width:470px; text-overflow: ellipsis; white-space: nowrap;overflow: hidden;"><?=$board['name']?></div><br>
													<div class="date_time">
									<?
									if ($board['diff_check'] == 'none')
									{
									?>
													<?=$board['year']?>년 <?=$board['month']?>월<?=$board['day']?>일 <?=$board['ampm']?> <?=$board['hour']?>:<?=$board['minute']?>
									<?
									}else if($board['diff_check'] == 'hour'){
									?>
													<?=$board['diff']?>시간 전
									<?
									}else if($board['diff_check'] == 'minute')
									{
									?>
													<?=$board['diff']?>분 전
									<?
									}else if($board['diff_check'] == 'now')
									{
									?>
													방금 전
									<?
									}
									?>
													</div>
									

													</span>
												</div>

												<div class="text"><?=$board['body']?><span><?=$board['body_dot']?></span><?
													if( $board['line_cnt'] > 5 ){
													?><span class="content_enter" onclick="content_text_more(<?=$board['seq']?>);" title="더 보기">더 보기</span><?
													}
													?></div>

												<div class="photo photo_<?=$board['seq']?> tooltip" 
						onclick="light_iframe_start(<?=$board['seq']?>);" title="크게 보기">

													<img class="con_img_<?=$board['seq']?>" src="<?=$board['photo_way']?>thumbnail/<?=$board['photo']?>" width="598px" onload="con_load(<?=$board['seq']?>,<?=$board['user_seq']?>); comment_reload(<?=$board['seq']?>); like_status(<?=$board['seq']?>,'content');">

													<!--<img src="<?=$board['photo_way']?>" width="598px" onclick="light_time_out(0,'<?=$board['photo']?>','con<?=$board['seq']?>','<?=$board['seq']?>');">-->													
												</div>

												<div class="content_footer content_footer_<?=$board['seq']?>">
														<span class="content_footer_btn content_like_<?=$board['seq']?>" title="좋아합니다" onclick="like_ajax(<?=$board['seq']?>,'content');">좋아요</span>
														<span>·</span>
														<span class="content_footer_btn" title="댓글 남기기" onclick="comment_btn_click(<?=$board['seq']?>);">댓글 달기</span>
														<div class="content_footer_like_count_<?=$board['seq']?>" style="display:inline;">
														<?
														if ($board['like_c']>0)
														{
														?>
														<span class="like_temp_<?=$board['seq']?>">· <img src="images/logo_normal.svg" class="like_logo" height="14px"></span>
														<span class="con<?=$board['seq']?>_like_count like_temp_<?=$board['seq']?>"><?=$board['like_c']?></span>
														<?
														}
														?>
														</div>
														<div class="content_footer_comment_count_<?=$board['seq']?>" style="display:inline;"></div>
												</div>
												
												<div class="comment">

													<div class="comment_like_count comment_like_count_<?=$board['seq']?>">
													<?
													if ($board['like_c']>0)
													{
													?>
														<div class="like_count_div2"><img src="images/logo_normal.svg" class="like_logo" height="14px">&nbsp;</div>

														<div class="like_count_div tooltip" onclick="like_iframe_start(<?=$board['seq']?>,'content');" title="누가 좋아했는지 보기">
															<span class="like_count con<?=$board['seq']?>_like_count"><?=$board['like_c']?></span><span>명</span>
														</div>

														<div>이 좋아합니다.</div>
													<?
													}else{
													?>
														<div>가장 먼저 좋아요를 눌러보세요!</div>
													<?
													}
													?>
													</div>


													

													<div class="comment_list">

							<?
							if(isset($_SESSION['user_email']))
							{
							?>
													<div class="comment_write comment_write_<?=$board['seq']?>">
														<form id="myForm_<?=$board['seq']?>" name="myForm_<?=$board['seq']?>" action="comment_write_ajax.php" method="post" accept-charset="utf-8" ENCTYPE="multipart/form-data">

															<img class="profile_img" src="<?=$user_profile_way?>" style="background:white;" width="30px" height="30px" onclick="profile_light_iframe_start(<?=$user_seq?>);" title="프로필 보기">
															<input name="comment_input" class="comment_input comment_input_<?=$board['seq']?>" placeholder="댓글을 입력하세요..." maxlength="140" autocomplete="off" required=""
															onkeypress="if (event.keyCode==13){comment_write(<?=$board['seq']?>,1);}">

															<input type="text" style="display: none;">

															<div class="image_add" onclick="image_add(<?=$board['seq']?>)"></div>

															<input type="hidden" class="seq" name="seq" value="<?=$board['seq']?>">
															<input type="hidden" class="user_seq" name="user_seq" value="<?=$user_seq?>">

														</form>

													</div>

													<div class="comment_reload_div comment_reload_div_<?=$board['seq']?>">

							<?
							}else{
							?>
													<div class="comment_write">
														<img class="profile_img" src="<?=$user_profile_way?>" style="background:white;" width="30px" height="30px">
														<input type="text" name="comment_input" class="comment_input comment_input_<?=$board['seq']?> mouse_over" placeholder="로그인이 필요합니다..." maxlength="140" autocomplete="off" required="" onkeypress="if (event.keyCode==13) alert('로그인이 필요합니다.');" disabled>
													</div>

													<div class="comment_reload_div comment_reload_div_<?=$board['seq']?>">

											        
							<?
							}
							?>
														
														</div><!-- comment_reload_div -->
												

													</div><!-- comment_list -->

												</div>

											</div>

										</div><!-- content -->

										
						<? 
						}
						?>

						

							<!--
							<div class="content_more_btn tooltip" onclick="content_more(); auto_more_count = 0;"  title="더 보기">
								<span oncontextmenu="return false" onselectstart="return false" ondragstart="return false">MORE</span>
							</div>

							-->

						</div><!-- content_board -->					
						
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
