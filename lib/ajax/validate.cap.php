<?php
	require_once '../CapValidatorChecker.class.php';

		$CVal = new CapChecker();
		if($_POST["webservice_aktive"] == 1) $CVal->profile = "meteoalarm";
		$CVal->cap_contend = $_POST["cap"];
		$info = $CVal->validate();
		echo $CVal->removeBody($info['html']);
?>
