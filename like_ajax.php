<?php

include "dbcon.php";

include "domain_security.php";


$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = mktime();
$get_date = date('Y-m-d H:i:s', $stamp);

session_start(); // 세션을 시작헌다.

$get_seq = $_POST['seq'];
$get_user_seq = $_POST['user_seq'];
$get_user_seq2 = $_SESSION['user_seq'];

if(isset($_SESSION['user_seq']))
{
}else{ //세션이 없을때는 에러처리
	echo "잘못된 접근입니다.";
	exit;
}

/*
//토큰
$second="600"; //시간초 지정 60 = 1분 저는 넉넉히 10분으로 하겠습니다. 
$time=date("YmdHis") -$_SESSION['tokensave'];
if(!$_SESSION['token'] or !$_SESSION['tokensave'] or !$_SESSION['fake'] or !$_POST['l_token']){echo "토큰이 유효하지 않습니다. 페이지를 새로고침 해주세요."; exit;}

//if($time<$second && $_SESSION['fake'] == $_POST['l_token']){ } //시간제한토큰

if($_SESSION['fake'] == $_POST['l_token']){
//echo $_POST['l_token'];
//echo "성공";


}else{echo "토큰이 유효하지 않습니다.  페이지를 새로고침 해주세요.2"; echo "\n".$_SESSION['fake']."\n".$_POST['l_token']; exit;}
*/

$get_status = $_POST['status'];
$get_like_check = $_POST['like_check'];
$get_disable = $_POST['disable'];

if ($get_disable == 1) //라이트박스에서는 카운트 중지
{
	$res = 1;
	$ajax_check=1;

}else{

	//좋아요 한적이 있는지 체크
	$sql_like_check = "SELECT count(distinct(like_user_seq)) as count FROM jamong_chat_like WHERE like_status = '$get_status' AND like_seq = '$get_seq' AND like_user_seq = '$get_user_seq2'";
	$res_like_check = mysql_query($sql_like_check,$conn);

	if( !$res_like_check ) {
	echo "Failed to list like_ajax_like_check.php";
	$isSuccess = FALSE;
	}

	while( $row = mysql_fetch_array($res_like_check) ) {
		$like_check = $row['count'];
	}

	//echo $like_check."\n".$get_status."\n".$get_seq."\n".$get_user_seq2;


	if($get_like_check == 1) //좋아요 등록
	{
		

		//좋아요를 등록한 적이 없을때만 좋아요 등록
		if($like_check == 0)
		{
			$ajax_check=0;

			$sqlInsert = "INSERT INTO jamong_chat_like(like_status, like_seq, like_user_seq, like_count, date, ip) VALUES ('$get_status', '$get_seq', '$get_user_seq', 1, '$get_date', '$get_ip')";
			$res = mysql_query($sqlInsert,$conn);

			if( !$res ) {
			echo "Failed to list content_like_ajax.php";
			$isSuccess = FALSE;
			}else{
				$ajax_check=2;
			}

			//좋아요 카운트 증가
			if($ajax_check == 2)
			{
				if( $get_status =="content" )
				{
					$sqlInsert2 = "UPDATE jamong_chat_freeboard SET like_c= like_c+1 WHERE seq = '$get_seq'";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list content_like_ajax_count1.php";
					$isSuccess = FALSE;
					}else{
						$ajax_check=1;
					}
					
					
					//포인트 증가를 위한 작성자 조회
					$query="SELECT * FROM jamong_chat_freeboard WHERE seq = '$get_seq'";
					$result=mysql_query($query, $conn);

					if( !$result ) {
						echo "Failed to list query like_ajax_user_search.php";
						$isSuccess = FALSE;
					}

					while( $row = mysql_fetch_array($result) ) {
						$board['seq'] = $row['seq'];
						$board_write_user = $row['user_seq'];
					}

					//포인트 증가
					$sqlInsert2 = "UPDATE jamong_chat_user SET point= point+1 WHERE seq = '$board_write_user'";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list like_ajax_point 1.php";
					$isSuccess = FALSE;
					}
					
					//등급 검사
					rating($board_write_user,$conn);

				}else if($get_status =="comment")
				{
					$sqlInsert2 = "UPDATE jamong_chat_comment SET like_comment= like_comment+1 WHERE seq = '$get_seq'";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list content_like_ajax_count1.php";
					$isSuccess = FALSE;
					}else{
						$ajax_check=1;
					}

					//포인트 감소를 위한 작성자 조회
					$query="SELECT * FROM jamong_chat_comment WHERE seq = '$get_seq'";
					$result=mysql_query($query, $conn);

					if( !$result ) {
						echo "Failed to list query like_ajax_user_search.php";
						$isSuccess = FALSE;
					}

					while( $row = mysql_fetch_array($result) ) {
						$board['seq'] = $row['seq'];
						$board_write_user = $row['user_seq'];
					}

					//포인트 증가
					$sqlInsert2 = "UPDATE jamong_chat_user SET point= point+1 WHERE seq = '$board_write_user'";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list like_ajax_point 2.php";
					$isSuccess = FALSE;
					}

					//등급 검사
					rating($board_write_user,$conn);


				}

			}

			//폭파시간 한시간 연장
			$sqlUpdate = "UPDATE jamong_chat_freeboard SET finish_date=date_add(finish_date, interval +1 hour) WHERE seq = '$get_seq'";
			$res_update = mysql_query($sqlUpdate,$conn);

			if( !$res_update ) {
			echo "Failed to list _update.php";
			$isSuccess = FALSE;
			}else{
				$ajax_check=1;
			}
			

		}//좋아요를 등록한 적이 없을때만 좋아요 등록

	}else if($get_like_check == 2) //좋아요 취소
	{
		//좋아요를 등록한 적이 있을때만 좋아요 등록
		if($like_check)
		{
			$ajax_check=0;

			$sqlInsert = "DELETE FROM jamong_chat_like WHERE like_status = '$get_status' AND like_seq = '$get_seq' AND like_user_seq ='$get_user_seq'";
			$res = mysql_query($sqlInsert,$conn);

			if( !$res ) {
			echo "Failed to list content_like_ajax2.php";
			$isSuccess = FALSE;
			}else{
				$ajax_check=2;
			}

			//좋아요 카운트 감소
			if($ajax_check == 2)
			{
				if( $get_status =="content" )
				{
					$sqlInsert2 = "UPDATE jamong_chat_freeboard SET like_c= like_c-1 WHERE seq = '$get_seq' AND like_c != 0";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list content_like_ajax_count2.php";
					$isSuccess = FALSE;
					}else{
						$ajax_check=1;
					}

					//작성자 조회
					$query="SELECT * FROM jamong_chat_freeboard WHERE seq = '$get_seq'";
					$result=mysql_query($query, $conn);

					if( !$result ) {
						echo "Failed to list query like_ajax_user_search.php";
						$isSuccess = FALSE;
					}

					while( $row = mysql_fetch_array($result) ) {
						$board['seq'] = $row['seq'];
						$board_write_user = $row['user_seq'];
					}

					//포인트 감소
					$sqlInsert2 = "UPDATE jamong_chat_user SET point= point-1 WHERE seq = '$board_write_user'";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list like_ajax_point.php 3";
					$isSuccess = FALSE;
					}

					//등급 검사
					rating($board_write_user,$conn);

				}else if($get_status =="comment")
				{
					$sqlInsert2 = "UPDATE jamong_chat_comment SET like_comment = like_comment-1 WHERE seq = '$get_seq' AND like_comment != 0";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list content_like_ajax_count2.php";
					$isSuccess = FALSE;
					}else{
						$ajax_check=1;
					}

					//작성자 조회
					$query="SELECT * FROM jamong_chat_comment WHERE seq = '$get_seq'";
					$result=mysql_query($query, $conn);

					if( !$result ) {
						echo "Failed to list query like_ajax_user_search.php";
						$isSuccess = FALSE;
					}

					while( $row = mysql_fetch_array($result) ) {
						$board['seq'] = $row['seq'];
						$board_write_user = $row['user_seq'];
					}

					//포인트 감소
					$sqlInsert2 = "UPDATE jamong_chat_user SET point= point-1 WHERE seq = '$board_write_user'";
					$res2 = mysql_query($sqlInsert2,$conn);

					if( !$res2 ) {
					echo "Failed to list like_ajax_point.php 4";
					$isSuccess = FALSE;
					}

					//등급 검사
					rating($board_write_user,$conn);

				}

			}

			//폭파시간 한시간 감소
			$sqlUpdate = "UPDATE jamong_chat_freeboard SET finish_date=date_add(finish_date, interval -1 hour) WHERE seq = '$get_seq'";
			$res_update = mysql_query($sqlUpdate,$conn);

			if( !$res_update ) {
			echo "Failed to list _update.php";
			$isSuccess = FALSE;
			}else{
				$ajax_check=1;
			}

		}//좋아요를 등록한 적이 있을때만 좋아요 등록

	}

}

