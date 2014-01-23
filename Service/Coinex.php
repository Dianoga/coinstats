<?php

class CoinexService extends JsonService {
	const ID = 'Coinex';
	const NAME = 'Coinex';
	const LINK = 'http://coinex.pw';

	public function __construct($config) {
		parent::__construct($config);
		$this->url = "http://coinx.pw/api/v2";
	}

	public function fetch() {
		//$data = $this->read_cache();
		if(!$data) {
			$sign = hash_hmac('sha512', '', $this->config['secret']);
			$headers = array(
				'Content-type: application/json',
				'API-Sign: '.$sign,
				'API-Key: '.$this->config['apikey'],
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, 'https://coinex.pw/api/v2/balances');
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// run the query
			$result = curl_exec($ch);
			if($result === false) {
				return array();
			}

			$data = json_decode($result, true);
			$this->write_cache($data);
		}

		return $this->process($data);
	}

	protected function process($data) {
		$clean = array();

		foreach($data['balances'] as $val) {
			$clean['balance'][] = array('type' => $val['currency_name'], 'value' => ($val['held'] + $val['amount']) / 100000000);
		}

		$clean['workers'] = array();

		return $clean;
	}
}
