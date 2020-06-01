<?php

include "../dbcon.php";
include "../domain_security.php";
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
				$board['profile_way'] = $profile_domain_name2.$board['profile'];
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
			var comment_height_number = 487 - $(".light_box_content").height();
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
	var temp_width = $(".text_div").width() - 76 ;
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
					url:"../like_ajax.php",
					data:"seq="+e+"&user_seq="+user_seq+"&status="+status+"&like_check="+like_check+"&disable=1",
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
			url:"../like_status_ajax.php",
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

//comment_ajax 댓글 등록 에이젝스
function comment_write(e,g)
{
	var user_seq = "<?=$user_seq?>";
	var comment_body = $(".comment_input_"+e).val();

	if(comment_body == '')
	{
		alert("내용을 입력해 주세요.");
	}else{
		
		$(".comment_input_"+e).attr('readonly',false);

		$.ajax({
				type:"POST",
				url:"../comment_write_ajax.php",
				data:"seq="+e+"&user_seq="+user_seq+"&body="+comment_body,
				dataType:"json",
				//traditional: true,
				//contentType: "application/x-www-form-urlencoded;charset=utf-8",
				success:function( data ){
					//alert(data);
					if(data == 0)
					{
						alert("등록 실패");
					}else{
						comment_reload(e,g);
						parent.comment_reload(e);

						
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
function comment_reload(e,g,a)
{
	

	$.ajax({
			type:"POST",
			url:"../comment_reload_ajax.php",
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
					var comment_reload_append = '<div class="comment_div comment_div_'+data[i].seq+'">															<div class="comment_list_image"><img class="profile_img" src="'+data[i].uoo_profile_way+'" style="background:white;" width="30px" height="30px" onload="like_status('+data[i].seq+',\'comment\')">	</div><div class="comment_list_body">					<div class="comment_list_body_div">	<div class="comment_body">																<span>'+data[i].uoo_name+'</span>																	'+data[i].body+'</div>	<div class="comment_footer">														<div class="comment_footer_group">																		<div class="comment_date">'+data[i].year+'. '+data[i].month+'. '+data[i].day+'. '+data[i].ampm+' '+data[i].hour+':'+data[i].minute+'</div><div class="comment_like_div">								<span style="float:left;color: #9197a3;">&nbsp;·&nbsp;</span>											<div class="comment_like comment_like_'+data[i].seq+'" title=" 좋아요" onclick="like_ajax('+data[i].seq+',\'comment\');">좋아요</div>																	<div class="comments_like_count_'+data[i].seq+'" style="display:inline;">';
							
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

				$(".comment_reload_div").mCustomScrollbar("scrollTo","bottom",{
					scrollEasing:"easeOut"
				});
				
				//스크롤바 이동
				$(".comment_reload_div .mCustomScrollBox .mCSB_scrollTools").css({'right':'5px'});

				//input 포커스 해제
				$(".comment_input_"+e).blur();

				tooltip_fun();
				
			},
			error:function(e){
				alert("에러");
				//alert( e.responseText );
			}
		});

		//setTimeout(function (){comment_height();},1000);

		
}
</script>

	</head>

	<body style="background: transparent; "onload="parent.profile_iframe_fadein(); light_time_out(0,'<?=$board['photo']?>','con<?=$board['seq']?>','<?=$board['seq']?>');">
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
						<a href="<?=$board['profile_way_original']?>" target="_blank" title="원본 보기">
							<img class='light_box_img' src='<?=$board['profile_way']?>'>
						</a>
					</div>

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

				</div>

			</div>

</div>
					
<? 
	}
?>
					
			

	</body>

</html>
