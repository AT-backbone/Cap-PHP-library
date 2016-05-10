<?php
/* Copyright (C) 2001      Eric Seigne         <erics@rycks.com>
 * Copyright (C) 2004-2012 Destailleur Laurent <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin       <regis.houssin@capnetworks.com>
 * Copyright (C) 2014-2015 Guido Schratzer     <schratzerg@backbone.co.at>
 * Copyright (C) 2014-2015 Niklas Spanring     <n.spanring@backbone.co.at>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
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
 *   	\file       /class/translate.class.php
 *    \ingroup    core
 *		\brief      File for Tanslate class
 */


/**
 *		Class to manage translations
 */
class Translate
{
	var $dir;							// Directories that contains /langs subdirectory

	var $defaultlang="auto";					// Current language for current user
	var $direction = 'ltr';				// Left to right or Right to left
	var $charset_output='UTF-8';		// Codage used by "trans" method outputs

	var $tab_translate=array();		// Array of all translations key=>value
	private $_tab_loaded=array();		// Array to store result after loading each language file
	//private $conf=array();		// Array to store result after loading each language file
	var $dol_document_root = "./";


	/**
	 *	Constructor
	 *
	 *  @param	string	$dir            Force directory that contains /langs subdirectory (value is sometine '..' like into install/* pages or support/* pages).
	 *  @param  Conf	$conf			Object with Dolibarr configuration
	 */
	function __construct($dir="")
	{
		if ($dir) $this->dir=array($dir);
		else $this->dir[]="./";
	}


