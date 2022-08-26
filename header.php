<?php
	/*header('Content-Type: text/html; charset=utf-8');
	echo "<h2>عذراً عاود المحاولة لاحقاً , يجري الآن عمل تحديثات للموقع , شكراً لزيارتكم</h2>";
	exit();*/
	require_once('functions.php');
	$session_login = session_login();
	$page = basename($_SERVER['PHP_SELF'],'.php');
	$actual_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
	<meta charset="utf-8">
	<script src="js/jquery-1.9.1.min.js"></script>
	<link rel="stylesheet" href="css/style.css?id=<?php echo time(); ?>" type="text/css" />
</head>
<body>
<header>
	<div class="welcome"><span><?php echo ($session_login && isset($_SESSION['username']))?'مرحباً بك '.$_SESSION['username']:'';?></span></div>
	<div class="topMenu">
		<a href="index.php" class="<?php echo ($page == 'index')?'active':'';?>">الرئيسية</a>
		<a href="contest.php" class="<?php echo ($page == 'contest')?'active':'';?>">قائمة المسابقات</a>
		<a href="evaluation.php" class="<?php echo ($page == 'evaluation')?'active':'';?>">التقييم</a>
		<span class='login-logout'>
			<?php
			if($session_login)
			{
			?>
				<a class="logout" href="logout.php">تسجيل الخروج</a>
			<?php
			}
			else
			{
			?>
				<a class="login">تسجيل الدخول</a>
			<?php
			}
			?>
		</span>
	</div>
</header>