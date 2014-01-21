<?php

class VircurexService extends JsonService {
	const ID = 'Vircurex';
	const NAME = 'Vircurex';
	const LINK = 'http://Vircurex.com';
	
	public function __construct($config) {
		parent::__construct($config);
		
		$time = gmdate('Y-m-d\TH:i:s');
		$id = microtime(true);
		$vData = "{$this->config['secret']};{$this->config['user']};{$time};{$id};get_balances";
		$vHash = hash('sha256', $vData);
		
		$this->url = "http://vircurex.com/api/get_balances.json?account={$this->config['user']}&id={$id}&token={$vHash}&timestamp={$time}";
	}
	
	protected function process($data) {
		$clean = array();
		
		foreach($data['balances'] as $type => $val) {
			if((float)$val['balance'] > 0) {
				$clean['balance'][] = array('type' => $type, 'value' => $val['balance']);
			}
		}
		$clean['workers'] = array();
		
		return $clean;
	}
}
