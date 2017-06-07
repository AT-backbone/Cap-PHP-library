<?php
	Class Configuration{

			var $conf;
			var $path;

			function __construct($path){
				$this->path = $path;
				$this->conf = parse_ini_file($path, true); // config.ini
				$this->setExtra();

				### Check the version of the conf file
				if(file_exists(dirname($path)."/standard.conf.ini")){
					$std_conf = parse_ini_file( dirname($path)."/standard.conf.ini", true); // config.ini find las
					if(empty($this->conf["ConfVersion"]) || $this->conf["ConfVersion"]["ver"] < $std_conf["ConfVersion"]["ver"]){
						$this->conf = array_merge($std_conf, $this->conf);
						$this->conf["ConfVersion"]["ver"] = $std_conf["ConfVersion"]["ver"];
						$this->write_php_ini();
					}
					unset($std_conf);
				}
			}

			// key name value
			function setValue($k, $n, $v){
				if(!empty($n)){
					$this->conf[$k][$n] = $v;
				} else {
					$this->conf[$k] = $v;
				}
				$this->write_php_ini();
				return true;
				//$this->out .= "$k=\"$v\"" . PHP_EOL;
			}

			function get($k, $n){
				if(!empty($n)){
					return $this->conf[$k][$n];
				} else {
					return $this->conf[$k];
				}
			}

			function write_php_ini()
			{
				$this->unsetExtra(); // encrypting
			    $res = array();
			    foreach($this->conf as $key => $val)
			    {
			        if(is_array($val))
			        {
			            $res[] = "\n[$key]";
			            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			        }
			        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
			    }
			   	$this->safefilerewrite($this->path, implode("\r\n", $res));
			   	$this->setExtra();
			}

			function safefilerewrite($fileName, $dataToSave)
			{
					if ($fp = fopen($fileName, 'w'))
			    {
			        $startTime = microtime(TRUE);
			        do
			        {            $canWrite = flock($fp, LOCK_EX);
			           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			           if(!$canWrite) usleep(round(rand(0, 100)*1000));
			        } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

			        //file was locked so now we can store information
			        if ($canWrite)
			        {            fwrite($fp, $dataToSave);
			            flock($fp, LOCK_UN);
			        }
			        fclose($fp);
			    }

			}

			function setExtra(){
				if($this->conf["proxy"]["proxyOn"] == 1){
					$this->conf["proxy"]["proxyUserPass"] = $this->encrypt_decrypt(2, $this->conf["proxy"]["proxyUserPass"]);
				}else{
					$this->conf["proxy"]["proxyIP"] = false;
					$this->conf["proxy"]["proxyPort"] = false;
					$this->conf["proxy"]["proxyUserName"] = false;
					$this->conf["proxy"]["proxyUserPass"] = false;
				}
			}

			function unsetExtra(){
				if($this->conf["proxy"]["proxyOn"] == 1){
					$this->conf["proxy"]["proxyUserPass"] = $this->encrypt_decrypt(1, $this->conf["proxy"]["proxyUserPass"]);
				}
			}

			/**
			* encrypt and decrypt function for passwords
			*
			* @return	string
			*/
			function encrypt_decrypt($action, $string, $key = "")
			{
				$output = false;

				$encrypt_method = "AES-256-CBC";
				$secret_key = ($key?$key:'NjZvdDZtQ3ZSdVVUMXFMdnBnWGt2Zz09');

				$secret_iv = ($this->conf["webservice"]["securitykey"] ? $this->conf["webservice"]["securitykey"] : 'WebTagServices#hash');

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
	}
?>
