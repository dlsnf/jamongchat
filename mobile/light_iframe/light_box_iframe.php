<?php

include "../../dbcon.php";
include "../../domain_security.php";

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


		$query="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE boo.seq = $get_seq"; // SQL 쿼리문
		$result=mysql_query($query, $conn) or die (mysql_error()); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


		if( !$result ) {
			echo "Failed to list query light_box_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['name'] = strip_tags($row['uoo_name']);
			$board['profile'] = $row['uoo_profile'];
			$board['user_seq'] = $row['user_seq'];
			$board['body'] = nl2br(strip_tags($row['body']));
			$board['like_c'] = $row['like_c'];
			$board['photo_way'] = $row['photo_way'];
			$board['photo'] = $row['photo'];
			$board['date'] = $row['date'];
			$board['finish_date'] = $row['finish_date'];
			$board['ip'] = $row['ip'];

			//프로필이 없을경우
			if($board['profile'] == '')
			{
				$board['profile'] = "btn_youtb.png";
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

			$board['finish_date_diff_hour'] = floor($finish_date_diff_second/3600);
			$board['finish_date_diff_minute'] = floor($finish_date_diff_second%3600/60);
			$board['finish_date_diff_second'] = floor($finish_date_diff_second%3600%60);

			$temp_seq = $board['seq'];

			//like 갯수			
			$query3="SELECT count(distinct(like_user_seq)) as count FROM jamong_chat_like WHERE like_seq = '$temp_seq' AND like_status = 'content'"; // SQL 쿼리문
			
			
			$result3=mysql_query($query3, $conn); // 쿼리문을 실행 결과

			if( !$result3 ) {
				echo "Failed to list query index_like_count";
				$isSuccess = FALSE;
			}

			while( $row3 = mysql_fetch_array($result3) ) {
				$board['like_c'] = $row3['count'];
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
	<script src="js/jquery-1.11.0.min.js"></script>
	<!-- light_box_jamongsoft -->
	<script src="js/light_box_jamongsoft.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/light_box_jamongsoft.css">

	<!--custom scrollbar css-->
	<link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
	<!-- custom scrollbar plugin -->
	<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">

	<!-- ajaxForm -->
	<script src="js/jquery.form.js"></script> 

	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />    
	<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

<?
	include "../jquery_common.php";
?>

<script>
		var login_check="<?=$_SESSION['user_email']?>";
	
		$(window).resize(center);
		$(window).scroll(center);


		function center()
		{
			light_box_black_size();

			light_box_center();		
		}

		

		function light_box_center()
		{
		
			var toppx = $(this).scrollTop();
			var bottompx = $(this).scrollTop() + $(window).height();


			if ( $(window).width() >= 1116 )
			{
				light_box_width = $(window).width()*0.8;
				//$(".light_box").css({'left':$(document).width()/2 - $(".light_box").width()/2 - 30});
			}else{
				light_box_width = 900;
			}
				
			if ( $(window).width() >= 1116 )
			{
				$(".light_box").css({'width':light_box_width});
			}

			if ($(window).height() <= $(".light_box").height()+60)
			{
				$(".light_box").css({'margin':'0 auto','margin-top':'60px','margin-bottom':'60px'});
				$(".light_box_black").css({'height':$(".light_box").height()+180});
			}else{
				$(".light_box").css({'margin':'auto'});
				//$(".light_box").css({'top':toppx + parent.document.body.clientHeight /2 - light_box_height/2 });
			}

			

				<? //모바일
				if($mobile){
				?>
					//$(".light_box_black").css({'height':<?=$get_pa_height?>});				
					$(".light_box").css({'margin':'0 auto'});
					$(".light_box").css({'top':  <?=$get_toppx?> + 50 });
				<?
					}else{
				?>
				<?
					}	
				?>				
	
					

			if( $(".light_box_img").height() >= $(".light_box").height())
			{
				$(".light_box_img").css({'width':'auto','height':$(".light_box").height()});
			}

			if( $(".light_box_img").width() >= $(".img_div").width())
			{
				$(".light_box_img").css({'width':'100%','height':'auto'});
			}

			$(".light_box_img").css({'margin-top':$(".img_div").height()/2 - $(".light_box_img").height()/2,'margin-left':$(".img_div").width()/2 - $(".light_box_img").width()/2});
		
			
			if($(".light_box_content").height() > 200)
			{
				$(".light_box_content").css({'height':'200px'});
			}


			comment_width();	

			comment_height();
			
		}



		function comment_height()
		{
			//댓글창 높이조절
			var comment_height_number = 487 - $(".light_box_content").height() - $(".file_div").height();
			$(".comment_reload_div").css({'height':comment_height_number});
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

			$(".content_load").animate({'opacity':'1.0'},500);;
			//$(".bg").fadeOut(500);
			setTimeout(function(){
				center();
			},3000);
		}

		function light_box_black_size()
		{
			if ( $(window).width() >= 960 )
			{
				$(".light_box_black").css({'width':$(window).width(),'height':$(document).height()});
			}else{
				$(".light_box_black").css({'width':$(document).width(),'height':parent.document.body.clientHeight});
			}
		}

	$(window).bind('orientationchange', function() {

		light_box_black_size();
	});


		$(window).ready(function(){

			light_box_black_size();
			tooltip_fun();
		});

function tooltip_fun()
{
	/*
	$( document ).tooltip({
		track: true
	});
	*/
	$('.tooltip2').tooltipster({
	   animation: 'fade',
	   delay: 200,
	   theme: 'tooltipster-default',
	   touchDevices: true,
	   trigger: 'hover',
	   hideOnClick: true,
		touchDevices:false
	});
	
}

function comment_width()
{
	var temp_width = $(".text_div").width() - 100 ;
	$(".comment_body").css({'width':temp_width});
	$(".comment_input").css({'width':temp_width});
}

//comment_btn_click
function comment_btn_click(e)
{
	if(login_check)
	{
		$(".comment_input_"+e).focus();
	}else{
		alert("로그인이 필요합니다.");
	}
}


//like_ajax 좋아요 등록 및 취소
function like_ajax(e,status)
{
	parent.like_ajax(e,status);

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
					url:"../../like_ajax.php",
					data:"seq="+e+"&user_seq="+user_seq+"&status="+status+"&like_check="+like_check+"&disable=1"+"&l_token="+encodeURIComponent($(".l_token").val()),
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
							parent.like_status(e,status);
							
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
										var like_btn_add_append2 = '<div class="like_count_div2"><img src="images/logo_normal.svg" class="like_logo" height="14px">&nbsp;</div>													<div class="like_count_div tooltip" onclick="parent.like_iframe_start('+e+',\'content\');" title="누가 좋아했는지 보기">															<span class="like_count con'+e+'_like_count"></span><span>명</span></div>	<div>이 좋아합니다.</div>';
										$(".comment_like_count_"+e).append(like_btn_add_append2);	
									}
									
									$(".con"+e+"_like_count").text('');
									$(".con"+e+"_like_count").append(' '+like_count);

									//시간 연장
									finish_effect(e,3);
									
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
									finish_effect(e,4);
								}
							}else if(status == 'comment'){ //댓글일때
								//comments_like_count_
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
										var like_btn_add_append = '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+e+'_like_count tooltip" onclick="parent.like_iframe_start('+e+',\'comment\');" title="누가 좋아했는지 보기"></span></span>';
										$(".comments_like_count_"+e).append(like_btn_add_append);									
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
										$(".comments_like_count_"+e).empty();
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
		//alert("로그인이 필요합니다.");
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
			url:"../../like_status_ajax.php",
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
function comment_reload2(e,g,a)
{
	

	$.ajax({
			type:"POST",
			url:"../../comment_reload_ajax.php",
			data:"seq="+e,
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert(data);
				

				//커스텀 스크롤 파괴
				$(".comment_reload_div").mCustomScrollbar("destroy");
				

				//$(".comment_reload_div_"+e).css({'opacity':'0.0'});

				$(".comment_reload_div_"+e).empty();
				$(".comment_reload_div_"+e).css({'display':'none'});

				//전체출력
				for(var i = 0 ; i < data.length ; i ++)
				{					
					var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img class="profile_img" src="../upload/up_profile/thumbnail/'+data[i].uoo_profile+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\'); comment_delete2('+data[i].seq+','+data[i].user_seq+');comment_img('+data[i].seq+','+data[i].user_seq+',\''+data[i].photo_way+'\',\''+data[i].photo+'\',\'light\');" onclick="parent.profile_light_iframe_start('+data[i].user_seq+');" title="프로필 보기">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comments_like_count_'+data[i].seq+'" style="display:inline;">';
							
					if(data[i].like_comment >0)
					{
						comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="parent.like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
					}
					
					comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

					$(".comment_reload_div_"+e).append(comment_reload_append);

					
					$(".comment_reload_div_"+e).fadeIn(300);
				}

				//댓글 갯수가 30개 이상일때 댓글 더보기 버튼 활성화 코드
				/*
				if(g == 1 || a == 1) //사용자가 입력했을때 체크 및 전체출력
				{
					//전체출력
					for(var i = 0 ; i < data.length ; i ++)
					{					
						var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img src="images/'+data[i].uoo_profile+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\')">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
								
						if(data[i].like_comment >0)
						{
							comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
						}
						
						comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

						$(".comment_reload_div_"+e).append(comment_reload_append);

						
						$(".comment_reload_div_"+e).fadeIn(300);
					}				

				}else{

					//코멘트 갯수에 따른 출력
					if(data.length > 30)
					{
						//갯수가 16개 이상일때 1개만 출력
						for(var i = 0 ; i < 30 ; i ++)
						{					
							var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img src="images/'+data[i].uoo_profile+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\')">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
									
							if(data[i].like_comment >0)
							{
								comment_reload_append += '<span class="jamong_color">&nbsp;·&nbsp;</span><span class="jamong_color"><img src="images/logo_normal.svg" class="like_logo" height="14px"> <span class="comment_like_count_iframe comment_'+data[i].seq+'_like_count tooltip" onclick="like_iframe_start('+data[i].seq+',\'comment\');" title="누가 좋아했는지 보기">'+data[i].like_comment+'</span></span>';
							}
							
							comment_reload_append += '</div></div>	</div>	</div>	</div></div>	</div>';

							$(".comment_reload_div_"+e).append(comment_reload_append);

							
							$(".comment_reload_div_"+e).fadeIn(300);
						}
					

						//댓글 더보기
						var comment_more = '<div class="comment_more" onclick="comment_reload('+e+',0,1);"><img src="images/comment_icon.svg" height="14px" class="comment_icon"">&nbsp;<span>댓글 더보기...</span></div>';
						$(".comment_reload_div_"+e).append(comment_more);
						
					}else{
						//전체출력
						for(var i = 0 ; i < data.length ; i ++)
						{					
							var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img src="images/'+data[i].uoo_profile+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\')">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comment_like_count_'+data[i].seq+'" style="display:inline;">';
									
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

				*/
				
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
				
				comment_width();				

				//커스텀 스크롤 생성
				$(".comment_reload_div").mCustomScrollbar();	
	
				setTimeout(function(){
					$(".comment_reload_div").mCustomScrollbar("scrollTo","bottom",{
						scrollEasing:"easeOut"
					});
				},100);
				
				
				//스크롤바 이동
				$(".comment_reload_div .mCustomScrollBox .mCSB_scrollTools").css({'right':'5px'});
				

				//input 포커스 해제
				$(".comment_input_"+e).blur();

				tooltip_fun();

				//댓글창 높이조절
				var comment_height_number = 487 - $(".light_box_content").height() - $(".file_div").height();
				$(".comment_reload_div").css({'height':comment_height_number});
				
				
				
			},
			error:function(e){
				alert("에러");
				//alert( e.responseText );
			}
		});

		//setTimeout(function (){comment_height();},1000);

		
}


function comment_delete2(e,user_e)
{
	//삭제버튼 삭제
	$(".btn_comment_delete_"+e).remove();
	//삭제버튼 추가
	if(user_e == login_seq_check || login_check == "admin")
	{
		var comment_delete_btn_append = '<div class="btn_comment_delete btn_comment_delete_'+e+' tooltip" title="댓글 삭제" onclick="btn_comment_delete_click2('+e+');"><img src="images/btn_close_gray.png" width="12px"></div>';
		$(".comment_div_"+e).prepend(comment_delete_btn_append);	
	}
}


function btn_comment_delete_click2(e)
{
	if (confirm("정말로 삭제 하시겠습니까?")) { 		
		$.ajax({
			type:"POST",
			url:"comment_delete_ajax.php",
			data:"seq="+e+"&user_seq="+login_seq_check+"&user_eamil="+login_check,
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){

				//alert(data);
				if(data == 3)
				{
					//alert("삭제 성공");
					//$(".con"+e).fadeOut(3000);
					//alert($(".content").length);
					$(".comment_div_"+e).remove();
					parent.$(".comment_div_"+e).remove();
					
				}
			},
			error:function(e){
				alert("에러");
				alert( e.responseText );
			}
		});

	}else {

	}
	

}
</script>

	</head>

	<body style="background: transparent; "onload="parent.light_iframe_fadein(); light_time_out(0,'<?=$board['photo']?>','con<?=$board['seq']?>','<?=$board['seq']?>');">
	<!-- 토큰 -->
<form>
	<input type='hidden' class="l_token" name='l_token' value='<?=$_SESSION['fake']?>'>
</form>



<? 
	foreach($boardList as $board) {	
?>
			

			
			

<div style="">

			<div class="light_box_black" onclick="light_box_finish();" style="" title="끄기"></div>
			<div class="light_box_black2"></div> 

			<div class="light_box" style="">


				
				<!-- button -->
				<img class="light_box_btn_close" src='images/btn_close.png' onclick="light_box_finish();" width='15px' title="끄기">

				<div class="light_box_group">
					<!-- images -->
					<div class="img_div">
						<a href="<?=$board['photo_way']?><?=$board['photo']?>" target="_blank" title="원본 보기">
							<img class='light_box_img' src='<?=$board['photo_way']?>thumbnail/<?=$board['photo']?>' onload="like_status(<?=$board['seq']?>,'content'); comment_reload2(<?=$board['seq']?>);">
						</a>
					</div>

					<div class="text_div">

						<div class='light_box_info con<?=$board['seq']?>'>

						<ul class="finish_date finish_date_<?=$board['seq']?> tooltip2" title="게시글 폭파까지 남은시간" style="right: 10px; top: 10px;">
							<li class="finish_day"><?=$board['finish_date_diff_day']?></li>
							<li class="finish_nbsp">&nbsp;</li>
							<li class="finish_hour"><?=$board['finish_date_diff_hour']?></li>
							<li class="finish_twinkle">:</li>
							<li class="finish_minute"><?=$board['finish_date_diff_minute']?></li>
							<li class="finish_twinkle">:</li>
							<li class="finish_second"><?=$board['finish_date_diff_second']?></li>
							<input class="finish_time_second" type="hidden" value="<?=$board['finish_date_diff_time_second']?>">
						</ul>

						<div class="con_number" style="  visibility: hidden;">												
							<a href="detail_content.php?seq=<?=$board['seq']?>" title="해당 게시물로 이동">No. <?=$board['seq']?></a>							
						</div>
						
						<img class="profile_img light_box_info_img" src='../upload/up_profile/thumbnail/<?=$board['profile']?>' style="background:white;"  width='40px' height="40px" onload="tooltip_fun(); finish_date(<?=$board['seq']?>);" onclick="parent.profile_light_iframe_start(<?=$board['user_seq']?>);" title="프로필 보기">

							<div class="light_box_info_info">
							
								<div class="user_name mouse_over_underline" style="font-weight: bold; width:210px; text-overflow: ellipsis; white-space: nowrap;overflow: hidden;"><?=$board['name']?></div><div class="date_time"><?=$board['year']?>년 <?=$board['month']?>월<?=$board['day']?>일 <?=$board['ampm']?> <?=$board['hour']?>:<?=$board['minute']?></div>
							
							</div>

						</div>
						<div class='light_box_content mCustomScrollbar'><?=$board['body']?></div>

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
														<div  class="like_count_div2"><img src="images/logo_normal.svg" class="like_logo" height="14px">&nbsp;</div>

														<div class="like_count_div tooltip" onclick="parent.like_iframe_start(<?=$board['seq']?>,'content');" title="누가 좋아했는지 보기">
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

														<form id="myForm_<?=$board['seq']?>" name="myForm_<?=$board['seq']?>" action="../comment_write_ajax.php" method="post" accept-charset="utf-8" ENCTYPE="multipart/form-data">
															<img class="profile_img" src="../upload/up_profile/thumbnail/<?=$board['profile']?>" style="background:white;" width="30px" height="30px" onclick="parent.profile_light_iframe_start(<?=$user_seq?>);" title="프로필 보기">
															<input name="comment_input" class="comment_input comment_input_<?=$board['seq']?>" placeholder="댓글을 입력하세요..." maxlength="140" autocomplete="off" required=""
															onkeypress="if (event.keyCode==13){comment_write(<?=$board['seq']?>,1,'light');}">

															<input type="text" style="display: none;">

															<div class="image_add" onclick="image_add(<?=$board['seq']?>,'light')"></div>											

															
															<input type="hidden" class="seq" name="seq" value="<?=$board['seq']?>">
															<input type="hidden" class="user_seq" name="user_seq" value="<?=$user_seq?>">

														</form>
													</div>

														<div class="comment_reload_div comment_reload_div_<?=$board['seq']?>">
														

							<?
							}else{
							?>
													<div class="comment_write">
														<img class="profile_img" src="../upload/up_profile/thumbnail/<?=$board['profile']?>" style="background:white;" width="30px" height="30px">
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

				</div>

			</div>

</div>
					
<? 
	}
?>
					
			

	</body>

</html>
