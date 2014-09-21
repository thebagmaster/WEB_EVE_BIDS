<?php
$url = "https://api.eveonline.com/corp/WalletJournal.xml.aspx?keyID=1834774&vCode=bD7ygwd2d1yb0wmbdjv5VtDFXMCQ8mgKVX3wFeQ2i0qoNJ6Ls9ENnMV82iMFKYes&characterID=93001602&rowCount=1000";
$feed = file_get_contents($url);
if($feed){
	include 'sqlcon.php';
	$xml = simplexml_load_string($feed);
	$entry = $xml->result->rowset;
	foreach ($entry->row as $row){
		if($row['refTypeID'] == 10){
			$time = sanitize($row['date']);
			$id = sanitize($row['ownerName1']);
			$credit = sanitize($row['amount']);
			
			echo "<div style='width:200px;display:inline-block;'>$id</div>$credit<br>";
			
			$result = mysql_query("SELECT * FROM credits WHERE time='$time'") or die(mysql_error());
			$exists = mysql_num_rows($result);
			if($exists > 0)
				$credited = intval(mysql_result($result,0,"credited"));
			$result = mysql_query("SELECT * FROM acct WHERE name = '$id'") or die(mysql_error());
			$charexists = mysql_num_rows($result);
			if($charexists)
				$balance = doubleval(mysql_result($result,0,"balance"));
			else
				$balance = 0;
			$balance += intval($credit);
			if($charexists and !$credited)
				mysql_query("UPDATE acct SET balance='$balance' WHERE name='$id'") or die(mysql_error());
			if(!$exists)
				mysql_query("INSERT INTO credits (time,id,credit,credited) VALUES ('$time','$id','$credit','$charexists')") or die(mysql_error());
			else if(!$credited and $charexists)
				mysql_query("UPDATE credits SET credited='1' WHERE time='$time'") or die(mysql_error());
		}		
	}
	mysql_close();
}
?>