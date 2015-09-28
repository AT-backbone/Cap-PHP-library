<?php

/*
 * Webservice conf
 */

$dolibarrkey 				= '';
$sourceapplication 	= '';
$login 							= '';
$password 					= '';
$entity 						= '';

$WS_METHOD 					= '';

// for option a other url as standard url
if($_POST[url])
{
	$WS_DOL_URL				= $_POST[url];
}
else
{
	$ns								= '';
	$WS_DOL_URL 			= $ns.'';
}

/*
 * Form conf
 */

date_default_timezone_set('Europe/Vienna');