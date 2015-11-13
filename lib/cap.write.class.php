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
 *	\file      	cap.write.class.php
 *  \ingroup   	xml
 *	\brief      File of class with XML builder
 */
 
 class xml{
 	
 	var $file;
 	var $break = "\r\n";
 	var $tabspace = "\t";
 	var $lt = '<';
 	var $gt = '>';
 	
 	var $tab = 0;
 	var $tab_tree=array();
 	
		/**
     * initialize XML
     *
     * @param   string	$version			The version of the XML
     * @param   string	$encoding			The encoding of the XML
     * @param   string	$options			The options of the XML
     * @return	None
     */
 		function xml($version, $encoding, $options=array()){
 			
 			$this->addrow(('<'.'?xml version="'.$version.'" encoding="'.$encoding.'"'.$this->aToT($options).' ?'.'>'));
 		}
 		
 		/**
     * make a tag in the XML file
     *
     * @param   string	$tag					The name of the XML Tag
     * @param   string	$value				The value of the XML Tag
     * @param   array		$options			The options of the XML Tag
     * @param   int			$trimtext			Trim or not Trim XML Tag value
     * @return	None
     */
 		function tag_simple($tag,$value='',$options=array(),$trimtext=false)
 		{ 			
 			if ($trimtext == 1) $value = trim($value); 
 			if($value == '' and empty($options)) return '';
  	
 				$row = ($this->lt.$tag.$this->aToT($options));
 				if(trim($value) != ''){
 					$row.= ($this->gt);
 					$row.= htmlspecialchars(($value), ENT_QUOTES, "UTF-8");	
 					$row.= ($this->lt.'/'.$tag.$this->gt);
 				}else{
 					if($tag == 'summary'){
 						$row.= ($this->gt);
 						$row.= 'No Summary';
 						$row.= ($this->lt.'/'.$tag.$this->gt);
 					}else{
 					$row.= ('/'.$this->gt);
 					}
 				}
 			$this->addrow($row);
 		}
 		
 		/**
     * make a open tag in the XML file
     *
     * @param   string	$tag					The name of the XML Tag
     * @param   array		$options			The options of the XML Tag
     * @return	None
     */
 		function tag_open($tag,$options=array() ){
 			$row =( $this->lt.$tag.$this->aToT($options).$this->gt );
 			$this->addrow($row);
		 	$this->tab++; 	
 			array_push($this->tab_tree,$tag);
 		}
 		
		/**
     * close a open tag in the XML file
     *
     * @param   string	$tag					The name of the XML Tag
     * @return	None
     */
 		function tag_close($tag){
 			$c=0;
 			if($this->tab > 0)
		 		do{
		 			$ltag = array_pop($this->tab_tree);
		 			$this->tab--;
		 			$row = ($this->lt.'/'.$ltag.$this->gt);
		 			$this->addrow($row);
					$c++;
				}while(($ltag != $tag || ( is_int($tag) && $c < $tag )) && $this->tab > 0  );		 	
		 		
 		}
 		
		/**
     * Make a blank line in the XML file
     *
     * @return	None
     */
 		function add_emptyrow(){
 			$this->addrow('');
 		}
 		
 		/**
     * Make a <![CDATA[]]> Tag in the XML File
     *
     * @param   string	$value					The name of the CDATA Tag
     * @return	None
     */
 		function cdata($value){
 			return '<![CDATA['.$value.']]>';
 		}
 		
 		/**
 		 *
     */
 		function tab(){
 			if($this->tab == 0) return '';
 			return implode('',array_fill(0,$this->tab,$this->tabspace));
 		}
 		
		/**
     * Add a row with content in the XML File
     *
     * @param   string	$content					The Content wich should be added
     * @return	None
     */
 		function addrow($content){
 			$this->file[] = $this->tab().$content.$this->break;	
 		}
 		
 		/**
     * make the option style
     *
     * @param   array	$options					The options wich should be added
     * @return	None
     */
 		function aToT($options){
 			if(!is_array($options)) return '';
 			$ret = '';
 			foreach($options as $key => $value){
 				$ret .= ' '.$key.'="'.$value.'"';
 			}
 			return $ret;
 		}
 		
		/**
     * Add a Comment
     *
     * @param   string	$comment					the comment
     * @return	None
     */
 		function addComment($comment){
 			$this->addrow(('<!--'.$comment.'-->'));
 		}
	 	
		/**
     * Output The File
     *
     * @return	$this->file 	All content of the File
     */
	 	function output(){
 			return (implode('',$this->file));
 		} 
 	 
		/*
		 * Function to Debug cap.write.class.php
		 *
		 * @return array 	$this 	All content of the Class
		 */	
		function Debug()
		{
			print '<pre>';
				print_r($this);
			print '</pre>';
			exit;
		}
 }
 

?>