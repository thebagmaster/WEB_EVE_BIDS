<?php
session_start();
if($_SESSION['login']) {
	include 'sqlcon.php';
	$user = $_SESSION['uname'];
	$result = mysql_query("SELECT * FROM acct WHERE name = '$user'");
	echo number_format(mysql_result($result,0,"bids"));
	echo " ";
	echo number_format(mysql_result($result,0,"balance"));
	mysql_close();
}
?>