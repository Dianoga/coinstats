<?php

class BtcguildService extends JsonService {
	const ID = 'Btcguild';
	const NAME = 'BTCGuild';
	const LINK = 'http://btcguild.com';
	
	public function __construct($config) {
		parent::__construct($config);
		$this->url = "https://www.btcguild.com/api.php?api_key={$this->config['apikey']}";
	}
	
	protected function process($data) {
		$clean = array();
		
		$clean['balance'][] = array('type' => 'BTC', 'value' => $data['user']['unpaid_rewards']);
		$clean['balance'][] = array('type' => 'NMC', 'value' => $data['user']['unpaid_rewards_nmc']);

		foreach($data['workers'] as $w) {
			$clean['workers'][] = array(
				'name' => $w['worker_name'],
				'total' => $w['valid_shares'] + $w['stale_shares'] + $w['dupe_shares'] + $w['unknown_shares'],
				'accepted' => $w['valid_shares'],
				'last_share' => date('n/j/Y G:i', strtotime("-{$w['last_share']} seconds")),
				'speed' => $w['hash_rate']
				);
		}
		
		return $clean;
	}
}
