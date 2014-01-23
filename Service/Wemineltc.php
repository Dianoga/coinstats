<?php

class WemineltcService extends JsonService {
	const ID = 'Wemineltc';
	const NAME = 'Wemineltc';
	const LINK = 'http://Wemineltc.com';
	const ICON = 'http://wemineltc.com/favicon.ico';

	public function __construct($config) {
		parent::__construct($config);
		$this->url = "https://www.wemineltc.com/api?api_key={$this->config['apikey']}";
	}

	protected function process($data) {
		$clean = array();

		$clean['balance'][] = array('type' => 'LTC', 'value' => $data['confirmed_rewards']);
		$clean['unconfirmed'] = $data['round_estimate'];

		foreach($data['workers'] as $name => $w) {
			$clean['workers'][] = array(
				'name' => $name,
				'speed' => $w['hashrate']
				);
		}

		return $clean;
	}
}
