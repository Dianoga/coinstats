<?php

abstract class JsonService extends BasicService {
	protected $urls = array();
	
	public function fetch() {
		$data = $this->read_cache();
		if(!$data) {
			if(is_array($this->url)) {
				foreach($this->urls as $type => $l) {
					$data[$type] = tidy_repair_file($l);
				}
			} else {
				$data = tidy_repair_file($this->url);
			}
			
			$this->write_cache($data);
		}
		
		return $this->process($data);
	}
	
	abstract protected function process($data);
}
