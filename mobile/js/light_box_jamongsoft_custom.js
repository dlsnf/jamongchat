
var now_index = 0;

var load_index = 0;

function light_box_start(e,q){

	//custom
	if ((e+1) == q)
	{
	}else if( load_index == e )
	{
		$(".light_box_img").eq(e+1).attr("src","images/photo/"+(q-(e+1))+".jpg");
		$(".light_box_img_a").eq(e+1).attr("href","images/photo/"+(q-(e+1))+".jpg");
		load_index++;
	}

	
	


	$(".light_box_black").fadeIn(300);

	if (e == undefined)
	{
		e = 0;
	}


	now_index = e;

	//custom
	$(".achive_photos").attr("onclick","light_box_start("+now_index+","+q+");");
	$(".utube").hide();


	$(".light_box").show();


	

	$(".light_box_count_1").text(now_index+1);
	$(".light_box_count_2").text($(".light_box_img").length);
	
	
	setTimeout(function() { light_box_height_bug(e); }, 10);


	setTimeout(function() { light_box_height_bug2(e); }, 50);


	setTimeout(function() { light_box_height_bug3(e,q); }, 100);
	

}


//left
function light_box_left(q)
{
	if(now_index == 0)
	{
		alert("FIRST PAGE");
	}else{
		$(".light_box_black2").show();

		$(".light_box_img").eq(now_index).fadeOut(200,function (){
			$(".light_box_img").eq(now_index).css({'width':'auto','height':'auto'});
			light_box_start(now_index-1,q);
		});
		
	}
}


//right
function light_box_right(q)
{
	if ($(".light_box_img").length == now_index+1)
	{
		alert("LAST PAGE");
	}else{
		$(".light_box_black2").show();

		$(".light_box_img").eq(now_index).fadeOut(200,function (){
			$(".light_box_img").eq(now_index).css({'width':'auto','height':'auto'});
			light_box_start(now_index+1,q);
		});
		
	}
}



//finish
function light_box_finish()
{
	$(".light_box_black2").show();

		$(".light_box_btn_left").fadeOut(300);
		$(".light_box_btn_right").fadeOut(300);
		$(".light_box_btn_close").fadeOut(300);
		$(".light_box_count").fadeOut(300);
		$(".light_box_name").fadeOut(300);
	$(".light_box_img").eq(now_index).fadeOut(500,function(){		

		$('.light_box').stop().animate({'width':'30px'},200,function(){
			$('.light_box').stop().animate({'height':'30px'},200,function(){
				$('.light_box').fadeOut(500,function(){
					$(".light_box_black").fadeOut(300);
					$(".light_box_black2").hide();
					$(".utube").show();
					center();
				});
				
			});
		});

	});
}


//bug
function light_box_height_bug(e)
{
	//가로 사이즈가 너무 클때 조건
	if($(".light_box_img").eq(e).width() >= ($(window).width()*0.8))
	{
		$(".light_box_img").eq(e).css({'height':'auto','width':($(window).width()*0.8)});

	}
}

function light_box_height_bug2(e)
{
	//세로 사이즈가 너무 클때 조건
	if($(".light_box_img").eq(e).height() >= ($(window).height()*0.8))
	{
		$(".light_box_img").eq(e).css({'width':'auto','height':($(window).height()*0.8)});

	}
}

function light_box_height_bug3(e,q)
{
	//$('.light_box').stop().animate({'height':$(".light_box_img").eq(e).height()},200,function(){
	$('.light_box').stop().animate({'height':($(window).height()*0.8)},200,function(){
		$(".light_box_btn_left").fadeIn(300);
		$(".light_box_btn_right").fadeIn(300);
		$(".light_box_btn_close").fadeIn(300);
		$(".light_box_count").fadeIn(300);
		$(".light_box_name").fadeIn(300);

		//$('.light_box').stop().animate({'width':$(".light_box_img").eq(e).width()},200,function(){			
		$('.light_box').stop().animate({'width':($(window).width()*0.8)},200,function(){
			$(".light_box_img").eq(e).fadeIn(500);
			$(".light_box_black2").hide();
		});
	});
}