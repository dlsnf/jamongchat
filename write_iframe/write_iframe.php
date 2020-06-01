<?php

include "../dbcon.php";

include "../domain_security.php";

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
	<script src="js/jquery-1.11.0.min.js"></script>
	<!-- light_box_jamongsoft -->
	<script src="js/light_box_jamongsoft.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/light_box_jamongsoft.css">

	

<script>
	var board_type = "<?=$board_type2?>";
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

var file_upload = false;


		$(window).ready(function(){
			light_box_black_size();


			//파일 사진 업로드 미리보기
			var opt = {
				img: $('#img_preview'),
				w: 96,
				h: 96
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
					$(".loading_img").show();		
					
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
										$(".loading_img").hide();
									}else if (response == 2)
									{
										alert("첨부파일 용량 제한을 초과하였습니다. ( 10MB )");
										$(".write_submit2").hide();
										$(".write_submit").show();
										$(".loading_img").hide();
									}else if (response == 3)
									{
										alert("html, htm, php, inc 업로드 금지 파일입니다.");
										$(".write_submit2").hide();
										$(".write_submit").show();
										$(".loading_img").hide();
									}else if (response == 4)
									{
										alert("동일 파일명이 있습니다. 파일명을 변경해주세요.");
										$(".write_submit2").hide();
										$(".write_submit").show();
										$(".loading_img").hide();
									}else if (response == 5)
									{
										alert("파일 업로드 실패");

										$(".write_submit2").hide();
										$(".write_submit").show();
										$(".loading_img").hide();

									}else if (response == 6)
									{
											alert("업로드 실패");	

											$(".write_submit2").hide();
											$(".write_submit").show();
											$(".loading_img").hide();
									}else if (response == 7)
									{
											//alert("업로드 성공");
											light_box_finish_write(1);
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

				if($("#write_body").val() == '')
				{
					alert("글을 입력해 주세요");
					$("#write_body").focus();
				}else if($('#chosenFile').val() != '')
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

				if(file_size > 10 ){

					bug_index = false;

					$("#img_preview").css({'display':'none'}); $('#chosenFile').val('');

					$(".file_label").css({'opacity':'1','filter':'alpha(opacity=100)'});	
					
					$(".file_status").text('');
					$(".file_size").text('');

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
</script>
<script src="js/jquery.form.js"></script> 
	</head>

	<body style="background: transparent; "onload="parent.light_iframe_fadein(); light_time_out();">


	
			

<div style="">

			<div class="light_box_black" onclick="" style="" title="끄기"></div>
			<div class="light_box_black2"></div> 

			<div class="light_box">


				
				<!-- button -->
				<img class="light_box_btn_close" src='images/btn_close.png' onclick="light_box_finish_write();" width='15px' title="끄기">

				<div class="light_box_group" style="width: 100%; height: 100%;">

				<img class="loading_img" src="images/loading_img.GIF" style="position:absolute; left:0px;right:0px;bottom:0px;top:600px; margin:auto; display:none; z-index:9999999;" width="30px">

				<form id="myForm" name="myForm" action="file_db.php" method="post" accept-charset="utf-8" ENCTYPE="multipart/form-data">

					<h1>글쓰기</h1>

					<div class="line"></div>					

					<textarea id="write_body" maxlength="10000" name="write_body"></textarea>
					<br><br>
					<h1>사진 등록</h1>

					<div class="line"></div>
					
					
						<div class="file_div">	
							<img id="img_preview" width="96px" height="96px" style="z-index:3; display:none;">

							<label class="file_label_click" for="chosenFile"></label>
							<label class="file_label" for="chosenFile">+</label>
							
							<input type="file" id="chosenFile" name="chosenFile" onchange="file_change();">

							<span class="file_status"></span>
							<span class="file_size"></span>
							
						</div>

						<input type="hidden" id="user_email" name="user_email" value="<?=$_SESSION['user_email']?>">

						
						<input type='hidden' class="board" name='board' value='<?=$board_type2?>'>
						<!-- 토큰 -->

						<input type='hidden' class="l_token" name='l_token' value='<?=$_SESSION['fake']?>'>

 
					</form>
			

					<div class="write_submit">등록</div>
					<div class="write_submit2"></div>
					
				</div>


			</div>

</div>		
			

	</body>

</html>
