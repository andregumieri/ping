#!/usr/bin/php
<?php
	date_default_timezone_set('America/Sao_Paulo');
	
	require 'libs/PHPMailer/class.phpmailer.php';
	require 'functions/gmail.php';
	require 'functions/tempo_extenso.php';
	if(!require 'settings.php') die("Create a settings.php file");

	$ERROR_FILE = __DIR__.'/.erro';

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, TEST_SITE);
	curl_setopt($curl, CURLOPT_FILETIME, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	$header = curl_exec($curl);
	$info = curl_getinfo($curl);
	

	if($header===false) {
		if(!file_exists($ERROR_FILE)) file_put_contents($ERROR_FILE, mktime());
		echo "Internet fora:\n";
		echo curl_errno($curl);
		echo " - ";
		echo curl_error($curl);
		echo "\n";
	} else {
		if(file_exists($ERROR_FILE)) {
			$time = intval(file_get_contents($ERROR_FILE));
			$tempo_num = mktime()-$time;
			$tempo_extenso = tempo_extenso($tempo_num);	

			$mensagem = "A internet ficou fora por " . $tempo_extenso;
			gmail(EMAIL, '[Ping] offline report', $mensagem);
			file_put_contents(__DIR__.'/reports.txt', date("d/m/y H:i:s") . "\n" . $mensagem . "\n\n", FILE_APPEND);
			unlink($ERROR_FILE);
		}
		echo "Tem conexão\n";
	}
	
	curl_close($curl);
?>