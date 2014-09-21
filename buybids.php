<?php
session_start();
if($_SESSION['login']&&isset($_POST['num'])) {
	$num = intval($_POST['num']);
	include 'getprice.php';
	if($num < 10)
		$price = $bidprice;
	else if($num < 100)
		$price = $bidprice-20000;
	else if($num < 200)
		$price = $bidprice-40000;
	else if($num < 500)
		$price = $bidprice-50000;
	else
		$price = $bidprice-80000;
	$total = $num*$bidprice;
	include 'sqlcon.php';
	$id=$_SERVER['HTTP_EVE_CHARID'];
	$result = mysql_query("SELECT * FROM acct WHERE id='$id'");
	$balance = doubleval(mysql_result($result,0,"balance"));
	$bids = intval(mysql_result($result,0,"bids"));
	if($balance >= $total){
		$newbal = $balance - $total;
		$newbids = $bids + $num;
		mysql_query("UPDATE acct SET balance='$newbal',bids='$newbids' WHERE id=$id");
		echo "bought";
	}
	else
		echo "nocash";
	mysql_close();
}
else
	echo "nolog";
?>