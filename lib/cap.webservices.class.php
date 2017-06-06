<?php

ini_set("default_socket_timeout", 60000);

Class MeteoalarmWebservice{

	var $soapclient;
	var $parameters;

	function __construct($WS_URL){
		
		$res=0;
		if (! $res && file_exists('includes/nusoap/lib/nusoap.php')) $res=require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP
		if (! $res && file_exists('../includes/nusoap/lib/nusoap.php')) $res=require_once '../includes/nusoap/lib/nusoap.php';		// Include SOAP
		if (! $res && file_exists('../../includes/nusoap/lib/nusoap.php')) $res=require_once '../../includes/nusoap/lib/nusoap.php';		// Include SOAP
		if (! $res && file_exists('../../../includes/nusoap/lib/nusoap.php')) $res=require_once '../../../includes/nusoap/lib/nusoap.php';		// Include SOAP
		if (! $res && file_exists('nusoap/lib/nusoap.php')) $res=require_once 'nusoap/lib/nusoap.php';		// Include SOAP
		if (! $res && file_exists('lib/nusoap.php')) $res=require_once 'lib/nusoap.php';		// Include SOAP
		if (! $res && file_exists('nusoap.php')) $res=require_once 'nusoap.php';		// Include SOAP
		if(!$res) die("Error: nusoap.php not found!");
		// Set the WebService URL

		$this->soapclient = new nusoap_client($WS_URL, '' , $configuration->conf["proxy"]["proxyIP"], $configuration->conf["proxy"]["proxyPort"], $configuration->conf["proxy"]["proxyUserName"], $configuration->conf["proxy"]["proxyUserPass"], 0, 300); // <-- set the Timeout above 300 Sec.
		if ($this->soapclient)
		{
			$this->soapclient->soap_defencoding='UTF-8';
			$this->soapclient->decodeUTF8(false);
		}
	}

	// key name value
	function setParameterArray($securitykey, $WS_METHOD, $login, $password, $filename, $contents, $import= false, $debug= true){
		// Call the WebService method and store its result in $result.
		$authentication=array(
			'dolibarrkey'=> $securitykey,
			'sourceapplication'=> $WS_METHOD,
			'login'=> $login,
			'password'=> $password
		);

		$document=array(
			'filename'=> $filename,
			'mimetype'=> 'text/xml',
			'content'=> $contents,
			'length'=> strlen($contents),
			'warning_import'=>$import,
			'debug_msg'=>$debug
		);

		$this->parameters = array('authentication'=>$authentication, 'document'=>$document);
	}

	function sendSoapCall($WS_METHOD, $ns){
		$result = $this->soapclient->call($WS_METHOD,$this->parameters,$ns,'');

		$out = "";
		if ($this->soapclient->fault) {
		    $out.= '<h2>Fault</h2><pre>';
		    $out.=var_dump($result);
		    $out.= '</pre>';
		} else {
		    // Check for errors
		    $err = $this->soapclient->getError();
		    if ($err) {
		        // Display the error
		        $out.= '<h2>Error</h2><pre>' . $err . '</pre>';
		    }
		}
		
		if(!empty($result["syntaxcheck"])) if($result["syntaxcheck"]["capformat_error"]==1) $format_error = "YES"; else $format_error = "NO";
		if(!empty($result["syntaxcheck"])) if($result["syntaxcheck"]["capvalue_error"]==1) $value_error = "YES"; else $value_error = "NO";
		if(!empty($result["syntaxcheck"])) if($result["syntaxcheck"]["cnt_errors"]>0) $error_bool = '<p>CAP format Error:'.$format_error.'<p>CAP value Error:'.$value_error.'<p>Cnt Errors:'.$result["syntaxcheck"]["cnt_errors"].'<p>';

		// Display the request and response
		$out.= '<h2>Response</h2>';
		$out.= '<h3>Message:</h3>';
		if(!empty($result["result"]["result_label"])) $out.= '<pre>'.$result["result"]["result_label"].'</pre>';
		if(!empty($result["result_label"])) $out.= '<pre>'.$result["result_label"].'</pre>';
		if(!empty($error_bool)) $out.= '<pre>'.$error_bool.'</pre>';
		if(!empty($result["syntaxcheck"]["error_log"]) || !empty($result["syntaxcheck"]["debug_msg"])) $out.= '<pre>'.$result["syntaxcheck"]["error_log"].$result["syntaxcheck"]["debug_msg"].'</pre>';
		// print_r($soapclient->response);
		$out.= '<h3>response:</h3>';
		$out.= '<pre>' . htmlspecialchars($this->soapclient->response, ENT_QUOTES) . '</pre>';
		$out.= '<h2>Request</h2>';
		$out.= '<pre>' . htmlspecialchars($this->soapclient->request, ENT_QUOTES) . '</pre>';
		// Display the debug messages
		$out.= '<h2>Debug</h2>';
		$out.= '<pre>' . htmlspecialchars($this->soapclient->debug_str, ENT_QUOTES) . '</pre>';

		return $out;
	}
}

?>