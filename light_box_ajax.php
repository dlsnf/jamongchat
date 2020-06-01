<?php

include "dbcon.php";

$get_ip = $_SERVER['REMOTE_ADDR'];
$get_date = date("Y-m-d H:i:s");

$get_seq = $_POST['seq'];


		$query="SELECT * FROM jamong_chat_freeboard WHERE seq = $get_seq"; // SQL 쿼리문
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
			$board['profile'] = $row['profile'];
			$board['body'] = $row['body'];
			$board['like'] = $row['like'];
			$board['photo_way'] = $row['photo_way'];
			$board['photo'] = $row['photo'];
			$board['date'] = $row['date'];
			$board['ip'] = $row['ip'];

			array_push($boardList, $board);
		}

if($result) {
	echo json_encode($boardList);
} else {
	echo "처리하지 못했습니다.";
}
mysql_close();
?>