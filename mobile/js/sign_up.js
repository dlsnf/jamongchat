function sign_ready(){
$(".btn-step1").click(function(){
		if( $(".agree1").is(":checked") && $(".agree2").is(":checked") )
		{

			$("#step1").hide();
			$("#step2").show();

			$(".select1").removeClass("select");
			$(".select2").addClass("select");

			//$("#id").focus();
		}else{
			alert("서비스 약관과 개인정보 수집 및 이용에 대한 안내를 모두 동의해 주세요.");
		}
	});
	
	$(".btn-step2").click(function(){

		$(".input_error").removeClass("input_error");

		if( $("#id").val() == "")
		{
			//$(".error").text("계정을 입력해주세요.");
			alert("계정을 입력해주세요.");
			$("#id").addClass("input_error");
			//$("#id").focus();
		}else if( $(".chosen-select").val() == "도메인 선택" )
		{
			//$(".error").text("도메인을 선택해주세요.");
			alert("도메인을 선택해주세요.");
			$(".chosen-select").addClass("input_error");
			//$(".chosen-select").focus();
		}else if( $(".chosen-select").val() == "직접입력" && $("#domain").val() == "")
		{
			//$(".error").text("도메인을 입력해주세요.");
			alert("도메인을 선택해주세요.");
			$("#domain").addClass("input_error");
			//$("#domain").focus();
		}else{

			if( $(".chosen-select").val() == "직접입력")
			{
				var email = $("#id").val() + "@" + $("#domain").val();
			}else if( $(".chosen-select").val() != "도메인 선택")
			{
				var email = $("#id").val() + "@" + $(".chosen-select").val();
			}
			
			if(chkEmail(email) == false)
			{
				//$(".error").text("이메일 형식이 맞지 않습니다.");
				alert("이메일 형식이 맞지 않습니다.");
				$("#id").addClass("input_error");
				$("#domain").addClass("input_error");
				//$("#id").focus();
			}
		}

		if(chkEmail(email) == true)
		{
			if( $("#password").val() == "")
			{
				//$(".error").text("비밀번호를 입력해 주세요.");
				alert("비밀번호를 입력해 주세요.");
				$("#password").addClass("input_error");
				//$("#password").focus();
			}else if( $("#password").val().length < 8 || $("#password").val().length > 16 )
			{
				//$(".error").text("비밀번호 길이를 8~16자리로 입력해 주세요.");
				alert("비밀번호 길이를 8~16자리로 입력해 주세요.");
				$("#password").addClass("input_error");
				//$("#password").focus();
			}else if( $("#confirm_password").val() == "")
			{
				//$(".error").text("비밀번호를 한번더 입력해 주세요.");
				alert("비밀번호를 한번더 입력해 주세요.");
				$("#confirm_password").addClass("input_error");
				//$("#confirm_password").focus();
			}else if( $("#password").val() != $("#confirm_password").val() )
			{
				//$(".error").text("비밀번호 동일하지 않습니다.");
				alert("비밀번호가 동일하지 않습니다.");
				$("#password").addClass("input_error");
				$("#confirm_password").addClass("input_error");
				//$("#confirm_password").focus();
			}else if( $("#name").val() == "")
			{
				//$(".error").text("별명을 입력해주세요.");
				alert("별명을 입력해주세요.");
				$("#name").addClass("input_error");
				//$("#name").focus();
			}else  if( $("#name").val().length < 3 || $("#name").val().length > 30 )
			{
				//$(".error").text("별명은 3~30자 이내 가능합니다.");
				alert("별명은 3~30자 이내 가능합니다.");
				$("#name").addClass("input_error");
				//$("#name").focus();
			}else{
				 if(email_check_index==0){
					//$(".error").text("계정 중복확인 버튼을 눌러주세요.");
					alert("계정 중복확인 버튼을 눌러주세요.");
					$(".btn-email-check").addClass("input_error");
				}else if(name_check_index==0)
				{
					//$(".error").text("별명 중복확인 버튼을 눌러주세요.");
					alert("별명 중복확인 버튼을 눌러주세요.");
					$(".btn-name-check").addClass("input_error");

				}else{
					var name = $("#name").val();
					var password = SHA256($("#password").val());

					$.ajax({
							type:"POST",
							url:"../sign_up_ajax.php",
							data:"name="+name+"&email="+email+"&password="+password+"&l_token="+encodeURIComponent($(".l_token").val()),
							dataType:"json",
							//traditional: true,
							//contentType: "application/x-www-form-urlencoded;charset=utf-8",
							success:function( data ){
								//alert(data);
								if(data == 0)
								{
									alert("가입 실패");
								}else{
									$("#step2").hide();
									$("#step3").show();

									$(".select2").removeClass("select");
									$(".select3").addClass("select");
								}
							},
							error:function(e){
								alert("에러");
								//alert( e.responseText );
							}
					});

				}
			}
		}		
		
	});

}

