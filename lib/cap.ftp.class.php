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
		
		/**
     * initialize Class with Data
     *
     * @return	None
     */
		function __construct()
		{
			
		}
		
		function cap_ftp_login($ftp_server, $ftp_user, $ftp_pass)
		{
			global $conf, $lang;
			$ftp_server = "ftp-outgoing2.dwd.de"; // /gds/specials/alerts/cap/GER/status/
			$ftp_user = "gds18541";
			$ftp_pass = "olvfEVIY";
			
			// Verbindung aufbauen
			$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to ".$ftp_server);
			
			// Anmeldung versuchen
			if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
			    echo "Angemeldet als ".$ftp_user."@".$ftp_server."\n";
			} else {
			    echo "Anmeldung als ".$ftp_user." nicht möglich\n";
			}
			
			// Verbindung schließen
			ftp_close($conn_id);
		}
	}
?>