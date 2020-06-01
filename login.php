<?php
		
//////////////////////////////////////// 게시판별 갯수 시작 ///////////////////////////////////////////

		//자유게시판 갯수
		$query="SELECT count(*) as board_count FROM jamong_chat_freeboard WHERE board_type = 'free'"; 
		$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query login";
			$isSuccess = FALSE;
		}


		while( $row = mysql_fetch_array($result2) ) {
			$free_board_count = "( ". $row['board_count'] . " )";
		}
		
		//패션게시판 갯수
		$query="SELECT count(*) as board_count FROM jamong_chat_freeboard WHERE board_type = 'fashion'"; 
		$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query login2";
			$isSuccess = FALSE;
		}


		while( $row = mysql_fetch_array($result2) ) {
			$fashion_board_count = "( ". $row['board_count'] . " )";
		}


		//셀피게시판 갯수
		$query="SELECT count(*) as board_count FROM jamong_chat_freeboard WHERE board_type = 'selfie'"; 
		$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query login2";
			$isSuccess = FALSE;
		}


		while( $row = mysql_fetch_array($result2) ) {
			$selfie_board_count = "( ". $row['board_count'] . " )";
		}

		//음식게시판 갯수
		$query="SELECT count(*) as board_count FROM jamong_chat_freeboard WHERE board_type = 'food'"; 
		$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query login2";
			$isSuccess = FALSE;
		}


		while( $row = mysql_fetch_array($result2) ) {
			$food_board_count = "( ". $row['board_count'] . " )";
		}

		//사진게시판 갯수
		$query="SELECT count(*) as board_count FROM jamong_chat_freeboard WHERE board_type = 'photo'"; 
		$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query login2";
			$isSuccess = FALSE;
		}


		while( $row = mysql_fetch_array($result2) ) {
			$photo_board_count = "( ". $row['board_count'] . " )";
		}


//////////////////////////////////////// 게시판별 갯수 끝 ///////////////////////////////////////////
		

?>
<script>

$(window).ready(function(){

/*



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
							$("body").css({'display':'none'});
							login();
						}
						
					}
	<?
	}
	?>	
*/

});
	
</script>
						<div class="section_left section_left2" style="position:absolute;"></div>

						<div class="section_left">							

<?
if(isset($_SESSION['user_email']))
{
?>
							<div class="user mouse_over">

								
								<!--
								<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto; background:white;"  width="30px">
								-->
								

								<div class="">

									<div class="user_info">
										<img class="profile_img" src="<?=$user_profile_way?>" width="40px" height="40px" onclick="profile_light_iframe_start(<?=$user_seq?>);" title="프로필 보기">
										<span>
											<div class="user_name mouse_over_underline"><?=$user_name?></div>
											<div class="user_modifiy mouse_over_underline" onclick="profile_start(<?=$user_seq?>)">프로필 변경</div>
										</span>	
										<a href="logout.php?mobile=<?=$mobile_check2?>" class="user_logout mouse_over" onClick="fb_logout();">
											로그아웃
										</a>
									</div>

								</div>

							</div><!-- user -->	
							<?
								if($_GET['board'])
								{ //게시판일때만
							?>
							<div class="content_write" onclick="write_start(<?=$user_seq?>);">
									글쓰기
							</div>
							<?
								}
							?>

<?
}else{
?>						
							<div class="login mouse_over">

							<!--
							<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">

							-->
							

								<div class="">
									<span class="login_label">
										LOGIN
									</span>

								<form class='login_form' name="login_form" action="login_form.php" method="post">

									<input type="text" name="login_id" class="login_id mouse_over" placeholder="계정(이메일)" maxlength="100" autocomplete="off" required="" onkeypress="if (event.keyCode==13) login();">

									<input type="password" name="login_password" class="login_password mouse_over" placeholder="비밀번호" maxlength="100" autocomplete="off" required="" onkeypress="if (event.keyCode==13) login();">

									<input type="hidden" name="password_hidden" class="password_hidden" value="123">
								
									<!--
									<input type="checkbox" id="idsave_id" name="idsave" value="idsaveOk" style="vertical-align: middle;" onChange="login_checkbox('id')" ><label  for="idsave_id" class="idsave" >아이디 저장</label>
								
									<input type="checkbox" id="pwsave_id" name="pwsave" value="pwsaveOk" style="vertical-align: middle;" onChange="login_checkbox('pw')"><label for="pwsave_id" class="pwsave">자동 로그인</label>

									-->


								</form>
									<div class="login_button" onclick="login();"><span>로그인</span></div>
								
										<div class="info_user">							
																
											<ul class="list_user">

												<li><a href="sign_up.php?mobile=<?=$mobile_check2?>" class="join_button">회원가입</a>	
													<span class="txt_bar"></span>
												</li>

												<li><a href="#" class="find_id_button" style="opacity: 0.2;
		cursor: default;
		text-decoration: none;">계정 찾기</a>
													<span class="txt_bar"></span>
												</li>
												
												<li><a href="#" class="find_password_button" style="opacity: 0.2;
		cursor: default;
		text-decoration: none;">비밀번호 찾기</a>
												</li>
											</ul>

										</div>

									</div><!-- content_load -->

								</div><!-- login -->
								<!--
								<div class="btn_facebook_login" onClick="facebooklogin();">Facebook 로그인</div>
								-->
<?
}
?>
								
								<div class="board_menu_info">< 게시판 ></div>
								<div class="board_menu">
									
									<a href="board.php?board=free&mobile=<?=$mobile_check2?>" class="btn_board free_board">자유게시판 <?=$free_board_count?></a>

									<a href="board.php?board=fashion&mobile=<?=$mobile_check2?>" class="btn_board fashion_board">패션 <?=$fashion_board_count?></a>

									<a href="board.php?board=selfie&mobile=<?=$mobile_check2?>" class="btn_board selfie_board">셀피 <?=$selfie_board_count?></a>

									<a href="board.php?board=food&mobile=<?=$mobile_check2?>" class="btn_board food_board">음식 <?=$food_board_count?></a>

									<a href="board.php?board=photo&mobile=<?=$mobile_check2?>" class="btn_board photo_board">사진 <?=$photo_board_count?></a>

									
								</div>

