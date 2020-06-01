<?
	include "analytics.php";
?>

<script>

//전역변수
var login_check="<?=$_SESSION['user_email']?>";
var login_seq_check="<?=$_SESSION['user_seq']?>";
var board_type = "<?=$board_type2?>";
var logout = "<?=$logout?>";

var mobile_check = "<?=$mobile?>";
var get_mobile = "<?=$mobile_check2?>";


var get_date_time = "<?=$get_date?>";
var visit_check = false;

var user_seq = "<?=$user_seq?>";

var url = "<?=$url?>";



//모바일일때 모바일 화면으로 넘기기
if(mobile_check)
{
	var url = $(location).attr('pathname'); //주소	
	var url2 = url.split("/");	

	var full_url = $(location).attr('href'); // ex) http://jamongserver.cafe24.com/jamongchat/board.php?board=free&mobile=

	var full_url2 = full_url.split("/"); //array
	var full_url3 = full_url2[full_url2.length-1]; // ex) board.php?board=free&mobile=

	//경로 지정
	var mobile_link_url ="";
	for(var i = 0 ; i < full_url2.length-1 ; i++)
	{
		mobile_link_url = mobile_link_url +  full_url2[i]+ "/";
	}
	mobile_link_url = mobile_link_url + "mobile/" + full_url3;


	//라이트박스 일때는 무시
	if( url2[2] == "profile_light_iframe" ||  url2[2] == "light_iframe" )
	{
		
	}else{
		//모바일이 pc버전 보고싶을경우
		if(get_mobile)
		{			

		}else{ //모바일로 주소 들어올경우 모바일페이지 자동이동		
			
			//alert(mobile_link_url);
			location.href=mobile_link_url;			

		}		
	}
}


function mobile_link()
{
	var full_url = $(location).attr('href'); // ex) http://jamongserver.cafe24.com/jamongchat/board.php?board=free&mobile=

	var full_url2 = full_url.split("/"); //array
	var full_url3 = full_url2[full_url2.length-1]; // ex) board.php?board=free&mobile=

	//경로 지정
	var mobile_link_url ="";
	for(var i = 0 ; i < full_url2.length-1 ; i++)
	{
		if(full_url2[i] == "jamongchat")
		{
			mobile_link_url = mobile_link_url +  full_url2[i]  + "/mobile/";
		}else{
			mobile_link_url = mobile_link_url +  full_url2[i] + "/";
		}		
	}

	var url = $(location).attr('pathname'); // /jamongchat/board.php

	

	if( url == "/jamongchat/" ||  url =="/jamongchat/index.php" )
	{		
		//모바일버전으로 이동
		location.href="mobile/";
	}else{
		mobile_link_url = mobile_link_url + full_url3 ;
		mobile_link_url = mobile_link_url.replace("&mobile=1", ''); // &mobile=1 문자 제거

		location.href = mobile_link_url;
	}	
	
}

function content_delete(e,user_e)
{
	//삭제버튼 삭제
	$(".btn_delete_"+e).remove();
	//삭제버튼 추가
	if(user_e == login_seq_check || login_check == "admin")
	{
		var delete_btn_append = '<div class="btn_delete btn_delete_'+e+' tooltip" title="글 삭제" onclick="btn_delete_click('+e+');"><img src="images/btn_close_gray.png" width="12px"></div>';
		$(".con"+e+" .content_load").prepend(delete_btn_append);	
		tooltip_fun();
	}
}

function comment_delete(e,user_e)
{
	//삭제버튼 삭제
	$(".btn_comment_delete_"+e).remove();
	//삭제버튼 추가
	if(user_e == login_seq_check || login_check == "admin")
	{
		var comment_delete_btn_append = '<div class="btn_comment_delete btn_comment_delete_'+e+' tooltip" title="댓글 삭제" onclick="btn_comment_delete_click('+e+');"><img src="images/btn_close_gray.png" width="12px"></div>';
		$(".comment_div_"+e).prepend(comment_delete_btn_append);	
	}
}

function btn_delete_click(e)
{
	if (confirm("정말로 삭제 하시겠습니까?")) { 		
		$.ajax({
			type:"POST",
			url:"content_delete_ajax.php",
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
					if($(".content").length <= 1 ) //마지막 게시글을 삭제했을때
					{
						$(".con"+e).hide();	

						var delete_btn_append = '<div class="mouse_over" style="position: relative;									  background-color: #fff;								  width: 600px;									  height:720px;	  padding: 10px;									  margin: 10px auto;									  border: 1px solid;									  border-color: #e9eaed #dfe0e4 #d0d1d5;									  border-radius: 3px;									  -webkit-border-radius: 3px;									  text-align: center;">										<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">										<div class="content_load not_present_div">										<span class="not_present">												게시물이 삭제 되었거나, 존재하지 않습니다. <br>												Posts have been deleted or does not exist.											</span>										 </div>									  </div>';
						$(".content_board").prepend(delete_btn_append);	

						$(".con_loading_img").hide();
						$(".content_load").animate({'opacity':'1.0'},500);				
					}

					$(".hot_issue_"+e).fadeOut(500);

					$(".con"+e).stop().animate({'opacity':'0.0'},300,function(){
						$(".con"+e).stop().animate({'height':'0px','margin': '0px auto','padding':'0px' },1000, 'easeOutExpo' ,function(){
							$(".con"+e).remove();							
						});

					});

					


				}
			},
			error:function(e){
				//alert("에러");
				//alert( e.responseText );
			}
		});

	}else {

	}
	

}

function btn_comment_delete_click(e)
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
					
				}
			},
			error:function(e){
				//alert("에러");
				//alert( e.responseText );
			}
		});

	}else {

	}
	

}

function finish_date(e)
{
	//삭제까지 남은시간 (초)
	var finish_time_second = $(".finish_date_"+e+" .finish_time_second").val();

	var finish_date_day = parseInt(finish_time_second/86400); //일

	var finish_date_hour = parseInt(finish_time_second%86400/3600); //시간

	var finish_date_minute = parseInt(finish_time_second%86400%3600/60); //분

	var finish_date_second = parseInt(finish_time_second%86400%3600%60); //초

	if(finish_date_day >= 1)
	{
		finish_date_day = finish_date_day+"day";

	}else{
		finish_date_day = '';
	}
 
	if(finish_date_hour <10)
	{
		finish_date_hour = "0"+finish_date_hour;
	}
	
	if(finish_date_minute <10)
	{
		finish_date_minute = "0"+finish_date_minute;
	}

	if(finish_date_second <10)
	{
		finish_date_second = "0"+finish_date_second;
	}

	//시간 임박했을때 빨간색 변경
	if (finish_date_day == 0 && finish_date_hour == 0)
	{
		$(".finish_date_"+e).css({'color': 'red'});
	}
	//시간 변경
	$(".content_board .finish_date_"+e+" .finish_day").text(finish_date_day);
	$(".content_board  .finish_date_"+e+" .finish_hour").text(finish_date_hour);
	$(".content_board  .finish_date_"+e+" .finish_minute").text(finish_date_minute);
	$(".content_board  .finish_date_"+e+" .finish_second").text(finish_date_second);
	
	
	if( finish_time_second <= 0)
	{
		$(".hot_issue_"+e).fadeOut(500);
		$(".con"+e).stop().animate({'opacity':'0.0'},300,function(){
			$(".con"+e).stop().animate({'height':'0px','margin': '0px auto','padding':'0px' },1000, 'easeOutExpo' ,function(){
				$(".con"+e).remove();							
			});
		});
	}else{
		setTimeout(function(){
			finish_time_second = $(".finish_date_"+e+" .finish_time_second").val();
			$(".finish_date_"+e+" .finish_time_second").val(finish_time_second-1);
			finish_date(e);
		},1000);
	}	
}

