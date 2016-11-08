<?php
error_reporting(E_ALL ^ E_NOTICE);
// jumlah maksimum modem yg bisa diset
$maxmodem = 8;

include 'function.php';
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

<h2>Langkah 2 - Setting Phone/Modem</h2>

<?php

if (isset($_GET['op']))
{
if ($_GET['op'] == 'simpan')
{
	include "db.php";
	$id = str_replace(" ","-",$_POST['id']);
	
	
	$smsdrc = $_POST['smsdrc'];
	$port = strtolower(str_replace(" ","", $_POST['port']));
	$connection = strtolower(str_replace(" ","", $_POST['connection']));
	$send = 'yes';
	$receive = 'yes';
	$path = str_replace('step2.php', '', $_SERVER['SCRIPT_FILENAME']);
	$handle = @fopen($smsdrc, "w");
	$text = "[gammu]
# isikan no port di bawah ini
port = ".$port.":
# isikan jenis connection di bawah ini
connection = ".$connection."

[smsd]
service = mysql
logfile = ".$path."log".$smsdrc."
debuglevel = 0
phoneid = ".$id."
commtimeout = 30
sendtimeout = 600
send = ".$send."
receive = ".$receive."
checksecurity = 0
#PIN = 1234

# -----------------------------
# Konfigurasi koneksi ke MySQL
# -----------------------------
pc = localhost

# isikan user untuk akses ke MySQL
user = ".$dbuser."
# isikan password user untuk akses ke MySQL
password = ".$dbpass."
# isikan nama database untuk Gammu
database = ".$dbname."\n";

  fwrite($handle, $text);
  fclose($handle);
  
	$string = "";
	$j = 0;
	for($i=1; $i<=$maxmodem; $i++)
	{
		if (is_file('smsdrc'.$i))
		{
			$handle = @fopen("smsdrc".$i, "r");
			if ($handle) 
			{
				while (!feof($handle)) 
				{
					$buffer = fgets($handle);
					if (substr_count($buffer, 'port = ') > 0)
					{
						$split = explode("port = ", $buffer);
						$port = $split[1];
					}
					if (substr_count($buffer, 'connection = ') > 0)
					{
						$split = explode("connection = ", $buffer);
						$connection = $split[1];
				}
				}
			}		
			fclose($handle);
			if ($j==0) $string .= "[gammu]\nport = ".$port."connection = ".$connection."\n";
			else $string .= "[gammu".($j)."]\nport = ".$port."connection = ".$connection."\n";
			$j++;
		}	
	}
	$handle = @fopen("gammurc", "w");
	fwrite($handle, $string);
	fclose($handle);

}
}

if (isset($_GET['op']))
{
if ($_GET['op'] == 'del')
{
	$id = $_GET['id'];
	

	if(is_file("logsmsdrc".$id)) unlink("logsmsdrc".$id);
	exec("gammu-smsd -n ".getParam('id', $id)." -k", $hasil);
	exec("gammu-smsd -n ".getParam('id', $id)." -u", $hasil);
	unlink("smsdrc".$id);
	
	$string = "";
	$j = 0;
	for($i=1; $i<=$maxmodem; $i++)
	{
		if (is_file('smsdrc'.$i))
		{
			$handle = @fopen("smsdrc".$i, "r");
			if ($handle) 
			{
				while (!feof($handle)) 
				{
					$buffer = fgets($handle);
					if (substr_count($buffer, 'port = ') > 0)
					{
						$split = explode("port = ", $buffer);
						$port = $split[1];
					}
					if (substr_count($buffer, 'connection = ') > 0)
					{
						$split = explode("connection = ", $buffer);
						$connection = $split[1];
					}
				}
			}		
			fclose($handle);
			if ($j==0) $string .= "[gammu]\nport = ".$port."connection = ".$connection."\n";
			else $string .= "[gammu".($j)."]\nport = ".$port."connection = ".$connection."\n";
			$j++;
		}	
	}
	$handle = @fopen("gammurc", "w");
	fwrite($handle, $string);
	fclose($handle);
	
}
}

for($i=1; $i<=$maxmodem; $i++)
{
	if (is_file('smsdrc'.$i))
	{
		$sum = $i + 1;
	}	
}

if ($sum == 0) $sum = 1;

$nextsmsdrc = "smsdrc".$sum;

