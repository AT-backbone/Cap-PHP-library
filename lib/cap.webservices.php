<?php
/**
 *       \file       htdocs/public/webservices/cap_export_client.php
 *       \brief      Client to make a client call to Meteoalarm WebServices "putCap"
 */

global $out;

ini_set("default_socket_timeout", 60000);
require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP

$filename = $_POST[filename];
if($_POST['import']==1) $import = true; else $import = false;
// if($_POST['debug']==1) $debug = true; else $debug = false;
$debug = true;
if($import == "") $import = true;

if ($_POST[filename])
{
	// Set the WebService URL
	$soapclient = new nusoap_client($conf->webservice->WS_DOL_URL, '' , false, false, false, false, 0, 300); // <-- set the Timeout above 300 Sec.
	if ($soapclient)
	{
		$soapclient->soap_defencoding='UTF-8';
		$soapclient->decodeUTF8(false);
	}
	
	// Call the WebService method and store its result in $result.
	$authentication=array(
	    'dolibarrkey'=>$conf->webservice->securitykey,
	    'sourceapplication'=>$conf->webservice->WS_METHOD,
	  	'login'=> $conf->webservice->login,
  	  'password'=> $conf->webservice->password);
 
  $tmpfile = $conf->cap->output.'/'.$_POST[filename].'.cap';
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
		
		$result = $soapclient->call($conf->webservice->WS_METHOD,$parameters,$conf->webservice->ns,'');
		if ($soapclient->fault) {
	    $out.= '<h2>Fault</h2><pre>';
	    $out.=var_dump($result);
	    $out.= '</pre>';
	} else {
	    // Check for errors
	    $err = $soapclient->getError();
	    if ($err) {
	        // Display the error
	        $out.= '<h2>Error</h2><pre>' . $err . '</pre>';
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
	$out.= '<h2>Response</h2>';
	$out.= '<h3>Message:</h3>';
	$out.= '<pre>'.$result["result"]["result_label"].'</pre>';
	$out.= '<pre>'.$result["result_label"].'</pre>';
	$out.= '<pre>'.$error_bool.$result["syntaxcheck"]["error_log"].$result["syntaxcheck"]["debug_msg"].'</pre>';
	// print_r($soapclient->response);
	$out.= '<h3>response:</h3>';
	$out.= '<pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
	$out.= '<h2>Request</h2>';
	$out.= '<pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';	
	// Display the debug messages
	$out.= '<h2>Debug</h2>';
	$out.= '<pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';

}
else
{
	$out.= '<form action="'.$_SERVER["PHP_SELF"].'" method="post" enctype="multipart/form-data" id="formcontent">';
	
	$out.= '<input type="text" name="url" value="" placeholder="WebService URL">';
	$out.= '<input type="text" name="Login" value="" placeholder="Login">';
	$out.= '<input type="password" name="Pass" value="" placeholder="Passwort">';
	
	$out.= '<input type="hidden" name="send" value="1" /><br>';
	$out.= 'Import <input type="checkbox" name="import" value="1" />? <br>';
	$out.= 'Debug Text <input type="checkbox" name="debug" value="1" />? <p>';
	$out.= '<input type="submit" name="submit" value="Submit">';		
	$out.= '<input type="hidden" name="filename" value="'.$cap->identifier.'.cap">';
	$out.= '<input type="hidden" name="destination" value="'.$cap->destination.'">';
	
	$out.= '</form>';
}				

?>