function hot_finish_date(e)
{
	//삭제까지 남은시간 (초)
	var finish_time_second2 = $(".hot_issue_"+e+" .finish_date_"+e+" .finish_time_second").val();

	var finish_date_day2 = parseInt(finish_time_second2/86400); //일

	var finish_date_hour2 = parseInt(finish_time_second2%86400/3600); //시간

	var finish_date_minute2 = parseInt(finish_time_second2%86400%3600/60); //분

	var finish_date_second2 = parseInt(finish_time_second2%86400%3600%60); //초

	if(finish_date_day2 >= 1)
	{
		finish_date_day2 = finish_date_day2+"day";
	}else{
		finish_date_day2 = '';
	}
 
	if(finish_date_hour2 <10)
	{
		finish_date_hour2 = "0"+finish_date_hour2;
	}
	
	if(finish_date_minute2 <10)
	{
		finish_date_minute2 = "0"+finish_date_minute2;
	}

	if(finish_date_second2 <10)
	{
		finish_date_second2 = "0"+finish_date_second2;
	}

	//시간 임박했을때 빨간색 변경
	if (finish_date_day2 == 0 && finish_date_hour2 == 0)
	{
		$(".hot_issue_"+e+" .finish_date_"+e).css({'color': 'red'});
	}
	//시간 변경
	$(".hot_issue_"+e+" .finish_date_"+e+" .finish_day").text(finish_date_day2);
	$(".hot_issue_"+e+" .finish_date_"+e+" .finish_hour").text(finish_date_hour2);
	$(".hot_issue_"+e+" .finish_date_"+e+" .finish_minute").text(finish_date_minute2);
	$(".hot_issue_"+e+" .finish_date_"+e+" .finish_second").text(finish_date_second2);
	
	
	if( finish_time_second2 <= 0)
	{
		$(".hot_issue_"+e).fadeOut(500);
		$(".con"+e).stop().animate({'opacity':'0.0'},300,function(){
			$(".con"+e).stop().animate({'height':'0px','margin': '0px auto','padding':'0px' },1000, 'easeOutExpo' ,function(){
				$(".con"+e).remove();							
			});
		});
	}else{
		setTimeout(function(){
			finish_time_second2 = $(".hot_issue_"+e+" .finish_date_"+e+" .finish_time_second").val();
			$(".hot_issue_"+e+" .finish_date_"+e+" .finish_time_second").val(finish_time_second2-1);
			hot_finish_date(e);
		},1000);
	}	
}

