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
		require_once('../PHPDB/PHPDB.php');
		$obj = new PHPDB();
		if(($myDB = $obj->selectDB($databaseName)) === false)
		{
			echo '<div class="error">'.$obj->getError().'</div>';
			exit();
		}
		if(($myBlock = $myDB->selectBlock('users')) === false)
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
			exit();
		}
		$result = $myBlock->getAll();
		$scoreArr = array();
		if(count($result) > 0)
		{
			foreach($result as $value)
			{
				$scoreArr[$value['username']]['score'] = 0;
				$scoreArr[$value['username']]['time'] = 0;
				$scoreArr[$value['username']]['overAnswers'] = 0;
			}
		}
		/*foreach($scoreArr as $key=>$value)
		{
			echo "<div>{$key}: {$value}</div>";
		}
		exit();*/
		if(($myBlock = $myDB->selectBlock('answers')) === false)
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
			exit();
		}
		$result = $myBlock->getAll();
		if(count($result) > 0)
		{
			foreach($result as $value)
			{
				if($value['answer_submit'] && (int)$value['evaluation'] > 0)
				{
					$scoreArr[$value['user_name']]['score'] += (int)$value['evaluation'];
					$scoreArr[$value['user_name']]['time'] += $value['end_time']-$value['start_time'];
					if($value['answerTimes']-1 > 0)
					{
						$scoreArr[$value['username']]['overAnswers'] += $value['answerTimes']-1;
					}
				}
			}
		}
		$sortArr = array();
		foreach($scoreArr as $key=>$value)
		{
			$sortArr[$key] = $scoreArr[$key]['score'];
		}
		arsort($sortArr);
		//var_dump($sortArr);exit();
		?>
		<table>
			<tr>
				<th>اسم المتسابق</th><th>التقييم الكلي</th><th>مدة الحل الكلية</th><th>الإجابات الزائدة الكلية</th>
			</tr>
			<?php
			foreach($sortArr as $key=>$value)
			{
				$score = $scoreArr[$key]['score'];
				if($score < 1)
				{
					continue;
				}
				$time = makeTime($scoreArr[$key]['time']);
				$overAnswers = $scoreArr[$key]['overAnswers'];
				echo "<tr><td>{$key}</td><td>{$score}</td><td>{$time}</td><td>{$overAnswers}</td></tr>";
			}
			?>
		</table>
	</div>
<script>
	$(function(){
	
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