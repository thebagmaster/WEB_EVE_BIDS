<?php
	session_start();
	if($_SESSION['uname'] == "Bagmaster Tso" or $_SESSION['uname'] == "eve bids") {
		echo "<form action=auction.php method=POST><input name='type'><input type=submit></form>";
	}
?>