<div class="content_board">
<!-- 게시물이 존재하지 않을경우 -->
<?
if(isset($board))
{
}else{
?>


<div class="mouse_over" style="position: relative;
  background-color: #fff;
  width: 600px;
  height:720px;
  padding: 10px;
  margin: 10px auto;
  border: 1px solid;
  border-color: #e9eaed #dfe0e4 #d0d1d5;
  border-radius: 3px;
  -webkit-border-radius: 3px;
  text-align: center;">

	<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">

	<div class="content_load not_present_div">
		<span class="not_present">
			게시물이 존재하지 않습니다.<br>
			This post does not exist.
		</span>
	 </div>

  </div>
<?
}
?>

						<? 
							foreach($boardList as $board) {	
						?>
										<div class="content mouse_over con<?=$board['seq']?>">
										<img class="load_temp" src="images/icon_512.png" style="display: none;" onload="content_delete(<?=$board['seq']?>,<?=$board['user_seq']?>);" height="30px">
			
										
										
										<img class="con_loading_img" src="images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">
										
											

											<div class="content_load content_load_div">
													
												<ul class="finish_date finish_date_<?=$board['seq']?> tooltip2" title="게시글 폭파까지 남은시간">
													<li class="finish_day"><?=$board['finish_date_diff_day']?></li>
													<li class="finish_nbsp">&nbsp;</li>
													<li class="finish_hour"><?=$board['finish_date_diff_hour']?></li>
													<li class="finish_twinkle">:</li>
													<li class="finish_minute"><?=$board['finish_date_diff_minute']?></li>
													<li class="finish_twinkle">:</li>
													<li class="finish_second"><?=$board['finish_date_diff_second']?></li>
													<input class="finish_time_second" type="hidden" value="<?=$board['finish_date_diff_time_second']?>">
												</ul>
												
												<div class="con_number">												
													<a href="detail_content.php?seq=<?=$board['seq']?>&board=<?=$board_type2?>&mobile=<?=$mobile_check2?>" title="해당 게시물로 이동">No. <?=$board['seq']?></a>							
												</div>
											
												<div class="info">
													<img class="profile_img" src="<?=$board['profile_way']?>" style="background:white;"  width="40px" height="40px" onclick="profile_light_iframe_start(<?=$board['user_seq']?>);" title="프로필 보기">
													<span><div class="user_name mouse_over_underline" style="display: inline-block; font-weight:bold; width:470px; text-overflow: ellipsis; white-space: nowrap;overflow: hidden;"><?=$board['name']?></div><br>
													<div class="date_time">
									<?
									if ($board['diff_check'] == 'none')
									{
									?>
													<?=$board['year']?>년 <?=$board['month']?>월<?=$board['day']?>일 <?=$board['ampm']?> <?=$board['hour']?>:<?=$board['minute']?>
									<?
									}else if($board['diff_check'] == 'hour'){
									?>
													<?=$board['diff']?>시간 전
									<?
									}else if($board['diff_check'] == 'minute')
									{
									?>
													<?=$board['diff']?>분 전
									<?
									}else if($board['diff_check'] == 'now')
									{
									?>
													방금 전
									<?
									}
									?>
													</div>
									

													</span>
												</div>

												<div class="text"><?=$board['body']?><span><?=$board['body_dot']?></span><?
													if( $board['line_cnt'] > 5 ){
													?><span class="content_enter" onclick="content_text_more(<?=$board['seq']?>);" title="더 보기">더 보기</span><?
													}
													?></div>

												<div class="photo photo_<?=$board['seq']?> tooltip" 
						onclick="light_iframe_start(<?=$board['seq']?>);" title="크게 보기">

													<img class="con_img_<?=$board['seq']?>" src="<?=$board['photo_way']?>thumbnail/<?=$board['photo']?>" width="598px" onload="con_load(<?=$board['seq']?>,<?=$board['user_seq']?>); comment_reload(<?=$board['seq']?>); like_status(<?=$board['seq']?>,'content');">

													<!--<img src="<?=$board['photo_way']?>" width="598px" onclick="light_time_out(0,'<?=$board['photo']?>','con<?=$board['seq']?>','<?=$board['seq']?>');">-->													
												</div>

												<div class="content_footer content_footer_<?=$board['seq']?>">
														<span class="content_footer_btn content_like_<?=$board['seq']?>" title="좋아합니다" onclick="like_ajax(<?=$board['seq']?>,'content');">좋아요</span>
														<span>·</span>
														<span class="content_footer_btn" title="댓글 남기기" onclick="comment_btn_click(<?=$board['seq']?>);">댓글 달기</span>
														<div class="content_footer_like_count_<?=$board['seq']?>" style="display:inline;">
														<?
														if ($board['like_c']>0)
														{
														?>
														<span class="like_temp_<?=$board['seq']?>">· <img src="images/logo_normal.svg" class="like_logo" height="14px"></span>
														<span class="con<?=$board['seq']?>_like_count like_temp_<?=$board['seq']?>"><?=$board['like_c']?></span>
														<?
														}
														?>
														</div>
														<div class="content_footer_comment_count_<?=$board['seq']?>" style="display:inline;">
														</div>
												</div>
												
												<div class="comment">

													<div class="comment_like_count comment_like_count_<?=$board['seq']?>">
													<?
													if ($board['like_c']>0)
													{
													?>
														<div class="like_count_div2"><img src="images/logo_normal.svg" class="like_logo" height="14px">&nbsp;</div>

														<div class="like_count_div tooltip" onclick="like_iframe_start(<?=$board['seq']?>,'content');" title="누가 좋아했는지 보기">
															<span class="like_count con<?=$board['seq']?>_like_count"><?=$board['like_c']?></span><span>명</span>
														</div>

														<div>이 좋아합니다.</div>
													<?
													}else{
													?>
														<div>가장 먼저 좋아요를 눌러보세요!</div>
													<?
													}
													?>
													</div>


													

													<div class="comment_list">

							<?
							if(isset($_SESSION['user_email']))
							{
							?>
													<div class="comment_write comment_write_<?=$board['seq']?>">
														<form id="myForm_<?=$board['seq']?>" name="myForm_<?=$board['seq']?>" action="comment_write_ajax.php" method="post" accept-charset="utf-8" ENCTYPE="multipart/form-data">

															<img class="profile_img" src="<?=$user_profile_way?>" style="background:white;" width="30px" height="30px" onclick="profile_light_iframe_start(<?=$user_seq?>);" title="프로필 보기">
															<input type="text" name="comment_input" class="comment_input comment_input_<?=$board['seq']?>" placeholder="댓글을 입력하세요..." maxlength="140" autocomplete="off" required=""
															onkeypress="if (event.keyCode==13){comment_write(<?=$board['seq']?>,1);}">

															<input type="text" style="display: none;">
																														
															<div class="image_add" onclick="image_add(<?=$board['seq']?>)"></div>

															
															<input type="hidden" class="seq" name="seq" value="<?=$board['seq']?>">
															<input type="hidden" class="user_seq" name="user_seq" value="<?=$user_seq?>">

														</form>

													</div>

													

												

													<div class="comment_reload_div comment_reload_div_<?=$board['seq']?>">

							<?
							}else{
							?>
													<div class="comment_write">
														<img class="profile_img" src="<?=$user_profile_way?>" style="background:white;" width="30px" height="30px">
														<input type="text" name="comment_input" class="comment_input comment_input_<?=$board['seq']?> mouse_over" placeholder="로그인이 필요합니다..." maxlength="140" autocomplete="off" required="" onkeypress="if (event.keyCode==13) alert('로그인이 필요합니다.');" disabled>
													</div>

													<div class="comment_reload_div comment_reload_div_<?=$board['seq']?>">

											        
							<?
							}
							?>
														
														</div><!-- comment_reload_div -->
												

													</div><!-- comment_list -->

												</div>

											</div>

										</div><!-- content -->

										
						<? 
						}
						?>

						

							<!--
							<div class="content_more_btn tooltip" onclick="content_more(); auto_more_count = 0;"  title="더 보기">
								<span oncontextmenu="return false" onselectstart="return false" ondragstart="return false">MORE</span>
							</div>

							-->

						</div><!-- content_board -->