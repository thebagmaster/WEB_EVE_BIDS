<script src="jquery.min.js"></script>
<script>
var date = [];
var id = [];
var ammount = [];

function rf(){
	$.ajax({
	  url: "wallet.php"
	}).done(function(data) {
		console.log(data);
		datas = data.split("~");
		var modul = 0;
		var id = [];
		var ammount = [];
		var date = [];
		var ind = 0;
		for (var i=1; i < datas.length; i++){
			modul = i%3;
			if(modul == 1)
				date[ind] = datas[i];
			else if(modul == 2)
				id[ind] = datas[i];
			else if(modul == 0){
				ammount[ind] = datas[i];
				ind++;
			}
		}
		var settings = {};
		for (var i=0; i < date.length; i++){
			settings["rdate" + i] = date[i];
			settings["rid" + i] = id[i];
			settings["rammount" + i] = ammount[i];
		}
		$.post("creditit.php", settings, function(data) {console.log(data)});
	});
}

$(document).ready( function() {
	setInterval(rf, 300000);
    rf();
});
</script>

<body>
</body>