<?
	include "share.php";
?>

								<?if($mobile)
									{
									?>
										<a href="javascript:;" class="mobile_link" onclick="mobile_link();">모바일버전 보기</a>
									<?
									}else{
									?>
										<a href="javascript:;" class="mobile_link" onclick="mobile_link();">모바일버전 보기</a>
									<?
									}
									?>

<!--
<div class="btn_facebook_logout" onClick="FB.logout();">Logout</div>
<p>- 사용자정보 출력 -</p>
<div align="left">
    <img id="image"/>
    <div id="name"></div>
    <div id="id"></div>
</div>
-->
							</div><!-- section_left -->



<script>
//facebook SDK 추가
(function(d){  
 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];  

 if (d.getElementById(id)) {return;}  

 js = d.createElement('script'); js.id = id; 
 js.async = true;  
 js.src = "//connect.facebook.net/en_US/all.js";  
 ref.parentNode.insertBefore(js, ref);  

}(document)); 

window.fbAsyncInit = function() {  
	 //초기화
	 FB.init({
			appId: '1451146561856155', // 앱 ID
			status: true, // 로그인 상태를 확인
			cookie: true, // 쿠키허용
			xfbml: true // parse XFBML    
	});

	//로그아웃
	FB.Event.subscribe('auth.logout', function(response) {
		document.location.reload();
	});
};


function fb_logout()
{
	if("<?=$_SESSION['fb']?>" == 1)
	{		
		FB.logout();
	}
}

//로그인
function facebooklogin() {  
 FB.login(function(response) {
   if (response.status === 'connected') {
  getMyProfile(); //로그인 성공
   } else if (response.status === 'not_authorized') {
     // 페이스북에는 로그인 되어있으나, 앱에는 로그인 되어있지 않다.
   } else {
     // 페이스북에 로그인이 되어있지 않아서, 앱에 로그인 되어있는지 불명확하다.
   }
 } , {scope: "user_about_me,email,user_birthday"} ); //나는 유저의 아이디(이메일)과 생일 정보를 얻어오고 싶다.
 
} 
 

 //로그인 성공시
function getMyProfile(){
 FB.api('/me',function(user){

 var myId = user.id; //89749541354
 var myName= user.name; //이누리
 
 
 if(myName != ""){
	$.ajax({
			type:"POST",
			url:"fb_login_ajax.php",
			data:"id="+myId+"&name="+myName+"&fb=1",
			dataType:"json",
			//traditional: true,
			//contentType: "application/x-www-form-urlencoded;charset=utf-8",
			success:function( data ){
				//alert("<?=$_SESSION['user_email']?>");
				if(data == 1)
				{
					location.reload();
				}else{
					alert("페이스북 로그인 에러");
				}
			},
			error:function(e){
				alert("에러");
				alert( e.responseText );
			}
		});

	
   //정보를 post로 보내고 submit처리

	//var image = document.getElementById('image');
	//image.src = 'http://graph.facebook.com/' + user.id + '/picture';
	//var name = document.getElementById('name');
	//name.innerHTML = user.name
	//var id = document.getElementById('id');
	//id.innerHTML = user.id



 }
 
  });
 FB.api('/me/picture?type=large',function(data){
 var myImg = data.data.url;
 });
}




</script>