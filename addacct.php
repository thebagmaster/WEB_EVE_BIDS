<?php
if(isset($_POST['ingame'])and$_SERVER['HTTP_EVE_CHARNAME']){
	include 'sqlcon.php';
	$ingame = sanitize($_SERVER['HTTP_EVE_CHARNAME']);
	$charid = $_SERVER['HTTP_EVE_CHARID'];
	$password = sanitize($_POST['ingame']);
	$result = mysql_query("SELECT * FROM acct WHERE name LIKE '$ingame'");
	if(!mysql_num_rows($result)){
		mysql_query("INSERT INTO acct (id,name,pass) VALUES('$charid','$ingame','" . md5($password) . "')"); 
		echo "add";
	}else{
		//mysql_query("UPDATE acct SET pass='" . md5($password) . "' WHERE name LIKE '$ingame'");  
		echo "update";
	}
	mysql_close();
}
else
	echo "notrust";

function pass($len){
	$password = "";
	$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
	$maxlength = strlen($possible);
	$i = 0; 
	while ($i <= $len && $i < $maxlength) { 
	  $char = substr($possible, mt_rand(0, $maxlength-1), 1);
	  if (!strstr($password, $char)) { 
		$password .= $char;
		$i++;
	  }
	}
	return $password;
}
?>