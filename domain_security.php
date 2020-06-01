<?php

//외부접근 막기
$get_domain = explode("/",$_SERVER["HTTP_REFERER"]);

if( $get_domain[2] == 'hansbuild.cafe24.com') {
	//echo $get_domain[2];
}
else {
 echo "비정상적인 접근입니다.";
// echo $get_domain[2];
 exit;
}

?>