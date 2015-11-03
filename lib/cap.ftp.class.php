<?php
/*
 *  Copyright (c) 2015  Guido Schratzer   <guido.schratzer@backbone.co.at>
 *  Copyright (c) 2015  Niklas Spanring   <n.spanring@backbone.co.at>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file      	cap.convert.class.php
 *  \ingroup   	build
 *	\brief      File of class with CAP 1.2 builder
 *	\standards  from http://docs.oasis-open.org/emergency/cap/v1.2/CAP-v1.2-os.html
 *
 */
	
	require_once 'cap.write.class.php'; // for the XML / CAP view
	require_once 'log.lib.php';

	class FTP_CAP_CLASS{
		
		var $conn_id = 0;
		var $debug = "<p>FTP: <p>"; // $this->debug = "
		var $files = array();
		/**
     * initialize Class with Data
     *
     * @return	None
     */
		function __construct()
		{
			
		}
		
		function cap_ftp_open_conection($ftp_server, $ftp_user, $ftp_pass)
		{
			global $conf, $lang;
			
			// Verbindung aufbauen
			$this->conn_id = ftp_connect($ftp_server) or $this->debug = "Couldn't connect to ".$ftp_server;
			
			// Anmeldung versuchen
			if (@ftp_login($this->conn_id, $ftp_user, $ftp_pass)) {
			    $this->debug.= "<p>Angemeldet als ".$ftp_user."@".$ftp_server;
			} else {
			    $this->debug.= "<p>Anmeldung als ".$ftp_user." nicht möglich";
			}
			
		}
		
		function cap_ftp_dir($dir)
		{
				$this->debug.= "<p>Change dir from: ".ftp_pwd($this->conn_id);
				ftp_chdir($this->conn_id, $dir);
				$this->debug.= "<p>To: ".ftp_pwd($this->conn_id);
		}
		
		function get_cap_ftp_content()
		{
			$time_start = microtime(true);
			$this->debug.= "<p>Read all files form: ".ftp_pwd($this->conn_id);
			$this->files = ftp_nlist($this->conn_id, ".");
			
			foreach($this->files as $key => $filename)
			{
				if(!file_exists("ftp_tmp/".$filename))
				{
					$this->debug.= "<br>".$key." => ".$filename;
					
					$filesize = ftp_size($this->conn_id, $filename);
					
					//echo $filename.' - '.$filesize.' - '.date("Y-m-d H:i:s").'<br>';
	
					$handle = "ftp_tmp/".$filename;
					
					$time_end = microtime(true);
					$time = $time_end - $time_start;
					
					echo "Start --- ".$filename." size: ".$filesize." time:".$time."<p>";
					flush();
					ob_flush();
					
					$res = $this->cap_ftp_download($handle, $filename);
					
					//sleep(1);
					
					echo "Fertig -- ".$filename." === ".$res."<p>";
					flush();
					ob_flush();
					
					if($res)
					{
						$this->debug.= "Finshd file: ".$handle;
					}
					else
					{
						$this->debug.= "Can't save file: ".$handle;
					}
				}
			}			
		}
		
		function cap_ftp_download($handle, $filename)
		{
			$res = ftp_get($this->conn_id, $handle, $filename, FTP_BINARY, 0);
			return $res;
		}
		
		function cap_zip_extract()
		{
			$files2 = scandir('ftp_tmp', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != "..")
				{
					$this->debug.= $file."<p>";
					$zip = new ZipArchive;
					if ($zip->open("ftp_tmp/".$file) === TRUE) {
					    $zip->extractTo('ftp_zip/');
					    $zip->close();
					    $this->debug.= ' ok<br>';
					} else {
					    $this->debug.= ' Fehler<br>';
					}
				}
			}
		}
		
		function convert_all_cap_ftp($input, $output)
		{
			global $conf;
			require_once 'lib/cap.convert.class.php';
			require_once 'lib/cap.read.class.php';
			
			error_reporting(E_ERROR);
			set_time_limit ( 1500 );
			$conf->disable_log = 1;
			
			$files2 = scandir('ftp_zip', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != "..")
				{
					// read the cap
					$alert = new alert("ftp_zip/".$file);
					$cap = $alert->output();
						
					if(!file_exists("ftp_convert/".$cap['identifier'].".conv.cap") && !file_exists("ftp_convert/".$cap['identifier'].".conv.cap.prc") && (strtotime($cap['info'][0]['expires']) > strtotime('now')))
					{
						echo "Start --- from: ".$file." to: "."ftp_convert/".$cap['identifier'].".conv.cap<p>";
						flush();
						ob_flush();
						
						$converter = new Convert_CAP_Class();						
						$capconvertet = $converter->convert($cap, 'test',	'test', $input, $output, 'ftp_convert/');
				
						$this->debug.= '<br>To convert: '.$file." id: ".$cap['identifier'];
						unset($converter, $cap, $alert);				
					}
					else
					{
						$this->debug.= '<br>Alredy converted: '.$file." id: ".$cap['identifier'];
					}
				}
			}
		}
		
		function send_cap_ftp_soap()
		{
			global $conf;
			
			error_reporting(E_ERROR);
			
			$files2 = scandir('ftp_convert', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != ".." && substr ($file, -4) != ".prc")
				{
					$conf->cap->output = "ftp_convert";
					$_POST[filename] = $file;
					
					$conf->webservice->password = $this->encrypt_decrypt(2, $conf->webservice->password);
					
															
										require_once 'includes/nusoap/lib/nusoap.php';		// Include SOAP
										
										$filename = $_POST[filename];
										if($_POST['import']==1) $import = true; else $import = false;
										// if($_POST['debug']==1) $debug = true; else $debug = false;
										$debug = true;
										if($import == "") $import = true;
										
										if ($_POST[filename])
										{
											// Set the WebService URL
											$soapclient = new nusoap_client($conf->webservice->WS_DOL_URL); // <-- set the Timeout above 300 Sec.
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
										 
										  $tmpfile = $conf->cap->output.'/'.$_POST[filename];
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
										
										}
					echo '<h1> File:'.$file.'</h1>'.$out;
					flush();
					ob_flush();
					unset($out);
					
					if($result["syntaxcheck"]["capformat_error"]==1 || $result["syntaxcheck"]["capvalue_error"] == 1 || $result["syntaxcheck"]["cnt_errors"] > 0)
					{
						rename ("ftp_convert/".$file, "ftp_convert/".$file.".err");
					}
					else
					{
						rename ("ftp_convert/".$file, "ftp_convert/".$file.".prc");
					}
					
					$conf->webservice->password = $this->encrypt_decrypt(1, $conf->webservice->password);
					
				}
			}
		}
		
		
		/**
     * encrypt and decrypt function for passwords
     *     
     * @return	string
     */
		function encrypt_decrypt($action, $string, $key) 
		{
			global $conf;
			
			$output = false;
		
			$encrypt_method = "AES-256-CBC";
			$secret_key = ($key?$key:'NjZvdDZtQ3ZSdVVUMXFMdnBnWGt2Zz09');
			$secret_iv = ($conf->webservice->securitykey ? $conf->webservice->securitykey : 'WebTagServices#hash');
		
			// hash
			$key = hash('sha256', $secret_key);
			
			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);
		
			if( $action == 1 ) {
				$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
				$output = base64_encode($output);
			}
			else if( $action == 2 ){
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
		
			return $output;
		}
		
		function delete_all_cap_ftp()
		{
			$files2 = scandir('ftp_tmp', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != "..")
				{
					unlink('ftp_tmp/'.$file);
				}
			}
			$files2 = scandir('ftp_zip', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != "..")
				{
					unlink('ftp_zip/'.$file);
				}
			}
			$files2 = scandir('ftp_convert', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != "..")
				{
					unlink('ftp_convert/'.$file);
				}
			}
		}
		
		function cap_ftp_close_conection()
		{
			// Verbindung schließen
			ftp_close($this->conn_id);
			$this->debug.= "<p>FTP Conection Closed";
		}
	}
?>