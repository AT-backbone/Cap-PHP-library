<?php 
/*
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
 *	\file      	index.php
 *  \ingroup   	main
 */
 
/**
 * Front end of the Cap-php-library
 */
	ini_set('error_reporting', E_STRICT);
	require_once 'class/cap.form.class.php';
	require_once 'lib/cap.create.class.php';
	require_once 'lib/cap.write.class.php';
	require_once 'lib/cap.convert.class.php';
	require_once 'class/translate.class.php';
	
	if(file_exists('conf/conf.php'))
	{
		include 'conf/conf.php';
	
		$langs = new Translate();		
		$langs->setDefaultLang($conf->user->lang);		
		$langs->load("main");	
	}
	
	if(!file_exists('conf/conf.php'))
	{
		$cap = new CAP_Form();			
		print $cap->install();
	}
	elseif($_GET['conv'] == 1)
	{
		if(! empty($_POST['location']) || ! empty($_FILES["uploadfile"]["name"]))
		{
			require_once 'lib/cap.read.class.php';
			// Get TEST Cap
			if(! empty($_FILES["uploadfile"]["name"]))
			{
				$location = $_FILES["uploadfile"]["tmp_name"];
			}
			else
			{
				$location = $conf->cap->output.'/'.urldecode($_POST['location']);
			}
			
			$alert = new alert($location);
			$cap = $alert->output();
			
			// Convert
			$converter = new Convert_CAP_Class();		
			$capconvertet = $converter->convert($cap, $_POST['inputconverter'], $_POST['outputconverter']);
			
			$form = new CAP_Form();
			print $form->CapView($capconvertet, $cap[identifier]); // Cap Preview +
		}
		else
		{
			$form = new CAP_Form();
			print $form->ListCap();
		}
	}
	elseif($_GET['read'] == 1)
	{
		require_once 'lib/cap.read.class.php';
		
		$location = "source/cap/".$_POST['location'];
		$alert = new alert($location);
		$cap = $alert->output();

		if(! empty($cap['msg_format']))
		{
			print $cap['msg_format'];
			exit;
		}
		
			$form = new CAP_Form($cap);

			print $form->Form();
	}
	elseif(empty($_POST['action']) && $_GET['webservice'] != 1)
	{
		// Build Cap Creator form
		
			$form = new CAP_Form();

			print $form->Form();
	}
	elseif($_POST['action'] == "create" && $_GET['conf'] != 1)
	{
		$form = new CAP_Form();
		$_POST = $form->MakeIdentifier($_POST);
		
		$cap = new CAP_Class($_POST);
		
		if(!empty($_GET['cap']))
		{
			// Used for the Cap preview
			$cap->buildCap();
			print $cap->cap;
		}
		else
		{
			// Used to build the cap and save it at $cap->destination
			$cap->buildCap();
			$cap->destination = $conf->cap->output;
			if($conf->cap->save == 1)	$path = $cap->createFile();
			
			$conf->identifier->ID_ID++;
			$form->WriteConf();
			
			print $form->CapView($cap->cap, $_POST[identifier]); // Cap Preview +
		}
	}
	elseif($_GET['webservice'] == 1)
	{
		// start webservices
			$form = new CAP_Form();

			print $form->Webservice($_POST[filename]);
	}
	elseif($_GET['conf'] == "1")
	{
		$form = new CAP_Form();		
		$form->PostToConf($_POST['conf']);		
		$form->WriteConf();
		return true;
	}
	
?>