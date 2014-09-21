<?php
	session_start();
	if(($_SESSION['uname'] == "Bagmaster Tso" or $_SESSION['uname'] == "eve bids") and isset($_POST['type'])){
		$typeID = $_POST['type'];
		//price code here to $price avg
		include 'getprice.php';
		include 'sqlcon.php';
		$result = mysql_query("SELECT * FROM types WHERE id = '$typeID'");
		$name = mysql_result($result,0,"id");
		$price = mysql_result($result,0,"price");
		$minbids = intval($price/$bidprice);
		mysql_query("INSERT INTO auctions (typeID,worth,minbids) VALUES ($typeID,$price,$minbids)") or die(mysql_error());;
		mysql_close();
	}
?>