<?php

class MultipoolService extends JsonService {
	const ID = 'Multipool';
	const NAME = 'Multipool';
	const LINK = 'http://multipool.us/accountdetails.php';
	const ICON = 'http://multipool.us/images/favicon.png';

	public function __construct($config) {
		parent::__construct($config);
		$this->url = "http://api.multipool.us/?api_key={$this->config['apikey']}";
	}

	protected function process($data) {
		$clean = array();

		foreach($data['currency'] as $type => $value) {
			if($value['confirmed_rewards'] > 0) {
				$clean['balance'][] = array('type' => strtoupper($type), 'value' => $value['confirmed_rewards']);
			}
		}

		$workers = array();
		foreach($data['workers'] as $type => $w) {
			foreach($w as $name => $value) {
				$workers["{$name}_{$type}"] = $value['hashrate'];
			}
		}
		$workers = array_filter($workers);

		foreach($workers as $name => $value) {
			$clean['workers'][] = array('name' => $name, 'speed' => $value);
		}

		return $clean;
	}
}
