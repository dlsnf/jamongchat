<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];
$get_date = date("Y-m-d H:i:s");

$get_seq = $_POST['seq'];

$profile_domain_name = "http://".$_SERVER["SERVER_NAME"]."/jamongchat/upload/up_profile/thumbnail/";

		$query="SELECT coo.*, uoo.seq uoo_seq, uoo.email uoo_email, uoo.profile_way uoo_profile_way, uoo.profile uoo_profile, uoo.name uoo_name FROM jamong_chat_comment coo LEFT JOIN jamong_chat_user uoo ON coo.user_seq = uoo.seq WHERE board_seq = '$get_seq' ORDER BY coo.like_comment DESC, coo.date ASC"; // SQL 쿼리문
		
				
		$result=mysql_query($query, $conn) or die (mysql_error()); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


		if( !$result ) {
			echo "Failed to list query comment_reload_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['user_seq'] = $row['user_seq'];
			$board['board_seq'] = $row['board_seq'];
			$board['body'] = $row['body'];
			$board['like_comment'] = $row['like_comment'];
			$board['photo_way'] = $row['photo_way'];
			$board['photo'] = $row['photo'];
			$board['date'] = $row['date'];
			$board['uoo_seq'] = $row['uoo_seq'];
			$board['uoo_email'] = $row['uoo_email'];
			$board['uoo_profile_way'] = $row['uoo_profile_way'];
			$board['uoo_profile'] = $row['uoo_profile'];
			$board['uoo_name'] = $row['uoo_name'];



			//프로필이 없을경우
			if($board['uoo_profile_way'] == '')
			{
				$board['uoo_profile_way'] = $profile_domain_name."btn_youtb.png";
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

			$temp_seq = $board['seq'];

			/*
			//like 갯수			
			$query3="SELECT count(distinct(like_user_seq)) as count FROM jamong_chat_like WHERE like_seq = '$temp_seq' AND like_status = 'comment'"; // SQL 쿼리문
			
			
			$result3=mysql_query($query3, $conn); // 쿼리문을 실행 결과

			if( !$result3 ) {
				echo "Failed to list query comment_reload_ajax2";
				$isSuccess = FALSE;
			}

			while( $row3 = mysql_fetch_array($result3) ) {
				$board['like_comment'] = $row3['count'];
			}
			*/
			

			array_push($boardList, $board);
		}

if($result) {
	echo json_encode($boardList);

} else {
	echo "처리하지 못했습니다.";
}

?>