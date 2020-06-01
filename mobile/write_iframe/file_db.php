<?php

include "../../dbcon.php";

include "../../domain_security.php";

session_start(); // 세션을 시작헌다.

header("Content-Type: text/html; charset=utf-8");

//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}

$get_ip = $_SERVER['REMOTE_ADDR'];

$user_seq=$_SESSION['user_seq'];
$user_name=$_SESSION['user_name'];
$user_email=$_SESSION['user_email'];

if(isset($_SESSION['user_seq']))
{
}else{ //세션이 없을때는 에러처리
	echo "잘못된 접근입니다.";
	exit;
}

$dir = '/var/www';
$free = disk_free_space("/var/www");
$total = disk_total_space("/var/www");
$free_to_mbs = round( $free / ((1024*1024)*1024), 1);
$total_to_mbs = round( $total / ((1024*1024)*1024), 1);
//echo 'You have '.$free_to_mbs.' GBs from '.$total_to_mbs.' total GBs';

if ($free_to_mbs <= 2)
{
echo 10;
exit();
}


if(isset($_FILES['chosenFile']['name']))
{
}else{ //파일이 없을때는 에러처리
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

}else{echo "토큰이 유효하지 않습니다.  페이지를 새로고침 해주세요.2"; exit;}

*/

$get_write_body = addslashes($_POST['write_body']);

$get_user_email = $_POST['user_email'];


//게시판 종류
$board_type = content($_POST['board']);

if($board_type == '')
{
	$board_type = "free";
}else if($board_type == 'free')
{
	$board_type = "free";
}else if($board_type == 'fashion')
{
	$board_type = "fashion";
}else if($board_type == 'selfie')
{
	$board_type = "selfie";
}else if($board_type == 'food')
{
	$board_type = "food";
}else if($board_type == 'photo')
{
	$board_type = "photo";
}else{
	echo "잘못된 경로입니다";
	exit;
}


$stamp = mktime();
$date = date('Y-m-d H:i:s', $stamp);

//finish_date 하루 뒤에 삭제 구하기
$today_date = new DateTime($date);

//두번째 날짜
$seconds = 60*60*24;
$finish_date = $today_date->add(new DateInterval('PT'.$seconds.'S')); // P10D 10일을 더한다 , PT10S 10초를 더한다
$finish_date = $finish_date->format('Y-m-d H:i:s');


$get_date2 = date('Y-m-d', $stamp);

$get_time = date("H:i");



