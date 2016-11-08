<?php
error_reporting(E_ALL ^ E_NOTICE);
?>
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
	
	td {
		font-size: 12px;
	}
	
	</style> 
</head>
<body>

<?php
include 'header.php';
?>

<?php

if (isset($_POST['submit']))
{
if ($_POST['submit'])
{
	$user = $_POST['username'];
	$pass = $_POST['pass'];
	$db   = str_replace(" ","", $_POST['db']);

	$file = "db.php";
	$arrayRead = file($file);
	
	$arrayRead[1] = "\$dbuser = \"".$user."\";\n";
    $arrayRead[2] = "\$dbpass = \"".$pass."\";\n";
	$arrayRead[3] = "\$dbname = \"".$db."\";\n";
	
	$simpan = file_put_contents($file, implode($arrayRead));
	
	mysql_connect("localhost", $user, $pass);

	$query = "CREATE DATABASE ".$db;
	$result = mysql_query($query);
	
    $handle = @fopen("mysql-table.sql", "r");
	$content = fread($handle, filesize("mysql-table.sql"));
	$split = explode(";", $content);
	
	mysql_select_db($db);
	
	for ($i=0; $i<=count($split)-1; $i++)
	{
	  mysql_query($split[$i]);
    }

	fclose($handle);  
	echo "<p><font color='red'><b>Database sudah disetting</b></font></p>";	
}
}

include "db.php";
?>

<h2>Langkah 1 - Setting Database</h2>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<table>

<tr><td>Username MySQL</td><td>:</td><td><input type="text" name="username" value="<?php echo $dbuser; ?>"></td></tr>
<tr><td>Password MySQL</td><td>:</td><td><input type="text" name="pass" value="<?php echo $dbpass; ?>"></td></tr>
<tr><td>Nama Database MySQL</td><td>:</td><td><input type="text" name="db" value="<?php echo $dbname; ?>"></td></tr>
</table>
<input type="submit" name="submit" value="Buat Database">
</form>



<br>
<br>

<hr>


</body>
</html>

