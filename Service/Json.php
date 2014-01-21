<?php

abstract class JsonService extends BasicService {
	protected $url = array();
	
	public function fetch() {
		$data = $this->read_cache();
		if(!$data) {
			if(is_array($this->url)) {
				foreach($this->url as $type => $l) {
					$data[$type] = json_decode(file_get_contents($l), true);
				}
			} else {
				$data = json_decode(file_get_contents($this->url), true);
			}
			
			$this->write_cache($data);
		}
		
		return $this->process($data);
	}
}