if(isset($_SESSION['user_email']))
{

//임시 디렉토리
$directory = "../../upload/up_img/";

//해당 디렉토리 안에 폴더 존재 유무
$file_date_dir = $get_date2; //ex 2015_04_30	
if(is_dir($directory.$file_date_dir)){

}else{
	//echo "폴더 존재 X";
	umask(0);
	mkdir($directory.$file_date_dir, 0755); //폴더 생성
}

//해당 디렉토리 안에 파일 개수 알아내기
$directory = "../../upload/up_img/".$file_date_dir."/"; // 이건 님의 최종 디렉토리
if (glob($directory . "*") != false){
$count = count(glob($directory . "*"));

}

if($count == '')
{
	$count = 0;
}

$count++;



################파일 업로드를 위해 추가된 부분 : 시작 ######################### 

// 업로드한 파일이 저장될 디렉토리 정의
$target_dir = $directory;  // 서버에 up 이라는 디렉토리가 있어야 한다.

//$filename = iconv("utf-8","euc-kr",$count . "_" . $_FILES['fans_write_file']['name']); //업로드할때 인코딩
//$filename =iconv("EUC-KR","UTF-8",$_FILES['upfile']['name']);//다운로드할때 인코딩

$file_name = $_FILES['chosenFile']['name'];//파일이름 dd.png

$file_name_arr = explode(".",$file_name); //파일이름 배열 dd , png
$file_type = end($file_name_arr); //배열의 마지막부분 (png)

$extension = strtolower($file_type); //파일 확장자명 소문자로



$file_name_web = iconv("utf-8","euc-kr", $count . "_" . $date . "." . $extension); //웹상에서 쓸 이름 인코딩해야함
$uploadfile = $target_dir . $file_name_web; // 웹상에서 쓸 파일 경로

//확장자명 검사
//$file_type=explode(".", $_FILES['fans_write_file']['name']);

//temp_file
$tmp_file = $_FILES["chosenFile"]["tmp_name"];

//dest_file
$dest_file = "../../upload/up_img/" . $get_date2 . "/" . $file_name_web;


if(!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'bmp'|| $extension == 'gif')){
	$up_chack=1;
	?><?=$file_name?><?
}else if($_FILES['chosenFile']['size'] > 10000*100*11){ //11MB
	$up_chack=1;
	?>2<?	
	}else if(!strcmp($extension,"html") || 
       !strcmp($extension,"htm") ||
       !strcmp($extension,"php") ||      
       !strcmp($extension,"inc") ||
		!strcmp($extension,"HTML") ||
		!strcmp($extension,"HTM") ||
		!strcmp($extension,"PHP") ||
		!strcmp($extension,"INC")){
			$up_chack=1;
			?>3<?
	}else if(file_exists($target_dir . $file_name_web)) {  // 동일한 파일이 있는지 확인하는 부분
      	$up_chack=1;
		?>4<?
	 }else{
		//사진회전 체크
		$exifData = exif_read_data($tmp_file);

        if($exifData['Orientation'] == 6) { 
            // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
            $degree = 270; 
        } 
        else if($exifData['Orientation'] == 8) { 
            // 반시계방향으로 90도 돌려줘야 정상 
            $degree = 90; 
        } 
        else if($exifData['Orientation'] == 3) { 
            $degree = 180; 
        } 

        if($degree) { 

            if($exifData[FileType] == 1) { 
                $source = imagecreatefromgif($tmp_file); 
                $source = imagerotate ($source , $degree, 0); 
                imagegif($source, $dest_file); 

				//썸네일 저장 함수
				thumbnail($directory,$file_name_web);

				//디비등록
				$up_chack = 2;				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (board_type, user_seq, body, like_c, photo_way, photo, date, finish_date, ip) VALUES ('$board_type' ,'$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/upload/up_img/$file_date_dir/', '$file_name_web', '$date', '$finish_date', '$get_ip')";

				$res = mysql_query($sqlInsert,$conn);
				
				if(!$res)
				{
					?>6<?
				}else{
					?>7<?
				}
            } 
            else if($exifData[FileType] == 2) { 
                $source = imagecreatefromjpeg($tmp_file); 
                $source = imagerotate ($source , $degree, 0); 
                imagejpeg($source, $dest_file); 


				//썸네일 저장 함수
				thumbnail($directory,$file_name_web);
				
				//디비등록
				$up_chack = 2;				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (board_type ,user_seq, body, like_c, photo_way, photo, date, finish_date, ip) VALUES ('$board_type' ,'$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/upload/up_img/$file_date_dir/', '$file_name_web', '$date', '$finish_date', '$get_ip')";

				$res = mysql_query($sqlInsert,$conn);
				
				if(!$res)
				{
					?>6<?
				}else{
					?>7<?
				}

            } 
            else if($exifData[FileType] == 3) { 
                $source = imagecreatefrompng($tmp_file); 
                $source = imagerotate ($source , $degree, 0); 
                imagepng($source, $dest_file); 

				//썸네일 저장 함수
				thumbnail($directory,$file_name_web);

				//디비등록
				$up_chack = 2;				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (board_type , user_seq, body, like_c, photo_way, photo, date, finish_date, ip) VALUES ('$board_type' ,'$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/upload/up_img/$file_date_dir/', '$file_name_web', '$date', '$finish_date', '$get_ip')";

				$res = mysql_query($sqlInsert,$conn);
				
				if(!$res)
				{
					?>6<?
				}else{
					?>7<?
				}
            } 

            imagedestroy($source); 
        }else{ 
            //파일업로드
			if(!move_uploaded_file($tmp_file, $directory . $file_name_web ))
			{
				echo "파일 업로드 실패<br>";
				echo $file_name_web;
				?>5<?
			}else{

				//썸네일 저장 함수
				thumbnail($directory,$file_name_web);

				$up_chack = 2;
				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (board_type, user_seq, body, like_c, photo_way, photo, date, finish_date, ip) VALUES ('$board_type' ,'$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/upload/up_img/$file_date_dir/', '$file_name_web', '$date', '$finish_date', '$get_ip')";

				$res = mysql_query($sqlInsert,$conn);
				
				if(!$res)
				{
					?>6<?
				}else{
					?>7<?
				}
			} 
		
		

		}
		//업로드할땐 꼭 iconv인코딩 된걸로

	 }