if($res) {
	echo ($ajax_check);
} else {
	echo "처리하지 못했습니다.";
}
mysql_close();

//랭크
function rating($e,$conn)
{
	if( $e >= 1 ) //관리자 계정이 아닐때만
	{
		//사용자 조회
		$query="SELECT seq, point FROM jamong_chat_user WHERE seq = '$e'";
		$result=mysql_query($query, $conn);

		if( !$result ) {
			echo "Failed to list query rating_ajax.php";
			$isSuccess = FALSE;
		}

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['point'] = $row['point'];

			

			if ($board['point'] >= 10000000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Challenger' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 1000000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Master' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 500000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Diamond 1' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 400000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Diamond 2' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 300000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Diamond 3' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 200000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Diamond 4' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 100000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Diamond 5' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 50000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Platinum 1' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 40000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Platinum 2' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 30000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Platinum 3' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 20000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Platinum 4' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 10000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Platinum 5' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 5000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Gold 1' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 4000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Gold 2' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 3000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Gold 3' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 2000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Gold 4' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 1000 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Gold 5' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 500 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Silver 1' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 400 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Silver 2' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 300 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Silver 3' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 200 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Silver 4' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 100 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Silver 5' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 50 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Bronze 1' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 40 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Bronze 2' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 30 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Bronze 3' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 20 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Bronze 4' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] >= 10 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Bronze 5' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);


				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}else if ($board['point'] <= 0 )
			{
				//포인트 감소
				$sqlInsert2 = "UPDATE jamong_chat_user SET rating='Stone' WHERE seq = '$e'";
				$res2 = mysql_query($sqlInsert2,$conn);

				if( !$res2 ) {
				echo "Failed to list rating_ajax2.php";
				$isSuccess = FALSE;
				}
			}

		}
	}


	
}

?>