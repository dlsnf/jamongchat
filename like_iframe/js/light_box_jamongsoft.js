
function light_time_out()
{
	//$("body").css({'overflow':'hidden'});	

	//parent.light_iframe_fadein();

	//light_box_ajax(g);	

	setTimeout(function() { light_box_start(); }, 100);

}
//var light_box_width = $(window).width()*0.8;
var light_box_width = 300;
var light_box_height = 500;

var now_index = 0;

function light_box_start(e,d,f){

light_box_center();

$('.light_box').css({'height':light_box_height*0.95,'width':light_box_width*0.95});

//$(".light_box").css({'width':'30px'});

	/*
	$(".all-wrap").bind("mousewheel DOMMouseScroll", function() {

         return false;
     });
	 $("body").css({'overflow':'hidden'});
	 */

	
	var toppx = $(this).scrollTop();

	$(".light_box_black2").show();




	$(".light_box_black").fadeIn(300);

	if (e == undefined)
	{
		e = 0;
	}


	now_index = e;


	
	light_box_height_bug3(e);
	

	$(".light_box_count_1").text(now_index+1);
	$(".light_box_count_2").text($(".light_box_img").length);
	
	
	


	//setTimeout(function() { light_box_height_bug(e); }, 10);


	//setTimeout(function() { light_box_height_bug2(e); }, 50);


	//setTimeout(function() { light_box_height_bug3(e); }, 100);




}


//left
function light_box_left()
{
	if(now_index == 0)
	{
		alert("FIRST PAGE");
	}else{
		$(".light_box_black2").show();

		$(".light_box_img").eq(now_index).fadeOut(200,function (){
			$(".light_box_img").eq(now_index).css({'width':'auto','height':'auto'});
			light_box_start(now_index-1);
		});
		
	}
}


//right
function light_box_right()
{
	if ($(".light_box_img").length == now_index+1)
	{
		alert("LAST PAGE");
	}else{
		$(".light_box_black2").show();

		$(".light_box_img").eq(now_index).fadeOut(200,function (){
			$(".light_box_img").eq(now_index).css({'width':'auto','height':'auto'});
			light_box_start(now_index+1);
		});
		
	}
}



//finish
function light_box_finish_write()
{
	$(".light_box_black2").show();

	$(".light_box_btn_left").fadeOut(150);
	$(".light_box_btn_right").fadeOut(150);
	$(".light_box_btn_close").fadeOut(150);
	$(".light_box_count").fadeOut(150);


	$(".light_box_group").fadeOut(300,function(){		

		$(".light_box_black").fadeOut(300);
		
		$('.light_box').animate({'height':light_box_height*0.95,'width':light_box_width*0.95,'opacity':'0.0'},300,function(){

					$('.light_box').hide();
					$(".light_box_black2").hide();

					parent.like_iframe_end();					

			
		});

		

	});
}

function light_box_finish_scroll()
{
	$(".light_box_black2").show();

	$(".light_box_btn_left").fadeOut(300);
	$(".light_box_btn_right").fadeOut(300);
	$(".light_box_btn_close").fadeOut(300);
	$(".light_box_count").fadeOut(300);
	$(".light_box_black").fadeOut(300);
	$('.light_box').fadeOut(300);
	$(".light_box_black2").hide();
	setTimeout(function() { $('.light_box').css({'width':'30px','height':'30px'});  }, 300);

}


//bug
function light_box_height_bug(e)
{
	//가로 사이즈가 너무 클때 조건
	if($(".light_box_img").eq(e).width() >= ($(window).width()*0.8))
	{
		//$(".light_box_img").eq(e).css({'height':'auto','width':($(window).width()*0.8)});
	}
}

function light_box_height_bug2(e)
{
	//세로 사이즈가 너무 클때 조건
	if($(".light_box_img").eq(e).height() >= ($(window).height()*0.8))
	{
		//$(".light_box_img").eq(e).css({'width':'auto','height':($(window).height()*0.8)});
	}

	
}

function light_box_height_bug3(e)
{
	
		
		//$(".light_box_btn_left").fadeIn(300);
		//$(".light_box_btn_right").fadeIn(300);
	$(".light_box_btn_close").fadeIn(300);
		//$(".light_box_count").fadeIn(300);

	$(".light_box").show();

	$('.light_box').animate({'height':light_box_height,'width':light_box_width,'opacity':'1.0'},300,function(){
	

			$(".img_div").fadeIn(300);
			$(".text_div").fadeIn(300);
			$(".light_box_black2").hide();

			$(".light_box_group").fadeIn(300);

			light_box_center();
		
			if( $(".light_box_img").height() >= $(".light_box").height())
			{
				$(".light_box_img").css({'width':'auto','height':$(".light_box").height()});
			}

			if( $(".light_box_img").width() >= $(".img_div").width())
			{
				$(".light_box_img").css({'width':'100%','height':'auto'});
			}

			
			$(".light_box_img").css({'margin-top':$(".img_div").height()/2 - $(".light_box_img").height()/2,'margin-left':$(".img_div").width()/2 - $(".light_box_img").width()/2});
	});
	
}


function light_box_ajax(e)
{
	/*
	$(".img_div").empty();
	$(".text_div").empty();

	

	$.ajax({
		type:"POST",
		url:"light_box_ajax.php",
		data:"seq="+e,
		dataType:"json",
		//traditional: true,
		//contentType: "application/x-www-form-urlencoded;charset=utf-8",
		success:function( data ){
			//alert(data);
			if(data)
			{		
				for(var i = 0 ; i < data.length ; i ++)
				{

					$(".img_div").append("<img class='light_box_img' src='"+data[i].photo_way+"'> ");

					$(".text_div").append("<div class='light_box_info'><img src='images/"+data[i].profile+"' width='40px'><span>"+data[i].name+"<br>"+data[i].date+"</span></div><div class='light_box_content'>"+data[i].body+"</div>");
					
					$(".light_box_content").mCustomScrollbar({axis:"y"});

				}
				
			}else{
				alert("Contact Fail");
			}
				

		},
		error:function(e){
			alert("에러");
			//alert( e.responseText );
		}
	});*/
}