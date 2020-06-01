<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);

$get_seq = $_POST['last_seq'];
$get_type = $_POST['get_type'];
$get_search = $_POST['search'];

//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}


//게시판 종류
$board_type2 = content($_POST['board']);
$board_type = $board_type2;
if($board_type == '')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'free')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'fashion')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'selfie')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'food')
{
	$board_type = "jamong_chat_freeboard";
}else if($board_type == 'photo')
{
	$board_type = "jamong_chat_freeboard";
}else{
	echo "잘못된 경로입니다";
	exit;
}


$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";

		//몇번째 글인지 분석하는 코딩
		if($get_type == "search")//검색화면일때
		{
			//전체검색
			if($board_type2 == '')
			{
				$query2 ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE ( boo.body LIKE '%$get_search%' OR uoo.name LIKE '%$get_search%' ) ORDER BY boo.seq DESC";
			}else{

				$query2 ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE ( boo.body LIKE '%$get_search%' OR uoo.name LIKE '%$get_search%' ) AND boo.board_type = '$board_type2' ORDER BY boo.seq DESC";
			}

		}else if($get_type == "hot")//핫이슈화면일때
		{
			//전체검색
			if($board_type2 == '')
			{
				$query2 ="SELECT seq, board_type, date FROM jamong_chat_freeboard ORDER BY like_c DESC, date ASC";
			}else{
				$query2 ="SELECT seq, board_type, date FROM jamong_chat_freeboard WHERE board_type = '$board_type2' ORDER BY like_c DESC, date ASC";
			}

			
		}else{
			$query2 ="SELECT seq, board_type from jamong_chat_freeboard WHERE board_type = '$board_type2' ORDER BY seq DESC";
		}

		

		//$query="SET @n=0; SELECT foo.*, @n := @n+1 AS rownum from jamong_chat_freeboard foo ORDER BY foo.seq DESC"; 

		//$query="SET @n=0;SELECT foo.*, @n := @n+1 AS rownum from jamong_chat_freeboard foo ORDER BY  foo.seq DESC LIMIT '$get_seq',5"; // SQL 쿼리문
		
		$result2=mysql_query($query2, $conn); // 쿼리문을 실행 결과

		if( !$result2 ) {
			echo "Failed to list query content_more_ajax2.php";
			$isSuccess = FALSE;
		}

		$boardList2 = array();
		$count = 0;

		while( $row2 = mysql_fetch_array($result2) ) {
			$count++;
			$board2['seq'] = $row2['seq'];
			
			if($get_seq == $board2['seq'])
			{
				array_push($boardList2, $board2);
				break;
			}

			array_push($boardList2, $board2);
		}


		//쿼리때리기
		if($get_type == "search")//검색화면일때
		{
			//전체검색
			if($board_type2 == '')
			{
				$query ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE ( boo.body LIKE '%$get_search%' OR uoo.name LIKE '%$get_search%' ) ORDER BY boo.seq DESC LIMIT $count,5";

			}else{
				$query ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE ( boo.body LIKE '%$get_search%' OR uoo.name LIKE '%$get_search%' ) AND boo.board_type = '$board_type2' ORDER BY boo.seq DESC LIMIT $count,5";
			}
			

		}else if($get_type == "hot")//핫이슈화면일때
		{
			//전체검색
			if($board_type2 == '')
			{
				$query ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq ORDER BY boo.like_c DESC, boo.date ASC limit $count,5";
			}else{
				$query ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE boo.board_type = '$board_type2' ORDER BY boo.like_c DESC, boo.date ASC limit $count,5";
			}

			
		}else{//일반화면
			$query ="SELECT boo.*, uoo.seq uoo_seq, uoo.name uoo_name, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile FROM jamong_chat_freeboard boo LEFT JOIN jamong_chat_user uoo ON boo.user_seq = uoo.seq WHERE boo.board_type = '$board_type2' ORDER BY boo.seq DESC LIMIT $count,5";
		}
		

		$result=mysql_query($query, $conn); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.

		if( !$result ) {
			echo "Failed to list query content_more_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['user_seq'] = $row['user_seq'];
			$board['name'] = strip_tags($row['uoo_name']);
			$board['profile_way'] = $row['uoo_profile_way'];
			$board['profile'] = $row['uoo_profile'];
			$board['body'] = nl2br(strip_tags($row['body']));
			$board['like_c'] = $row['like_c'];
			$board['photo_way'] = $row['photo_way'];
			$board['photo'] = $row['photo'];
			$board['date'] = $row['date'];
			$board['finish_date'] = $row['finish_date'];
			$board['ip'] = $row['ip'];

			$board['count'] = $count;

			//프로필이 없을경우
			if($board['profile_way'] == '')
			{
				$board['profile_way'] = $profile_domain_name."btn_youtb.png";
			}

			//시간
			$date_time = explode(" ",$board['date']);
			$date = $date_time[0];
			$time = $date_time[1];

			$date_array =  explode("-",$date);
			$board['year'] = intval($date_array[0]);
			$board['month'] = intval($date_array[1]);
			$board['day'] = intval($date_array[2]);

			$time_array =  explode(":",$time);
			$board['hour'] = intval($time_array[0]);
			$board['minute'] = intval($time_array[1]);
			$board['second'] = intval($time_array[2]);

			if( $board['hour'] > 12)
			{
				$board['ampm'] = "오후";
				$board['hour'] = $board['hour'] - 12;
			}else{
				$board['ampm'] = "오전";
			}

			if( $board['minute'] < 10)
			{
				$board['minute'] = "0".$board['minute'];
			}

			
			
			$date1 = new DateTime($board['date']);
			$date2 = new DateTime($get_date);

			$date3 = date_diff($date1, $date2);
			
			$diff = $date3->days * 1440 + $date3->h * 60 + $date3->i;

			if($diff == 0)
			{
				$board['diff_check'] = 'now';
			}else if( floor($diff/60) == 0 )
			{
				$board['diff'] = $diff;
				$board['diff_check'] = 'minute';
			}else if (floor($diff/60) < 11)
			{
				$board['diff'] = floor($diff/60);
				$board['diff_check'] = 'hour';
			}else{
				$board['diff_check'] = 'none';
			}

			//자동 폭파시간
			$today_date = new DateTime($get_date);
			$finish_date = new DateTime($board['finish_date']);

			//오늘시간과 폭파시간 차이 구하기
			$finish_date_diff = date_diff($today_date, $finish_date);

			$finish_date_diff_minute = $finish_date_diff->days * 1440 + $finish_date_diff->h * 60 + $finish_date_diff->i;
			$finish_date_diff_second = $finish_date_diff->days * 86400 + $finish_date_diff->h * 3600 + $finish_date_diff->i * 60 + $finish_date_diff->s;
			
			//시간 차이
			$board['finish_date_diff_time_minute'] = $finish_date_diff_minute;
			$board['finish_date_diff_time_second'] = $finish_date_diff_second;

			$board['finish_date_diff_day'] = floor($finish_date_diff_second/86400);
			if($board['finish_date_diff_day'] == 0)
			{
				$board['finish_date_diff_day'] = '';
			}
			$board['finish_date_diff_hour'] = floor($finish_date_diff_second%86400/3600);
			$board['finish_date_diff_minute'] = floor($finish_date_diff_second%86400%3600/60);
			$board['finish_date_diff_second'] = floor($finish_date_diff_second%86400%3600%60);

			//줄수
			$board['body_dot']='';
			$board['line_cnt'] =0; # 총 라인수
			$set_str = 76; # 한라인에 들어가는 바이트수
			$tmp = explode("\n", $board['body']); # 3줄

			$tmp_int = count($tmp);
			for( $i =0; $i < $tmp_int; ++$i ){
			$board['line_cnt'] += ceil( strlen($tmp[$i]) / $set_str ); # 한줄을 한줄에 들어갈수 있는 바이트로 나누면 되겠어요 ^^
			}
			mb_internal_encoding ( 'UTF-8' );

			if ($board['line_cnt'] > 5)
			{
				if($tmp > 3) //엔터로 친것이 3줄 이상일 경우
				{
					//$board['body'] = mb_strcut ( $board['body'] , 0 ,20,'utf-8'); //100바이트까지 표시
					$board['body'] = mb_substr( $board['body'] , 0 ,51,'utf-8'); //170자까지 표시
					$board['body_dot'] = "...";
				}else{
					//$board['body'] = mb_strcut ( $board['body'] , 0 ,76,'utf-8'); //100바이트까지 표시
					$board['body'] = mb_substr( $board['body'] , 0 ,170,'utf-8'); //170자까지 표시
					$board['body_dot'] = "...";
				}
			}


			$temp_seq = $board['seq'];

			//like 갯수			
			$query3="SELECT count(distinct(like_user_seq)) as count FROM jamong_chat_like WHERE like_seq = '$temp_seq' AND like_status = 'content'"; // SQL 쿼리문
			
			
			$result3=mysql_query($query3, $conn); // 쿼리문을 실행 결과

			if( !$result3 ) {
				echo "Failed to list query index_like_count";
				$isSuccess = FALSE;
			}

			while( $row3 = mysql_fetch_array($result3) ) {
				$board['like_c'] = $row3['count'];
			}
			
			array_push($boardList, $board);
		}


if($result) {
	echo json_encode($boardList);
} else {
	echo "처리하지 못했습니다.";
}

?>