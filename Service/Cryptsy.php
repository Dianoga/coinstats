<?php

class CryptsyService extends JsonService {
	const ID = 'Cryptsy';
	const NAME = 'Cryptsy';
	const LINK = 'http://cryptsy.com';
	const ICON = 'http://cryptsy.com/favicon.ico';
	protected $cacheTime = 60;
	public $autosell;

	public function __construct($config) {
		parent::__construct($config);
		$this->url = "http://api.cryptsy.com/api";
		$this->autosell = explode(',', $config['autosell']);
	}

	public function fetch($useCache = true) {
		$data = $useCache? $this->read_cache() : false;
		if(!$data) {
			$data['info'] = $this->api('getinfo');
			$data['exchange'] = $this->api('getmarkets');
			if($useCache) {
				$this->write_cache($data);
			}
		}

		return $this->process($data);
	}

	protected function process($data) {
		//pr($data);
		$clean = array();

		$info = $data['info']['return'];
		foreach($info['balances_available'] as $type => $val) {
			if((float)$val > 0) {
				$clean['balance'][$type] = array('type' => $type, 'value' => $val);
				$clean['available'][$type] = array('type' => $type, 'value' => $val);
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
			if($exc['secondary_currency_code'] == 'BTC' || !isset($clean['exchange'][$exc['primary_currency_code']])) {
				$clean['exchange'][$exc['primary_currency_code']] = array(
					'from' => $exc['primary_currency_code'],
					'to' => $exc['secondary_currency_code'],
					'value' => $exc['last_trade'],
					'marketid' => $exc['marketid'],
					);
			}
		}

		return $clean;
	}

	public function api($method, $req = array()) {
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
