<?php

include "dbcon.php";

include "domain_security.php";

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);

$get_seq = $_POST['seq'];



		$query ="SELECT * FROM jamong_chat_freeboard WHERE seq = '$get_seq'";

		$result=mysql_query($query, $conn); // 쿼리문을 실행 결과
		//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.

		if( !$result ) {
			echo "Failed to list query content_text_more_ajax.php";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['body'] = nl2br(strip_tags($row['body']));
			
			array_push($boardList, $board);
		}


if($result) {
	echo json_encode($boardList);
} else {
	echo "처리하지 못했습니다.";
}

?>