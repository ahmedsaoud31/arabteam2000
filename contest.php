<?php
include('header.php');
/*if(!$session_login)
{
	header('location: index.php');
	exit();
}*/
?>
<script>
	$(function(){
		var actual_url = '<?php echo $actual_url;?>';
		$('.login').click(function(){
			$('#loginBox #messageBox').fadeIn(500).css({"display":"table"});
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
	});
</script>
<article>
<div id="loginBox">
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
					<div class="message"></div>
					<div>
						<span class="answerSubmitOK">تأكيد</span><span class="cancleAnswerSubmit">إلغاء</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="alertBox2">
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
		<div class="testsList">
			<?php
			if(!isset($_GET['id']))
			{
			?>
			<div>قائمة المسابقات</div>
			<div class="tableList">
				<table>
				<?php
				$databaseName = 'myDB1';
				$blockName = 'algorithms';
				require_once('PHPDB/PHPDB.php');
				$obj = new PHPDB();
				if(($myDB = $obj->selectDB($databaseName)) === false)
				{
					$out = array('error'=>$obj->getError());
					echo json_encode($out);
					exit();
				}
				if(($myBlock = $myDB->selectBlock($blockName)) === false)
				{
					$out = array('error'=>$myBlock->getError());
					echo json_encode($out);
					exit();
				}
				$result = $myBlock->getAll();
				if(count($result) > 0)
				{
					foreach($result as $value)
					{
						if((int)$value['active'] == 1)
						{
							$active = 'متاح';
							echo "<tr><td class='first'>{$value['title']}</td><td class='active'>{$active}</td><td class='last'><a href='?id={$value['PHPDBID']}'>دخول</a></td></tr>";
						}
						else
						{
							$active = 'غير متاح';
							echo "<tr><td class='first'>{$value['title']}</td><td  class='disActive'>{$active}</td><td class='last'><a href='?id={$value['PHPDBID']}'>مشاهدة</a></td></tr>";
						}
					}
				}
				?>
				</table>
			</div>
			<?php
			}
			else
			{
			?>
			<div class="answerBorder">
					<?php
					$databaseName = 'myDB1';
					$blockName = 'algorithms';
					require_once('PHPDB/PHPDB.php');
					$obj = new PHPDB();
					if(($myDB = $obj->selectDB($databaseName)) === false)
					{
						$out = array('error'=>$obj->getError());
						echo json_encode($out);
						exit();
					}
					if(($myBlock = $myDB->selectBlock($blockName)) === false)
					{
						$out = array('error'=>$myBlock->getError());
						echo json_encode($out);
						exit();
					}
					$id = (int)$_GET['id'];
					$result = $myBlock->getAll(array('PHPDBID =='=>$id));
					if(count($result) == 1 && $result[0]['active'] && $session_login)
					{
						if(($answers = $myDB->selectBlock('answers')) === false)
						{
							$out = array('error'=>$myDB->getError());
							echo json_encode($out);
							exit();
						}
						$result2 = $answers->getAll(array('and'=>array('user_name =='=>$_SESSION['username'],'algorithm_id =='=>$id)));
						if(count($result2) > 0)
						{
							if($result2[0]['answer_submit'])
							{
							?>
								<div class="timeAgo">
									<span class="text">الوقت المنقضي  </span> <span class="days">00</span>:<span class="hours">00</span>:<span class="minuts">00</span>:<span class="sconds">00</span>
								</div>
								<?php
									$timeAgo = time()-$result2[0]['start_time'];
									$algorithm = $result[0]['algorithm'];
									
									$answer = preg_replace('/&LT;/','<',$result2[0]['answer']);
									$answer = preg_replace('/&GT;/','>',$answer);
									$answerResult = preg_replace('/&LT;/','<',$result2[0]['result']);
									$answerResult = preg_replace('/&GT;/','>',$answerResult);
								?>
								<div class='title'><?php echo $result[0]['title']; ?></div>
								<div class='answer'>
									<div class='alogrithmBox'><?php echo $algorithm; ?></div>
									<div class='answerBox'>
										<textarea dir='ltr' name='answer'><?php echo $answer; ?></textarea>
										<input type='hidden' name='username' value="<?php echo $_SESSION['username']; ?>">
										<input type='hidden' name='algorithm_id' value="<?php echo $id; ?>">
										<br><br>
										<div>اللغة المستخدمة: 
											<select name="lang" dir="ltr">
												<option value="0">إختر لغة</option>
											<?php
												$arrLangs = array('C','C++','C#','Fortran','Go','Haskell','java','javaScript','Pascal','Perl','PHP','Prolog','Python','Ruby','VB6','VB.NET');
												foreach($arrLangs as $value)
												{
													if($result2[0]['lang'] == $value)
													{
														echo "<option value=\"{$value}\" selected>{$value}</option>";
													}
													else
													{
														echo "<option value=\"{$value}\">{$value}</option>";
													}
												}
											?>
											</select>
										</div>
										<br>
										<div class="testResult">ناتج الحل : <input dir="ltr" type='text' name="result" value="<?php echo $answerResult;?>"/> <span data-algorithm_id="<?php echo $id; ?>" data-username="<?php echo $_SESSION['username']; ?>">إختبر الناتج</span></div>
										<div><span class='answerSubmit'>تقديم الإجابة</span></div>
									</div>
								</div>
							<?php
							}
							else
							{
							?>
							<div class="timeAgo">
								<span class="text">الوقت المنقضي  </span> <span class="days">00</span>:<span class="hours">00</span>:<span class="minuts">00</span>:<span class="sconds">00</span>
							</div>
							<?php
								$timeAgo = time()-$result2[0]['start_time'];
								$algorithm = $result[0]['algorithm'];
							?>
							<div class='title'><?php echo $result[0]['title']; ?></div>
							<div class='answer'>
								<div class='alogrithmBox'><?php echo $algorithm; ?></div>
								<div class='answerBox'>
									<textarea dir='ltr' name='answer'></textarea>
									<input type='hidden' name='username' value="<?php echo $_SESSION['username']; ?>">
									<input type='hidden' name='algorithm_id' value="<?php echo $id; ?>">
									<br><br>
									<div>اللغة المستخدمة: 
										<select name="lang" dir="ltr">
											<option value="0">إختر لغة</option>
											<option value="C">C</option>
											<option value="C++">C++</option>
											<option value="C#">C#</option>
											<option value="Fortran">Fortran</option>
											<option value="Go">Go</option>
											<option value="Haskell">Haskell</option>
											<option value="java">java</option>
											<option value="javaScript">javaScript</option>
											<option value="Pascal">Pascal</option>
											<option value="Perl">Perl</option>
											<option value="PHP">PHP</option>
											<option value="Prolog">Prolog</option>
											<option value="Python">Python</option>
											<option value="Ruby">Ruby</option>
											<option value="VB6">VB6</option>
											<option value="VB.NET">VB.NET</option>
										</select>
									</div>
									<br>
									<div class="testResult">ناتج الحل : <input dir="ltr" type='text' name='result'/> <span data-algorithm_id="<?php echo $id; ?>" data-username="<?php echo $_SESSION['username']; ?>">إختبر الناتج</span></div>
									<div><span class='answerSubmit'>تقديم الإجابة</span></div>
								</div>
							</div>
							<?php
							}
						}
						else
						{
						?>
						<div class="timeAgo">
							<span class="text">الوقت المنقضي  </span> <span class="days">00</span>:<span class="hours">00</span>:<span class="minuts">00</span>:<span class="sconds">00</span>
						</div>
						<?php
							$arr = array('user_name'=>$_SESSION['username'],'algorithm_id'=>$id,'start_time'=>time(),'end_time'=>0,'answer'=>'','answer_submit'=>false,'evaluation'=>-1,'result'=>'none','lang'=>'none','answerTimes'=>0);
							$answers->put($arr);
							$timeAgo = 0;
							$algorithm = $result[0]['algorithm'];
						?>
							<div class='title'><?php echo $result[0]['title']; ?></div>
							<div class='answer'>
								<div class='alogrithmBox'><?php echo $algorithm; ?></div>
								<div class='answerBox'>
									<textarea dir='ltr' name='answer'></textarea>
									<input type='hidden' name='username' value="<?php echo $_SESSION['username']; ?>">
									<input type='hidden' name='algorithm_id' value="<?php echo $id; ?>">
									<br><br>
									<div>اللغة المستخدمة: 
										<select name="lang" dir="ltr">
											<option value="0">إختر لغة</option>
											<option value="C">C</option>
											<option value="C++">C++</option>
											<option value="C#">C#</option>
											<option value="Fortran">Fortran</option>
											<option value="Go">Go</option>
											<option value="Haskell">Haskell</option>
											<option value="java">java</option>
											<option value="javaScript">javaScript</option>
											<option value="Pascal">Pascal</option>
											<option value="Perl">Perl</option>
											<option value="PHP">PHP</option>
											<option value="Prolog">Prolog</option>
											<option value="Python">Python</option>
											<option value="Ruby">Ruby</option>
											<option value="VB6">VB6</option>
											<option value="VB.NET">VB.NET</option>
										</select>
									</div>
									<br>
									<div class="testResult">ناتج الحل : <input dir="ltr" type='text' name='result'/> <span data-algorithm_id="<?php echo $id; ?>" data-username="<?php echo $_SESSION['username']; ?>">إختبر الناتج</span></div>
									<div><span class='answerSubmit'>تقديم الإجابة</span></div>
								</div>
							</div>
						<?php
						}
					}
					else if(count($result) == 1 && $result[0]['active'] && !$session_login)
					{
						echo 'يرجى تسجيل الدخول لتستطيع المشاركة وتقديم الإجابات';
					}
					else if(count($result) == 1 && !$result[0]['active'])
					{
					?>
						<div class='title'><?php echo $result[0]['title']; ?></div>
						<div class='answer'>
							<div class='alogrithmBox'><?php echo $result[0]['algorithm']; ?></div>
						</div>
					<?php
					}
					else
					{
						echo 'هذه المسابقة غير موجودة';
					}
				}
				?>
			</div>
			<div></div>
		</div>
	</div>
	</article>
<footer>
	
</footer>
<script>
	$(function(){
		$('.cancleAnswerSubmit').click(function(){
			$('.alertBox #messageBox').fadeOut(500);
		});
		$('.answerSubmitOK').click(function(){
			var postData = {
					answer: $('.answer textarea[name=answer]').val(),
					username: $('.answer input[name=username]').val(),
					algorithm_id: $('.answer input[name=algorithm_id]').val(),
					result:$('.answer input[name=result]').val(),
					lang:$('.answer select[name=lang]').val()
					};
			//alert(JSON.stringify(postData));
			$.ajax({
				type: "POST",
				url: "answerSubmit.php",
				data: postData,
				beforeSend: function ( xhr ) {
					$('.alertBox #messageBox').fadeOut(500);
				}
			}).done(function(comeData){
				//alert(comeData);
				comeData = JSON.parse(comeData);
				if(typeof comeData.error == 'undefined'){
					alert('Undefined Error !');
				}
				if(comeData.error === false){
					alert('تم تقديم الإجابة');
					clearInterval(answerTimer);
				}
				else{
					alert(comeData.error);
				}
			});
		});
		$('.answerSubmit').click(function(){
			if($('textarea[name=answer]').val() == ''){
				alert('فضلاً ضع إجابة لإرسالها');
				return false;
			}
			if($('select[name=lang]').val() == '' || $('select[name=lang]').val() == '0'){
				alert('فضلاً إختر اللغة المستخدمة في الحل');
				return false;
			}
			if($('input[name=result]').val() == ''){
				alert('فضلاً ضع ناتج الحل');
				return false;
			}
			$('.alertBox #messageBox .message').html('تأكيد الإجابة مع العلم سيتم إحتساب عدد مرات تقديم الإجابة ؟');
			$('.alertBox #messageBox').fadeIn(500).css({"display":"table"});
			return false;
			//alert($('textarea[name=answer]').val());
		});
		var time = <?php echo isset($timeAgo)?$timeAgo:0; ?>;
		var days,hours,minuts,sconds;
		makeTime();
		
		var answerTimer = setInterval(function(){
			makeTime();
		},1000);
		
		function makeTime(){
			++time;
			days = parseInt(time/(60*60*24));
			var temp = time%(60*60*24);
			hours = parseInt(temp/(60*60));
			temp = temp%(60*60);
			minuts = parseInt(temp/60);
			sconds = temp%60;
			days = str_pad(days,2);
			hours = str_pad(hours,2);
			minuts = str_pad(minuts,2);
			sconds = str_pad(sconds,2);
			$('.timeAgo .days').html(days);
			$('.timeAgo .hours').html(hours);
			$('.timeAgo .minuts').html(minuts);
			$('.timeAgo .sconds').html(sconds);
		}
		
		function str_pad(input, len){
			input += '';
			var dif = len-input.length;
			var out = '';
			if(dif > 0){
				for($i=0;$i<dif;++$i){
					out += '0';
				}
				return out+input;
			}
			return input;
		}
	});
</script>
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
				//alert(comeData);
				$('#lodingBar').fadeOut();
				comeData = JSON.parse(comeData);
				if(typeof comeData.error == 'undefined'){
					alert('Undefined Error !');
				}
				if(comeData.error === false){
					if(comeData.testResult === true){
						$('.alertBox2 .message').html('إجابة صحيحة');
					}else{
						$('.alertBox2 .message').html('إجابة خاطئة');
					}
					$('.alertBox2 #messageBox').fadeIn(500).css({"display":"table"});
				}
				else{
					$('.alertBox2 .message').html(comeData.error);
					$('.alertBox2 #messageBox').fadeIn(500).css({"display":"table"});
				}
			});
		});
		$('.cancelMessageBox').click(function(){
			$('.alertBox2 #messageBox').fadeOut(500);
		});
	});
</script>
<script type="text/javascript" src="admin/js/jquery.snippet.min.js"></script>
<script type="text/javascript" src="admin/js/jquery.snippet.run.js"></script>
<link rel="stylesheet" type="text/css" href="admin/css/jquery.snippet.min.css">
<script>
	$(function(){
		//$('#textareaContent_ifr').contents().find('body').css('text-align','left');
		/*$('input[name=makePDF]').click(function(){
			$('#codes div').html(tinymce.editors[0].getContent());
			$('#codes div').find('div.snippet pre.snippet').each(function(){
				$(this).html('');
			});
			runSnippet();
			$('#textareaContent2').val($('#codes').html());
		});*/
	});
</script>
<?php
	include('footer.php');
?>