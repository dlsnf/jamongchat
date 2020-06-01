<?php

include "../../dbcon.php";

include "../../domain_security.php";

include "../common.php";

session_start(); // 세션을 시작헌다.


if(isset($_SESSION['user_email']))
{

}else{
exit();
}

//메모리사용 무한
ini_set('memory_limit', -1); 

//모바일 판독
$mobile = !!(FALSE !== strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'));

$get_seq = $_GET['seq'];
//$get_pa_height = $_GET['pa_height'];
$get_toppx = $_GET['toppx'];


$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";

		$query="SELECT * FROM jamong_chat_user WHERE seq = $get_seq"; // SQL 쿼리문
		$result=mysql_query($query, $conn) or die (mysql_error()); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


		if( !$result ) {
			echo "Failed to list query light_box_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['name'] = $row['name'];
			$board['email'] = $row['email'];

			$board['profile_way'] = $row['profile_way'];

			$board['profile'] = $row['profile'];

			//프로필이 없을경우
			if($board['profile_way'] == '')
			{
				$board['profile_way'] = $profile_domain_name."btn_youtb.png";
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

<title>프로필 변경</title>
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


var file_upload = false;

		$(window).ready(function(){

			
			$( "#book" ).load(function() {
			  // Handler for .load() called.
			});

			//파일 사진 업로드 미리보기
			var opt = {
				img: $('#img_preview'),
				//w: 96
				//h:96
			};

			$('#chosenFile').setPreview(opt);

			


			$(".write_submit").click(function() {
				
				//에이젝스폼 플러그인
				$('#myForm').ajaxForm({
					beforeSubmit: function (data,form,option) {
					//validation체크 
					//막기위해서는 return false를 잡아주면됨
					//alert('서브밋 직전입니다!');
					//return true;
					$(".write_submit2").show();
					$(".write_submit").hide();	
					
					},
					success: function(response,textStatus){
						if (textStatus == "success")
								{
									//alert(textStatus);
								
									//alert(response);

									if(response == 1)
									{
										alert("jpg, png, bmp, gif (이미지) 파일만 업로드 할수 있습니다.");
										$(".write_submit2").hide();
										$(".write_submit").show();
									}else if (response == 2)
									{
										alert("첨부파일 용량 제한을 초과하였습니다. ( 10MB )");
										$(".write_submit2").hide();
										$(".write_submit").show();
									}else if (response == 3)
									{
										alert("html, htm, php, inc 업로드 금지 파일입니다.");
										$(".write_submit2").hide();
										$(".write_submit").show();
									}else if (response == 4)
									{
										alert("동일 파일명이 있습니다. 파일명을 변경해주세요.");
										$(".write_submit2").hide();
										$(".write_submit").show();
									}else if (response == 5)
									{
										alert("파일 업로드 실패");

										$(".write_submit2").hide();
										$(".write_submit").show();

									}else if (response == 6)
									{
											alert("업로드 실패");	

											$(".write_submit2").hide();
											$(".write_submit").show();
									}else if (response == 7)
									{
											//alert("업로드 성공");
											//location.href="../board.php?board=<?=$board_type2?>&mobile=<?=$mobile_check2?>";
											history.go(-1);
											//light_box_finish_write(1);
									}else if (response == 10)
									{
											alert("서버에 남은 용량이 별로 없습니다... 정말 죄송하지만 좀 있다가 다시 시도해주세요... ㅠ.ㅠ");
											$(".light_box_btn_close").show();
									}else{
										alert(response);
										$(".light_box_btn_close").show();
									}

									
								}

								console.log(response, textStatus);
					},
					error: function(e){
						alert(e);
						$(".light_box_btn_close").show();
						//에러발생을 위한 code페이지
					} 
				});

				if($('#chosenFile').val() != '')
				{
					$(".light_box_btn_close").hide();
					if (confirm("등록하시겠습니까?")) { 								
						$('#myForm').submit();						
					}else {
						$(".light_box_btn_close").show();
					}
					

				//기존 에이젝스를 통한 에이젝스 파일업로드 방법
				/*	$.ajax({
						url: "file_db.php",
						type: "POST",
						contentType: false,
						processData: false,
						data: function() {
								var data = new FormData();
							   //data.append("fileDescription", jQuery("#desc").val());
								data.append("chosenFile", $("#chosenFile").get(0).files[0]);
								return data;
								// Or simply return new FormData(jQuery("form")[0]);
							}(),
							error: function(_, textStatus, errorThrown) {
								alert("Error");
								console.log(textStatus, errorThrown);
							},
							success: function(response, textStatus) {
								
								if (textStatus == "success")
								{
									//alert(textStatus);
								
									//alert(response);

									if(response == 1)
									{
										alert("jpg, png, bmp, gif (이미지) 파일만 업로드 할수 있습니다.");
									}else if (response == 2)
									{
										alert("첨부파일 용량 제한을 초과하였습니다. ( 5MB )");
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
										alert("파일 업로드 성공");
									}
								}

								console.log(response, textStatus);
							}
						});*/


					}else{
						alert("사진을 첨부해주세요");
					}
				});			
		});

		var bug_index = false;

		function file_change()
		{
			$("#img_preview").attr('src','');
			$(".file_status").text('');
			$(".file_size").text('');
		
			if( $('#chosenFile').val() != '' )
			{

				$(".file_label").css({'opacity':'0.5','filter':'alpha(opacity=50)'});

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
					$(".file_status").text(file_name);
					$(".file_size").text(file_size + 'MB');			

				}else{
					bug_index = false;

					$("#img_preview").css({'display':'none'}); $('#chosenFile').val('');

					$(".file_label").css({'opacity':'1','filter':'alpha(opacity=100)'});	
					
					$(".file_status").text('');
					$(".file_size").text('');

					alert("jpg, png, bmp, gif (이미지) 파일만 업로드 할수 있습니다.");
							
				}

				if(file_size > 5 ){

					bug_index = false;

					$("#img_preview").css({'display':'none'}); $('#chosenFile').val('');

					$(".file_label").css({'opacity':'1','filter':'alpha(opacity=100)'});	
					
					$(".file_status").text('');
					$(".file_size").text('');

					alert("첨부파일 용량 제한을 초과하였습니다. ( 5MB )");
				}
			
			}

		}

var load_check = 1;
function img_preview_load()
{
	
	if(load_check == 0)
	{
		$("#img_preview").css({'width':'auto','height':'auto'});

		var temp_width = $("#img_preview").width();
		var temp_height = $("#img_preview").height();

		if (	temp_width  >= temp_height)
		{
			var ratio ="가로";
		}else{
			var ratio ="세로";
		}
		
		$("#img_preview").css({'top':'0px','left':'0px'});

		if ( ratio == "가로")
		{
			$("#img_preview").css({'height':'96px'});
			$("#img_preview").css({'width':'auto'});

			//사진 중앙정렬
			$("#img_preview").css({'top': $(".img_preview_div").height()/2 - $("#img_preview").height()/2 }); 
			$("#img_preview").css({'left': $(".img_preview_div").width()/2 - $("#img_preview").width()/2 });
			
		}else{
			$("#img_preview").css({'width':'96px'});
			$("#img_preview").css({'height':'auto'});

			//사진 중앙정렬
			$("#img_preview").css({'top': $(".img_preview_div").height()/2 - $("#img_preview").height()/2 }); 
			$("#img_preview").css({'left': $(".img_preview_div").width()/2 - $("#img_preview").width()/2 });		
		}
	}
	load_check = 0;

}

$.fn.setPreview = function(opt){

    "use strict"
    var defaultOpt = {
        inputFile: $(this),
        img: null,
        //w: 200,
       // h: 200
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
</script>
<script src="js/jquery.form.js"></script> 
	</head>

	<body>


			

<div class="all-wrap">

				<!-- button -->
				<img class="light_box_btn_close" src='../../images/btn_close_gray.png' width='20px' title="끄기" onclick="history.go(-1);">
				

				<div class="light_box_group" style="width: 100%; height: 100%;">

				<img class="loading_img" src="images/loading_img.GIF" style="position:absolute; left:0px;right:0px;bottom:0px;top:180px; margin:auto; display:none; z-index:9999999;" width="30px">

				<form id="myForm" name="myForm" action="file_db.php" method="post" accept-charset="utf-8" ENCTYPE="multipart/form-data">

					<h1>프로필 변경</h1>

					<div class="line"></div>
					
					
						<div class="file_div">	

							<div class="img_preview_div" style="width:96px; height:96px; position: relative; overflow: hidden;">
								<img id="img_preview" src="<?=$board['profile_way']?>" style="position:absolute;" width="96px" height="96px" onload="img_preview_load();">
							</div>

							<label class="file_label_click" for="chosenFile"></label>
							<label class="file_label" for="chosenFile">+</label>
							
							<input type="file" id="chosenFile" name="chosenFile" onchange="file_change();">

							<span class="file_status"></span>
							<span class="file_size"></span>

							
							
						</div>

						<input type="hidden" id="user_email" name="user_email" value="<?=$_SESSION['user_email']?>">
						<!-- 토큰 -->
						<input type='hidden' class="l_token" name='l_token' value='<?=$_SESSION['fake']?>'>
 
					</form>
			

					<div class="write_submit">등록</div>
					<div class="write_submit2"><img class="loading_img" src="images/loading_img.GIF" style="position:absolute; left:0px;right:0px;bottom:0px;top:0px; margin:auto; z-index:9999999;" width="30px"></div>
					
				</div>



</div>		
			

	</body>

</html>