function finish_effect(e,a)
{
		

	if( a == 1 ) //시간 연장
	{
		//모바일
		if(mobile_check){
			}else{

			$("body, html").animate({scrollTop:$(".con_img_"+e).offset().top + $(".con_img_"+e).height()/2- $(window).height()/2 - $(".top").height() },100 , 'easeOutExpo',function(){
			});


		}	


		$(".finish_date_"+e).css({'color':'red'});
		var temp_hour = parseInt($(".finish_date_"+e+" .finish_hour").text());
		$(".finish_date_"+e+" .finish_hour").text().replace(temp_hour,temp_hour+1);
		$(".finish_date_"+e+" .finish_time_second").val(parseInt($(".finish_date_"+e+" .finish_time_second").val())+3600);
		$(".finish_date_"+e).stop().animate({'font-size':'18px'},10, 'easeOutExpo',function(){
			$(".finish_date_"+e).animate({'font-size':'10px'},300, 'easeOutExpo');
		});
		
		//이펙트
		$(".finish_effect").remove();
		var finish_effect = "<div class='finish_effect'><img src='images/logo_normal.svg' style='display:inline;' class='like_logo' height='14px'><br>+1 HOUR</div>";
		$(".con"+e).append(finish_effect);
	
		//이펙트 좌표
		var like_y = $(".content_like_"+e).position().top-14;
		var like_x = $(".content_like_"+e).position().left+10;
		var finish_date_y = $(".finish_date_"+e).position().top+25;
		var finish_date_x = $(".con"+e+" .con_number").position().left + $(".con"+e+" .con_number").width() - 57;

		$(".con"+e+" .finish_effect").css({'top':like_y,'left':like_x,'color':'red'});

		$(".con"+e+" .finish_effect").animate({'top':'-=20px'},500, 'easeOutExpo', function(){
			$(".con"+e+" .finish_effect").animate({'top':finish_date_y,'left':finish_date_x},1500, 'easeOutExpo',function(){
				$(".con"+e+" .finish_effect").animate({'opacity':'0'},500,function(){
					$(".finish_date_"+e).css({'color':'#9197a3'});
				});
			});
		});
	}else if (a == 2)
	{//시간 감소

		 //모바일
		if(mobile_check){
			
		
			}else{
		
			$("body, html").animate({scrollTop:$(".con_img_"+e).offset().top + $(".con_img_"+e).height()/2- $(window).height()/2 - $(".top").height() },100 , 'easeOutExpo',function(){
			});
		
		}	
			

		$(".finish_date_"+e).css({'color':'blue'});
		if($(".finish_date_"+e+" .finish_hour").text() != 0 )
		{
			var temp_hour = parseInt($(".finish_date_"+e+" .finish_hour").text());
			$(".finish_date_"+e+" .finish_hour").text().replace(temp_hour,temp_hour-1);
		}
		$(".finish_date_"+e+" .finish_time_second").val(parseInt($(".finish_date_"+e+" .finish_time_second").val())-3600);

		$(".finish_date_"+e).stop().animate({'font-size':'18px'},10, 'easeOutExpo',function(){
			$(".finish_date_"+e).animate({'font-size':'10px'},300, 'easeOutExpo');
		});

		//이펙트
		$(".finish_effect").remove();
		var finish_effect = "<div class='finish_effect'>-1 HOUR</div>";
		$(".con"+e).append(finish_effect);

		//이펙트 좌표
		var like_y = $(".content_like_"+e).position().top;
		var like_x = $(".content_like_"+e).position().left+10;
		var finish_date_y = $(".finish_date_"+e).position().top+25;
		var finish_date_x = $(".con"+e+" .con_number").position().left + $(".con"+e+" .con_number").width() - 54;

		$(".con"+e+" .finish_effect").css({'top':like_y,'left':like_x,'color':'blue'});

		$(".con"+e+" .finish_effect").animate({'top':'-=20px'},500, 'easeOutExpo', function(){
			$(".con"+e+" .finish_effect").animate({'top':finish_date_y,'left':finish_date_x},1500, 'easeOutExpo',function(){
				$(".con"+e+" .finish_effect").animate({'opacity':'0'},500,function(){
					$(".finish_date_"+e).css({'color':'#9197a3'});
				});
			});
		});
	}else if( a == 3 ) //시간 연장
	{
		$(".finish_date_"+e).css({'color':'red'});
		var temp_hour = parseInt($(".finish_date_"+e+" .finish_hour").text());
		$(".finish_date_"+e+" .finish_hour").text().replace(temp_hour,temp_hour+1);
		$(".finish_date_"+e+" .finish_time_second").val(parseInt($(".finish_date_"+e+" .finish_time_second").val())+3600);
		$(".finish_date_"+e).stop().animate({'font-size':'18px'},10, 'easeOutExpo',function(){
			$(".finish_date_"+e).animate({'font-size':'10px'},300, 'easeOutExpo');
		});
		
		//이펙트
		$(".finish_effect").remove();
		var finish_effect = "<div class='finish_effect'><img src='images/logo_normal.svg' style='display:inline;' class='like_logo' height='14px'><br>+1 HOUR</div>";
		$(".con"+e).append(finish_effect);
	
		//이펙트 좌표
		var like_y = $(".content_like_"+e).position().top-14;
		var like_x = $(".content_like_"+e).position().left;
		var finish_date_y = $(".finish_date_"+e).position().top+14;
		var finish_date_x = $(".con"+e+" .con_number").position().left + $(".con"+e+" .con_number").width() - 57;

		$(".con"+e+" .finish_effect").css({'top':like_y,'left':like_x,'color':'red'});

		$(".con"+e+" .finish_effect").animate({'top':'-=20px'},500, 'easeOutExpo', function(){
			$(".con"+e+" .finish_effect").animate({'top':finish_date_y,'left':finish_date_x},1500, 'easeOutExpo',function(){
				$(".con"+e+" .finish_effect").animate({'opacity':'0'},500,function(){
					$(".finish_date_"+e).css({'color':'#9197a3'});
				});
			});
		});
	}else if (a == 4)
	{//시간 감소
		$(".finish_date_"+e).css({'color':'blue'});
		if($(".finish_date_"+e+" .finish_hour").text() != 0 )
		{
			var temp_hour = parseInt($(".finish_date_"+e+" .finish_hour").text());
			$(".finish_date_"+e+" .finish_hour").text().replace(temp_hour,temp_hour-1);
		}
		$(".finish_date_"+e+" .finish_time_second").val(parseInt($(".finish_date_"+e+" .finish_time_second").val())-3600);

		$(".finish_date_"+e).stop().animate({'font-size':'18px'},10, 'easeOutExpo',function(){
			$(".finish_date_"+e).animate({'font-size':'10px'},300, 'easeOutExpo');
		});

		//이펙트
		$(".finish_effect").remove();
		var finish_effect = "<div class='finish_effect'>-1 HOUR</div>";
		$(".con"+e).append(finish_effect);

		//이펙트 좌표
		var like_y = $(".content_like_"+e).position().top;
		var like_x = $(".content_like_"+e).position().left;
		var finish_date_y = $(".finish_date_"+e).position().top+14;
		var finish_date_x = $(".con"+e+" .con_number").position().left + $(".con"+e+" .con_number").width() - 54;

		$(".con"+e+" .finish_effect").css({'top':like_y,'left':like_x,'color':'blue'});

		$(".con"+e+" .finish_effect").animate({'top':'-=20px'},500, 'easeOutExpo', function(){
			$(".con"+e+" .finish_effect").animate({'top':finish_date_y,'left':finish_date_x},1500, 'easeOutExpo',function(){
				$(".con"+e+" .finish_effect").animate({'opacity':'0'},500,function(){
					$(".finish_date_"+e).css({'color':'#9197a3'});
				});
			});
		});
	}
}


