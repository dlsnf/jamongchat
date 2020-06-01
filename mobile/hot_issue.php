<?

$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";


//전체검색
if( $board_type2 == '')
{
	$hot_query="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM $board_type boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq ORDER BY boo.like_c DESC, boo.date ASC limit 0,3"; // SQL 쿼리문
}else{

//게시글 쿼리
$hot_query="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM $board_type boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE boo.board_type = '$board_type2' ORDER BY boo.like_c DESC, boo.date ASC limit 0,3"; // SQL 쿼리문
}

$hot_result=mysql_query($hot_query, $conn); // 쿼리문을 실행 결과

if( !$hot_result ) {
	echo "Failed to hot index";
	$isSuccess = FALSE;
}

$hot_boardList = array();

while( $row = mysql_fetch_array($hot_result) ) {
	$hot_board['seq'] = $row['seq'];
	$hot_board['user_seq'] = $row['user_seq'];
	$hot_board['name'] = strip_tags($row['uoo_name']);
	$hot_board['profile_way'] = $row['uoo_profile_way'];
	$hot_board['profile'] = $row['uoo_profile'];
	$hot_board['body'] = nl2br(strip_tags($row['body']));
	$hot_board['like_c'] = $row['like_c'];
	$hot_board['photo_way'] = $row['photo_way'];
	$hot_board['photo'] = $row['photo'];
	$hot_board['date'] = $row['date'];
	$hot_board['finish_date'] = $row['finish_date'];
	$hot_board['ip'] = $row['ip'];

	//프로필이 없을경우
	if($hot_board['profile_way'] == '')
	{
		$hot_board['profile_way'] = $profile_domain_name."btn_youtb.png";
	}

	//시간
	$date_time = explode(" ",$hot_board['date']);
	$date = $date_time[0];
	$time = $date_time[1];

	$date_array =  explode("-",$date);
	$hot_board['year'] = intval($date_array[0]);
	$hot_board['month'] = intval($date_array[1]);
	$hot_board['day'] = intval($date_array[2]);

	$time_array =  explode(":",$time);
	$hot_board['hour'] = intval($time_array[0]);
	$hot_board['minute'] = intval($time_array[1]);
	$hot_board['second'] = intval($time_array[2]);

	if( $hot_board['hour'] > 12)
	{
		$hot_board['ampm'] = "오후";
		$hot_board['hour'] = $hot_board['hour'] - 12;
	}else{
		$hot_board['ampm'] = "오전";
	}

	if( $hot_board['minute'] < 10)
	{
		$hot_board['minute'] = "0".$hot_board['minute'];
	}

	$date1 = new DateTime($hot_board['date']);
	$date2 = new DateTime($get_date);

	$date3 = date_diff($date1, $date2);
	
	$diff = $date3->days * 1440 + $date3->h * 60 + $date3->i;

	if($diff == 0)
	{
		$hot_board['diff_check'] = 'now';
	}else if( floor($diff/60) == 0 )
	{
		$hot_board['diff'] = $diff;
		$hot_board['diff_check'] = 'minute';
	}else if (floor($diff/60) < 11)
	{
		$hot_board['diff'] = floor($diff/60);
		$hot_board['diff_check'] = 'hour';
	}else{
		$hot_board['diff_check'] = 'none';
	}

	//자동 폭파시간
	$today_date = new DateTime($get_date);
	$finish_date = new DateTime($hot_board['finish_date']);

	//오늘시간과 폭파시간 차이 구하기
	$finish_date_diff = date_diff($today_date, $finish_date);

	$finish_date_diff_minute = $finish_date_diff->days * 1440 + $finish_date_diff->h * 60 + $finish_date_diff->i;
	$finish_date_diff_second = $finish_date_diff->days * 86400 + $finish_date_diff->h * 3600 + $finish_date_diff->i * 60 + $finish_date_diff->s;
			
	//시간 차이
	$hot_board['finish_date_diff_time_minute'] = $finish_date_diff_minute;
	$hot_board['finish_date_diff_time_second'] = $finish_date_diff_second;

	$hot_board['finish_date_diff_day'] = floor($finish_date_diff_second/86400);
	if($hot_board['finish_date_diff_day'] == 0)
	{
		$hot_board['finish_date_diff_day'] = '';
	}
	$hot_board['finish_date_diff_hour'] = floor($finish_date_diff_second%86400/3600);
	$hot_board['finish_date_diff_minute'] = floor($finish_date_diff_second%86400%3600/60);
	$hot_board['finish_date_diff_second'] = floor($finish_date_diff_second%86400%3600%60);


	$seq_temp = $hot_board['seq'];

	//코멘트 갯수 쿼리문
	$hot_comment_count_query="SELECT count(*) count FROM jamong_chat_comment coo LEFT JOIN jamong_chat_user uoo ON coo.user_seq = uoo.seq WHERE board_seq = '$seq_temp'"; // SQL 쿼리문
	$hot_comment_count_result=mysql_query($hot_comment_count_query, $conn); // 쿼리문을 실행 결과
	if( !$hot_comment_count_result ) {
		echo "Failed to hot_comment_count index";
		$isSuccess = FALSE;
	}
	
	while( $row = mysql_fetch_array($hot_comment_count_result) ) {
		$hot_board['comment_count'] = $row['count'];
	}


	array_push($hot_boardList, $hot_board);
}


