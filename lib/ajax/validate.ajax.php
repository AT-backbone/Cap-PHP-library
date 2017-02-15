<?php
	require_once '../CapValidatorChecker.class.php';
	require_once '../../class/conf.class.php';

	$configuration = new Configuration("../../conf/conf.ini");

	$CVal = new CapChecker($configuration->conf['Validator']['url']);
	if(!empty($_POST["webservice_aktive"])) if($_POST["webservice_aktive"] == 1) $CVal->profile = "meteoalarm";
	$CVal->cap_contend = $_POST["cap"];
	$info = $CVal->validate();
	echo $CVal->removeBody($info['html']);
?>
