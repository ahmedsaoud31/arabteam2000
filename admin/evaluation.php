<?php
	require_once('../config.php');
	if(SESSION_PATH != '')
	{
		session_save_path(SESSION_PATH); 
	}
	require_once('functions.php');
	$admin_login = session_admin_login();
	if(!$admin_login)
	{
		header('location: login.php');
		exit();
	}
	require_once('header.php');
?>
	<div id="messageBox">
		<div id="messageBox2">
			<div id="messageBox3">
				<div class="content">
					<div class="close">X</div>
					<textarea name="answer" dir="ltr"></textarea>
				</div>
			</div>
		</div>
	</div>
	<div id="userEvaluation">
		<form action="" method="POST">
		<?php
		$databaseName = 'myDB1';
		$blockName = 'algorithms';
		require_once('../PHPDB/PHPDB.php');
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
			echo '<select name="algorithms"><option value="FALSE">الكل</option>';
			foreach($result as $value)
			{
				$algorithms[$value['PHPDBID']] = $value;
				if(isset($_POST['algorithms']) && $_POST['algorithms'] == $value['PHPDBID'])
				{
					echo "<option value='{$value['PHPDBID']}' selected>{$value['title']}</option>";
				}
				else
				{
					echo "<option value='{$value['PHPDBID']}'>{$value['title']}</option>";
				}
			}
			echo '</select>';
		}
		if(($myBlock2 = $myDB->selectBlock('users')) === false)
		{
			echo '<div class="error">'.$myBlock2->getError().'</div>';
			exit();
		}
		$result2 = $myBlock2->getAll();
		if(count($result2) > 0)
		{
			echo '<select name="users"><option value="FALSE">الكل</option>';
			foreach($result2 as $value)
			{
				if(isset($_POST['users']) && $_POST['users'] == $value['username'])
				{
					echo "<option value='{$value['username']}' selected>{$value['username']}</option>";
				}
				else
				{
					echo "<option value='{$value['username']}'>{$value['username']}</option>";
				}
			}
			echo '</select>';
		}
		?>
		<input type="submit" value="فرز" name="sortSubmit">
		</form>
		<table>
			<tr>
				<th>اسم المتسابق</th><th>عنوان المسابقة</th><th>مرات الحل</th><th>مدة الحل</th><th>لغة الحل</th><th>ناتج الحل</th><th>التقييم</th><th>الدرجة النهائية</th><th></th><th></th>
			</tr>
			<?php
			if(($myBlock3 = $myDB->selectBlock('answers')) === false)
			{
				echo '<div class="error">'.$myBlock3->getError().'</div>';
				exit();
			}
			if(isset($_POST['algorithms']) && $_POST['algorithms'] != "FALSE")
			{
				$cond['algorithm_id =='] = $_POST['algorithms'];
			}
			if(isset($_POST['users']) && $_POST['users'] != "FALSE")
			{
				$cond['user_name =='] = $_POST['users'];
			}
			if(isset($cond) && count($cond) > 1)
			{
				$result3 = $myBlock3->getAll(array('and'=>$cond));
			}
			else if(isset($cond))
			{
				$result3 = $myBlock3->getAll($cond);
			}
			else
			{
				$result3 = $myBlock3->getAll();
			}
			
			if(count($result3) > 0)
			{
				foreach($result3 as $value)
				{
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
					if($value['evaluation'] == -1)
					{
						$evaluation = 'لم يتم بعد';
					}
					else
					{
						$evaluation = $value['evaluation'];
					}
					if(isset($value['result']))
					{
						$answerResult = $value['result'];
					}
					else
					{
						$answerResult = '';
					}
					if(isset($value['lang']))
					{
						$answerLang = $value['lang'];
					}
					else
					{
						$answerLang = '';
					}
					$answer = str_replace("'",'{%%%SINGLEQU%%%}',$value['answer']);
					echo "<tr><td><span data-answer='{$answer}'></span>{$value['user_name']}</td><td>{$algorithms[$value['algorithm_id']]['title']}</td><td>{$value['answerTimes']}</td><td>{$answerTime}</td><td dir='ltr'>{$answerLang}</td><td dir='ltr'>{$answerResult}</td><td class='evaluationVal'>{$evaluation}</td><td>{$algorithms[$value['algorithm_id']]['score']}</td><td><span class='showAnswer'>إظهار الحل</span></td><td class='Eval'><input type='hidden' value='{$value['PHPDBID']}' name='answer_id'><span class='addEval'>تقييم</span><input type='hidden' name='EvalVal'><input type='hidden' name='addEval' value='حفظ'></td></tr>";
				}
			}
			?>
		</table>
	</div>
<script>
	$(function(){
		$('.addEval').click(function(){
			$(this).hide();
			$(this).parent().find('input[name=EvalVal]').attr({type:'text'});
			$(this).parent().find('input[name=addEval]').attr({type:'button'});
			//var inputData = prompt("Please enter your name","");
			//return false;
		});
		$('input[name=addEval]').click(function(){
			if($(this).parent().find('input[name=EvalVal]').val() == ''){
				alert('فضلاً أدخل تقييم');
				return false;
			}
			var pattern = /[^0-9-]/;
			if(pattern.test($(this).parent().find('input[name=EvalVal]').val())){
				alert('فضلاً أدخل رقماً');
				return false;
			}
			var postData = {answer_id:$(this).parent().find('input[name=answer_id]').val(),
							evaluation:$(this).parent().find('input[name=EvalVal]').val()};
			var evalThis = $(this);
			$.ajax({
				type: "POST",
				url: "addEval.php",
				data: postData,
				beforeSend: function ( xhr ) {
					$(evalThis).attr({type:'hidden'});
					$(evalThis).parent().find('input[name=EvalVal]').attr({type:'hidden'});
					$(evalThis).parent().find('.addEval').show();
					$('#lodingBar').fadeIn(500);
				}
			}).done(function(comeData){
				//alert(comeData);
				comeData = JSON.parse(comeData);
				if(typeof comeData.error == 'undefined'){
					alert('Undefined Error !');
				}
				if(comeData.error === false){
					$(evalThis).parent().parent().find('.evaluationVal').html(postData.evaluation);
				}
				else
				{
					alert(comeData.error);
				}
				$('#lodingBar').fadeOut(500);
			});
		});
		$('.showAnswer').click(function(){
			var textAreaVal = ''+$(this).parent().parent().find('td:first span').data('answer');
			textAreaVal = textAreaVal.replace(/&LT;/g,'<');
			textAreaVal = textAreaVal.replace(/&GT;/g,'>');
			textAreaVal = textAreaVal.replace(/\{\%\%\%SINGLEQU\%\%\%\}/g,'\'');
			$('#messageBox textarea').val(textAreaVal);
			$('#messageBox').fadeIn(500).css({"display":"table"});
			return false;
		});
		$('#messageBox').click(function(){
			$('#messageBox').fadeOut(500);
			$('#messageBox textarea').val('');
		});
		$('#messageBox .close').click(function(){
			$('#messageBox').fadeOut(500);
			$('#messageBox textarea').val('');
		});
		$('#messageBox3').click(function(){
			return false;
		});
	});
</script>
<?php
	require_once('footer.php');

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