mysql_close();

?>

<div class="hot_issue2"></div>

<div class="hot_issue mouse_over">
	<div class="hot_issue_content">

	<!-- <img class="con_loading_img" src="../images/loading_img/loading_img.GIF" style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; margin: auto;" width="30px">-->

	<div class="content_load not_present_div">
<?
if(isset($hot_board))
{//게시물이 존재
?>
	<span class="hot_issue_title">HOT ISSUE</span>
	<a class="btn_hot_issue_more" href="hot_content.php?board=<?=$board_type2?>&mobile=<?=$mobile_check2?>" title="핫이슈 전체 보기">more</a>
	<br><br>
<?
}else{//게시물이 존재하지 않음
?>
		<span class="not_present">
			게시물이 존재하지 않습니다.<br>
			This post does not exist.
		</span>
<?
}
?>

<? 
	foreach($hot_boardList as $hot_board) {	
?>
<div class="hot_issue_div hot_issue_<?=$hot_board['seq']?>">
		<ul class="finish_date finish_date_<?=$hot_board['seq']?> tooltip2" title="게시글 폭파까지 남은시간">
			<li class="finish_day"><?=$hot_board['finish_date_diff_day']?></li>
			<li class="finish_nbsp">&nbsp;</li>
			<li class="finish_hour"><?=$hot_board['finish_date_diff_hour']?></li>
			<li class="finish_twinkle">:</li>
			<li class="finish_minute"><?=$hot_board['finish_date_diff_minute']?></li>
			<li class="finish_twinkle">:</li>
			<li class="finish_second"><?=$hot_board['finish_date_diff_second']?></li>
			<input class="finish_time_second" type="hidden" value="<?=$hot_board['finish_date_diff_time_second']?>">
		</ul>

		<div class="info">
			<img class="profile_img" src="<?=$hot_board['profile_way']?>" style="background:white;" width="40px" height="40px" onclick="profile_light_iframe_start(<?=$hot_board['user_seq']?>);" title="프로필 보기">
			<span><div class="user_name mouse_over_underline"><?=$hot_board['name']?></div><br>
			<div class="date_time">
<?
if ($hot_board['diff_check'] == 'none')
{
?>
			<?=$hot_board['year']?>년 <?=$hot_board['month']?>월<?=$hot_board['day']?>일 <!--<?=$hot_board['ampm']?> <?=$hot_board['hour']?>:<?=$hot_board['minute']?>-->
<?
}else if($hot_board['diff_check'] == 'hour'){
?>
			<?=$hot_board['diff']?>시간 전
<?
}else if($hot_board['diff_check'] == 'minute')
{
?>
			<?=$hot_board['diff']?>분 전
<?
}else if($hot_board['diff_check'] == 'now')
{
?>
			방금 전
<?
}
?>
			</div>


			</span>
		</div> <!-- info -->

		<div class="hot_photo_div"  title="자세히 보기" style="margin-bottom:14px;">
			
			<a href="detail_content.php?seq=<?=$hot_board['seq']?>&board=<?=$board_type2?>&mobile=<?=$mobile_check2?>" title="해당 게시물로 이동">
				<div class="hot_photo_div2 hot_photo_div2_<?=$hot_board['seq']?>">
					<img class="hot_con_img_<?=$hot_board['seq']?>" src="<?=$hot_board['photo_way']?>thumbnail/<?=$hot_board['photo']?>" style="  position: relative;" width="100%" onload="hot_issue_img_load(<?=$hot_board['seq']?>);">
				</div>
			</a>
			
			<div class="hot_like" style="margin-top:2px;padding-top:2px;  margin-left: 10px;">
				<span style="font: 0.7em '나눔고딕','Nanum Gothic',sans-serif;  color: rgb(245,130,32);text-decoration: none;vertical-align: middle;">
					<img src="../images/logo_normal.svg" class="like_logo" height="14px"> <?=$hot_board['like_c']?>
					&nbsp; · &nbsp;<img src="../images/comment_icon.svg" height="14px" class="comment_icon"> <?=$hot_board['comment_count']?>
				</span>
			</div>

		</div>
</div> <!-- hot_issue_seq-->
<?
}
?>
	</div>
	</div><!-- hot_issue_content -->
</div>