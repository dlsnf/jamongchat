
<!--
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>

<script type="text/javascript" src="js/kakao.link.js"></script>
-->

<script type="text/javascript" src="//developers.band.us/js/share/band-button.js?v=20150520"></script>

<script>


$(window).ready(function(){
	//katok();
});

//카카오톡
function katok()
{
	Kakao.init('f88395acf00e5aa0656287851d48dd87');
	Kakao.Link.createTalkLink({
		container: '#kakao-link-btn',
		label: '< 자몽챗 >\n내가 올린 글이 하루 뒤에 자동으로 삭제된다면?',
		image: {
			src: 'http://hansbuild.cafe24.com/jamongchat/images/jamongchat_promo_500.png',
			width: '500',
			height: '334'
		},
		webButton: {
			text: '홈페이지 이동',
			url:'http://jamongserver.cafe24.com/jamongchat/'
		},
			//텍스트링크
		/*webLink:{
			text:'test',
			url:'http://jamongserver.cafe24.com/ysm'
		},*/
		fail: function() {
			alert('KakaoLink is currently only supported in iOS and Android platforms.');
		}
	});

}
</script>
<div class="share">
	
	<div class="share_title">< 공유하기 ></div>

	<!-- <img id="kakao-link-btn" class="katok" src="images/jamongshake_katok.png"> -->

	<ul>

		<li>
			<div class="fb_div">
				<a class="fb-share-button" href="https://www.facebook.com/sharer/sharer.php?u=http://hansbuild.cafe24.com/jamongchat/" target="_blank">
					<img class="btn_share_img" src="images/FB_logo_128.png">
				</a>
			</div>
		</li>


		<li>
			<div class="twitter_div">
				<a href="https://twitter.com/intent/tweet?url=http://hansbuild.cafe24.com/jamongchat/" target="_blank">
					<img class="btn_share_img" src="images/twitter_128.png">
				</a>
			</div>
			
		</li>


		<li>
			<div>
				<img class="btn_share_img" src="images/BAND_128.png" onclick="band();">
				<span class="btn_band_share" style="visibility: hidden;">
					
					<script type="text/javascript">
					new ShareBand.makeButton({'createImageUrl':'http://hansbuild.cafe24.com/jamongchat/images/BAND_512.png',"lang":"ko","type":"e","text":"< 자몽챗 >\n내가 올린 글이 하루 뒤에 자동으로 삭제된다면?","withUrl":true}  );

					function band()
					{
						$(".btn_band_share a img").trigger("click");
					}
					</script>
				</span>
			</div>
		</li>

		<li style="opacity: 0.2;
		cursor: default;
		text-decoration: none;">
			<div>
				<script>
					function executeKakaoStoryLink()
					{
					kakao.link("story").send({
						post : "< 자몽챗 >\n내가 올린 글이 하루 뒤에 자동으로 삭제된다면?\nhttp://jamongserver.cafe24.com/jamongchat/",
						appid : "http://jamongserver.cafe24.com/jamongchat/",
						appver : "1.0",
						appname : "자몽챗",
						urlinfo : JSON.stringify({title:"< 자몽챗 >", desc:"내가 올린 글이 하루 뒤에 자동으로 삭제된다면?", imageurl:["http://hansbuild.cafe24.com/jamongchat/images/jamongchat_promo_1000.png"], type:"article"})
					});
					}
				</script>
				<img class="btn_share_img" src="images/kakao_story_icon_128.png" onclick="executeKakaoStoryLink();">
			</div>
		</li>

		
		
	<ul>

</div>