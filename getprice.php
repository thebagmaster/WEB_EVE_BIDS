<?php
include 'sqlcon.php';
$result = mysql_query("SELECT price FROM bidprice");
$bidprice = doubleval(mysql_result($result,0,"price"));
mysql_close();
?>