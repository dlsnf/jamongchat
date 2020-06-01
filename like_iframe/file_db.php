<?php

include "../dbcon.php";

include "../domain_security.php";

session_start(); // 세션을 시작헌다.

header("Content-Type: text/html; charset=utf-8");

$get_ip = $_SERVER['REMOTE_ADDR'];

$user_seq=$_SESSION['user_seq'];
$user_name=$_SESSION['user_name'];
$user_email=$_SESSION['user_email'];



$get_write_body = addslashes($_POST['write_body']);

$get_user_email = $_POST['user_email'];

$stamp = mktime();
$date = date('Y-m-d H:i:s', $stamp);

$get_date = date("Y") . "/" . date("m") . "/" . date("d");
$get_time = date("H:i");


if(isset($_SESSION['user_email']))
{

//해당 디렉토리 안에 파일 개수 알아내기
$directory = "upload/up_img/"; // 이건 님의 디렉토리
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
$target_dir = 'upload/up_img';  // 서버에 up 이라는 디렉토리가 있어야 한다.

//$filename = iconv("utf-8","euc-kr",$count . "_" . $_FILES['fans_write_file']['name']); //업로드할때 인코딩
//$filename =iconv("EUC-KR","UTF-8",$_FILES['upfile']['name']);//다운로드할때 인코딩

$file_name = $_FILES['chosenFile']['name'];//파일이름 dd.png

$file_name_arr = explode(".",$file_name); //파일이름 배열 dd , png
$file_type = end($file_name_arr); //배열의 마지막부분 (png)

$extension = strtolower($file_type); //파일 확장자명 소문자로



$file_name_web = iconv("utf-8","euc-kr", $count . "_" . $date . "." . $extension); //웹상에서 쓸 이름 인코딩해야함
$uploadfile = $target_dir . "/" . $file_name_web; // 웹상에서 쓸 파일 경로

//확장자명 검사
//$file_type=explode(".", $_FILES['fans_write_file']['name']);

//temp_file
$tmp_file = $_FILES["chosenFile"]["tmp_name"];

//dest_file
$dest_file = "./upload/up_img/" . $file_name_web;


if(!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'bmp'|| $extension == 'gif')){
	$up_chack=1;
	?><?=$file_name?><?
}else if($_FILES['chosenFile']['size'] > 10000*100*5){ //5MB
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
	}else if(file_exists($target_dir . "/" . $file_name_web)) {  // 동일한 파일이 있는지 확인하는 부분
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

				//디비등록
				$up_chack = 2;				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (user_seq, body, like_c, photo_way, photo, date, ip) VALUES ('$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/write_iframe/upload/up_img/$file_name_web', '$file_name_web', '$date', '$get_ip')";

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
				

				//디비등록
				$up_chack = 2;				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (user_seq, body, like_c, photo_way, photo, date, ip) VALUES ('$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/write_iframe/upload/up_img/$file_name_web', '$file_name_web', '$date', '$get_ip')";

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

				//디비등록
				$up_chack = 2;				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (user_seq, body, like_c, photo_way, photo, date, ip) VALUES ('$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/write_iframe/upload/up_img/$file_name_web', '$file_name_web', '$date', '$get_ip')";

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
			if(!move_uploaded_file($tmp_file, "./upload/up_img/" . $file_name_web ))
			{
				echo "파일 업로드 실패<br>";
				echo $file_name_web;
				?>5<?
			}else{

				$up_chack = 2;
				
				$sqlInsert = "INSERT INTO jamong_chat_freeboard (user_seq, body, like_c, photo_way, photo, date, ip) VALUES ('$user_seq', '$get_write_body', '0', '$hans_url/jamongchat/write_iframe/upload/up_img/$file_name_web', '$file_name_web', '$date', '$get_ip')";

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