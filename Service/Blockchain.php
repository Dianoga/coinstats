<?php

class BlockchainService extends JsonService {
	const ID = 'Blockchain';
	const NAME = 'Blockchain';
	const LINK = 'http://blockchain.info';
	
	public function __construct($config) {
		parent::__construct($config);
		$this->url['all'] = "https://blockchain.info/address/{$this->config['address']}?format=json";
		$this->url['ticker'] = 'https://blockchain.info/ticker';
	}
	
	protected function process($data) {
		$clean = array();
		
		$balance = $data['all']['final_balance'] / 100000000;
		$clean['balance'][] = array('type' => 'BTC', 'value' => $balance);
		$clean['balance'][] = array('type' => 'USD', 'value' => number_format($balance * $data['ticker']['USD']['sell'], 2));		
		$clean['balance'][] = array('type' => 'Exchange', 'value' => number_format($data['ticker']['USD']['sell'], 2));
		//$clean['unconfirmed'] = 0;
		$clean['workers'] = array();
		
		return $clean;
	}
}