function tooltip_fun()
{
/*
	$( document ).tooltip({
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


function top_setting()
{
			 //모바일
			if(mobile_check){
				
			
				}else{
			
					if( $(window).width() < $(".all-wrap").width() || $(window).height() < 400 )
					{
						
						if( light_box_chk == 1 || like_light_box_chk == 1)
						{
							$(".top3").show();
						}else{
							$(".top").css({'position':'relative','opacity':'1.0'});
							$(".top2").hide();
							$(".top3").hide();
						}
						
					}else{						
						$(".top").css({'position':'fixed','opacity':'1.0'});
						$(".top2").show();
						$(".top3").hide();
					}
			
			}	
			
}

function section_fixed()
{
	if($(".hot_issue").html()) //핫이슈가 있을경우만 실행
	{

		var toppx = $(this).scrollTop();

		//모바일
		if(mobile_check){
			
		
			}else{
		
			if( $(window).width() < $(".all-wrap").width() || $(window).height() < 400 )
			{
				$(".section_left").css({'position':'relative'});
				$(".section_left2").css({'position':'absolute'});

				$(".hot_issue").css({'position':'relative','left':'0px','top':'0px'});
				$(".hot_issue2").css({'position':'absolute','display':'none'});
			}else{

				$(".section_left").css({'position':'fixed'});
				$(".section_left2").css({'position':'relative'});
				
				
					
				$(".hot_issue").css({'position':'fixed','left':$(".content_board").offset().left + $(".content_board").width() ,'top':$(".section_left").offset().top -10 });
				$(".hot_issue2").css({'position':'relative','display':'block'});
				
				
			}
		
		}
		
	}	
}

var light_box_chk= 0;
var like_light_box_chk= 0;
	//글쓰기창 열기
	function write_start(e)
	{

		light_box_chk= 1;
		top_setting();
		$(".loading_img").fadeIn(100);

		//광클 방지
		var prepend_speed_click = "<div class='speed_click' style='position:fixed; width:100%; height:100%; z-index:99999999999999;'></div>";
		$("body").prepend(prepend_speed_click);

		//모바일
		if(mobile_check){
		
			var toppx = $(window).scrollTop() + $(window).height()/2 - 350;
		
			}else{
		
			var toppx = $(window).scrollTop();
		
		}	
		
		

		var prepend_body = "<div class='iframe_div' style='position:absolute; z-index:999999;top:0; left:0; width:100%; height:100%; display: none; '><iframe src='"+url+"/write_iframe/write_iframe.php?seq="+e+"&toppx="+toppx+"&board="+board_type+"' class='light_iframe' allowtransparency='true' frameborder='0' width='100%' height='100%' style='position:absolute; z-index:999999;  top:0; left:0;'></iframe></div>";

		$("body").prepend(prepend_body);

		$("body").css({'overflow':'hidden'});

		setTimeout(function(){
			$(".speed_click").remove();
		},300);

		

				//모바일
				if(mobile_check){
			
					$(".iframe_div").css({'height':$(document).height() });
				
					}else{
				
					$(".iframe_div").css({'top':toppx });
				
					}	
					
		
		section_fixed();
	
	}



	//프로필변경창 열기
	function profile_start(e)
	{

		light_box_chk= 1;
		top_setting();
		$(".loading_img").fadeIn(100);

		//광클 방지
		var prepend_speed_click = "<div class='speed_click' style='position:fixed; width:100%; height:100%; z-index:99999999999999;'></div>";
		$("body").prepend(prepend_speed_click);

		//모바일
		if(mobile_check){
		
			var toppx = $(window).scrollTop() + $(window).height()/2 - 350;
		
			}else{
		
			var toppx = $(window).scrollTop();
		
		}	
			

		

		var prepend_body = "<div class='iframe_div' style='position:absolute; z-index:999999; top:0; left:0; width:100%; height:100%; display: none; '><iframe src='"+url+"/profile_iframe/profile_iframe.php?seq="+e+"&toppx="+toppx+"' class='light_iframe' allowtransparency='true' frameborder='0' width='100%' height='100%' style='position:absolute; z-index:999999;  top:0; left:0;'></iframe></div>";

		$("body").prepend(prepend_body);

		$("body").css({'overflow':'hidden'});

		setTimeout(function(){
			$(".speed_click").remove();
		},300);

		

				//모바일
				if(mobile_check){
				
					$(".iframe_div").css({'height':$(document).height() });
				
					}else{
				
					$(".iframe_div").css({'top':toppx });
				
				}	
				
		
		section_fixed();
	
	}

			

	function light_iframe_start(e)
	{
		light_box_chk= 1;
		top_setting();
		$("body").css({'overflow':'hidden'});

		//광클 방지
		var prepend_speed_click = "<div class='speed_click' style='position:fixed; width:100%; height:100%; z-index:99999999999999;'></div>";

		$("body").prepend(prepend_speed_click);

		$(".loading_img").fadeIn(100);

		//모바일
		if(mobile_check){
		
			
		
			}else{
		
			$("body, html").animate({scrollTop:$(".con_img_"+e).offset().top + $(".con_img_"+e).height()/2- $(window).height()/2 - $(".top").height() },500 , 'easeOutExpo',function(){
			});
		
		}	
		
		
		
		setTimeout(function(){
		
		
		 //모바일
		if(mobile_check){
		
			var toppx = $(window).scrollTop() + $(window).height()/2 - 350;
		
			}else{
		
			var toppx = $(window).scrollTop();
		
		}	
		
		

		var prepend_body = "<div class='iframe_div' style='position:absolute; z-index:999999; top:0; left:0; width:100%; height:100%; display: none; '><iframe src='"+url+"light_iframe/light_box_iframe.php?seq="+e+"&toppx="+toppx+"' class='light_iframe' allowtransparency='true' frameborder='0' width='100%' height='100%' style='position:absolute; z-index:999999;  top:0; left:0;'></iframe></div>";

		$("body").prepend(prepend_body);
		
		
		$(".speed_click").remove();


				//모바일
				if(mobile_check){
				
					$(".iframe_div").css({'height':$(document).height() });
				
					}else{
				
					$(".iframe_div").css({'top':toppx });
				
				}	
					
		},300);

		section_fixed();
	}

	//프로필 사진 보기
	function profile_light_iframe_start(e)
	{
		like_light_box_chk= 1;
		top_setting();
		$("body").css({'overflow':'hidden'});

		//광클 방지
		var prepend_speed_click = "<div class='speed_click' style='position:fixed; width:100%; height:100%; z-index:99999999999999;'></div>";

		$("body").prepend(prepend_speed_click);

		$(".loading_img").fadeIn(100);
		
		setTimeout(function(){
		
		
		//모바일
		if(mobile_check){
		
			var toppx = $(window).scrollTop() + $(window).height()/2 - 350;
		
			}else{
		
			var toppx = $(window).scrollTop();
		
		}	
			
		

		var prepend_body = "<div class='iframe_div_profile' style='position:absolute; z-index:9999999; top:0; left:0; width:100%; height:100%; display: none; '><iframe src='"+url+"profile_light_iframe/profile_light_iframe.php?seq="+e+"&toppx="+toppx+"' class='light_iframe' allowtransparency='true' frameborder='0' width='100%' height='100%' style='position:absolute; z-index:999999;  top:0; left:0;'></iframe></div>";

		$("body").prepend(prepend_body);
		
		
		setTimeout(function(){
			$(".speed_click").remove();
		},300);


				 //모바일
				if(mobile_check){
				
					$(".iframe_div_profile").css({'height':$(document).height() });
				
					}else{
				
					$(".iframe_div_profile").css({'top':toppx });
				
				}	
				
		},300);

		section_fixed();
	}


	function like_iframe_start(e,status)
	{
		like_light_box_chk= 1;
		top_setting();
		$(".loading_img").fadeIn(100);

		//광클 방지
		var prepend_speed_click = "<div class='speed_click' style='position:fixed; width:100%; height:100%; z-index:99999999999999;'></div>";

		$("body").prepend(prepend_speed_click);

		//모바일
		if(mobile_check){
		
			var toppx = $(window).scrollTop() + $(window).height()/2 - 350;
		
			}else{
		
			var toppx = $(window).scrollTop();
		
		}	
		
		

		var prepend_body = "<div class='iframe_div_like' style='position:absolute; z-index:9999999; top:0; left:0; width:100%; height:100%; display: none; '><iframe src='"+url+"like_iframe/like_iframe.php?seq="+e+"&toppx="+toppx+"&status="+status+"' class='light_iframe' allowtransparency='true' frameborder='0' width='100%' height='100%' style='position:absolute; z-index:999999;  top:0; left:0;'></iframe></div>";

		$("body").prepend(prepend_body);

		$("body").css({'overflow':'hidden'});

		setTimeout(function(){
			$(".speed_click").remove();
		},300);


				 //모바일
				if(mobile_check){
				
					$(".iframe_div_like").css({'height':$(document).height() });
				
					}else{
				
					$(".iframe_div_like").css({'top':toppx });
				
				}	
				

		section_fixed();
	
	}


	function light_iframe_fadein()
	{
		$(".loading_img").hide();

		$(".iframe_div").delay( 50 ).fadeIn(300);
	}

	function like_iframe_fadein()
	{
		$(".loading_img").hide();

		$(".iframe_div_like").delay( 50 ).fadeIn(300);
	}

	function profile_iframe_fadein()
	{
		$(".loading_img").hide();

		$(".iframe_div_profile").delay( 50 ).fadeIn(300);
	}

	function light_iframe_end()
	{
		light_box_chk= 0;
		top_setting();
		$("body").css({'overflow':'auto'});

		$(".iframe_div").fadeOut(300,function(){
			$('.iframe_div').remove();
		});

		section_fixed();
	}


	function light_iframe_end_write()
	{
		light_box_chk= 0;
		top_setting();
		$("body").css({'overflow':'auto'});

		$(".iframe_div").fadeOut(300,function(){
			$('.iframe_div').remove();
			location.reload();
			//location.reload("http://jamongserver.cafe24.com/jamongchat/");
		});		
		section_fixed();
	}

	
	function like_iframe_end()
	{
	
		like_light_box_chk = 0;
		top_setting();

		if( light_box_chk == 1 )
		{
			
		}else{
			$("body").css({'overflow':'auto'});
		}

		$(".iframe_div_like").fadeOut(300,function(){
			$('.iframe_div_like').remove();
		});

		section_fixed();
	}

	function profile_iframe_end()
	{
	
		like_light_box_chk = 0;
		top_setting();

		if( light_box_chk == 1 )
		{
			
		}else{
			$("body").css({'overflow':'auto'});
		}

		$(".iframe_div_profile").fadeOut(300,function(){
			$('.iframe_div_profile').remove();
		});

		section_fixed();
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



	function login()
	{

		if($(".login_id").val() == '')
		{
			alert("계정(이메일)을 입력해 주세요.");
			$(".login_id").focus();
		}else if(chkEmail($(".login_id").val()) == false && $(".login_id").val() != 'admin')
		{
			alert("이메일 형식이 맞지 않습니다.");
			$(".login_id").focus();
		}else if( $(".login_password").val() == '')
		{
			alert("비밀번호를 입력해 주세요.");
			$(".login_password").focus();
		}else{

/*
			if($("#idsave_id").is(":checked") == false && $("#pwsave_id").is(":checked") == true)
			{
				//아이디 체크 안하고 비밀번호에 체크했을경우 쿠키저장 방지
				setCookie("id", document.login_form.login_id.value, 0); //날짜를 0으로 저장하여 쿠키삭제
				setCookie("pw", document.login_form.login_password.value, 0); //날짜를 0으로 저장하여 쿠키삭제
			}else{
				//id 저장
				if (document.login_form.idsave.checked == true) { // 아이디 저장을 체크 하였을때
					setCookie("id", document.login_form.login_id.value, 90); //쿠키이름을 id로 아이디입력필드값을 90일동안 저장
				} else { // 아이디 저장을 체크 하지 않았을때
					setCookie("id", document.login_form.login_id.value, 0); //날짜를 0으로 저장하여 쿠키삭제
				}
				//pw 저장
				if (document.login_form.pwsave.checked == true) { // 아이디 저장을 체크 하였을때
					var pass = SHA256($(".login_password").val());
					var pass2 = $(".login_password").val();
					setCookie("pw", pass2, 30); //쿠키이름을 id로 아이디입력필드값을 90일동안 저장
				} else { // 비밀번호 저장을 체크 하지 않았을때
					var pass = SHA256($(".login_password").val());
					setCookie("pw", pass, 0); //날짜를 0으로 저장하여 쿠키삭제
				}

			}
*/

			$(".password_hidden").val(SHA256($(".login_password").val()));
			

			var login_id = $(".login_id").val();
			var login_password = $(".password_hidden").val();
			
			//$(".loading_img").show();

			$.ajax({
				type:"POST",
				url:"login_ajax.php",
				data:"login_id="+login_id+"&password_hidden="+login_password,
				dataType:"json",
				//traditional: true,
				//contentType: "application/x-www-form-urlencoded;charset=utf-8",
				success:function( data ){
					//alert(data);

					//$(".loading_img").hide();

					if(data == 1)
					{
						//성공
						//location.href = $(location).attr('pathname'); //index.php
						if(logout == 1)
						{
							location.href = "/jamongchat/?mobile="+get_mobile;
						}else{
							location.reload();
						}
						//수정해야함
					}else if( data == 2)
					{
						alert("비밀번호가 틀립니다.");
						$(".login_password").val('')
						$(".login_password").focus();
					}else if( data == 0)
					{
						alert("해당 계정이 없습니다.");
						$(".login_id").focus();
					}

					
				},
				error:function(e){
					//alert("에러");
					//alert( e.responseText );
				}
			});

		}

	}

