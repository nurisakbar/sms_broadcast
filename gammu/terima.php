<html>
	<head>
	<style type="text/css"> 
	
	h1 {
		font-family: Verdana;
	}
	
	body {
		font-family: Verdana;
		font-size: 12px;
	}	
	
	td, th {
		font-size: 12px;
	}
	
	</style>

	<script type="text/javascript">
			function ajaxrunning()
			{
				if (window.XMLHttpRequest)
				{
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					xmlhttp =new ActiveXObject("Microsoft.XMLHTTP");
				}
	
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						document.getElementById("inbox").innerHTML = xmlhttp.responseText;
					}
				}
	
				xmlhttp.open("GET","run.php");
				xmlhttp.send();
				setTimeout("ajaxrunning()", 5000); 
			}
	</script>

	
	</head>
	<body onload="ajaxrunning()">
	
	<?php
		include 'header.php';
	?>
	
	<h2>Test Terima SMS</h2>
	
	<p>Pastikan service Gammu untuk modem sudah dijalankan sebelum test menerima sms.</p>
	
	<div id="inbox"></div>
	
	</body>
</html>