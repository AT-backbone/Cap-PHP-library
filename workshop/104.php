<?php
#### Workshop Example ####
// load the Convert Class
$res=0;
if (! $res && file_exists('../lib/cap.convert.class.php')) $res=@require_once '../lib/cap.convert.class.php';
if (! $res && file_exists('../../lib/cap.convert.class.php')) $res=@require_once '../../lib/cap.convert.class.php';	
if (! $res) die("Failure by include of cap.convert.class.php");

// load the cap Class
$res=0;
if (! $res && file_exists('../lib/cap.class.php')) $res=@require_once '../lib/cap.class.php';
if (! $res && file_exists('../../lib/cap.class.php')) $res=@require_once '../../lib/cap.class.php';	
if (! $res) die("Failure by include of cap.class.php");

$inputFile = "Z_CAP_C_EDZW_20170223134450_PVW_4.xml"; // out Input File located in ("workshop/input/")
$sourceConvertFile = "dwd"; // convert/conv_[NAME].conf.php == convert/conv_dwd.conf.php
$outputConvertFile = "meteoalarm"; // convert/conv_[NAME].conf.php == convert/onv_meteoalarm.conf.php
$outputFile = "output/"; // cap will be saved as Z_CAP_C_EDZW_20170223134450_PVW_4.conv.xml

$Cap = new CapProcessor(); // init the CapProcessor Class
$Cap->readCap("input/".$inputFile); // read Input File
$capXmlArray = $Cap->getCapXmlArray(); // Get the Xml Array of the CAP
$sourceFile = json_decode(json_encode($capXmlArray), true); // this is needed to provide a associative array

$converter = new Convert_CAP_Class(); // init the Convert_CAP_Class
$outputContent = $converter->convert($sourceFile, $sourceConvertFile, $outputConvertFile, $outputFile); // convert the sourceFile with the two ConvertFiles

print "<a href=".$res.">click to open this cap here</a><p>";
print str_replace(array("\r\n", "\n", "\r"), '<br />', $converter->_log); // provide the log as html
?>

