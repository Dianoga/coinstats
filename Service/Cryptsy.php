<?php

class CryptsyService extends JsonService {
	const ID = 'Cryptsy';
	const NAME = 'Cryptsy';
	const LINK = 'http://cryptsy.com';
	const ICON = 'http://cryptsy.com/favicon.ico';
	protected $cacheTime = 60;

	public function __construct($config) {
		parent::__construct($config);

		$time = gmdate('Y-m-d\TH:i:s');
		$id = microtime(true);
		$vData = "{$this->config['secret']};{$this->config['user']};{$time};{$id};get_balances";
		$vHash = hash('sha256', $vData);

		$this->url = "http://api.cryptsy.com/api";
	}

	public function fetch() {
		$data = $this->read_cache();
		if(!$data) {
			$data['info'] = $this->api('getinfo');
			$data['exchange'] = $this->api('getmarkets');
			$this->write_cache($data);
		}

		return $this->process($data);
	}

	protected function process($data) {
		$clean = array();

		$info = $data['info']['return'];
		foreach($info['balances_available'] as $type => $val) {
			if((float)$val > 0) {
				$clean['balance'][$type] = array('type' => $type, 'value' => $val);
			}
		}

		if(!empty($info['balances_hold'])) {
			foreach($info['balances_hold'] as $type => $val) {
				if((float)$val > 0) {
					$total = $val;
					if(isset($clean['balance'][$type]['value'])) {
						$total += $clean['balance'][$type]['value'];
					}
					$clean['balance'][$type] = array('type' => $type, 'value' => $total );
				}
			}
		}
		$clean['workers'] = array();

		$exchange = $data['exchange']['return'];
		foreach($exchange as $exc) {
			if($exc['secondary_currency_code'] == 'BTC') {
				$clean['exchange'][] = array(
					'from' => $exc['primary_currency_code'],
					'to' => $exc['secondary_currency_code'],
					'value' => $exc['last_trade'],
					);
			}
		}

		return $clean;
	}

	private function api($method) {
		$req['method'] = $method;
		$req['nonce'] = microtime(true);
		$post = http_build_query($req, '', '&');
		$sign = hash_hmac('sha512', $post, $this->config['secret']);
		$headers = array(
			'Sign: '.$sign,
			'Key: '.$this->config['apikey'],
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, 'https://api.cryptsy.com/api');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// run the query
		$result = curl_exec($ch);
		if($result === false) {
			return array();
		}

		$data = json_decode($result, true);
		return $data?: array();
	}
}
