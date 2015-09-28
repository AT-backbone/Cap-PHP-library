<?php
/**
 *       \file       htdocs/public/webservices/cap_export_client.php
 *       \brief      Client to make a client call to Meteoalarm WebServices "putCap"
 */

//error_reporting(E_ALL | E_STRICT);
require_once 'nusoap/lib/nusoap.php';		// Include SOAP
Include 'source/conf/conf.php';

$filename = $_POST[filename];
if($_POST['import']==1) $import = true; else $import = false;
if($_POST['debug']==1) $debug = true; else $debug = false;
if($import == "") $import = false;

if ($_POST[filename])
{
	// Set the WebService URL
	$soapclient = new nusoap_client($WS_DOL_URL); // <-- set the Timeout above 300 Sec.
	if ($soapclient)
	{
		$soapclient->soap_defencoding='UTF-8';
		$soapclient->decodeUTF8(false);
	}
	
	// Call the WebService method and store its result in $result.
	$authentication=array(
	    'dolibarrkey'=>$dolibarrkey,
	    'sourceapplication'=>$WS_METHOD,
	  	'login'=> $login,
  	  'password'=> $password);
 
  $tmpfile = $_POST[destination].'/'.$_POST[filename];
	$handle = fopen($tmpfile, "r");                  // Open the temp file
	$contents = fread($handle, filesize($tmpfile));  // Read the temp file
	fclose($handle);                                 // Close the temp file

   	//$contents = preg_replace('/[\x00-\x1f]/', ' ', $contents);
	$document=array(
	    'filename'=> $_POST[filename],
	    'mimetype'=> 'text/xml',
	    'content'=> $contents,						//$_FILES["uploadfile"]["tmp_name"]
	    'length'=> filesize($tmpfile),
		'warning_import'=>$import,
		'debug_msg'=>$debug);
	    	
	
		$parameters = array('authentication'=>$authentication, 'document'=>$document);
		
		$WS_METHOD="putCap";
		$result = $soapclient->call($WS_METHOD,$parameters,$ns,'');
		if ($soapclient->fault) {
	    echo '<h2>Fault</h2><pre>';
	    print_r($result);
	    echo '</pre>';
	} else {
	    // Check for errors
	    $err = $soapclient->getError();
	    if ($err) {
	        // Display the error
	        echo '<h2>Error</h2><pre>' . $err . '</pre>';
	    } else {
	        // write file to tmp Directory
	        // file_put_contents('tmp/'.$result["document"]["filename"], $result["document"]["content"]);
	        // Display the result
	    }
	}
	if($result["syntaxcheck"]["capformat_error"]==1) $format_error = "YES"; else $format_error = "NO";
	if($result["syntaxcheck"]["capvalue_error"]==1) $value_error = "YES"; else $value_error = "NO";
	if($result["syntaxcheck"]["cnt_errors"]>0) $error_bool = '<p>CAP format Error:'.$format_error.'<p>CAP value Error:'.$value_error.'<p>Cnt Errors:'.$result["syntaxcheck"]["cnt_errors"].'<p>';
	
	// Display the request and response
	print '<h2>Response</h2>';
	print '<h3>Message:</h3>';
	print '<pre>'.$error_bool.$result["syntaxcheck"]["error_log"].$result["syntaxcheck"]["debug_msg"].'</pre>';
	// print_r($soapclient->response);
	print '<h3>response:</h3>';
	print '<pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
	print '<h2>Request</h2>';
	print '<pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';	
	// Display the debug messages
	print '<h2>Debug</h2>';
	print '<pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';

}
else
{
	print '<form action="'.$_SERVER["PHP_SELF"].'" method="post" enctype="multipart/form-data" id="formcontent">';
	
	print '<input type="text" name="url" value="" placeholder="WebService URL">';
	print '<input type="text" name="Login" value="" placeholder="Login">';
	print '<input type="password" name="Pass" value="" placeholder="Passwort">';
	
	print '<input type="hidden" name="send" value="1" /><br>';
	print 'Import <input type="checkbox" name="import" value="1" />? <br>';
	print 'Debug Text <input type="checkbox" name="debug" value="1" />? <p>';
	print '<input type="submit" name="submit" value="Submit">';		
	print '<input type="hidden" name="filename" value="'.$cap->identifier.'.cap">';
	print '<input type="hidden" name="destination" value="'.$cap->destination.'">';
	
	print '</form>';
}				

?>