################파일 업로드를 위해 추가된 부분 : 끝 ######################### 
}else{
 echo "로그아웃 되었습니다.";
// echo $get_domain[2];
 exit;
}


//섬네일 저장함수
function thumbnail($directory,$file_name_web)
{
	//이미지 사이즈 가져오기
	$info_image=getimagesize($directory.$file_name_web);
	
	$w = $info_image[0]; //가로사이즈
	$h = $info_image[1]; //세로사이즈
/*
	echo "가로:".$w; 
	echo "세로:".$h;
	echo "확장자:".$info_image['mime'];
*/
	//해당 디렉토리 안에 파일 존재 유무
	$file_date_dir = "thumbnail";	
	if(is_dir($directory.$file_date_dir)){

	}else{
		//echo "폴더 존재 X";
		umask(0);
		mkdir($directory.$file_date_dir, 0755); //폴더 생성
	}

/*
	//동일파일 삭제
	if(is_file($directory."thumbnail/".$file_name_web)){
		//echo "파일삭제";
		//echo $directory.$board['profile'];
		unlink($directory."thumbnail/".$file_name_web);
	}
*/

	switch($info_image['mime']){
		case "image/gif":
		$get_type = "gif";
		$origin_img=imagecreatefromgif($directory.$file_name_web);
		break;
		case "image/jpeg":
		$get_type = "jpeg";
		$origin_img=imagecreatefromjpeg($directory.$file_name_web);
		break;
		case "image/png":
		$get_type = "png";
		$origin_img=imagecreatefrompng($directory.$file_name_web);
		break;
		case "image/bmp":
		$get_type = "bmp";
		$origin_img=imagecreatefrombmp($directory.$file_name_web);
		break;
	}

	//사진 비율 구하기
	//가로 : 세로 = 1 : 세로/가로

	if($w >= 800 || $h >= 800) //이미지 사이즈가 긴축이 800px 이상이면 줄여줌
	{
		if($w >= $h) //가로가 긴축일때
		{
			$new_width = 800;
			$new_height = $new_width*($h/$w);

			$new_width2 = 400;
			$new_height2 = $new_width2*($h/$w);//400px짜리 썸네일 만들기
		}else{ //세로가 긴축일때
			$new_height = 800;
			$new_width = $new_height*($w/$h);
			
			$new_height2 = 400;
			$new_width2 = $new_height2*($w/$h);	//400px짜리 썸네일 만들기
		}
		
	}else{
		$new_width = $w;
		$new_height = $h;
	}


	//1:1기준 이미지틀
	//$new_width = 100;
	//$new_height = 100;

	// 새 이미지 틀을 만든다.
	$new_img=imagecreatetruecolor($new_width,$new_height);  // 가로  픽셀, 세로 픽셀 //긴축이 800px짜리 만들기
	$new_img2=imagecreatetruecolor($new_width2,$new_height2);  //긴축이 400px짜리 만들기
	
	$offset_x = 0;
	$offset_y = 0;
	
	//크롭 원본사이즈
	$crop_width = $w;
	$crop_height = $h;

/*
	//1:1 크롭하기
	if($w >= $h) //가로가 클경우 세로기준
	{
		$crop_width = $h;
		$crop_height = $h;
		
		//사진 중앙정렬
		$offset_x = $w/2 - $crop_width/2 ;
	}else{ //세로가 클경우 가로기준
		$crop_width = $w;
		$crop_height = $w;

		//사진 중앙정렬
		$offset_y = $h/2 - $crop_height/2 ;

	}
	*/

	imagecopyresampled($new_img, $origin_img, 0, 0, $offset_x, $offset_y, $new_width, $new_height, $crop_width, $crop_height);

	imagecopyresampled($new_img2, $origin_img, 0, 0, $offset_x, $offset_y, $new_width2, $new_height2, $crop_width, $crop_height);
	


	//사진 저장
	switch($get_type){
		case "gif":
			// 저장한다.

			//움직이는 gif는 썸네일 안되기때문에 그냥 아래에서 원본 복사
			//$save_path=$directory."thumbnail/".$file_name_web;
			//imagegif($new_img, $save_path);
		break;

		case "jpeg":
			$save_path=$directory."thumbnail/".$file_name_web;
			imagejpeg($new_img, $save_path);
			$save_path2=$directory."thumbnail/400_".$file_name_web;
			imagejpeg($new_img2, $save_path2);
		break;

		case "png":
			$save_path=$directory."thumbnail/".$file_name_web;
			imagepng($new_img, $save_path);

			$save_path2=$directory."thumbnail/400_".$file_name_web;
			imagepng($new_img2, $save_path2);
		break;

		case "bmp":
			$save_path=$directory."thumbnail/".$file_name_web;
			imagewbmp($new_img, $save_path);

			$save_path2=$directory."thumbnail/400_".$file_name_web;
			imagewbmp($new_img2, $save_path2);
		break;

	}

	//썸네일 저장에 실패할경우 원본사진으로 저장하기

	//파일복사
	$oldfile = $directory.$file_name_web; // a.php 라는 파일을 지정합니다
	$newfile = $directory."thumbnail/".$file_name_web; // /test/ 디렉토리 안에 a.php 이름으로 정해 옮길것입니다.

	//파일 찾기
	if(is_file($directory."thumbnail/".$file_name_web)){
		//해당 파일이 있을경우
		
	}else{ //파일이 없을경우
		if(!copy($oldfile, $newfile)) { //복사합니다 
			echo "Error\n$oldfile\n$newfile"; // 에러가 나면 출력합니다 
		} else if(file_exists($newfile)) { // 성공을 할시
			//내용을 입력합니다
		}
	}

		//400px짜리 파일없을시에 복사
	$oldfile2 = $directory.$file_name_web; // a.php 라는 파일을 지정합니다
	$newfile2 = $directory."thumbnail/400_".$file_name_web; // /test/ 디렉토리 안에 a.php 이름으로 정해 옮길것입니다.

	//파일 찾기
	if(is_file($directory."thumbnail/400_".$file_name_web)){
		//해당 파일이 있을경우
		
	}else{ //파일이 없을경우
		if(!copy($oldfile2, $newfile2)) { //복사합니다 
			echo "Error\n$oldfile\n$newfile"; // 에러가 나면 출력합니다 
		} else if(file_exists($newfile2)) { // 성공을 할시
			//내용을 입력합니다
		}
	}

	/*
	imagecopyresampled($new_img, $origin_img, 0, 0, $offset_x, $offset_y, $width, $height, $crop_width, $crop_height);
	이제껏 내가 본 내장함수 중에 파라미터가 엄청 많다.
	위 함수와 유사한 것으로 'imagecopyresized'가 있다. 파라미터는 동일하다.
	다만 퀄리티가 'imagecopyresampled' 더 낳다고 한다.

	그럼 파라미터에 대해 보자.
	$new_img : 기존 이미지를 축소하여 붙여 넣을 대상
	$origin_img: 기존 이미지

	$offset_x : 기존 이미지의 영역을 기준점으로 부터 x축 좌표를 지정한다.
	$offset_y : 기존 이미지의 영역을 기준점으로 부터 y축 좌표를 지정한다.


	*/

}	

/*

if($up_chack == 2)
{
	?>
	<script>
	alert("등록완료");
	location.replace('../index.php');
	</script>
	<?
}else{
?>
<script>
alert("에러");
//history.go(-1);
</script>
<?
}
?>
*/