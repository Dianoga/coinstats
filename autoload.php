<?php

spl_autoload_register(function($class) {
	$bits = array_filter(array_reverse(preg_split('/(?=[A-Z])/', $class)));
	require_once implode('/', $bits) . '.php';
});

function pr($element, $varDump = false) {
	$cli = php_sapi_name() == 'cli';

	$trace = debug_backtrace();
	foreach ($trace as $key => $step) {
			//var_dump($step);
		if (isset($step['class']) || !in_array($step['function'], array('pr', 'debug'))) {
			$line = $trace[$key-1]['line'];
			$file = $trace[$key-1]['file'];
			break;
		}
	}

	if(empty($line)) {
		$last = end($trace);
		$line = $last['line'];
		$file = $last['file'];
	}

	if(!$cli) {
		echo "<div  style='text-align: left; background: #f8f8f8; padding: 1em; border: 1px solid #888; margin: 1em;'>";
		echo "<strong style='border-bottom: 1px solid #888; display: block; padding-bottom: 0.5em; '>{$file} [ {$line} ]</strong>";
		echo "<pre style='margin-top: 0.5em;'>";
	} else {
		echo "{$file} [ {$line} ]\n";
		echo "==============================================================\n";
	}

	if($varDump) {
		var_dump($element);
	} else {
		print_r($element);
	}

	if(!$cli) {
		echo "</pre>";
		echo "</div>";
	} else {
		echo "\n";
	}
}

