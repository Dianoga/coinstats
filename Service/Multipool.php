<?php

class MultipoolService extends JsonService {
	const ID = 'Multipool';
	const NAME = 'Multipool';
	const LINK = 'http://multipool.us/accountdetails.php';
	
	public function __construct($config) {
		parent::__construct($config);
		$this->url = "http://api.multipool.us/?api_key={$this->config['apikey']}";
	}
	
	protected function process($data) {
		$clean = array();
		
		foreach($data['currency'] as $type => $value) {
			if($value['confirmed_rewards'] > 0) {
				$clean['balance'][] = array('type' => $type, 'value' => $value['confirmed_rewards']);
			}
		}
				
		$workers = array();
		foreach($data['workers'] as $w) {
			foreach($w as $name => $value) {
				$workers[$name] += $value['hashrate'];
			}
		}
		
		foreach($workers as $name => $value) {
			$clean['workers'][] = array('name' => $name, 'speed' => $value);
		}
		
		return $clean;
	}
}
