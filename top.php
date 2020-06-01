<?php

?>

			<div class="top">
				<div class="top_group">
				<img class="load_temp" src="images/icon_512.png" style="display: none;" onload="visit_count_insert();" height="30px">

	
<?
if($version<=9)
{

?>
					<a class="home_go" href="<?=$url?>?mobile=<?=$mobile_check2?>" title="HOME 이동">
						<img class="home_img" src="images/icon_512.png" height="30px">
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
						<img for="search" class="btn_search grayscale grayscale-fade tooltip" src="images/btn_search_14.png" onclick=" search_content_link();" title="별명 또는 내용 검색">
					</div>

					<div class="navigate">
						<span></span>
					</div>

					<!-- visiter -->

					<div class="visit_count">
						<span class="today_span">TODAY : </span><span class="today_count"></span>
						<span class="txt_bar2" style="margin:0 8px;"></span>
						<span class="total_span">TOTAL : </span><span class="total_count"></span>				
					</div>

					<!-- 자몽챗
					<div class="version">
						<span>Copyright © 2014 <a href="http://jamongsoft.com" target="_blank" class="company_a_black">JamongSoft</a> All rights reserved.</span>
					</div>	 -->			

				</div><!-- top_group -->		

			</div><!-- top -->



			<div class="top2">	
			</div>

			<div class="top3">	
			</div>

