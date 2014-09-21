<?php
$connect = mysql_connect("localhost", "bagmasta_root", 'Nukit123');
mysql_select_db("bagmasta_bids");
if (!function_exists('sanitize')) { 
	function sanitize($in){
		$out=str_replace("'","''",$in);
		return $out;
	}
}
?>