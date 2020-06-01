<?php

//웹 버전 체크
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
if(count($matches)<2){
preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
}
	if (count($matches)>1){ $version = $matches[1];//$matches변수값이 있으면 IE브라우저
		if($version<=8){ 
				//익스8이하
		}else{ 
				//익스9이상
		} 
	
	}else{
		$version = 50;
		//다른 브라우져
}

?>