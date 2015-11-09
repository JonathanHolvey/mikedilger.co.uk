<?php
	$headers = "From: webmaster@example.com" . "\r\n" .	"Reply-To: webmaster@example.com" . "\r\n" . "X-Mailer: PHP/" . phpversion();

	if (mail("jonathan.holvey@hotmail.co.uk","Test subject","Message body",$headers))
		echo "email sent";
	else
		echo "error";
?> 
