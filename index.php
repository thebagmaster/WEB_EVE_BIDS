<?php 
	session_start(); 
?>
<!DOCTYPE html>
<html>
<head><title>Eve Bids</title>
<link rel="stylesheet" type="text/css" href="index.css">
<script src="jquery.min.js"></script>
<script>
<?php		
	if(!isset($_SERVER['HTTP_EVE_CHARNAME']))
		echo "
		try{
		CCPEVE.requestTrust(
			location.protocol
			+ '//' + 
			location.host
		);
		var wrongbrowser = false;
		}catch(err){
			var wrongbrowser = true;
		}
		";
	else
		echo "var uname = '" . $_SERVER['HTTP_EVE_CHARNAME'] . "';\n";
	if($_SESSION['login']) {
		echo "logged = true;\n";
	}
	else
		echo "logged = false;\n";
?>
var rcount = 0;
var curtd = "";

function buybids(many){
	$.ajax({
	  type: "POST",
	  url: "buybids.php",
	  data: { num: many }
	}).done(function(msg) {
		updateISK();
		if(msg == "bought")
			$( "#result" ).empty().append("You have purchased " + many + " bids!").slideDown();
		else if(msg == "nocash")
			$( "#result" ).empty().append("You must have the proper cash available to buy.").slideDown();
		else
			$( "#result" ).empty().append("You are not logged in.").slideDown();
	});
}

function bid(aid){
	$.ajax({
	  type: "POST",
	  url: "bid.php",
	  data: { id: aid }
	}).done(function(msg) {
		updateISK();
		if(msg == "nolog")
			$( "#result" ).empty().append("You must be logged in to bid.").slideDown();
		else if(msg == "nocash")
			$( "#result" ).empty().append("You must have the cash available to buy the item to bid.").slideDown();
		else if(msg == "nobid")
			$( "#result" ).empty().append("You must have bids to bid.").slideDown();
		else if(msg == "aover")
			$( "#result" ).empty().append("The auction has completed, you may not bid.").slideDown();
	});
}

function updateISK(){
	$.ajax({
	  url: "getisk.php"
	}).done(function(data) {
	  datas = data.split(" ");
	  if(parseInt(datas[0]) == 0)
		datas[0] = "0";
	  if(parseInt(datas[1]) == 0)
		datas[1] = "0";
		$( "#bids" ).empty().append(datas[0] + " Bids");
		$( "#isk" ).empty().append(datas[1] + " ISK");
	});
	if(window.logged && !window.wrongbrowser){
		$( "#disc" ).slideDown();
		$( "#loggedcontainer" ).slideDown();
		$( "#uname" ).text(uname);
		$( "#logincontainer" ).slideUp();
		$( "#registercontainer" ).slideUp();
		$( "#bids" ).slideDown();
		$( "#isk" ).slideDown();
		$( "#result" ).empty().slideUp();
		$( "#logout" ).slideDown();
		$( "#buymore" ).slideDown();
	}else if(!window.wrongbrowser) {
		$( "#disc" ).slideDown();
		$( "#logincontainer" ).slideDown();
		$( "#registercontainer" ).slideDown();
		$( "#logout" ).slideUp();
		$( "#buymore" ).slideUp();
		$( "#loggedcontainer" ).slideUp();
		$( "#isk" ).slideUp();
		$( "#bids" ).slideUp();
		$( "#uname" ).text('');
		$( "#result" ).empty().slideUp();;
	}else{
		$( "#isk" ).slideUp();
		$( "#bids" ).slideUp();
		$( "#loggedcontainer" ).slideUp();
		$( "#logincontainer" ).slideUp();
		$( "#registercontainer" ).slideUp();
		$( "#result" ).empty().append("You Must Be In The In Game Browser To Log In.");
		$( "#buymore" ).slideUp();
	}
}
function logout(){
	$.ajax({
	  url: "logout.php"
	}).done(function() {
		window.logged=false;
		updateISK();
	});
}

function rf(){
	$.ajax({
	  url: "refresh.php"
	}).done(function(data) {
		rcount++;
		datas = data.split("~");
		var modul = 0;
		var id = 0;
		var atime = 0;
		var ahigh = "";
		var aprice = 0;
		for (var i=1; i < datas.length; i++){
			modul = i%4;
			if(modul == 1)
				id = datas[i];
			else if(modul == 2)
				atime = datas[i];
			else if(modul == 3)
				ahigh = (datas[i]=="")?"None":datas[i];
			else if(modul == 0){
				aprice = datas[i];
				if(rcount > 1){
					if($( "#td" + id ).find(".abid").text() != "Bid NOW!")
						$( "#td" + id ).find(".abid").attr("tag","Bid NOW!").fadeOut(100,function(){$(this).text($(this).attr("tag")).fadeIn(100);});
												
					$( "#td" + id ).find(".atime").text(formatSecs(atime));
					if($( "#td" + id ).find(".aprice").text() != aprice)
						$( "#td" + id ).find(".aprice").attr("tag",aprice).fadeOut(100,function(){$(this).text($(this).attr("tag")).fadeIn(100);});
					if($( "#td" + id ).find(".ahigh").text() != ahigh)
						$( "#td" + id ).find(".ahigh").attr("tag",ahigh).fadeOut(100,function(){$(this).text($(this).attr("tag")).fadeIn(100);});
				}
				id = 0;
				atime = 0;
				ahigh = "";
				aprice = 0;
			}
		}
	});
}

