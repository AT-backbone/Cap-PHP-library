<?php
/* Copyright (C) 2000-2007 	Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      	Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2013 	Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      	Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      	Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2004      	Christophe Combelles <ccomb@free.fr>
 * Copyright (C) 2005-2012	Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2008      	Raphael Bertrand (Resultic)       <raphael.bertrand@resultic.fr>
 * Copyright (C) 2010-2014 	Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2013      	Cédric Salvador      <csalvador@gpcsolutions.fr>
 * Copyright (C) 2013      	Alexandre Spangaro   <aspangaro.dolibarr@gmail.com>
 * Copyright (C) 2014     	Cédric GROSS         <c.gross@kreiz-it.fr>
 * Copyright (C) 2014-2015 	Marcos García        <marcosgdf@gmail.com>
 * Copyright (C) 2015       Jean-François Ferry	 <jfefe@aternatik.fr>
 * Copyright (c) 2015  			Guido Schratzer   	 <guido.schratzer@backbone.co.at>
 * Copyright (c) 2015 			 Niklas Spanring   	 <n.spanring@backbone.co.at>
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
 * or see http://www.gnu.org/
 */

/**
 *	\file			htdocs/core/lib/functions.lib.php
 *	\brief			A set of functions for Dolibarr
 *					This file contains all frequently used functions.
 */


/**
 *  Write log message into outputs. Possible outputs can be:
 *  SYSLOG_HANDLERS = ["mod_syslog_file"]       file name is then defined by SYSLOG_FILE
 *  SYSLOG_HANDLERS = ["mod_syslog_syslog"]     facility is then defined by SYSLOG_FACILITY
 *  Warning, syslog functions are bugged on Windows, generating memory protection faults. To solve
 *  this, use logging to files instead of syslog (see setup of module).
 *  Note: If SYSLOG_FILE_NO_ERROR defined, we never output any error message when writing to log fails.
 *  Note: You can get log message into html sources by adding parameter &logtohtml=1 (constant MAIN_LOGTOHTML must be set)
 *  This function works only if syslog module is enabled.
 *  This must not use any call to other function calling dol_syslog (avoid infinite loop).
 *
 *  @param  string      $message                Line to log.
 *  @param  int         $level                  Log level
 *                                              0=Show nothing
 *                                              On Windows LOG_ERR=4, LOG_WARNING=5, LOG_NOTICE=LOG_INFO=6, LOG_DEBUG=6 si define_syslog_variables ou PHP 5.3+, 7 si dolibarr
 *                                              On Linux   LOG_ERR=3, LOG_WARNING=4, LOG_INFO=6, LOG_DEBUG=7
 *  @param  int         $ident                  1=Increase ident of 1, -1=Decrease ident of 1
 *  @param  string      $suffixinfilename       When output is a file, append this suffix into default log filename.
 *  @param  string      $restricttologhandler   Output log only for this log handler
 *  @return void
 */
function cap_syslog($message = '', $level = LOG_INFO, $name='CAP_Creator', $ident = 0, $suffixinfilename='', $restricttologhandler='')
{
    global $conf;

    // If syslog module enabled
    //if (empty($conf->syslog->enabled)) return;

    if (! empty($level) && empty($conf->disable_log))
    {
        // Test log level
        $logLevels = array( LOG_EMERG, LOG_ALERT, LOG_CRIT, LOG_ERR, LOG_WARNING, LOG_NOTICE, LOG_INFO, LOG_DEBUG);
        
        if (!in_array($level, $logLevels))
        {
            throw new Exception('Incorrect log level');
        }

        //if ($level > $conf->SYSLOG_LEVEL) return;

        // If adding log inside HTML page is required
        if (! empty($_REQUEST['logtohtml']) && ! empty($conf->MAIN_LOGTOHTML))
        {
            $conf->logbuffer[] = dol_print_date(time(),"%Y-%m-%d %H:%M:%S")." ".$message;
        }

        //TODO: Remove this. MAIN_ENABLE_LOG_HTML should be deprecated and use a log handler dedicated to HTML output
        // If enable html log tag enabled and url parameter log defined, we show output log on HTML comments
        if (! empty($conf->MAIN_ENABLE_LOG_HTML) && ! empty($_GET["log"]))
        {
            print "\n\n<!-- Log start\n";
            print $message."\n";
            print "Log end -->\n";
        }

				if (is_array($message))
				{
					$tmp = $message[0];
					$tmp1 = $message[1];
					$tmp2 = $message[2];
					$tmp3 = $message[3];
					$tmp4 = $message[4];
					
					if($tmp)
					{
						while(strlen($tmp) < 15)
						{
							$tmp.= ' ';
						}
					}
					if($tmp1)
					{
						while(strlen($tmp1) < 30)
						{
							$tmp1.= ' ';
						}
						$tmp1 = '| '.$tmp1;
					}
					if($tmp2)
					{
						while(strlen($tmp2) < 30)
						{
							$tmp2.= ' ';
						}
						$tmp2 = '| '.$tmp2;
					}
					if($tmp3)
					{
						while(strlen($tmp3) < 30)
						{
							$tmp3.= ' ';
						}
						$tmp3 = '| '.$tmp3;
					}
					if($tmp4)
					{
						while(strlen($tmp4) < 30)
						{
							$tmp4.= ' ';
						}
						$tmp4 = '| '.$tmp4;
					}
					unset($message);
					$message = $tmp.$tmp1.$tmp2.$tmp3.$tmp4;
					
					
					
				}

        $data = array(
            'message' => $message,
            'script' => (isset($_SERVER['PHP_SELF'])? basename($_SERVER['PHP_SELF'],'.php') : false),
            'level' => $level,
            'ip' => false
        );

        if (! empty($_SERVER["REMOTE_ADDR"])) $data['ip'] = $_SERVER['REMOTE_ADDR'];
        // This is when PHP session is ran inside a web server but not inside a client request (example: init code of apache)
        else if (! empty($_SERVER['SERVER_ADDR'])) $data['ip'] = $_SERVER['SERVER_ADDR'];
        // This is when PHP session is ran outside a web server, like from Windows command line (Not always defined, but useful if OS defined it).
        else if (! empty($_SERVER['COMPUTERNAME'])) $data['ip'] = $_SERVER['COMPUTERNAME'].(empty($_SERVER['USERNAME'])?'':'@'.$_SERVER['USERNAME']);
        // This is when PHP session is ran outside a web server, like from Linux command line (Not always defined, but usefull if OS defined it).
        else if (! empty($_SERVER['LOGNAME'])) $data['ip'] = '???@'.$_SERVER['LOGNAME'];
        // Loop on each log handler and send output

        $facility = LOG_USER;

        // (int) is required to avoid error parameter 3 expected to be long
        openlog($name, LOG_PID | LOG_PERROR, (int) $facility);
        syslog($data['level'], '['.$data['level'].']: '.$data['message']);
        closelog();

        unset($data);
    }
}