var checkbox_change = false;
//로그인 쿠키 체크박스
function login_checkbox(e)
{
	checkbox_change == true;

	if(e == 'id')
	{
		if( $("#idsave_id").is(":checked") == false && $("#pwsave_id").is(":checked") == true )
		{
			$("#pwsave_id").attr("checked", false);
		}
	}

	if(e == 'pw')
	{
		//pw 저장
		if (document.login_form.pwsave.checked == false) { // 비밀번호 저장을 체크해제 하였을때
			setCookie("pw", document.login_form.login_password.value, 0); //날짜를 0으로 저장하여 쿠키삭제
		}
		
		if($("#pwsave_id").is(":checked") == true)
		{
			if (confirm("자동 로그인 기능을 사용하시겠습니까?\n\n자동 로그인 사용시 다음 접속부터는 로그인을 하실필요가 없습니다.\n\n단, 게임방, 학교등 공공장소에서 이용시 개인정보가 유출될수 있으니 주의해주세요")) { 		
				$("#idsave_id").attr("checked", true);
			}else {
				$("#pwsave_id").attr("checked", false);
			}
		}

	}	

}


//콘텐츠 텍스트 더 보기 에이젝스
function content_text_more(e)
{
	$.ajax({
			type:"POST",
			url:"content_text_more_ajax.php",
			data:"seq="+e,
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert(data);
				//alert(data[0].body);
				$(".con"+e+" .content_load .text").empty();
				var content_text = data[0].body;
				$(".con"+e+" .content_load .text").append(content_text);
			},
			error:function(e){
				//alert("에러");
				//alert( e.responseText );
			}
		});
}