?>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>?op=simpan">
<table>
 <tr valign="top"><td>ID Phone/Modem</td><td>:</td><td><input type="text" name="id"><br><small>Isikan sembarang nama untuk identitas modem Anda, Contoh: Modem 1</small></td></tr>
 <tr valign="top"><td>PORT</td><td>:</td><td><input type="text" name="port"><input type="hidden" name="smsdrc" value="<?php echo $nextsmsdrc; ?>"><br><small>Masukkan nomor port modem/hp.</small> <br><small>Contoh penulisan: <b>com4</b> (dengan huruf kecil dan tanpa spasi apa-apa)</small></td></tr>
 <tr valign="top"><td>CONNECTION</td><td>:</td><td><select name="connection"><option>at115200</option><option>at19200</option><option>at9600</option><option>at</option></select><br><small>Pilih jenis connection hp/modem Anda. <br>Modem Wavecom = at115200<br><a href="connection.xls">Lihat Jenis Connection</a></small></td></tr>
 <tr valign="top"><td>Send SMS</td><td>:</td><td><select name="send" disabled><option>yes</option><option>no</option></select></td></tr>
 <tr valign="top"><td>Receive SMS</td><td>:</td><td><select name="receive" disabled><option>yes</option><option>no</option></select></td></tr>
</table>
<input type="submit" name="submit1" value="Simpan">
</form>


<?php
$sum = 0;
for($i=1; $i<=$maxmodem; $i++)
{
	if (is_file('smsdrc'.$i))
	{
		$sum++;
	}	
}

if ($sum == 0) echo "<p>Phone/Modem belum ada</p>";
else
{
echo "<table width='100%' border='0'>";
echo "<tr><td width='50%'>";

echo "<table border='1' width='100%' style='font-size: 10px;'>";
echo "<tr><th>ID Phone</th><th>Port</th><th>Connection</th><th>Send</th><th>Receive</th><th width='45%'>Action</th></tr>";
$count = 0;
for($i=1; $i<=$maxmodem; $i++)
{
	if (is_file('smsdrc'.$i))
	{
			$count++;
			$handle = @fopen("smsdrc".$i, "r");
			if ($handle) 
			{
				while (!feof($handle)) 
				{
					$buffer = fgets($handle);
					if (substr_count($buffer, 'port = ') > 0)
					{
						$split = explode("port = ", $buffer);
						$port = $split[1];
					}
					if (substr_count($buffer, 'phoneid = ') > 0)
					{
						$split = explode("phoneid = ", $buffer);
						$phone = $split[1];
					}
					if (substr_count($buffer, 'connection = ') > 0)
					{
						$split = explode("connection = ", $buffer);
						$conn = $split[1];
					}
				}
			}
	
		echo "<tr valign='top' style='font-size: 10px;'><td style='font-size: 11px;'>".$phone."</td><td style='font-size: 11px;'>".$port."</td><td style='font-size: 11px;'>".$conn."</td><td style='font-size: 11px;'>".getParam('send', $i)."</td><td style='font-size: 11px;'>".getParam('receive', $i)."</td><td align='center' style='font-size: 11px;'>&nbsp;<a href='".$_SERVER['PHP_SELF']."?op=cek&id=".$count."'><b><font color='red'>CEK KONEKSI</font></b></a> | <a href='".$_SERVER['PHP_SELF']."?op=service&id=".$i."'><b><font color='green'>BUAT SERVICE</font></a> |  <a href='".$_SERVER['PHP_SELF']."?op=del&id=".$i."'><b>HAPUS</b></a>&nbsp;</td></tr>";
		$sum++;
		
	}	
}
echo "</table>";
echo "<p><b>Penting !!!</b><br>Pastikan sebelum menghapus modem, service Gammu untuk modem tersebut harus dimatikan dahulu</p>";
echo "</td>";
echo "<td width='2%'>&nbsp;";
echo "</td><td width='48%' valign='top'>";

if (isset($_GET['op']))
{
if ($_GET['op'] == 'cek')
{
	$id = ($_GET['id']-1);
	echo "<p><b>Status Koneksi Phone/Modem ".$_GET['id']."</b></p>";
	echo "<pre>";
    passthru("gammu -s ".$id." -c gammurc identify", $hasil);
    echo "</pre>";
}
}

if (isset($_GET['op']))
{
if ($_GET['op'] == 'service')
{
	$id = $_GET['id'];
	echo "<p><b>Status Service Phone/Modem: ".getParam('id', $id)."</b></p>";
	echo "<pre>";
    exec("gammu-smsd -n ".getParam('id', $id)." -k", $hasil);
	exec("gammu-smsd -n ".getParam('id', $id)." -u", $hasil);
	passthru("gammu-smsd -c smsdrc".$id." -n ".getParam('id', $id)." -i");
	exec("sc config ".getParam('id', $id)." start= demand");
    echo "</pre>";
}
}

echo "</td></tr>";
echo "</table>";
}

?>


</body>
</html>
