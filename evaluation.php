<?php
include('header.php');
?>
<script>
	$(function(){
		var actual_url = '<?php echo $actual_url;?>';
		$('.login').click(function(){
			$('.messageBox #messageBox').fadeIn(500).css({"display":"table"});
			return false;
		});
		$('input[name=submitCancel]').click(function(){
			$('#messageBox').fadeOut(500);
			$('#messageBox input[type=text],#messageBox input[type=password]').val('');
		});
		$('.messageBox #messageBox').click(function(){
			$('#messageBox').fadeOut(500);
			$('#messageBox input[type=text],#messageBox input[type=password]').val('');
		});
		$('.messageBox #messageBox3').click(function(){
			return false;
		});
		$('.messageBox #messageBox3 a').click(function(){
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
			urlGoTo = 'contest.php';
			return false;
		});*/
	});
</script>
<article>
<div class="messageBox">
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
</div>
<div class="alertBox">
	<div id="messageBox">
		<div id="messageBox2">
			<div id="messageBox3">
				<div class="content">
					<br>
					<div class="message"></div>
					<div>
						<span class="cancelMessageBox">خروج</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="lineTop"></div>
	<div class="lineLeft"></div>
	<div class="lineRight"></div>
	<div class="lineBottom"></div>
	<div class="body">
		<?php
		if($session_login)
		{
			$databaseName = 'myDB1';
			$blockName = 'algorithms';
			require_once('PHPDB/PHPDB.php');
			$obj = new PHPDB();
			if(($myDB = $obj->selectDB($databaseName)) === false)
			{
				echo '<div class="error">'.$obj->getError().'</div>';
				exit();
			}
			if(($myBlock = $myDB->selectBlock($blockName)) === false)
			{
				echo '<div class="error">'.$myBlock->getError().'</div>';
				exit();
			}
			$result = $myBlock->getAll();
			$algorithms = array();
			if(count($result) > 0)
			{
				foreach($result as $value)
				{
					$algorithms[$value['PHPDBID']] = $value;
				}
			}
			if(($myBlock2 = $myDB->selectBlock('answers')) === false)
			{
				echo '<div class="error">'.$myBlock2->getError().'</div>';
				exit();
			}
			$result2 = $myBlock2->getAll(array('user_name =='=>$_SESSION['username']));
			if(count($result2) > 0)
			{
				echo "<table class='userEvaluation'><tr><th>عنوان المسابقة</th><th>مرات الحل</th><th>مدة الحل</th><th>التقييم</th><th>الدرجة النهائية</th><th>إختبار الناتج</th></tr>";
				foreach($result2 as $value)
				{
					if($value['evaluation'] == -1)
					{
						$evaluation = 'لم يتم بعد';
					}
					else
					{
						$evaluation = $value['evaluation'];
					}
					if($value['answer_submit'])
					{
						$answer_submit = 'تم';
						$answerTime = makeTime($value['end_time']-$value['start_time']);
					}
					else
					{
						$answer_submit = 'لم يتم بعد';
						$answerTime = '';
					}
					$testResult = "<input type='text' name='result' dir='ltr'/><span data-algorithm_id='{$value['algorithm_id']}' data-username='{$_SESSION['username']}'>إختبر</span>";
					echo "<tr><td>{$algorithms[$value['algorithm_id']]['title']}</td><td>{$value['answerTimes']}</td><td>{$answerTime}</td><td>{$evaluation}</td><td>{$algorithms[$value['algorithm_id']]['score']}</td><td class='testResult'>{$testResult}</td></tr>";
				}
				echo "</table>";
			}
		}
		else
		{
			echo '<br><br>'.'يرجى تسجيل الدخول أولاً';
		}
		?>
	</div>
	</article>
<footer>
	
</footer>
<script>
	$(function(){
		$('.testResult span').click(function(){
			var testResult = $(this).parent().find('input').val();
			if(testResult == ''){
				alert('فضلاً أدخل قيمة');
				return false;
			}
			var postData = {username:$(this).data('username'),
							algorithm_id:$(this).data('algorithm_id'),
							testResult:testResult};
			$.ajax({
				type: "POST",
				url: "testResult.php",
				data: postData,
				beforeSend: function ( xhr ) {
					$('#lodingBar').fadeIn();
				}
			}).done(function(comeData){
				$('#lodingBar').fadeOut();
				comeData = JSON.parse(comeData);
				if(typeof comeData.error == 'undefined'){
					alert('Undefined Error !');
				}
				if(comeData.error === false){
					if(comeData.testResult === true){
						$('.alertBox .message').html('إجابة صحيحة');
					}else{
						$('.alertBox .message').html('إجابة خاطئة');
					}
					$('.alertBox #messageBox').fadeIn(500).css({"display":"table"});
				}
				else{
					$('.alertBox .message').html(comeData.error);
					$('.alertBox #messageBox').fadeIn(500).css({"display":"table"});
				}
			});
		});
		$('.cancelMessageBox').click(function(){
			$('.alertBox #messageBox').fadeOut(500);
		});
	});
</script>
<?php
	include('footer.php');
function makeTime($time){
	$ret['days'] = (int)($time/(60*60*24));
	$temp = $time%(60*60*24);
	$ret['hours'] = (int)($temp/(60*60));
	$temp = $temp%(60*60);
	$ret['minuts'] = (int)($temp/60);
	$ret['sconds'] = $temp%60;
	$ret['days'] = str_pad($ret['days'], 2, "0", STR_PAD_LEFT);
	$ret['hours'] = str_pad($ret['hours'], 2, "0", STR_PAD_LEFT);
	$ret['minuts'] = str_pad($ret['minuts'], 2, "0", STR_PAD_LEFT);
	$ret['sconds'] = str_pad($ret['sconds'], 2, "0", STR_PAD_LEFT);
	return implode(':',$ret);
}
?>