var name_check_index = 0;
function name_check()
{
	$(".input_error").removeClass("input_error");

	if( $("#name").val() == "")
	{
		alert("별명을 입력해 주세요.");
		$("#name").addClass("input_error");
		//$("#name").focus();
	}else if($("#name").val().length < 3)
	{
		alert("3자리 이상 입력해 주세요.");
		$("#name").addClass("input_error");
		//$("#name").focus();
	}else if( !inputCheckSpecial($("#name").val()) )
	{
		alert("특수문자는 사용할 수 없습니다.");
		$("#name").addClass("input_error");
		//$("#name").focus();
	}else{
		var user_name = $("#name").val();
		$.ajax({
				type:"POST",
				url:"../name_check_ajax.php",
				data:"name="+user_name+"&l_token="+$(".l_token").val(),
				dataType:"json",
				//traditional: true,
				//contentType: "application/x-www-form-urlencoded;charset=utf-8",
				success:function( data ){
					//alert(data);
					if(data == 0)
					{
						alert("사용 가능한 별명입니다.");
						name_check_index = 1;
					}else{
						alert("이미 사용중인 별명입니다.");
						name_check_index = 0;
						$("#name").addClass("input_error");
						//$("#name").focus();
					}
				},
				error:function(e){
					//alert("에러");
					alert( e.responseText );
				}
		});

	}

}

