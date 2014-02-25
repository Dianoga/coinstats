<?php
if(php_sapi_name() != 'cli') {
	die;
}

include 'autoload.php';
include 'Log.php';

$config = parse_ini_file('config.ini.php', true);
$cryptsy = new CryptsyService($config['Cryptsy']);
$log = CLib\Log::get();

$log->status('Getting balance info from Cryptsy');
$data = $cryptsy->fetch(false);
$log->done();

foreach($cryptsy->autosell as $coin) {
	$balance = $data['available'][$coin]['value'];
	$marketid = $data['exchange'][$coin]['marketid'];
	$exCoin = $data['exchange'][$coin]['to'];

	if(!is_numeric($balance)) {
		continue;
	}

	$log->heading($coin);
	$log->status('Getting market orders');
	$orders = $cryptsy->api('marketorders', array('marketid' => $marketid));
	if($orders['success']) {
		$log->done();

		$price = $orders['return']['buyorders'][0]['buyprice'];
		$log->writeLine("Balance: {$balance}");
		$log->writeLine("Order Price: {$price} {$exCoin}");

		$log->status('Creating Order');
		$order = $cryptsy->api('createorder', array('marketid' => $marketid, 'ordertype' => 'Sell', 'quantity' => $balance, 'price' => $price));
		if($order['success']) {
			$log->done();
		} else {
			$log->error();
		}
	} else {
		$log->error();
	}

	$log->endSection();
}