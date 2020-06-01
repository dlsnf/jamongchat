<?

header ( "Content-Type : text / html; charset=utf-8");

// db 연결부분
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_db = "";

$isSuccess = TRUE;

$conn = @mysql_connect($mysql_host, $mysql_user, $mysql_password);

if( !$conn ) {
	echo "Failed to mysql_connect ";
	$isSuccess = FALSE;
}else
mysql_select_db($mysql_db, $conn);

mysql_query("set session character_set_connection=utf8;");
mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=utf8;");
//****** mysql_query("set session character_set_connection=euckr;");

/* count query for clients*/

/*
$sqlCount = "SELECT count(1) as cnt FROM client "; 

$resultCount = mysql_query($sqlCount, $conn);
if( !$resultCount ) {
	echo "Failed to count query ";
	$isSuccess = FALSE;
}

if( $row = mysql_fetch_array($resultCount) ) {
	$totalCount = $row['cnt'];
}

$sqlList  = "SELECT c_name, c_addr, c_phone, gift.g_state as g_state, client.fp_seq as fp_seq ";  
$sqlList .= "FROM client LEFT JOIN gift on client.g_seq = gift.g_seq ";
$sqlList .= "order by client.fp_seq";


$resultList = mysql_query($sqlList, $conn);;
if( !$resultList ) {
	echo "Failed to list query1";
	$isSuccess = FALSE;
}

$client_list = array();

while( $row = mysql_fetch_array($resultList) ) {

	$clients['c_name'] = $row['c_name'];
	$clients['c_addr'] = $row['c_addr'];
	$clients['c_phone'] = $row['c_phone'];
	if($row['g_state'] == NULL){
		$clients['g_state'] = "신청 전";
	} else{
		$clients['g_state'] = $row['g_state'];
	}
	$clients['fp_seq'] = $row['fp_seq'];

	array_push($client_list, $clients);
}
*/

mysql_close();

?>