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