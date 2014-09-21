<?php
include 'sqlcon.php';
$result = mysql_query("SELECT * FROM auctions");
while ($row = mysql_fetch_array($result)) {
	$id = $row["id"];
	$db = $row["start"];
	$bidder = $row["highbid"];
	if($bidder!=""){
		$result2 = mysql_query("SELECT * FROM acct WHERE id='$bidder'");
		$name = mysql_result($result2,0,"name");
	}
	else
		$name = "";
	$price = number_format($row["price"]) . " ISK";
	$timestamp = strtotime($db);
	$dater = date('Y-m-d H:i:s');
	$today = strtotime($dater);
	$timefromstart = abs($timestamp - $today);
	//echo "$today<BR>$timestamp";
	$timeleft = intval($row["secsleft"]) - $timefromstart;
	if($timeleft > 0)
		echo "~$id~$timeleft~$name~$price";
	else if(intval($row["bids"]) >= intval($row["minbids"])){
		mysql_query("UPDATE auctions SET won='1' WHERE id=$id");
		echo "~$id~0~$name~$price";
	} else
		echo "~$id~0~$name~$price";
	if($timeleft < 11 and intval($row["bids"]) < intval($row["minbids"])){
		$timefromstart += 20;
		mysql_query("UPDATE auctions SET secsleft='20',start='$dater' WHERE id=$id");
	}
}
mysql_close();
?>