<?php

function getParam($x, $i)
{
	$handle = @fopen("smsdrc".$i, "r");
	if ($handle) 
	{
		while (!feof($handle)) 
		{
			$buffer = fgets($handle);
			if (substr_count($buffer, $x.' = ') > 0)
			{
				$split = explode($x." = ", $buffer);
				$param = str_replace(chr(13).chr(10), "", $split[1]);
			}
		}
	}		
	fclose($handle);
	return $param;
}


?>