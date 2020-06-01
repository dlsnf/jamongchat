<?php

include "dbcon.php";

require_once "domain_security.php";

//투데이
$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);


//투데이 출력
$today_start = date('Y-m-d 00:00:00',$stamp);
$today_end = date('Y-m-d 23:59:59',$stamp);

$today_date = date('Y-m-d',$stamp);

//$query="SELECT * FROM visit_count group by ip having date >= '$today_start' and date <= '$today_end'";
$query="SELECT seq, ip, DATE_FORMAT(date, '%Y-%m-%d') as date FROM visit_count group by ip, DATE_FORMAT(date, '%Y-%m-%d') having date = '$today_date'";

$result2=mysql_query($query, $conn);

if( !$result2 ) {
	echo "Failed to list query visit_count_select_today";
	$isSuccess = FALSE;
}

$boardList = array();

while( $row = mysql_fetch_array($result2) ) {
	$board['seq'] = $row['seq'];
	$board['ip'] = $row['ip'];
	$board['date'] = $row['date'];
	array_push($boardList, $board);
}

/*
foreach($boardList as $board) {
	echo "<br>".$board['seq'];
	echo "<br>".$board['ip'];
	echo "<br>".$board['date'];
}
*/


//전체 방문자 출력
$query_total="SELECT seq, ip, DATE_FORMAT(date, '%Y-%m-%d') as date FROM visit_count 
group by ip, DATE_FORMAT(date, '%Y-%m-%d')";
$result2_total=mysql_query($query_total, $conn);

if( !$result2_total ) {
	echo "Failed to list query visit_count_select_total";
	$isSuccess = FALSE;
}

$boardList_total = array();

while( $row_total = mysql_fetch_array($result2_total) ) {
	$board_total['seq'] = $row_total['seq'];
	$board_total['ip'] = $row_total['ip'];
	$board_total['date'] = $row_total['date'];
	array_push($boardList_total, $board_total);
}

/*
echo "<br>Today : ".count($boardList);

echo "<br>Total : ".count($boardList_total);
*/

$visit_count_array = array();

$visit_count_array['today'] = count($boardList);

$visit_count_array['total'] = count($boardList_total);

if($result2_total) {
	echo json_encode($visit_count_array);

	//echo $visit_count_array['total'];
} else {
	echo "처리하지 못했습니다.";
}


?>