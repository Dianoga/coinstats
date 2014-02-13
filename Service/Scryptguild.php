<?php

class ScryptguildService extends JsonService {
	const ID = 'Scryptguild';
	const NAME = 'ScryptGuild';
	const LINK = 'http://scryptguild.com';
	const ICON = 'http://scryptguild.com/img/favicon.ico';

	public function __construct($config) {
		parent::__construct($config);
		$this->url = "https://www.scryptguild.com/api.php?api_key={$this->config['apikey']}&workers=1&balances=1";
	}

	protected function process($data) {
		$clean = array();
		foreach($data['balances']['earnings'] as $type => $val) {
			$clean['balance'][] = array('type' => strtoupper($type), 'value' => $val + $data['balances']['adjustments'][$type] - $data['balances']['payouts'][$type]);
		}

		foreach($data['worker_stats'] as $w) {
			$clean['workers'][] = array(
				'name' => $w['worker_name'],
				'total' => $w['valid'] + $w['stale'] + $w['dupe'] + $w['unknown'],
				'accepted' => $w['valid_shares'],
				'last_share' => date('n/j/Y G:i', strtotime("-{$w['last_share']} seconds")),
				'speed' => $w['speed']
				);
		}

		return $clean;
	}
}
