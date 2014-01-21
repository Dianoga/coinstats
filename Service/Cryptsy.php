<?php

class CryptsyService extends JsonService {
	const ID = 'Cryptsy';
	const NAME = 'Cryptsy';
	const LINK = 'http://cryptsy.com';
	
	public function __construct($config) {
		parent::__construct($config);
		
		$time = gmdate('Y-m-d\TH:i:s');
		$id = microtime(true);
		$vData = "{$this->config['secret']};{$this->config['user']};{$time};{$id};get_balances";
		$vHash = hash('sha256', $vData);
		
		$this->url = "http://www.cryptsy.com/api";
	}
	
	public function fetch() {		
		$data = $this->read_cache();
		if(!$data) {
			$req['method'] = 'getinfo';
			$req['nonce'] = microtime(true);
			$post = http_build_query($req, '', '&');
			$sign = hash_hmac('sha512', $post, $this->config['secret']);
			$headers = array(
				'Sign: '.$sign,
				'Key: '.$this->config['apikey'],
			);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, 'https://www.cryptsy.com/api');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
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
		
		foreach($data['return']['balances_available'] as $type => $val) {
			if((float)$val > 0) {
				$clean['balance'][$type] = array('type' => $type, 'value' => $val);
			}
		}
		
		foreach($data['return']['balances_hold'] as $type => $val) {
			if((float)$val > 0) {
				$total = $val;
				if(isset($clean['balance'][$type]['value'])) {
					$total += $clean['balance'][$type]['value'];
				}
				$clean['balance'][$type] = array('type' => $type, 'value' => $total );
			}
		}
		$clean['workers'] = array();
		
		return $clean;
	}
}