//네비게이트 설정
function navigate()
{
	var url = $(location).attr('pathname');
	var search = $(location).attr('search');

	//카테고리 색깔
	if(board_type == "free")
	{
		$(".free_board").css({'color':'#fff !important','background':'rgb(245,130,32)'});
	}else if(board_type == "fashion")
	{
		$(".fashion_board").css({'color':'#fff !important','background':'rgb(245,130,32)'});
	}else if(board_type == "selfie")
	{
		$(".selfie_board").css({'color':'#fff !important','background':'rgb(245,130,32)'});
	}else if(board_type == "food")
	{
		$(".food_board").css({'color':'#fff !important','background':'rgb(245,130,32)'});
	}else if(board_type == "photo")
	{
		$(".photo_board").css({'color':'#fff !important','background':'rgb(245,130,32)'});
	}else{
		$(".navigate span").text("자몽챗 >> 전체 검색");
	}

	

	//네비게이터
	switch(url)
	{
		case "/jamongchat/":$(".navigate span").text("자몽챗 > 홈");
				$("#search").attr("disabled",true);
				$(".btn_search").attr("onclick","");
			break;
		case "/jamongchat/index.php":$(".navigate span").text("자몽챗 > 홈");
				$("#search").attr("disabled",true);
				$(".btn_search").attr("onclick","");
			break;
		case "/jamongchat/board.php": 			
				if( board_type == 'free' )
				{
					$(".navigate span").text("자몽챗 > 자유게시판");					
				}else if(board_type == 'fashion' )
				{
					$(".navigate span").text("자몽챗 > 패션");	
				}else if(board_type == 'selfie' )
				{
					$(".navigate span").text("자몽챗 > 셀피");	
				}else if(board_type == 'food' )
				{
					$(".navigate span").text("자몽챗 > 음식");	
				}else if(board_type == 'photo' )
				{
					$(".navigate span").text("자몽챗 > 사진");	
				}

			break;		
		case "/jamongchat/detail_content.php":
				if( board_type == 'free' )
				{
					$(".navigate span").text("자몽챗 > 자유게시판 > 자세히 보기");				
				}else if(board_type == 'fashion' )
				{
					$(".navigate span").text("자몽챗 > 패션 > 자세히 보기");
				}else if(board_type == 'selfie' )
				{
					$(".navigate span").text("자몽챗 > 셀피  > 자세히 보기");	
				}else if(board_type == 'food' )
				{
					$(".navigate span").text("자몽챗 > 음식  > 자세히 보기");	
				}else if(board_type == 'photo' )
				{
					$(".navigate span").text("자몽챗 > 사진  > 자세히 보기");	
				}
			
		break;
		case "/jamongchat/hot_content.php":
				if( board_type == 'free' )
				{
					$(".navigate span").text("자몽챗 > 자유게시판 > 핫이슈");				
				}else if(board_type == 'fashion' )
				{
					$(".navigate span").text("자몽챗 > 패션 > 핫이슈");
				}else if(board_type == 'selfie' )
				{
					$(".navigate span").text("자몽챗 > 셀피  > 핫이슈");	
				}else if(board_type == 'food' )
				{
					$(".navigate span").text("자몽챗 > 음식  > 핫이슈");	
				}else if(board_type == 'photo' )
				{
					$(".navigate span").text("자몽챗 > 사진  > 핫이슈");	
				}

		break;
		case "/jamongchat/search_content.php":
			
				if( board_type == 'free' )
				{
					$(".navigate span").text("자몽챗 > 자유게시판 > 검색");				
				}else if(board_type == 'fashion' )
				{
					$(".navigate span").text("자몽챗 > 패션 > 검색");
				}else if(board_type == 'selfie' )
				{
					$(".navigate span").text("자몽챗 > 셀피  > 검색");	
				}else if(board_type == 'food' )
				{
					$(".navigate span").text("자몽챗 > 음식  > 검색");	
				}else if(board_type == 'photo' )
				{
					$(".navigate span").text("자몽챗 > 사진  > 검색");	
				}
				
		break;

		case "/jamongchat/sign_up.php":

				$("#search").attr("disabled",true);
				$(".btn_search").attr("onclick","");			
				$(".navigate span").text("자몽챗 >> 회원가입");	
				
		break;

		default: $(".navigate span").text("JamongChat");
			break;
	}

}



function visit_count_insert()
{	
	var get_date = get_date_time.split(" "); //오늘 날짜

	//setCookie("today", get_date[0], 0); //쿠키삭제
	//setCookie("ip", get_ip, 0); //쿠키삭제
	if(getCookie("today2") != get_date[0]) //쿠키값이랑 다를경우만 저장
	{
		visit_check = true;
		$.ajax({
				type:"POST",
				url:"visit_count_insert.php",
				//data:"",
				dataType:"json",
				//traditional: true,
				//contentType: "application/x-www-form-urlencoded;charset=utf-8",
				success:function( data ){
					//alert(data);
					
					if(data == 1)
					{	
						setCookie("today2", get_date[0], 0); //삭제
						setCookie("today2", get_date[0], 1); //등록
						visit_count_select();
					}

				},
				error:function(e){
					//alert("에러");
					//alert( e.responseText );
				}
			});		
	}

	if (visit_check == false)
	{
		visit_count_select();
	}

	
}

function visit_count_select()
{
	$.ajax({
			type:"POST",
			url:"visit_count_select.php",
			//data:"",
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert(data);

				//alert(data.total);

				$(".today_count").text(data.today);
				$(".total_count").text(data.total);

				$(".visit_count").fadeIn(300);

			},
			error:function(e){
				//alert("에러");
				//alert( e.responseText );
			}
		});
}



//자세히 보기 링크 이동
function detail_content_link(e)
{
	location.href = "detail_content.php?seq="+e+"&board=board_type";
}

//검색 링크 이동
function search_content_link()
{
	var search_keyword = $.trim( $(".search").val() ); //공백제거
	if(search_keyword == '')
	{	
		alert("검색 내용을 입력해 주세요.");
		$(".search").focus();
	}else if(search_keyword.length < 2)
	{
		alert("두 글자 이상 입력해 주세요.");
		$(".search").focus();
	}else{
		location.href = "search_content.php?search="+search_keyword+"&board="+board_type+"&mobile="+get_mobile;
	}
}


function setCookie(name, value, expiredays) //쿠키 저장함수
{
	var todayDate = new Date();
	todayDate.setDate(todayDate.getDate() + expiredays);
	document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";";
	//document.cookie = name + "=" + escape(value) + "; path=/;";
}

function getCookie(Name) { // 쿠키 불러오는 함수
	var search = Name + "=";
	if (document.cookie.length > 0) { // if there are any cookies
		offset = document.cookie.indexOf(search);
		if (offset != -1) { // if cookie exists
			offset += search.length; // set index of beginning of value
			end = document.cookie.indexOf(";", offset); // set index of end of cookie value
			if (end == -1)
				end = document.cookie.length;
			return unescape(document.cookie.substring(offset, end));
		}
	}
}

// 정규식 : 이메일
function chkEmail(str)
{
var reg_email = /^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
if(!reg_email.test(str))
{
return false;
}
return true;
}

//문자열의 태그 제거 함수
function strip_tags(value){

     var startTag = /<([a-z][a-z0-9]*)\b[^>]*>/gmi;
     var endTag = /<\/([a-z][a-z0-9]*)\b[^>]*>/gmi;

     var stripped = value.replace(startTag, '').replace(endTag, '');

     return stripped;

};

