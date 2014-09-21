<?php
session_start();
if($_SESSION['login']&&isset($_POST['id'])) {
	$id=$_POST['id'];
	$high=$_SERVER['HTTP_EVE_CHARID'];
	include 'sqlcon.php';
	$result = mysql_query("SELECT * FROM acct WHERE id = '$high'");
	$charbids = mysql_result($result,0,"bids");
	$charbal = mysql_result($result,0,"balance");
	$result = mysql_query("SELECT * FROM auctions WHERE id = '$id'");
	$db = mysql_result($result,0,"start");
	$bidder = mysql_result($result,0,"highbid");
	$bids = intval(mysql_result($result,0,"bids"))+1;
	$price = intval(mysql_result($result,0,"price")) + 5000;
	$timestamp = strtotime($db);
	$today = strtotime(date('Y-m-d H:i:s'));
	$timefromstart = abs($timestamp - $today);
	$timeleft = intval(mysql_result($result,0,"secsleft")) - $timefromstart;
	if($charbids > 0&&$timeleft>0){
		$charbids--;
		$timeleft += 20;
		$result = mysql_query("SELECT * FROM bids WHERE auctionid = '$id' and acctid = '$high'");
		$exists = mysql_num_rows($result);
		if($exists){
			$abids = intval(mysql_result($result,0,"bids"));
			$abids++;
			mysql_query("UPDATE bids SET bids='$abids' WHERE auctionid = '$id' and acctid = '$high'");
		}else
			mysql_query("INSERT INTO bids (auctionid,acctid,bids) VALUES ($id,$high,1)");
		mysql_query("UPDATE acct SET bids='$charbids' WHERE id=$high");
		mysql_query("UPDATE auctions SET secsleft='$timeleft',start=NOW(),price='$price',highbid='$high',bids='$bids' WHERE id=$id");
		echo "bid";
	}elseif($charbal<$price)
		echo "nocash";
	elseif($charbids <= 0)
		echo "nobids";
	else
		echo "aover";
	mysql_close();
}
else
	echo "nolog";
?>