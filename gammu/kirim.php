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
		include 'koneksi.php';
	?>
	
	<h2>Test Kirim SMS</h2>
	<p>Pastikan sebelum test mengirim SMS, service gammu untuk modem yang akan digunakan untuk mengirim SMS sudah dijalankan</p>
	
	<form method="post" action="kirim.php?op=send">
	<table>
		<tr><td>Nomor HP Tujuan</td><td>:</td><td><input type="text" name="nohp"></td></tr>
		<tr><td>Dikirim via Modem</td><td>:</td><td>
		
		<select name="modem">
		<?php
			$query = "SELECT ID FROM phones ORDER BY ID";
			$hasil = mysql_query($query);
			while($data = mysql_fetch_array($hasil))
			{
				echo "<option>".$data['ID']."</option>";
			}
		?>
		</select>
		
		</td></tr>
		<tr valign="top"><td>Pesan SMS (Max. 160 karakter)</td><td>:</td><td><textarea name="pesan" rows="5"></textarea></td></tr>
		<tr><td></td><td></td><td><input type="submit" name="submit" value="Kirim SMS"></td></tr>
	</table>
	</form>
	
	<?php
	
	if (isset($_GET['op']))
	{
		if ($_GET['op'] == 'send')
		{
			$nohp = $_POST['nohp'];
			$modem = $_POST['modem'];
			$pesan = $_POST['pesan'];
			$query = "INSERT INTO outbox (DestinationNumber, SenderID, TextDecoded, CreatorID) VALUES ('$nohp', '$modem', '$pesan', 'Gammu 1.28.90')";
			$hasil = mysql_query($query);
			if ($hasil) echo "<p>SMS dalam proses pengiriman</p>";
			else echo "<p>Pengiriman SMS gagal</p>";
		}
	}
	
	?>
	
	</body>
</html>