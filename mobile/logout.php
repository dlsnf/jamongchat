<?php
session_start(); // 세션을 시작헌다.

include "../dbcon.php";

include "../domain_security.php";

include "common.php";


$logout = $_SESSION['user_email'];




if($_SESSION['user_email']!=NULL)
{
	session_destroy();
?>
<script>
//alert("로그아웃 되었습니다.");
location.href="<?=$hans_url?>/jamongchat/mobile/?logout=1&mobile=<?=$mobile_check2?>";
</script>
<?
}else{
	session_destroy();
?>
<script>
//alert("로그아웃 되었습니다.");
location.href="<?=$hans_url?>/jamongchat/mobile/?logout=1&mobile=<?=$mobile_check2?>";
</script>
<?
}
?>