var comment_check = false;
//comment_ajax 댓글 등록 에이젝스
function comment_write(e,g,light)
{

	
	var comment_body = $(".comment_input_"+e).val();
	
	if(comment_body == '')
	{
		alert("내용을 입력해 주세요.");
		$(".comment_input_"+e).focus();
	}else{ //등록이 완료된후에만 가능

		$(".comment_input_"+e).attr('readonly',false);
				

		//에이젝스폼 플러그인
		$('#myForm_'+e).ajaxForm({
			beforeSubmit: function (data,form,option) {
				//validation체크 
				//막기위해서는 return false를 잡아주면됨
				//alert('서브밋 직전입니다!');
				//return true;

				//광클방지
				$(".comment_input_"+e).attr('disabled',true);
				$(".comment_input_"+e).attr('readonly',false);
				$(".comment_submit").hide();
				$(".file_label_click").hide();
				$(".file_label").hide();
				$("#chosenFile").hide()

				var comment_loading_append = '<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="z-index: 999;position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">';

				$(".file_div").append(comment_loading_append);

					
					
				
			},
			success: function(response,textStatus){
				if (textStatus == "success")
						{
							//광클방지
							$(".comment_input_"+e).attr('disabled',false);
							comment_check = false;

							if(response == 0)
							{
								alert("등록 실패");
							}else if(response == 1)
							{
								alert("jpg, png, bmp, gif (이미지) 파일만 업로드 할수 있습니다.");
							}else if (response == 2)
							{
								alert("첨부파일 용량 제한을 초과하였습니다. ( 10MB )");
							}else if (response == 3)
							{
								alert("html, htm, php, inc 업로드 금지 파일입니다.");
							}else if (response == 4)
							{
								alert("동일 파일명이 있습니다. 파일명을 변경해주세요.");
							}else if (response == 5)
							{
								alert("파일 업로드 실패");
							}else if (response == 6)
							{
									alert("업로드 실패");
							}else if (response == 7)
							{
									//alert("업로드 성공");
									$(".file_div").remove();
									

									if(light == "light")
									{
										comment_reload2(e,g);
										parent.comment_reload(e,g);
									}else{
										comment_reload(e,g);
									}

									

							}else{
								alert(response);
							}							

							
						}

						console.log(response, textStatus);
			},
			error: function(e){
				$(".comment_input_"+e).attr('disabled',false);
				comment_check = false;
				//alert(e);
				//에러발생을 위한 code페이지
			} 
		});
		
		if(comment_check == false)
		{
			//광클방지
			$('#myForm_'+e).submit();
			comment_check = true;
		}
		
	}
		
}
function comment_img(seq,user_seq,photo_way,photo,light){
	if(photo_way != '')
	{
		var comment_img_append = '<img class="comment_img comment_img_'+seq+'" src="'+photo_way+'thumbnail/'+photo+'">';
		$(".comment_div_"+seq+" .comment_body").append(comment_img_append);
	
	}

}

//댓글 파일추가 생성
function image_add(e,light)
{
	$(".file_div").remove();

	
	//light_box 댓글창 높이조절
	if(light == 'light')
	{
		var comment_file_append = '<div class="file_div">																<img id="img_preview" width="96px" height="96px" style="z-index:3; display:none;">																<label class="file_label_click" for="chosenFile"></label>																<label class="file_label" for="chosenFile">+</label>															<input type="file" id="chosenFile" name="chosenFile" onchange="comment_file_change();">											<div class="comment_submit" onclick="comment_write('+e+',1,\'light\');">등록</div>			</div>';
		$("#myForm_"+e).append(comment_file_append);
	}else{
		var comment_file_append = '<div class="file_div">																<img id="img_preview" width="96px" height="96px" style="z-index:3; display:none;">																<label class="file_label_click" for="chosenFile"></label>																<label class="file_label" for="chosenFile">+</label>															<input type="file" id="chosenFile" name="chosenFile" onchange="comment_file_change();">											<div class="comment_submit" onclick="comment_write('+e+',1);">등록</div>			</div>';
	$("#myForm_"+e).append(comment_file_append);
	}

	//light_box 댓글창 높이조절
	if(light == 'light')
	{
		//댓글창 높이조절
		var comment_height_number = 487 - $(".light_box_content").height() - $(".file_div").height();
		$(".comment_reload_div").css({'height':comment_height_number});
	}

	//파일 사진 업로드 미리보기
	var opt = {
		img: $('#img_preview'),
		w: 96,
		h: 96
	};

	$('#chosenFile').setPreview(opt);	

	$("#myForm_"+e+" .file_label").trigger("click");
}


//댓글 파일 추가
var bug_index = false;
function comment_file_change()
{
	$("#img_preview").attr('src','');
	//$(".file_status").text('');
	//$(".file_size").text('');

	if( $('#chosenFile').val() != '' )
	{

		//$(".file_label").css({'opacity':'0.5','filter':'alpha(opacity=50)'});

		var file_way = $('#chosenFile').val().split("\\"); // dlsnf.png ex)file_way[2]

		var file_name = $('#chosenFile')[0].files[0].name; // dlsnf.png 

		var file_name_arr = file_way[2].split("."); // dlsnf png

		//var file_name = file_name_arr[0]; //dlsnf

		var file_type = file_name_arr[file_name_arr.length - 1].toLowerCase(); // png 소문자 변환

		var file_size = $('#chosenFile')[0].files[0].size/1024/1000;
		file_size = file_size.toFixed(2);

		//alert(file_name);

		//alert( file_name );
		//alert( file_type );

	

		if( file_type == 'jpg' ||  file_type == 'jpeg' ||  file_type == 'png' ||  file_type == 'bmp'||  file_type == 'gif' )
		{
			bug_index = true;	

			//alert("이미지 파일입니다.");

			$("#img_preview").css({'display':'block'});

		}else{
			bug_index = false;

			$("#img_preview").css({'display':'none'}); $('#chosenFile').val('');

			alert("jpg, png, bmp, gif (이미지) 파일만 업로드 할수 있습니다.");
					
		}

		if(file_size > 10 ){

			bug_index = false;

			$("#img_preview").css({'display':'none'}); $('#chosenFile').val('');


			alert("첨부파일 용량 제한을 초과하였습니다. ( 10MB )");
		}
	
	}

}


