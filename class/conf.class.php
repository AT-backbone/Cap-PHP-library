<?php
	Class Configuration{

			var $conf;
			var $path;

			function __construct($path){
				$this->path = $path;
				$this->conf = parse_ini_file($path, true); // config.ini
			}

			// key name value
			function set($k, $n, $v){
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
			    $res = array();
			    foreach($this->conf as $key => $val)
			    {
			        if(is_array($val))
			        {
			            $res[] = "[$key]";
			            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			        }
			        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
			    }
			   	$this->safefilerewrite($this->path, implode("\r\n", $res));
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
	}
?>