	/**
	 *  Set accessor for this->defaultlang
	 *
	 *  @param	string	$srclang     	Language to use. If '' or 'auto', we use browser lang.
	 *  @return	void
	 */
	function setDefaultLang($srclang='en_US')
	{
		global $conf;

		
		// If a module ask to force a priority on langs directories (to use its own lang files)
		if (! empty($conf->global->MAIN_FORCELANGDIR))
		{
			$more=array();
			$i=0;
			foreach($dol_document_root as $dir)
			{
				$newdir=$dir.$conf->global->MAIN_FORCELANGDIR;    // For example $conf->global->MAIN_FORCELANGDIR is '/mymodule' meaning we search files into '/mymodule/langs/xx_XX'
				if (! in_array($newdir,$this->dir))
				{
				    $more['module_'.$i]=$newdir; $i++;   // We add the forced dir into the array $more. Just after, we add entries into $more to list of lang dir $this->dir.
				}
			}
			$this->dir=array_merge($more,$this->dir);    // Forced dir ($more) are before standard dirs ($this->dir)
		}

		$this->origlang=$srclang;

		if (empty($srclang) || $srclang == 'auto')
		{
			$langpref=empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])?'':$_SERVER['HTTP_ACCEPT_LANGUAGE'];
			$langpref=preg_replace("/;([^,]*)/i","",$langpref);
			$langpref=str_replace("-","_",$langpref);
			$langlist=preg_split("/[;,]/",$langpref);
			$codetouse=$langlist[0];
		}
		else $codetouse=$srclang;

		// We redefine $srclang
		$langpart=explode("_",$codetouse);
		//print "Short before _ : ".$langpart[0].'/ Short after _ : '.$langpart[1].'<br>';
		if (! empty($langpart[1]))	// If it's for a codetouse that is a long code xx_YY
		{
			// Array force long code from first part, even if long code is defined
			$longforshort=array('ar'=>'ar_SA');
			if (isset($longforshort[strtolower($langpart[0])])) $srclang=$longforshort[strtolower($langpart[0])];
			else if (! is_numeric($langpart[1])) {		// Second part YY may be a numeric with some Chrome browser
				$srclang=strtolower($langpart[0])."_".strtoupper($langpart[1]);
				$longforlong=array('no_nb'=>'nb_NO');
				if (isset($longforlong[strtolower($srclang)])) $srclang=$longforlong[strtolower($srclang)];
			}
			else $srclang=strtolower($langpart[0])."_".strtoupper($langpart[0]);
		}
		else {						// If it's for a codetouse that is a short code xx
    	    // Array to convert short lang code into long code.
	        $longforshort=array('ar'=>'ar_SA', 'el'=>'el_GR', 'ca'=>'ca_ES', 'en'=>'en_US', 'nb'=>'nb_NO', 'no'=>'nb_NO');
			if (isset($longforshort[strtolower($langpart[0])])) $srclang=$longforshort[strtolower($langpart[0])];
			else if (! empty($langpart[0])) $srclang=strtolower($langpart[0])."_".strtoupper($langpart[0]);
			else $srclang='en_US';
		}

		$this->defaultlang=$srclang;
		//print 'this->defaultlang='.$this->defaultlang;
	}


	/**
	 *  Return active language code for current user
	 * 	It's an accessor for this->defaultlang
	 *
	 *  @param	int		$mode       0=Long language code, 1=Short language code
	 *  @return string      		Language code used (en_US, en_AU, fr_FR, ...)
	 */
	function getDefaultLang($mode=0)
	{
	    if (empty($mode)) return $this->defaultlang;
	    else return substr($this->defaultlang,0,2);
	}


	/**
	 *  Load translation key-value for a particular file, into a memory array.
	 *  If data for file already loaded, do nothing.
	 * 	All data in translation array are stored in UTF-8 format.
     *  tab_loaded is completed with $domain key.
     *  Warning: MAIN_USE_CUSTOM_TRANSLATION is an old deprecated feature. Do not use it. It will revert
     *  rule "we keep first entry found with we keep last entry found" so it is probably not what you want to do.
     *
     *  Value for hash are: 1:Loaded from disk, 2:Not found, 3:Loaded from cache
     *
	 *  @param	string	$domain      		File name to load (.lang file). Must be "file" or "file@module" for module language files:
 	 *										If $domain is "file@module" instead of "file" then we look for module lang file
	 *										in htdocs/custom/modules/mymodule/langs/code_CODE/file.lang
	 *										then in htdocs/module/langs/code_CODE/file.lang instead of htdocs/langs/code_CODE/file.lang
	 *  @param	string	$alt         		0 (try xx_ZZ then 1), 1 (try xx_XX then 2), 2 (try en_US)
	 * 	@param	int		$stopafterdirection	Stop when the DIRECTION tag is found (optimize speed)
	 * 	@param	int		$forcelangdir		To force a different lang directory
	 *	@return	int							<0 if KO, 0 if already loaded or loading not required, >0 if OK
	 */
	function Load($domain,$alt=0,$stopafterdirection=0,$forcelangdir='')
	{
		global $conf;

		// Check parameters
		if (empty($domain))
		{
			print "Load ErrorWrongParameters";
			exit;
		}
		if ($this->defaultlang == 'none_NONE') return 0;    // Special language code to not translate keys

		$newdomain = $domain;
		$modulename = '';

		// Search if a module directory name is provided into lang file name
		if (preg_match('/^([^@]+)@([^@]+)$/i',$domain,$regs))
		{
			$newdomain = $regs[1];
			$modulename = $regs[2];
		}

        // Check cache
		if (! empty($this->_tab_loaded[$newdomain]))	// File already loaded for this domain
		{
	
			return 0;
		}

   
    $fileread=0;
		//$this->setDefaultLang();
		$langofdir=(empty($forcelangdir)?$this->defaultlang:$forcelangdir);

		// Redefine alt
		$langarray=explode('_',$langofdir);
		if ($alt < 1 && isset($langarray[1]) && strtolower($langarray[0]) == strtolower($langarray[1])) $alt=1;
		if ($alt < 2 && strtolower($langofdir) == 'en_us') $alt=2;
 		
		if (empty($langofdir))	// This may occurs when load is called without setting the language and without providing a value for forcelangdir
		{
			//print ("Error: ".get_class($this)."::Load was called but language was not set yet with langs->setDefaultLang(). Nothing will be loaded.", LOG_WARNING);
		
			return -1;
		}

		foreach($this->dir as $keydir => $searchdir)
		{
			// Directory of translation files
			$file_lang = $searchdir.($modulename?'/'.$modulename:'')."/langs/".$langofdir."/".$newdomain.".lang";
			$file_lang_osencoded=$file_lang;
			
			$filelangexists=file_exists($file_lang_osencoded);

			if ($filelangexists)
			{
				// TODO Move cache read out of loop on dirs or at least filelangexists
			    $found=false;

				// Enable caching of lang file in memory (not by default)
				$usecachekey='';
				// Using a memcached server
				if (! empty($conf->memcached->enabled) && ! empty($conf->global->MEMCACHED_SERVER))
				{
					$usecachekey=$newdomain.'_'.$langofdir.'_'.md5($file_lang);    // Should not contains special chars
				}
				// Using cache with shmop. Speed gain: 40ms - Memory overusage: 200ko (Size of session cache file)
				else if (isset($conf->global->MAIN_OPTIMIZE_SPEED) && ($conf->global->MAIN_OPTIMIZE_SPEED & 0x02))
				{
					$usecachekey=$newdomain;
				}

				
				if (! $found)
				{
					if ($fp = @fopen($file_lang,"rt"))
					{
						if ($usecachekey) $tabtranslatedomain=array();	// To save lang content in cache

						while ($line = fgets($fp,4096))	// Ex: Need 225ms for all fgets on all lang file for Third party page. Same speed than file_get_contents
						{
							if ($line[0] != "\n" && $line[0] != " " && $line[0] != "#")
							{
								$tab=explode('=',$line,2);
								$key=trim($tab[0]);
								//print "Domain=$domain, found a string for $tab[0] with value $tab[1]<br>";
								if ((! empty($conf->global->MAIN_USE_CUSTOM_TRANSLATION) || empty($this->tab_translate[$key])) && isset($tab[1]))    // If translation was already found, we must not continue, even if MAIN_FORCELANGDIR is set (MAIN_FORCELANGDIR is to replace lang dir, not to overwrite entries)
								{
									$value=trim(preg_replace('/\\n/',"\n",$tab[1]));

									if ($key == 'DIRECTION')	// This is to declare direction of language
									{
										if ($alt < 2 || empty($this->tab_translate[$key]))	// We load direction only for primary files or if not yet loaded
										{
                                            $this->tab_translate[$key]=$value;
											if ($stopafterdirection) break;	// We do not save tab if we stop after DIRECTION
											else if ($usecachekey) $tabtranslatedomain[$key]=$value;
										}
									}
									else
									{
										$this->tab_translate[$key]=$value;
										if ($usecachekey) $tabtranslatedomain[$key]=$value;	// To save lang content in cache
									}
								}
							}
						}
						fclose($fp);
						$fileread=1;

	

						if (empty($conf->global->MAIN_FORCELANGDIR) && empty($conf->global->MAIN_USE_CUSTOM_TRANSLATION)) break;		// Break loop on each root dir. If a module has forced dir, we do not stop loop.
					}
				}
			}
		}

		// Now we complete with next file
		if ($alt == 0)
		{
			// This function MUST NOT contains call to syslog
	
			$langofdir=strtolower($langarray[0]).'_'.strtoupper($langarray[0]);
			$this->load($domain,$alt+1,$stopafterdirection,$langofdir);
		}

		// Now we complete with reference en_US/fr_FR/es_ES file
		if ($alt == 1)
		{
			// This function MUST NOT contains call to syslog
			
			$langofdir='en_US';
			//if (preg_match('/^fr/i',$langarray[0])) $langofdir='fr_FR';
			//if (preg_match('/^es/i',$langarray[0])) $langofdir='es_ES';
			$this->load($domain,$alt+1,$stopafterdirection,$langofdir);
		}

		if ($alt == 2)
		{
			if ($fileread) $this->_tab_loaded[$newdomain]=1;	// Set domain file as loaded

			if (empty($this->_tab_loaded[$newdomain])) $this->_tab_loaded[$newdomain]=2;           // Marque ce fichier comme non trouve
		}

		// Check to be sure that SeparatorDecimal differs from SeparatorThousand
		if (! empty($this->tab_translate["SeparatorDecimal"]) && ! empty($this->tab_translate["SeparatorThousand"])
		&& $this->tab_translate["SeparatorDecimal"] == $this->tab_translate["SeparatorThousand"]) $this->tab_translate["SeparatorThousand"]='';

		return 1;
	}

	

	/**
	 *  Return text translated of text received as parameter (and encode it into HTML)
	 *              Si il n'y a pas de correspondance pour ce texte, on cherche dans fichier alternatif
	 *              et si toujours pas trouve, il est retourne tel quel
	 *              Les parametres de cette methode peuvent contenir de balises HTML.
	 *
	 *  @param	string	$key        Key to translate
	 *  @param  string	$param1     chaine de param1
	 *  @param  string	$param2     chaine de param2
	 *  @param  string	$param3     chaine de param3
	 *  @param  string	$param4     chaine de param4
	 *	@param	int		$maxsize	Max length of text
	 *  @return string      		Translated string (encoded into HTML entities and UTF8)
	 */
	function trans($key, $param1='', $param2='', $param3='', $param4='', $maxsize=0)
	{
        global $conf;

	    if (! empty($this->tab_translate[$key]))	// Translation is available
		{
            $str=$this->tab_translate[$key];

		    // Overwrite translation (TODO Move this at a higher level when we load tab_translate to avoid doing it for each trans call)
		    $overwritekey='MAIN_OVERWRITE_TRANS_'.$this->defaultlang;
     

            if (! preg_match('/^Format/',$key)) $str=sprintf($str,$param1,$param2,$param3,$param4);	// Replace %s and %d except for FormatXXX strings.

			// if ($maxsize) $str=dol_trunc($str,$maxsize);

			// We replace some HTML tags by __xx__ to avoid having them encoded by htmlentities
            $str=str_replace(array('<','>','"',),array('__lt__','__gt__','__quot__'),$str);

			// Crypt string into HTML
			$str=htmlentities($str,ENT_QUOTES,$this->charset_output);

			// Restore HTML tags
            $str=str_replace(array('__lt__','__gt__','__quot__'),array('<','>','"',),$str);

			return $str;
		}
		return $key;
		
	}


	/**
	 *  Return translated value of a text string
	 *               Si il n'y a pas de correspondance pour ce texte, on cherche dans fichier alternatif
	 *               et si toujours pas trouve, il est retourne tel quel.
	 *               Parameters of this method must not contains any HTML tags.
	 *
	 *  @param	string	$key        Key to translate
	 *  @param  string	$param1     chaine de param1
	 *  @param  string	$param2     chaine de param2
	 *  @param  string	$param3     chaine de param3
	 *  @param  string	$param4     chaine de param4
	 *  @return string      		Translated string (encoded into UTF8)
	 */
	function transnoentities($key, $param1='', $param2='', $param3='', $param4='')
	{
		return $this->convToOutputCharset($this->transnoentitiesnoconv($key, $param1, $param2, $param3, $param4));
	}


	/**
	 *  Return translated value of a text string
	 *               Si il n'y a pas de correspondance pour ce texte, on cherche dans fichier alternatif
	 *               et si toujours pas trouve, il est retourne tel quel.
	 *               No convert to encoding charset of lang object is done.
	 *               Parameters of this method must not contains any HTML tags.
	 *
	 *  @param	string	$key        Key to translate
	 *  @param  string	$param1     chaine de param1
	 *  @param  string	$param2     chaine de param2
	 *  @param  string	$param3     chaine de param3
	 *  @param  string	$param4     chaine de param4
	 *  @return string      		Translated string
	 */
	function transnoentitiesnoconv($key, $param1='', $param2='', $param3='', $param4='')
	{
		global $conf;

		if (! empty($this->tab_translate[$key]))	// Translation is available
		{
		    $str=$this->tab_translate[$key];

		    // Overwrite translation
		    $overwritekey='MAIN_OVERWRITE_TRANS_'.$this->defaultlang;
            if (! empty($conf->global->$overwritekey))    // Overwrite translation with key1:newstring1,key2:newstring2
            {
                $tmparray=explode(',', $conf->global->$overwritekey);
                foreach($tmparray as $tmp)
                {
                    $tmparray2=explode(':',$tmp);
                	if ($tmparray2[0]==$key) { $str=$tmparray2[1]; break; }
                }
            }

            if (! preg_match('/^Format/',$key)) $str=sprintf($str,$param1,$param2,$param3,$param4);	// Replace %s and %d except for FormatXXX strings.

            return $str;
		}
		
	}


	/**
	 *  Return translation of a key depending on country
	 *
	 *  @param	string	$str            string root to translate
	 *  @param  string	$countrycode    country code (FR, ...)
	 *  @return	string         			translated string
	 */
	function transcountry($str, $countrycode)
	{
		if ($this->tab_translate["$str$countrycode"]) return $this->trans("$str$countrycode");
		else return $this->trans($str);
	}


	/**
	 *  Retourne la version traduite du texte passe en parametre complete du code pays
	 *
	 *  @param	string	$str            string root to translate
	 *  @param  string	$countrycode    country code (FR, ...)
	 *  @return string         			translated string
	 */
	function transcountrynoentities($str, $countrycode)
	{
		if ($this->tab_translate["$str$countrycode"]) return $this->transnoentities("$str$countrycode");
		else return $this->transnoentities($str);
	}


	/**
	 *  Convert a string into output charset (this->charset_output that should be defined to conf->file->character_set_client)
	 *
	 *  @param	string	$str            String to convert
	 *  @param	string	$pagecodefrom	Page code of src string
	 *  @return string         			Converted string
	 */
	function convToOutputCharset($str,$pagecodefrom='UTF-8')
	{
		if ($pagecodefrom == 'ISO-8859-1' && $this->charset_output == 'UTF-8')  $str=utf8_encode($str);
		if ($pagecodefrom == 'UTF-8' && $this->charset_output == 'ISO-8859-1')	$str=utf8_decode(str_replace('€',chr(128),$str));
		return $str;
	}


	/**
	 *  Return list of all available languages
	 *
	 * 	@param	string	$langdir		Directory to scan
	 *  @param  string	$maxlength   	Max length for each value in combo box (will be truncated)
	 *  @param	int		$usecode		Show code instead of country name for language variant
	 *  @return array     				List of languages
	 */
	function get_available_languages($langdir=DOL_DOCUMENT_ROOT,$maxlength=0,$usecode=0)
	{
		global $conf;

		// We scan directory langs to detect available languages
		$handle=opendir($langdir."/langs");
		$langs_available=array();
		while ($dir = trim(readdir($handle)))
		{
			if (preg_match('/^[a-z]+_[A-Z]+/i',$dir))
			{
				$this->load("languages");

				if ($usecode || ! empty($conf->global->MAIN_SHOW_LANGUAGE_CODE))
				{
					$langs_available[$dir] = $dir.': '.dol_trunc($this->trans('Language_'.$dir),$maxlength);
				}
				else
				{
					$langs_available[$dir] = $this->trans('Language_'.$dir);
				}
			}
		}
		return $langs_available;
	}


	/**
	 *  Return if a filename $filename exists for current language (or alternate language)
	 *
	 *  @param	string	$filename       Language filename to search
	 *  @param  string	$searchalt      Search also alernate language file
	 *  @return boolean         		true if exists and readable
	 */
	function file_exists($filename,$searchalt=0)
	{
		// Test si fichier dans repertoire de la langue
		foreach($this->dir as $searchdir)
		{
			if (is_readable(dol_osencode($searchdir."/langs/".$this->defaultlang."/".$filename))) return true;

			if ($searchalt)
			{
				// Test si fichier dans repertoire de la langue alternative
				if ($this->defaultlang != "en_US") $filenamealt = $searchdir."/langs/en_US/".$filename;
				//else $filenamealt = $searchdir."/langs/fr_FR/".$filename;
				if (is_readable(dol_osencode($filenamealt))) return true;
			}
		}

		return false;
	}




	/**
	 * Return an array with content of all loaded translation keys (found into this->tab_translate)
	 *
	 * @return array	Array of translation keys lang_key => string_translation_loaded
	 */
	function get_translations_for_substitutions()
	{
		$substitutionarray = array();

		foreach($this->tab_translate as $code => $label) {
			$substitutionarray['lang_'.$code] = $label;
		}

		return $substitutionarray;
	}
}

