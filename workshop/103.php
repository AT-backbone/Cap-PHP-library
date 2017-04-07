<?php
#### Workshop Example SOAP Webservice####
error_reporting(E_ALL);
ini_set("display_errors", 1); 
$res=0;
if (! $res && file_exists('../lib/cap.webservices.class.php')) $res=@require_once '../lib/cap.webservices.class.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists('../../lib/cap.webservices.class.php')) $res=@require_once '../../lib/cap.webservices.class.php';	
if (! $res) die("Failure by require of cap.webservices.class.php");

$WS_URL = "http://meteoalarm.eu:8080/functions/webservices/capimport.php";
$MWS = new MeteoalarmWebservice($WS_URL);

$ns = "http://meteoalarm.eu:8080/functions/webservices/";
$WS_METHOD = "putCap";
$securitykey = "{SECURITY_KEY}";
$login= "{YOUR_USER}";
$password= "{YOUR_PASSWORD}";

// Specify the filename and the Caps content as a string:
$filename= "test.cap";

// convert in UTF-8
$contents = file_get_contents('./output/test.cap.xml');
$import="false";
$debug="true";

$MWS->setParameterArray($securitykey, $WS_METHOD, $login, $password, $filename, $contents, $import, $debug);
$soap_response = $MWS->sendSoapCall($WS_METHOD, $ns);
echo $soap_response;
?>


