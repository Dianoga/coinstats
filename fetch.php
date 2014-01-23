<?php
include 'autoload.php';

$config = parse_ini_file('config.ini.php', true);

if(empty($_GET['pool'])) {
	$data = array();
	foreach(array_keys($config) as $pool) {
		$service = "{$pool}Service";
		$data[] = $service::get_data();
	}
} else {
	BasicService::set_cache_dir(__DIR__ . '/cache');
	$pool = $_GET['pool'];
	if(!isset($config[$pool])) {
		die('Bad Pool');
	}

	$service = "{$pool}Service";
	$s = new $service($config[$pool]);
	$data = $s->fetch();
}
header('Content-Type: application/json');
echo json_encode($data);

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
