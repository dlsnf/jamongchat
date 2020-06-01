 <?php

include "dbcon.php";

session_start(); // 세션을 시작헌다.


//메모리사용 무한
ini_set('memory_limit', -1); 

	$get_people = 1;

		$page = $_GET['page'];

		if($page == '')
		{	
			$page = 1; //페이지 번호가 없으면 1
		}

		$get_people_search = $_GET['people_search'];

		if($get_people_search == '')
		{	
			$get_people_search = ''; 
		}

		
		$list_num = 19; //한 페이지에 보여줄 목록 갯수
		$page_num = 10; //한 화면에 보여줄 페이지 링크(묶음) 갯수
		$offset = $list_num*($page-1); //한 페이지의 시작 글 번호(listnum 수만큼 나누었을 때 시작하는 글의 번호)

		
		if ($get_people_search != '')
		{
			//전체 글 수를 구합니다. (쿼리문을 사용하여 결과를 배열로 저장하는 일반적 인 방법)
			$query="SELECT count(*) FROM classic_people WHERE title LIKE '%$get_people_search%' ORDER BY seq DESC"; // SQL 쿼리문을 문자열 변수에 일단 저장하고
			$result1=mysql_query($query, $conn); // 위의 쿼리문을 실제로 실행하여 결과를 result에 대입

			if( !$result1 ) {
				echo "Failed to list query people_count";
				$isSuccess = FALSE;
			}
			while( $row = mysql_fetch_array($result1) ) {
				$total_no = $row[0];
			}


			//전체 페이지 수와 현재 글 번호를 구합니다.
			$total_page=ceil($total_no/$list_num); // 전체글수를 페이지당글수로 나눈 값의 올림 값을 구합니다.
			$cur_num=$total_no - $list_num*($page-1); //현재 글번호
			 

			//bbs테이블에서 목록을 가져옵니다. (위의 쿼리문 사용예와 비슷합니다 .)
			$query="SELECT * FROM classic_people WHERE title LIKE '%$get_people_search%' ORDER BY seq DESC limit $offset, $list_num"; // SQL 쿼리문
			$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과
			//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


			if( !$result2 ) {
				echo "Failed to list query people_select";
				$isSuccess = FALSE;
			}

			$peopleList = array();

			while( $row = mysql_fetch_array($result2) ) {
				$people['seq'] = $row['seq'];
				$people['name'] = $row['name'];
				$people['email'] = $row['email'];
				$people['birth'] = $row['birth'];
				$people['sns'] = $row['sns'];
				array_push($peopleList, $people);
			}

		}else{
			//전체 글 수를 구합니다. (쿼리문을 사용하여 결과를 배열로 저장하는 일반적 인 방법)
			$query="SELECT count(*) FROM classic_people"; // SQL 쿼리문을 문자열 변수에 일단 저장하고
			$result1=mysql_query($query, $conn); // 위의 쿼리문을 실제로 실행하여 결과를 result에 대입

			if( !$result1 ) {
				echo "Failed to list query people_count";
				$isSuccess = FALSE;
			}
			while( $row = mysql_fetch_array($result1) ) {
				$total_no = $row[0];
			}


			//전체 페이지 수와 현재 글 번호를 구합니다.
			$total_page=ceil($total_no/$list_num); // 전체글수를 페이지당글수로 나눈 값의 올림 값을 구합니다.
			$cur_num=$total_no - $list_num*($page-1); //현재 글번호
			 

			//bbs테이블에서 목록을 가져옵니다. (위의 쿼리문 사용예와 비슷합니다 .)
			$query="SELECT * FROM classic_people ORDER BY seq DESC limit $offset, $list_num"; // SQL 쿼리문
			$result2=mysql_query($query, $conn); // 쿼리문을 실행 결과
			//쿼리 결과를 하나씩 불러와 실제 HTML에 나타내는 것은 HTML 문 중간에 삽입합니다.


			if( !$result2 ) {
				echo "Failed to list query people_select";
				$isSuccess = FALSE;
			}

			$peopleList = array();

			while( $row = mysql_fetch_array($result2) ) {
				$people['seq'] = $row['seq'];
				$people['name'] = $row['name'];
				$people['email'] = $row['email'];
				$people['birth'] = $row['birth'];
				$people['sns'] = $row['sns'];
				array_push($peopleList, $people);
			}
		}
