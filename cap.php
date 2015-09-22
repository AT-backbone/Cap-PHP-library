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
 *	\file      	cap.php
 *  \ingroup   	main
 */
 
/**
 * Front end of the Cap-php-library
 */
 
	require_once 'class/cap.form.class.php';
	require_once 'class/cap.create.class.php';
	require_once 'class/cap.write.class.php';


	if(! empty($_POST['type']))
	{
		// Used to load new eventCode Parameter or geocode
		$cap = new CAP_Form();
		print '<tr><td>'.$_POST['type'].': </td><td>'.$cap->InputStandard($_POST['type']).'</td></tr>';
	}
	elseif(empty($_POST['action']))
	{
		// Used to build the entire Form
		$cap = new CAP_Form();
		print $cap->Form();
	}
	elseif($_POST['action'] == "create")
	{
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
			$path = $cap->createFile();
			
			// show the path to the file as link
			print '<a href="'.$path.'">'.$cap->identifier.'.cap</a>';
		}
	}
	
?>