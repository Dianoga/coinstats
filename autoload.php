<?php

spl_autoload_register(function($class) {
	$bits = array_filter(array_reverse(preg_split('/(?=[A-Z])/', $class)));
	require_once implode('/', $bits) . '.php';
});
