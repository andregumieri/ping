#!/usr/bin/php
<?php
	$PID = __DIR__.'/.pid';

	if(file_exists($PID)) die("Ja tem um processo em andamento\n");
	file_put_contents($PID, "");

	date_default_timezone_set('America/Sao_Paulo');
	
	require 'libs/PHPMailer/class.phpmailer.php';
	require 'functions/gmail.php';
	require 'functions/tempo_extenso.php';
	require 'functions/online.php';
	if(!require 'settings.php') die("Create a settings.php file");

	$ERROR_FILE = __DIR__.'/.erro';

	$SITES = explode(";", TEST_SITE);
	$totalonline = 0;

	foreach($SITES as $SITE) {
		$totalonline+=online(trim($SITE));
	}
	

	if($totalonline==0) {
		if(!file_exists($ERROR_FILE)) file_put_contents($ERROR_FILE, mktime());
		echo date("d/m/Y H:i:s") . " - Internet fora:\n";
		echo curl_errno($curl);
		echo " - ";
		echo curl_error($curl);
		echo "\n";
	} else {
		if(file_exists($ERROR_FILE)) {
			$time = intval(file_get_contents($ERROR_FILE));
			$time_agora = mktime();
			$tempo_num = $time_agora-$time;
			$tempo_extenso = tempo_extenso($tempo_num);

			$das = date("d/m/Y H:i:s", $time);
			$ate = date("d/m/Y H:i:s", $time_agora);	

			$mensagem = "A internet ficou fora por " . $tempo_extenso . "<br />{$das} até {$ate}";
			gmail(EMAIL, '[Ping] offline report', $mensagem);
			file_put_contents(__DIR__.'/reports.txt', date("d/m/y H:i:s") . "\n" . str_replace("<br />", "\n", $mensagem) . "\n\n", FILE_APPEND);
			unlink($ERROR_FILE);
		}
		echo date("d/m/Y H:i:s") . " - Tem conexão ({$totalonline} de " . count($SITES) . ")\n";
	}
	
	
	unlink($PID);
?>
