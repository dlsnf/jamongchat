
function start_load()
{
	

    clickslide_f();
    $("#slider").css({"height":$(".main_img").height()+"px"});
    light_box();
    
    //var pos=$("#top").height();
    
    //콘텐츠 최상단으로 가는 애니메이션
    //$("body").stop().animate({scrollTop:pos},'slow');
}

function start_ready()
{

	
	$("#slider2").css({"height":"0px"});
    top_button_f();
    
    Kakao.init('a8fb30cbfcaf7b7faf51b1b926da18a1');
    Kakao.Link.createTalkLink({
		container: '#kakao-link-btn',
		label: '서대문구청장 2 문석진 후보',
		image: {
			src: 'http://siracus.coopmk.com/Mletter/MSJ/image/logo2.png',
			width: '600',
			height: '200'
        },
        webButton: {
			text: '확인하러 가기 !'
		},
        fail: function() {
			alert('KakaoLink is currently only supported in iOS and Android platforms.');
		}
	});
    			
    window.mySwipe2 = new Swipe2(document.getElementById('slider2'), {
        startSlide: 0,
        speed: 300,
        auto:3000,
        continuous: true,
        disableScroll: false,
        stopPropagation: false,
        callback: function(index, elem) {
        	
        },

    });
    
	window.mySwipe = new Swipe(document.getElementById('slider'), {
		startSlide: 0,
	    speed: 300,
	    continuous: true,
	    disableScroll: false,
	    stopPropagation: false,
	    callback: function(index, elem) {
			switch(index)	{
				case 0:
					$("#slider").css({"height":$(".main_img").height()+"px"});
	        		$("#slider2").css({"height":"0px"});
	        	break;
				
				case 1:
					$("#slider").css({"height":$(".c_1").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        	
				case 2:
					$("#slider").css({"height":$(".c_2").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        	
				case 3:
					$("#slider").css({"height":$(".c_3_1").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        	
				case 4:
					$("#slider").css({"height":$(".c_3_2").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        	
				case 5:
					$("#slider").css({"height":$(".c_3_3").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        		
				case 6:
					$("#slider").css({"height":$(".c_4_1").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        	
				case 7:
					$("#slider").css({"height":$(".c_4_2").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        	
				case 8:
					$("#slider").css({"height":$(".c_4_3").height()+"px"});
	        		$("#slider2").css({"height":"146px"});
	        	break;
	        }
	        	
	        $("body").stop().animate({scrollTop:0},'slow');
		},
	});
    
    //슬라이더 버튼들
    $('.arrowBox a').eq(0).click(function() {    
		mySwipe.next();
	});

    $('.arrowBox a').eq(1).click(function() {
		mySwipe.prev();
    });
}


function top_button_f()
{
    $("#top_button").hide();
    
    $("#top_button").click(function(){
		$("body").stop().animate({scrollTop:0},'slow');
	});
}


function m_touch(e)
{
    $("#home img").eq(0).hide();
    $("#home img").eq(1).show();
  
    var pos = 0;
    $("body").stop().animate({scrollTop:pos},'slow');
}



function menu_touch()
{
    $(".share").slideToggle('slow');
}

//슬라이더 버튼들 위치지정 함수
function clickslide_f()
{
    //슬라이더 세로 가운데 부분찾는 픽셀값
    var clickslide_height = $("#slider img").height()/2 - $(".arrow").height()/2;
    $("#clickslide").css({"top":clickslide_height+"px"});
}


function setTopPos() { // setTopPos 함수
    
    var toppx = $(this).scrollTop();

    var scrollBottom = $(this).scrollTop() + $(window).height();
    var documentHeight = $(document).height();
    
    
    if(scrollBottom == documentHeight)
    {
        //$("#top").show();
    }
    
    //탑버튼 나오게하기
    if(toppx >= 10)    {
        $("#top_button").fadeIn(300);
    }
	else    {
        $("#top_button").fadeOut();
    }
    
}

function light_box()
{
    var hhh = $("#top").height() + $("#swipe-wrap img").height() - $("#swipe-wrap img").height()/1.5;
	
    var box = $(".Box");
    box.css({"top":hhh});
    box.animate({top:hhh+30,opacity:'0.0'},500);
    box.animate({top:hhh,opacity:'1.0'},500);
    box.animate({opacity:'1.0'},100);
    box.animate({right:'40%'},300);
    box.animate({opacity:'0.0'},300);
    box.animate({right:'15%'},300);
    box.animate({opacity:'1.0'},300);
    box.animate({right:'40%'},300);
    box.animate({opacity:'0.0'},300);
}

function m_click(e){
	mySwipe.slide(e-1);
	
}

function slide_length(e){
    /*
		var i = 0;
		
    for(i=0; i<e; i++){
			//alert(i);
			$("<span class='cc'>0</span>").appendTo(".clon");
    }*/
	
}
function slide_index(e){

	 if(e == 1){
		  $(".share1").css({ "border-bottom": "4px solid #005bac"});
		  $(".m_1_1").css({ "color": "#005bac"});
		  $(".m_1_2").css({ "color": "#005bac"});
	 }
	 else{
		 $(".share1").css({ "border-bottom": "4px solid #a0a0a0"});
		 $(".m_1_1").css({ "color": "black"});
		 $(".m_1_2").css({ "color": "black"});
	 }

	 if(e == 2){
		  $(".share2").css({ "border-bottom": "4px solid #005bac"});
		  $(".m_2_1").css({ "color": "#005bac"});
		  $(".m_2_2").css({ "color": "#005bac"});
	 }
	 else{
		 $(".share2").css({ "border-bottom": "4px solid #a0a0a0"});
		 $(".m_2_1").css({ "color": "black"});
		 $(".m_2_2").css({ "color": "black"});
	 }

	 if(e == 3 || e == 4 || e == 5){
		  $(".share3").css({ "border-bottom": "4px solid #005bac"});
		  $(".m_3_1").css({ "color": "#005bac"});
		  $(".m_3_2").css({ "color": "#005bac"});
	 }
	 else{
		 $(".share3").css({ "border-bottom": "4px solid #a0a0a0"});
		 $(".m_3_1").css({ "color": "black"});
		 $(".m_3_2").css({ "color": "black"});
	 }

	 if(e == 6 || e == 7 || e == 8){
		  $(".share4").css({ "border-bottom": "4px solid #005bac"});
		  $(".m_4_1").css({ "color": "#005bac"});
		  $(".m_4_2").css({ "color": "#005bac"});
	 }
	 else{
		 $(".share4").css({ "border-bottom": "4px solid #a0a0a0"});
		 $(".m_4_1").css({ "color": "black"});
		 $(".m_4_2").css({ "color": "black"});
	 }

	if (e == 0){

		 $(".share1").css({ "border-bottom": "4px solid #005bac"});
		 $(".m_1_1").css({ "color": "#005bac"});
		 $(".m_1_2").css({ "color": "#005bac"});

		 $(".share2").css({ "border-bottom": "4px solid #005bac"});
		 $(".m_2_1").css({ "color": "#005bac"});
		 $(".m_2_2").css({ "color": "#005bac"});

		 $(".share3").css({ "border-bottom": "4px solid #005bac"});
		 $(".m_3_1").css({ "color": "#005bac"});
		 $(".m_3_2").css({ "color": "#005bac"});

		 $(".share4").css({ "border-bottom": "4px solid #005bac"});
		 $(".m_4_1").css({ "color": "#005bac"});
		 $(".m_4_2").css({ "color": "#005bac"});
	}
	
}