$.fn.setPreview = function(opt){
    "use strict"
    var defaultOpt = {
        inputFile: $(this),
        img: null,
        w: 200,
        h: 200
    };
    $.extend(defaultOpt, opt);
 
    var previewImage = function(){
        if (!defaultOpt.inputFile || !defaultOpt.img) return;
 
        var inputFile = defaultOpt.inputFile.get(0);
        var img       = defaultOpt.img.get(0);
 
        // FileReader
        if (window.FileReader) {
            // image 파일만
            if (!inputFile.files[0].type.match(/image\//)) return;
 
            // preview
            try {
                var reader = new FileReader();
                reader.onload = function(e){
                    img.src = e.target.result;
                    img.style.width  = defaultOpt.w+'px';
                    img.style.height = defaultOpt.h+'px';
                    img.style.display = '';
                }
                reader.readAsDataURL(inputFile.files[0]);
            } catch (e) {
                // exception...
            }
        // img.filters (MSIE)
        } else if (img.filters) {
            inputFile.select();
            inputFile.blur();
            var imgSrc = document.selection.createRange().text;
 
            img.style.width  = defaultOpt.w+'px';
            img.style.height = defaultOpt.h+'px';
            img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";            
            img.style.display = '';
        // no support
        } else {
            // Safari5, ...
        }
    };
 
    // onchange
    $(this).change(function(){
		if (bug_index)
		{
			previewImage();
		}
        
    });
};

//sha256
function SHA256(s){
  var chrsz   = 8;
  var hexcase = 0;

 function safe_add (x, y) {
   var lsw = (x & 0xFFFF) + (y & 0xFFFF);
   var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
   return (msw << 16) | (lsw & 0xFFFF);
 }

 function S (X, n) { return ( X >>> n ) | (X << (32 - n)); }
 function R (X, n) { return ( X >>> n ); }
 function Ch(x, y, z) { return ((x & y) ^ ((~x) & z)); }
 function Maj(x, y, z) { return ((x & y) ^ (x & z) ^ (y & z)); }
 function Sigma0256(x) { return (S(x, 2) ^ S(x, 13) ^ S(x, 22)); }
 function Sigma1256(x) { return (S(x, 6) ^ S(x, 11) ^ S(x, 25)); }
 function Gamma0256(x) { return (S(x, 7) ^ S(x, 18) ^ R(x, 3)); }
 function Gamma1256(x) { return (S(x, 17) ^ S(x, 19) ^ R(x, 10)); }

 function core_sha256 (m, l) {
   var K = new Array(0x428A2F98, 0x71374491, 0xB5C0FBCF, 0xE9B5DBA5, 0x3956C25B, 0x59F111F1, 0x923F82A4, 0xAB1C5ED5, 0xD807AA98, 0x12835B01, 0x243185BE, 0x550C7DC3, 0x72BE5D74, 0x80DEB1FE, 0x9BDC06A7, 0xC19BF174, 0xE49B69C1, 0xEFBE4786, 0xFC19DC6, 0x240CA1CC, 0x2DE92C6F, 0x4A7484AA, 0x5CB0A9DC, 0x76F988DA, 0x983E5152, 0xA831C66D, 0xB00327C8, 0xBF597FC7, 0xC6E00BF3, 0xD5A79147, 0x6CA6351, 0x14292967, 0x27B70A85, 0x2E1B2138, 0x4D2C6DFC, 0x53380D13, 0x650A7354, 0x766A0ABB, 0x81C2C92E, 0x92722C85, 0xA2BFE8A1, 0xA81A664B, 0xC24B8B70, 0xC76C51A3, 0xD192E819, 0xD6990624, 0xF40E3585, 0x106AA070, 0x19A4C116, 0x1E376C08, 0x2748774C, 0x34B0BCB5, 0x391C0CB3, 0x4ED8AA4A, 0x5B9CCA4F, 0x682E6FF3, 0x748F82EE, 0x78A5636F, 0x84C87814, 0x8CC70208, 0x90BEFFFA, 0xA4506CEB, 0xBEF9A3F7, 0xC67178F2);
   var HASH = new Array(0x6A09E667, 0xBB67AE85, 0x3C6EF372, 0xA54FF53A, 0x510E527F, 0x9B05688C, 0x1F83D9AB, 0x5BE0CD19);
   var W = new Array(64);
   var a, b, c, d, e, f, g, h, i, j;
   var T1, T2;

   m[l >> 5] |= 0x80 << (24 - l % 32);
   m[((l + 64 >> 9) << 4) + 15] = l;

   for ( var i = 0; i<m.length; i+=16 ) {
     a = HASH[0];
     b = HASH[1];
     c = HASH[2];
     d = HASH[3];
     e = HASH[4];
     f = HASH[5];
     g = HASH[6];
     h = HASH[7];

     for ( var j = 0; j<64; j++) {
       if (j < 16) W[j] = m[j + i];
       else W[j] = safe_add(safe_add(safe_add(Gamma1256(W[j - 2]), W[j - 7]), Gamma0256(W[j - 15])), W[j - 16]);

       T1 = safe_add(safe_add(safe_add(safe_add(h, Sigma1256(e)), Ch(e, f, g)), K[j]), W[j]);
       T2 = safe_add(Sigma0256(a), Maj(a, b, c));

       h = g;
       g = f;
       f = e;
       e = safe_add(d, T1);
       d = c;
       c = b;
       b = a;
       a = safe_add(T1, T2);
     }

     HASH[0] = safe_add(a, HASH[0]);
     HASH[1] = safe_add(b, HASH[1]);
     HASH[2] = safe_add(c, HASH[2]);
     HASH[3] = safe_add(d, HASH[3]);
     HASH[4] = safe_add(e, HASH[4]);
     HASH[5] = safe_add(f, HASH[5]);
     HASH[6] = safe_add(g, HASH[6]);
     HASH[7] = safe_add(h, HASH[7]);
   }
   return HASH;
 }

 function str2binb (str) {
   var bin = Array();
   var mask = (1 << chrsz) - 1;
   for(var i = 0; i < str.length * chrsz; i += chrsz) {
     bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (24 - i%32);
   }
   return bin;
 }

 function Utf8Encode(string) {
   string = string.replace(/\r\n/g,"\n");
   var utftext = "";

   for (var n = 0; n < string.length; n++) {

     var c = string.charCodeAt(n);

     if (c < 128) {
       utftext += String.fromCharCode(c);
     }
     else if((c > 127) && (c < 2048)) {
       utftext += String.fromCharCode((c >> 6) | 192);
       utftext += String.fromCharCode((c & 63) | 128);
     }
     else {
       utftext += String.fromCharCode((c >> 12) | 224);
       utftext += String.fromCharCode(((c >> 6) & 63) | 128);
       utftext += String.fromCharCode((c & 63) | 128);
     }

   }

   return utftext;
 }

 function binb2hex (binarray) {
   var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
   var str = "";
   for(var i = 0; i < binarray.length * 4; i++) {
     str += hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8+4)) & 0xF) +
     hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8  )) & 0xF);
   }
   return str;
 }

 s = Utf8Encode(s);
 return binb2hex(core_sha256(str2binb(s), s.length * chrsz));
}

//hot_issue
function hot_issue_img_load(e)
{
	
	$(".hot_con_img_"+e).css({'top': $(".hot_photo_div2_"+e).height()/2 - $(".hot_con_img_"+e).height()/2});
	$(".hot_con_img_"+e).css({'left': $(".hot_photo_div2_"+e).width()/2 - $(".hot_con_img_"+e).width()/2});
	
	//자동폭파 기능
	hot_finish_date(e);
	
}



</script>