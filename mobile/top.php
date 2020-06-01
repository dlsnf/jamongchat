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

			<div class="top">
				<div class="top_group">
				<!--<img class="load_temp" src="../images/icon_512.png" style="display: none;" onload="visit_count_insert();" height="30px">-->

	
<?
if($version<=9)
{
?>
					<a class="home_go" href="<?=$url?>?mobile=<?=$mobile_check2?>" title="HOME 이동">
						<img class="home_img" src="../images/icon_512.png" height="30px">
					</a>
<?
}else{
?>

					<a class="home_go" href="<?=$url?>?mobile=<?=$mobile_check2?>" title="HOME 이동">

						<div class="logo_back">

							<div class="logo">
								<!--<div class="logo_shadow"></div>
								<div class="logo_shadow2"></div>-->
								<div class="logo_top">
								</div>
								<div class="logo_bottom">
								</div>
							</div> <!-- logo -->
						</div>
					</a>
<?
}
?>					
						
						<!--
						<ul>
							<li><a href="">MENU1.</a><li>
							<li><a href="">MENU2.</a><li>
							<li><a href="">MENU3.</a><li>
							<li><a href="">MENU4.</a><li>
						</ul>

						-->

					<div class="search_div">
						<input type="text" id="search" name="search" class="search" placeholder="별명 또는 내용 검색" value="<?=$get_search?>" maxlength="100" autocomplete="off" required="" onkeypress="if (event.keyCode==13) search_content_link();" title="별명 또는 내용 검색">
						<img for="search" class="btn_search grayscale grayscale-fade tooltip" src="../images/btn_search_14.png" onclick=" search_content_link();" title="별명 또는 내용 검색">
					</div>

					<div class="navigate">
						<span></span>
					</div>

					<div class="top_right">

						
						<a class="btn_hot_issue_more_top" href="hot_content.php?board=<?=$board_type2?>&mobile=<?=$mobile_check2?>" title="핫이슈 전체 보기">HOT</a>

						<div class="btn_menu" onclick="menu_on();">
						
							<div class="btn-menu">
	
								<div class="btn-menu_i">
		
									<div class="btn-menu_ii">
										<div class="btn-menu__line-1"></div>
										<div class="btn-menu__line-2"></div>
										<div class="btn-menu__line-3"></div>
									</div>
			
								</div>
		
							</div><!-- btn-menu -->
							
							<!-- <img src="../images/btn_menu.svg" height="30px"> -->
						</div>

						

					</div><!-- top_right -->
<!-- 
					<div class='iframe_div' style='position:fixed; z-index:99999999999;top:0; left:0; width:50%; height:100%;'><iframe src='http://jamongserver.cafe24.com/test' class='light_iframe' allowtransparency='true' frameborder='0' width='50%' height='100%' style='position:fixed; z-index:999999999999;  top:0; left:0;'></iframe></div> -->

					
					<div class="menu">
						
						<div class="menu_black" onclick="menu_off();">	
							<div class="menu_black_block"></div>
						</div>

						

						<div class="menu_body">
							<!--<img class="btn_menu_close" src ="../images/btn_close.png" onclick="menu_off();" width="20px" title="">-->
							
							<div class="menu_background"></div>

							<div class="menu_title"></div>

							<div class="menu_board" >

																
<?
	include "login.php";
?>


								<div class="board_menu">
									<div class="board_menu_info">< 포럼 ></div>
									<a href="board.php?board=free&mobile=<?=$mobile_check2?>" class="btn_board free_board">자유게시판 <?=$free_board_count?></a>

									<a href="board.php?board=fashion&mobile=<?=$mobile_check2?>" class="btn_board fashion_board">패션 <?=$fashion_board_count?></a>

									<a href="board.php?board=selfie&mobile=<?=$mobile_check2?>" class="btn_board selfie_board">셀피 <?=$selfie_board_count?></a>

									<a href="board.php?board=food&mobile=<?=$mobile_check2?>" class="btn_board food_board">음식 <?=$food_board_count?></a>

									<a href="board.php?board=photo&mobile=<?=$mobile_check2?>" class="btn_board photo_board">사진 <?=$photo_board_count?></a>


									
								</div><!-- board_menu -->

								<?
									include "share.php";
								?>

							</div><!-- menu_board -->

							<a href="javascript:;" class="pc_link" onclick="pc_link();">PC버전</a>

							<span class="company">Copyright © 2020 <a href="http://nuricarrot.com" class="company_a_2" target="blank" style="color: #fff; text-decoration:underline">NuriCarrot</a> All rights reserved.</span>

						</div><!-- menu_body -->

					</div><!-- menu -->

					

					<!-- visiter 

					<div class="visit_count">
						<span class="today_span">TODAY : </span><span class="today_count"></span>
						<span class="txt_bar2"></span>
						<span class="total_span">TOTAL : </span><span class="total_count"></span>				
					</div>

					-->

					<!-- 자몽챗
					<div class="version">
						<span>Copyright © 2014 <a href="http://jamongsoft.com" target="_blank" class="company_a_black">JamongSoft</a> All rights reserved.</span>
					</div>	 -->			

				</div><!-- top_group -->		


<?
if(isset($_SESSION['user_email']))
{
		if($_GET['board'])
		{ //게시판일때만
	?>
		<!--<div class="content_write2" onclick="write_start(<?=$user_seq?>);" title="글쓰기">
		글쓰기
		</div>-->
	<?
		}
}
?>
						

			</div><!-- top -->





			<div class="top2">	
			</div>

			<div class="top3">	
			</div>

