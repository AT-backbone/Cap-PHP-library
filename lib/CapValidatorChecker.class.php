<?php

	Class CapChecker{

		var $vali_url;
		var $cap_contend;
		var $html_response;
		var $profile = "";

		function __construct($validate_url = "http://validator.meteoalarm.eu/validate"){
			$this->vali_url = $validate_url;
		}

		function validate_CAP($cap_path){
			$this->cap_contend = file_get_contents($cap_path);

			$info = $this->validate();

			return $info;
		}

		function validate_URL($cap_url){
			$this->cap_contend = $cap_url;

			$info = $this->validate();

			return $info;
		}

		function validate(){
			$headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
	    $postfields = array("action" => "/validate#r", "input" => $this->cap_contend, "inputfile/" => "", "profile" => $this->profile, "example" => "");
	    $ch = curl_init();
	    $options = array(
	        CURLOPT_URL => $this->vali_url,
	        CURLOPT_HEADER => true,
	        CURLOPT_POST => 1,
	        CURLOPT_HTTPHEADER => $headers,
	        CURLOPT_POSTFIELDS => $postfields,
	        CURLOPT_INFILESIZE => 0,
	        CURLOPT_RETURNTRANSFER => true
	    ); // cURL options
	    curl_setopt_array($ch, $options);
	    $html = curl_exec($ch);
	    if(!curl_errno($ch))
	    {
	        $info = curl_getinfo($ch);
	        if ($info['http_code'] == 200)
	            $errmsg = "File uploaded successfully";
	    }
	    else
	    {
	        $info = curl_error($ch);
	    }
	    curl_close($ch);

			$info['nohtml'] = strip_tags($html);

			$info['html'] = $html;

			return $info;
		}

		function removeBody($html){
			$split = explode('<h4>Validation messages</h4>',$html);
			$split = explode('<!--<div class=footer>',$split[1]);
			return '<div class="container"><h4>Validation messages</h4>'.$split[0]."<br>";
		}
	}

?>