?>

<!doctype html>
<html lang="kr">
	<head>
		<meta charset="UTF-8">
		<link type="text/css" rel="stylesheet" href="css/cssreset.css">
		<link type="text/css" rel="stylesheet" href="css/admin_style.css">
		<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC:300' rel='stylesheet' type='text/css'>
		<title>The Classic Admin</title>
	</head>
	<body>
		<header>	
			<h1>THE<BR>CLASSIC<span>admin</span></h1>
		</header>

		<nav>
			<ul class="menu">
				<li><a href="#">user</a></li>
				<li><a href="#">lightbox</a></li>
			</ul>
			<button>logout</button>
		</nav>

		<article>
			<h1>user</h1>
			<section>
				<ul class="table_top">
					<li>Sequence</li>
					<li>Name</li>
					<li>Date of birth</li>
					<li>E-mail</li>
					<li>SNS</li>
				</ul>
<? 
	foreach($peopleList as $people) {
?>
				<ul class="table_middle">
					<li><?=$people['seq']?></li>
					<li><?=$people['name']?></li>
					<li><?=$people['birth']?></li>
					<li><?=$people['email']?></li>
					<li><?=$people['sns']?></li>
				</ul>
<?
	}
?>

<?
if($people !='')
{
//여기서부터 각종 페이지 링크
//먼저, 한 화면에 보이는 블록($page_num 기본값 이상일 때 블록으로 나뉘어짐 )
$total_block=ceil($total_page/$page_num);
$block=ceil($page/$page_num); //현재 블록
 
$first=($block-1)*$page_num; // 페이지 블록이 시작하는 첫 페이지
$last=$block*$page_num; //페이지 블록의 끝 페이지
 
if($block >= $total_block) {
        $last=$total_page;
}
 
echo "&nbsp;<p class='page_p'align=center>";
//[처음][*개앞]
if($block > 1) {
        $prev=$first-1;
        echo "<a href='index.php?menu=$get_menu&page=1&people_list=$get_people_list&people_search=$get_people_search'><img src='images/btn_1st_page.png'></a>&nbsp;&nbsp;";
        //echo "<a href='index.php?page=$prev'>[$page_num 개 앞]</a>";
}
 
//[이전]
if($page > 1) {
        $go_page=$page-1;
        echo "<a href='index.php?menu=$get_menu&page=$go_page&people_list=$get_people_list&people_search=$get_people_search'><img src='images/btn_left_page.png'></a>&nbsp;";
}else{
		echo "<img src='images/btn_left_page.png'>&nbsp;";
}
 
//페이지 링크
for ($page_link=$first+1;$page_link<=$last;$page_link++) {
        if($page_link==$page) {
                echo "<span class='page_on'><font color=white><b>$page_link</b></font></span>";
        }
        else {
                echo "<a href='index.php?menu=$get_menu&page=$page_link&people_list=$get_people_list&people_search=$get_people_search' class='page'>$page_link</a>";
        }
}
 
//[다음]
if($total_page > $page) {
        $go_page=$page+1;
        echo "&nbsp;<a href='index.php?menu=$get_menu&page=$go_page&people_list=$get_people_list&people_search=$get_people_search'><img src='images/btn_right_page.png'></a>";
}else{
		echo "&nbsp;<img src='images/btn_right_page.png'>";
}
 
//[*개뒤][마지막]
if($block < $total_block) {
        $next=$last+1;
        //echo "<a href='index.php?page=$next'>[$page_num 개 뒤]</a>&nbsp;";
        echo "&nbsp;&nbsp;<a href='index.php?menu=$get_menu&page=$total_page&people_list=$get_people_list&people_search=$get_people_search'><img src='images/btn_last_page.png'></a></p>";
}
echo "&nbsp;</p>";
}
?>
				<button>Excel download</button>
			</section>
		</article>

		<footer>
			<p>
				Copyright © 2014 JamongSoft All rights reserved.
			</p>
		</footer>
	</body>
</html>