//특수문자 체크
 function inputCheckSpecial(text){
	var resultCheck = true;

	var strobj = text;
	var re = /[~!@\#$%^&*\()\=+_'.`\-|,?<>:;\/\"\[\]\{\}\\]/gi;
	if(re.test(strobj)){
		// 특수문자는 입력하실수 없습니다.
		//alert("Tidak bisa memasukkan karakter khusus");
		return false;
	}
		return true;
}

var email_check_index = 0;
function email_check()
{

	$(".input_error").removeClass("input_error");

	if( $("#id").val() == "")
	{
		alert("계정을 입력해 주세요.");
		$("#id").addClass("input_error");
		//$("#id").focus();
	}else if( $(".chosen-select").val() == "도메인 선택" )
	{
		alert("도메인을 선택해주세요.");
		$(".chosen-select").addClass("input_error");
		//$(".chosen-select").focus();
	}else if( $(".chosen-select").val() == "직접입력" && $("#domain").val() == "")
	{
		alert("도메인을 입력해주세요.");
		$("#domain").addClass("input_error");
		//$("#domain").focus();
	}else{

		if( $(".chosen-select").val() == "직접입력")
		{
			var email = $("#id").val() + "@" + $("#domain").val();
		}else if( $(".chosen-select").val() != "도메인 선택")
		{
			var email = $("#id").val() + "@" + $(".chosen-select").val();
		}
		
		if(chkEmail(email) == false)
		{
			alert("이메일 형식이 맞지 않습니다.");
			$("#id").addClass("input_error");
			$("#domain").addClass("input_error");
			//$("#id").focus();
		}else{

			if( $(".chosen-select").val() == "직접입력")
			{
				var email = $("#id").val() + "@" + $("#domain").val();
			}else if( $(".chosen-select").val() != "도메인 선택")
			{
				var email = $("#id").val() + "@" + $(".chosen-select").val();
			}

			$.ajax({
					type:"POST",
					url:"../email_check_ajax.php",
					data:"email="+email+"&l_token="+$(".l_token").val(),
					dataType:"json",
					//traditional: true,
					//contentType: "application/x-www-form-urlencoded;charset=utf-8",
					success:function( data ){
						//alert(data);
						if(data == 0)
						{
							alert("사용 가능한 email 입니다.");
							email_check_index = 1;
						}else{
							alert("이미 사용중인 email 입니다.");
							email_check_index = 0;
							$("#id").addClass("input_error");
							//$("#id").focus();
						}
					},
					error:function(e){
						//alert("에러");
						alert( e.responseText );
					}
			});
		}
	}
}


// 정규식 : 이메일
function chkEmail(str)
{
var reg_email = /^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
if(!reg_email.test(str))
{
return false;
}
return true;
}

function domain_change(e)
{
	if(e =="도메인 선택")
	{
	}else if( e == "직접입력")
	{
		$("#domain").show();
		//$("#domain").focus();
	}else{
		$("#domain").val("");
		$("#domain").hide();
	}
}
		

		
//sha256
function SHA256(s){
  var chrsz   = 8;
  var hexcase = 0;

 function safe_add (x, y) {
   var lsw = (x & 0xFFFF) + (y & 0xFFFF);
   var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
   return (msw << 16) | (lsw & 0xFFFF);
 }

 function S (X, n) { return ( X >>> n ) | (X << (32 - n)); }
 function R (X, n) { return ( X >>> n ); }
 function Ch(x, y, z) { return ((x & y) ^ ((~x) & z)); }
 function Maj(x, y, z) { return ((x & y) ^ (x & z) ^ (y & z)); }
 function Sigma0256(x) { return (S(x, 2) ^ S(x, 13) ^ S(x, 22)); }
 function Sigma1256(x) { return (S(x, 6) ^ S(x, 11) ^ S(x, 25)); }
 function Gamma0256(x) { return (S(x, 7) ^ S(x, 18) ^ R(x, 3)); }
 function Gamma1256(x) { return (S(x, 17) ^ S(x, 19) ^ R(x, 10)); }

 function core_sha256 (m, l) {
   var K = new Array(0x428A2F98, 0x71374491, 0xB5C0FBCF, 0xE9B5DBA5, 0x3956C25B, 0x59F111F1, 0x923F82A4, 0xAB1C5ED5, 0xD807AA98, 0x12835B01, 0x243185BE, 0x550C7DC3, 0x72BE5D74, 0x80DEB1FE, 0x9BDC06A7, 0xC19BF174, 0xE49B69C1, 0xEFBE4786, 0xFC19DC6, 0x240CA1CC, 0x2DE92C6F, 0x4A7484AA, 0x5CB0A9DC, 0x76F988DA, 0x983E5152, 0xA831C66D, 0xB00327C8, 0xBF597FC7, 0xC6E00BF3, 0xD5A79147, 0x6CA6351, 0x14292967, 0x27B70A85, 0x2E1B2138, 0x4D2C6DFC, 0x53380D13, 0x650A7354, 0x766A0ABB, 0x81C2C92E, 0x92722C85, 0xA2BFE8A1, 0xA81A664B, 0xC24B8B70, 0xC76C51A3, 0xD192E819, 0xD6990624, 0xF40E3585, 0x106AA070, 0x19A4C116, 0x1E376C08, 0x2748774C, 0x34B0BCB5, 0x391C0CB3, 0x4ED8AA4A, 0x5B9CCA4F, 0x682E6FF3, 0x748F82EE, 0x78A5636F, 0x84C87814, 0x8CC70208, 0x90BEFFFA, 0xA4506CEB, 0xBEF9A3F7, 0xC67178F2);
   var HASH = new Array(0x6A09E667, 0xBB67AE85, 0x3C6EF372, 0xA54FF53A, 0x510E527F, 0x9B05688C, 0x1F83D9AB, 0x5BE0CD19);
   var W = new Array(64);
   var a, b, c, d, e, f, g, h, i, j;
   var T1, T2;

   m[l >> 5] |= 0x80 << (24 - l % 32);
   m[((l + 64 >> 9) << 4) + 15] = l;

   for ( var i = 0; i<m.length; i+=16 ) {
     a = HASH[0];
     b = HASH[1];
     c = HASH[2];
     d = HASH[3];
     e = HASH[4];
     f = HASH[5];
     g = HASH[6];
     h = HASH[7];

     for ( var j = 0; j<64; j++) {
       if (j < 16) W[j] = m[j + i];
       else W[j] = safe_add(safe_add(safe_add(Gamma1256(W[j - 2]), W[j - 7]), Gamma0256(W[j - 15])), W[j - 16]);

       T1 = safe_add(safe_add(safe_add(safe_add(h, Sigma1256(e)), Ch(e, f, g)), K[j]), W[j]);
       T2 = safe_add(Sigma0256(a), Maj(a, b, c));

       h = g;
       g = f;
       f = e;
       e = safe_add(d, T1);
       d = c;
       c = b;
       b = a;
       a = safe_add(T1, T2);
     }

     HASH[0] = safe_add(a, HASH[0]);
     HASH[1] = safe_add(b, HASH[1]);
     HASH[2] = safe_add(c, HASH[2]);
     HASH[3] = safe_add(d, HASH[3]);
     HASH[4] = safe_add(e, HASH[4]);
     HASH[5] = safe_add(f, HASH[5]);
     HASH[6] = safe_add(g, HASH[6]);
     HASH[7] = safe_add(h, HASH[7]);
   }
   return HASH;
 }

 function str2binb (str) {
   var bin = Array();
   var mask = (1 << chrsz) - 1;
   for(var i = 0; i < str.length * chrsz; i += chrsz) {
     bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (24 - i%32);
   }
   return bin;
 }

 function Utf8Encode(string) {
   string = string.replace(/\r\n/g,"\n");
   var utftext = "";

   for (var n = 0; n < string.length; n++) {

     var c = string.charCodeAt(n);

     if (c < 128) {
       utftext += String.fromCharCode(c);
     }
     else if((c > 127) && (c < 2048)) {
       utftext += String.fromCharCode((c >> 6) | 192);
       utftext += String.fromCharCode((c & 63) | 128);
     }
     else {
       utftext += String.fromCharCode((c >> 12) | 224);
       utftext += String.fromCharCode(((c >> 6) & 63) | 128);
       utftext += String.fromCharCode((c & 63) | 128);
     }

   }

   return utftext;
 }

 function binb2hex (binarray) {
   var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
   var str = "";
   for(var i = 0; i < binarray.length * 4; i++) {
     str += hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8+4)) & 0xF) +
     hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8  )) & 0xF);
   }
   return str;
 }

 s = Utf8Encode(s);
 return binb2hex(core_sha256(str2binb(s), s.length * chrsz));
}