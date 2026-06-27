<?php
function e7061($e)
{
	$ed = base64_decode($e);
	$n = openssl_decrypt("$ed","AES-256-CBC","1008141403081403",0,"1403081403081403");
	return $n;
}

?>


