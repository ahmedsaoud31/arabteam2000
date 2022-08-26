<?php
	include('header.php');
?>
<script>
	$(function(){
		var actual_url = '<?php echo $actual_url;?>';
		$('.goto').click(function(){
			window.location = 'contest.php';
			/*if($(this).data('open') == 'on'){
				window.location = 'contest.php';
			}else{
				$('#messageBox').fadeIn(500).css({"display":"table"});
				return false;
			}*/
		});
		$('.login').click(function(){
			$('#messageBox').fadeIn(500).css({"display":"table"});
			return false;
		});
		$('input[name=submitCancel]').click(function(){
			$('#messageBox').fadeOut(500);
			$('#messageBox input[type=text],#messageBox input[type=password]').val('');
		});
		$('#messageBox').click(function(){
			$('#messageBox').fadeOut(500);
			$('#messageBox input[type=text],#messageBox input[type=password]').val('');
		});
		$('#messageBox3').click(function(){
			return false;
		});
		$('#messageBox3 a').click(function(){
			window.location = $(this).attr('href');
		});
		$('input[name=submitOK]').click(function(){
			var username = $('#messageBox input[name=username]').val();
			var password = $('#messageBox input[name=password]').val();
			if(username == '' || password == ''){
				return false;
			}
			var postData = {
							username: username,
							password: password
							};
			$.ajax({
				type: "POST",
				url: "login.php",
				data: postData,
				beforeSend: function ( xhr ) {
					//sendStatus = true;
				}
			}).done(function(comeData){
				//alert(comeData);
				comeData = JSON.parse(comeData);
				if(typeof comeData.error == 'undefined'){
					alert('Undefined Error !');
				}
				if(comeData.error === false){
					$('.login-logout').html('<a class="logout" href="logout.php">تسجيل الخروج</a>');
					$('#messageBox').fadeOut(500);
					$('.goto').data('open','on');
					$('#messageBox input[type=text],#messageBox input[type=password]').val('');
					window.location = actual_url;
				}
			});
		});
		/*$('.gotoContest').click(function(){
			$('#messageBox').fadeIn(500).css({"display":"table"});
			return false;
		});*/
	});
</script>
<article>
	<div class="lineTop"></div>
	<div class="lineLeft"></div>
	<div class="lineRight"></div>
	<div class="lineBottom"></div>
	<div class="body">
		<div class="welcome">مرحباً بكم في مسابقات الفريق العربي للبرمجة</div>
		<div class="goto" data-open="<?php echo $session_login?'on':'off'; ?>">الدخول للمسابقات</div>
	</div>
	<div id="messageBox">
		<div id="messageBox2">
			<div id="messageBox3">
				<div class="content">
					<form>
						<table>
							<tr>
								<td class="left">اسم المتسابق :</td>
								<td class="right"><input type="text" name="username" /></td>
							</tr>
							<tr>
								<td class="left">كلمة المرور :</td>
								<td class="right"><input type="password" name="password" /></td>
							</tr>
							<tr>
								<td colspan="2"><input type="button" name="submitOK" value="تسجيل الدخول" /><input type="button" name="submitCancel" value="إلغاء" /></td>
							</tr>
							<tr>
								<td colspan="2">إن لم يكن لديك اسم مستخدم وكلمة مرور يرجي زيارة <a href="http://arabteam2000-forum.com/index.php/topic/280241-%D9%85%D8%B3%D8%A7%D8%A8%D9%82%D8%A9-%D8%A7%D9%84%D9%81%D8%B1%D9%8A%D9%82-%D8%A7%D9%84%D8%B9%D8%B1%D8%A8%D9%8A-%D9%84%D9%84%D8%A8%D8%B1%D9%85%D8%AC%D8%A9/" target="_blank">هذا الرابط</a> وطلب اسم مستخدم وكلمة مرور</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
	</article>
<footer>
	
</footer>
<?php
	include('footer.php');
?>