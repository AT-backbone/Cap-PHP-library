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
		
		function convert_all_cap_ftp()
		{
			$files2 = scandir('ftp_zip', 1);
			foreach($files2 as $file)
			{
				if($file != "." && $file != "..")
				{
					$this->debug.= '<br>To convert: '.$file ;
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