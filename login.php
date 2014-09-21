<?php
if(isset($_SERVER['HTTP_EVE_CHARNAME'])&&isset($_POST['pass'])){
	include 'sqlcon.php';
	$user = sanitize($_SERVER['HTTP_EVE_CHARNAME']);
	$pass = sanitize($_POST['pass']);
	$result = mysql_query("SELECT * FROM acct WHERE name = '$user'");
	while ($row = mysql_fetch_array($result)) {
		if (md5($pass) == $row['pass']) {
			session_start();
			$_SESSION['login'] = 1;
			$_SESSION['uname'] = $row['name'];
			echo $_SESSION['uname'];
		}
	}
	mysql_close();
}
?>