function formatSecs(secs){
	var hrs = Math.abs(parseInt(secs/3600));
	if(hrs == 0)
		hrs = "00";
	else if(hrs < 10)
		hrs = "0" + hrs;

	var min = Math.abs(parseInt(secs/60));
	if(min == 0)
		min = "00";
	else if(min < 10)
		min = "0" + min;

	var sec = Math.abs(secs%60);
	if(sec < 10)
		sec = "0" + sec;

	return hrs + ":" + min + ":" + sec;
}

$(document).ready( function() {
    setInterval(updateISK, 20000);
    updateISK();
	setInterval(rf, 1000);
    rf();
});

</script>
</head>
<body>
<div id=page>
<div id=title>EVE Bids<div id=disc onClick="CCPEVE.showInfo(2, 98175908); $('#discpic').slideDown();setTimeout(function(){$('#discpic').slideUp();},3000);">Click To Donate to Corp</div><div id=discpic></div></div>
<div id=auctionbox>
<span id=price>
Bid Price:&nbsp;
<?php
	include 'getprice.php';
	echo number_format($bidprice);
?>&nbsp;ISK
</span>
<br>
<table id=auctiontable>
<tr>
<?php
	include 'sqlcon.php';
	$result = mysql_query("SELECT * FROM auctions");
	$i=0;
	while ($row = mysql_fetch_array($result)) {
		$result2 = mysql_query("SELECT name FROM acct WHERE id = '" . $row["highbid"] . "'");
		$result2 = mysql_query("SELECT name FROM types WHERE id = '" . $row["typeID"] . "'");
		$type = mysql_result($result2,0,"name");
		$indx = $row["id"];
		echo "<td id=td$indx>";
		echo "<div class=atype>" . $type . "</div>";
		echo "<div class=apic style='background-image:url(/types/" . $row["typeID"] . "_64.png)' 
			  onClick='CCPEVE.showInfo(" . $row["typeID"] . ");'></div>";
		echo "<div class=aprice></div>";
		echo "<div class=ahigh></div>";
		echo "<div class=atime></div>";
		echo "<div class=abid onClick='bid($indx)' title='Bid'>Bid NOW!</div>";
		echo "</td>";
		$i++;
		if($i%6 == 0)
			echo "</tr><tr>";
	}
	mysql_close();
?>
</tr>
</table>
<div id=buy>
	<div class=pkg><div class=plabel>1 bid</div><div class=pprice>300,000 ISK</div><div class=pbuy onClick="buybids(1);">BUY!</div></div>
	<div class=pkg><div class=plabel>10 bids</div><div class=pprice>2,800,000 ISK</div><div class=pbuy onClick="buybids(10);">BUY!</div></div>
	<div class=pkg><div class=plabel>100 bids</div><div class=pprice>26,000,000 ISK</div><div class=pbuy onClick="buybids(100);">BUY!</div></div>
	<div class=pkg><div class=plabel>200 bids</div><div class=pprice>50,000,000 ISK</div><div class=pbuy onClick="buybids(200);">BUY!</div></div>
	<div class=pkg><div class=plabel>500 bids</div><div class=pprice>110,000,000 ISK</div><div class=pbuy onClick="buybids(500);">BUY!</div></div>
</div>
</div>
<div class=container id=sidebar>
	<div id=logincontainer class=container>
		<div class=label>Login</div>
		<form id=login action='/login.php'>
		<input id=pass name=pass type=password>
		<input type=submit value=Login>
		</form>
	</div>
	<div id=loggedcontainer class=container>
		<div class=label id=uname></div>
		<div class=button id=logout  onClick="logout();" title="Logout">X</div>
	</div>
	<div id=registercontainer class=container>
		<div class=label>Register</div>
		<form id=addForm action='/addacct.php'>
		<input id=ingame name=ingame type=password>
		<input type=submit>
		</form>
	</div>
	<div id=bids class=currency></div><div  title="Buy Bids" class=button id=buymore  onClick="$('#buy').slideToggle(400,function(){var deg = 180*!($('#buy').is(':hidden')); $('#buymore').css({ WebkitTransform: 'rotate(' + deg + 'deg)'})});">$&#9166;</div>
	<div id=isk class=currency></div>
	<div id=result></div>
	</div>
</div>
<script>
$("#addForm").submit(function(event) {
  event.preventDefault();
  var $form = $( this ),
      term = $form.find( 'input[name="ingame"]' ).val(),
      url = $form.attr( 'action' );
  var posting = $.post( url, { 'ingame': term }, function(data, status) {
    $( "#ingame" ).val('');
	if(data == "update")
	  $( "#result" ).empty().append("Someone has already registered this name. Send mail to corp to reset your password.").slideDown();
	 else if(data == "add")
	  $( "#result" ).empty().append("A new account with a new password has been assigned.").slideDown();
	 else if(data == "notrust")
	  $( "#result" ).empty().append("You must trust this website to register.").slideDown();
  });
});

$("#login").submit(function(event) {
  event.preventDefault();
  var $form = $( this ),
      user = $form.find( 'input[name="user"]' ).val(),
      pass = $form.find( 'input[name="pass"]' ).val(),
      url = $form.attr( 'action' );
  var posting = $.post( url, { 'user' : user, 'pass' : pass }, function(data, status) {
    $( "#user" ).val('');
    $( "#pass" ).val('');
	if(data != ""){
	  $( "#result" ).empty().append("Login Successful, You may now bid.").slideDown();
	  uname = data;
	  window.logged=true;
	  updateISK();
	 }else
	  $( "#result" ).empty().append("Login Failure.").slideDown();
  });
});


</script>
</body>
</html>