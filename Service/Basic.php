<?php

abstract class BasicService {
	const ID = '';
	static protected $cacheDir = '';
	protected $config;
<<<<<<< HEAD
=======
	protected $cacheTime = 300;
>>>>>>> 70bf40109b2f908636527c3edda4c009509ea819

	public function __construct($config = array()) {
		$this->config = $config;
	}

	abstract public function fetch();
	abstract protected function process($data);

	public function write_cache($data) {
		file_put_contents($this->get_cache_path(), serialize($data));
	}

	public function read_cache() {
<<<<<<< HEAD
		if(is_file($this->get_cache_path()) && filemtime($this->get_cache_path()) > time() - 5*60) {
=======
		if(is_file($this->get_cache_path()) && filemtime($this->get_cache_path()) > time() - $this->cacheTime) {
>>>>>>> 70bf40109b2f908636527c3edda4c009509ea819
			return unserialize(file_get_contents($this->get_cache_path()));
		}
	}

	protected function get_cache_path() {
		$c = get_called_class();
		return self::$cacheDir . $c::ID;
	}

	public static function get_data() {
		$c = get_called_class();
		return array(
			'name' => $c::NAME,
			'id' => $c::ID,
			'link' => $c::LINK,
			'icon' => $c::ICON,
		);
	}

	public static function get_id() {
		$c = get_called_class();
		return $c::ID;
	}

	public static function set_cache_dir($dir) {
		$dir = rtrim($dir, '/') . '/';
		self::$cacheDir = $dir;
	}
}
