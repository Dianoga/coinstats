<?php
namespace CLib;

/**
 * @package CornellLibraries
 */

class Log {
	private static $instance;

	public $indent;
	public $endline;
	public $newline;
	public $statusSeparator = '...';
	public $statusWidth;

	private $echo = true;
	private $file = null;

	private $level;

	private function __construct() {
		if(php_sapi_name() == 'cli') {
			$this->endline = "\n";
		} else {
			$this->endline = "<br />\n";
		}

		$this->level = 0;
		$this->newline = false;
		$this->indent = "\t";
	}

	public function __destruct() {
		if($this->file) {
			fclose($this->file);
		}
	}

	public function setFile($filename) {
		$this->file = fopen($filename, 'wb');
	}

	public function setEcho($enable) {
		$this->echo = $enable;
	}

	// Allow for simple completion methods
	public function __call($name, $arguments){
		$this->writeLine(ucwords(str_replace('_', ' ', $name)));
	}

	public static function get() {
		if(!isset(self::$instance)) {
			self::$instance = new Log();
		}

		return self::$instance;
	}

	public function write($message) {
		if($this->newline){
			$message = str_repeat($this->indent, $this->level) . $message;
		}

		if($this->echo) {
			echo $message;
		}

		if($this->file) {
			fwrite($this->file, $message);
		}

		$this->newline = false;
	}

	public function writeLine($message) {
		$this->write($message . $this->endline);
		$this->newline = true;
	}

	public function status($message){
		if($this->statusWidth) {
			// Add a space to make sure there is alway a gap
			$message .= ' ';
			$message = str_pad($message, $this->statusWidth);
		} else {
			$message = $message . $this->statusSeparator;
		}

		$this->write($message);
	}

	public function heading($message) {
		$this->writeLine($message);
		$this->newline = true;
		$this->level++;
	}

	public function indent() {
		$this->level++;
	}

	public function endSection(){
		$this->newline = true;
		$this->level--;
	}

	public function exception($e){
		$this->failed();

		$this->heading('Exception Details');
		$this->writeLine("Type: " . get_class($e));
		$this->writeLine("Code: " . $e->getCode());
		$this->writeLine("Message: " . $e->getMessage());
		$this->writeLine("File: " . $e->getFile());
		$this->writeLine("Line: " . $e->getLine());
		$this->heading("Trace");

		$trace = $e->getTraceAsString();
		$trace = explode("\n", $trace);
		foreach($trace as $line){
			$this->writeLine($line);
		}
	}
}