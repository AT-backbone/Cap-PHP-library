<?php
require_once 'class/conf.class.php';

	$configuration = new Configuration("conf/conf.ini");

	$configuration->set("conf", "test", "1231");
	$configuration->set("conf", "123", "value2");
	$configuration->set("conf", "test3", "value3");
	$configuration->set("conf", "test4", "4");

	print '<pre>';
		print_r($configuration);
	print '</pre>';

	$configuration->write_php_ini();
	
?>
