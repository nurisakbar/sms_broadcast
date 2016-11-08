<?php

include 'koneksi.php';

// menampilkan semua sms di inbox

$query = "SELECT * FROM inbox ORDER BY ReceivingDateTime DESC";
$hasil = mysql_query($query);

echo "<table border='1'>";
echo "<tr><th>Pesan SMS</th><th>Pengirim</th><th>Waktu</th><th>Modem</th></tr>";		
while ($data = mysql_fetch_array($hasil))
{
	$nohp = $data['SenderNumber'];
	$modem = $data['RecipientID']; 
	$time = $data['ReceivingDateTime'];
	$text = $data['TextDecoded'];
	echo "<tr><td>".$text."</td><td>".$nohp."</td><td>".$time."</td><td>".$modem."</td></tr>";
}	
echo "</table>";
?>