<?php
/*
 *  Copyright (c) 2015  Guido Schratzer   <schratzerg@backbone.co.at>
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
 *	\file      	/class/cap.form.class.php
 *  \ingroup   	core
 *	\brief      JQuery Form to Insert Data to CAP-File and create config File
 */


/**
 *	Class to manage generation of HTML components
 *	Only common components must be here.
 *
 */
	require_once 'class/plugin.install.class.php';

	class CAP_Form{

		var $version = '1.5.5';
		var $login_id = 0;
		/**
	 * initialize Class with Data
	 *
	 * @param   string	$post			Array with Type/Tag of CAP 1.1
	 * @return	None
	 */
		function __construct($post = "")
		{
			if(is_array($post))
			{
				$this->output[] 				= $post['output'];
				$this->identifier[] 			= $post['identifier'];
				$this->sender[]					= $post['sender'];
				$this->sent[]					= $post['sent'];
				$this->status[]					= $post['status'];
				$this->msgType[]				= $post['msgType'];
				$this->references[]				= $post['references'];
				$this->scope[]					= $post['scope'];

				$this->source[]					= $post['source'];
				$this->restriction[]			= $post['restriction'];
				$this->addresses[]				= $post['addresses'];
				$this->code[]					= $post['code'];
				$this->note[]					= $post['note'];
				$this->incidents[]				= $post['incidents'];

				foreach($post['info'] as $key => $info)
				{

					$this->language[]			= $info['language'];
					$this->category[]			= $info['category'];
					$this->event[]				= $info['event'];
					$this->responseType[]		= $info['responseType'];
					$this->urgency[]			= $info['urgency'];
					$this->severity[]			= $info['severity'];
					$this->certainty[]			= $info['certainty'];
					$this->audience[]			= $info['audience'];
					$this->eventCode[]			= $info['eventCode'];
					$this->effective[]			= $info['effective'];
					$this->onset[]				= $info['onset'];
					$this->expires[]			= $info['expires'];
					$this->senderName[]			= $info['senderName'];
					$this->headline[]			= $info['headline'];
					$this->description[]		= $info['description'];
					$this->instruction[]		= $info['instruction'];
					$this->web[]				= $info['web'];
					$this->contact[]			= $info['contact'];
					$this->parameter[]			= $info['parameter'];

					foreach($info['area'] as $key2 => $area)
					{
						$this->areaDesc[]			= $area['areaDesc'];
						$this->polygon[]			= $area['polygon'];
						$this->circle[]				= $area['circle'];
						$this->geocode[]			= $area['geocode'];
					}
				}
				$this->cap = $post;
			}else if(is_object($post)){
				$this->identifier[] 			= (string) $post->identifier;
				$this->sender[]						= (string) $post->sender;
				$this->sent[]							= (string) $post->sent;
				$this->status[]						= (string) $post->status;
				$this->msgType[]					= (string) $post->msgType;
				$this->references[]				= (string) $post->references;
				$this->scope[]						= (string) $post->scope;

				$this->source[]						= (string) $post->source;
				$this->restriction[]			= (string) $post->restriction;
				$this->addresses[]				= (string) $post->addresses;
				$this->code[]							= (string) $post->code;
				$this->note[]							= (string) $post->note;
				$this->incidents[]				= (string) $post->incidents;

				$i=0;
				foreach($post->info as $key => $info)
				{
					$this->language[]				= (string) $info->language;
					$this->category[]				= (string) $info->category;
					$this->event[]					= (string) $info->event;
					foreach($info->responseType as $key => $responseType)
					{
						$this->responseType[]	= (array) $responseType;
					}
					foreach($info->urgency as $key => $urgency)
					{
						$this->urgency[]			= (string) $urgency;
					}
					$this->severity[]				= (string) $info->severity;
					$this->certainty[]			= (string) $info->certainty;
					$this->audience[]				= (string) $info->audience;

					$e=0;
					foreach($info->eventCode as $key => $eventCarr)
					{
						$this->eventCode[$i][$e]['value']			= (string) $eventCarr->value;
						$this->eventCode[$i][$e++]['valueName']			= (string) $eventCarr->valueName;
					}

					$this->effective[]			= (string) $info->effective;
					$this->onset[]					= (string) $info->onset;
					$this->expires[]				= (string) $info->expires;
					$this->senderName[]			= (string) $info->senderName;
					$this->headline[]				= (string) $info->headline;
					$this->description[]		= (string) $info->description;
					$this->instruction[]		= (string) $info->instruction;
					$this->web[]						= (string) $info->web;
					$this->contact[]				= (string) $info->contact;

					$p=0;
					foreach($info->parameter as $key => $paramarr)
					{
						$this->parameter[$i][$p]['value']			= (string) $paramarr->value;
						$this->parameter[$i][$p++]['valueName']			= (string) $paramarr->valueName;
					}

					$a=0;
					foreach($info->area as $key2 => $area)
					{
						$this->areaDesc[]			= (string) $area->areaDesc;
						$this->polygon[$a][]			= (string) $area->polygon;
						$this->circle[$a][]				= (string) $area->circle;

						$g=0;
						foreach($area->geocode as $key => $geoarr)
						{
							$this->geocode[$i][$g]['value']			= (string) $geoarr->value;
							$this->geocode[$i][$g++]['valueName']			= (string) $geoarr->valueName;
						}
						$a++;
					}
					$i++;
				}
				$this->cap = $post;

				//$this->Debug();
			}
		}

		/*
		 * Function to Debug cap.create.class.php
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

		function GetTypeStatusFromArray($status_theme, $getreq = 0)
		{
			$required = "";
			switch($status_theme) // if object have a value than its data-theme="f"
			{
				case 'O':
					$status_theme = 'data-theme="a"';
					break;

				case 'OD':
					$status_theme = 'data-theme="b"';
					break;

				case 'C':
					$status_theme = 'data-theme="e"';
					break;

				case 'R':
					$status_theme = 'data-theme="c"';
					$required = '';
					break;

				default:
					$status_theme = "";
					break;
			}

			if($getreq == 1) return $required;
			return $status_theme;
		}

	/**
	 * Output input field for CAP 1.1 value's
	 *
	 * @param   string	$type			Type/Tag of CAP 1.1
	 * @param   string	$lang			the language (in RFC 3066)
	 * @return	string						HTML edit field
	 */
		function InputStandard($type, $status_arr = "")
		{
			global $configuration, $langs, $AreaCodesArray, $ParameterArray, $soap_SVG, $SVLdetail, $AreaVLArray, $plugin, $login_to_webservice_faild, $login_error, $login_error_html;

			$st['date'] = date('Y-m-d');
			$st['time'] = date('H:i');
			$st['zone'] = substr(date('P'), 1);
			$timezone_name =  date('P').' '.date('T').' '.date_default_timezone_get();

			/*
			Requirenes level
			key : theme : desc
			O   : A     : optional
			OD  : B     : optional (Dark)
			C   : E     : conditional
			R   : C     : required
			*/

			if(is_array($status_arr))
			{
				$status_theme = $this->GetTypeStatusFromArray($status_arr[$type]);
			}

			switch($type)
			{
				case 'meteo_map':

					if(!empty($plugin->name))
					{
						//print '<pre>Plugin: ';
						//print_r($plugin);
						//print '</pre>';
						//exit;
						$out.= '<input type="hidden" id="plugin" value="1">';
						$out.= '<input type="hidden" id="plugin_name" value="'.$plugin->name.'">';
						$out.= '<input type="hidden" id="cap_engine" value="'.$plugin->cap_engine.'">';
						$out.= '<script> var area_vl;</script>'; // for non webservice app
						$soap_SVG = $plugin->svg_val;
						$countryName = $plugin->svg_name;
						$AreaCodesArray = $plugin->area_codes;
						$ParameterArray['AWT'] = $plugin->AWT;
						$ParameterArray['AWL'] = $plugin->AWL;
					}
					else
					{
						$out.= '<input type="hidden" id="plugin_name" value="webservice">';
						$out.= '<input type="hidden" id="cap_engine" value="lib/cap.create.from_js_array.2.php">';
						$out.= '<input type="hidden" id="plugin" value="1">';
						$out.= '<script> var area_vl;</script>'; // for non webservice app
						if($SVLdetail) $out.= str_replace('&nbsp;', ' ', $SVLdetail);
					}

					if(!isset($_GET['data'])) $_GET['data'] = 0;
					$langs_arr = $this->getlang();

					foreach($langs_arr as $key_l => $val_l)
					{
						if(in_array($key,$this->language)) unset($langs_arr[$key]);
					}
					foreach ($langs_arr as $key_l => $val_l)
					{
						$langs_keys[] = $key_l;
					}

					$out.= '<style>.ui-footer {display:none !important;} .svg_darker{filter:url(#css_brightness);}</style>';
					if(basename($_SERVER['PHP_SELF']) == "map.php") $out.= '<input type="hidden" value="1" id="init_map">';
					$out.= '<input type="hidden" value="'.$langs->trans('WarnGetCalculated').'" id="mk_process_lang">';
					$out.= '<input type="hidden" value="'.$langs->trans('DelWarning').'" id="del_warn_lang">';
					$out.= '<input type="hidden" value="'.$langs->trans('ChangeWithoutSave').'" id="chang_without_save">';
					$out.= '<input type="hidden" value="'.$_GET['data'].'" id="data">';
					$out.= '<input type="hidden" value="2" id="green">';
					$out.= '<input type="hidden" value="'.date('P').'" name="timezone">';
					$utc = date('P');
					if($_GET['data']) $out.= '<input type="hidden" value="'.date('Y-m-d', strtotime('now + '.$_GET['data'].' days')).'" id="today">';
					else $out.= '<input type="hidden" value="'.date('Y-m-d').'" id="today">';
					$out.= '<input type="hidden" value="'.date('H:00:00', strtotime('23:00 '.$utc[0].' '.$utc[1].$utc[2].' hours')).'" id="st_from">';
					$out.= '<input type="hidden" value="'.date('H:59:59', strtotime('22:59 '.$utc[0].' '.$utc[1].$utc[2].' hours')).'" id="st_to">';

					$timezone_h =  date('P');
					$out.= '<input type="hidden" value="'.$timezone_h.'" id="timezone_h">';

					foreach($langs_keys as $key => $lang_val)
					{
						$out.= '<input name="langs" type="hidden" value="'.$lang_val.'" id="lang_'.$key.'">';
					}
					//$out.= '<input type="hidden" value="'.$langs_keys[0].'" id="lang_0">';
					//$out.= '<input type="hidden" value="'.$langs_keys[1].'" id="lang_1">';
					$out.= '<span id="info_text" class="area_deaktive">'.$langs->trans('DESC_EditWarningNotAktive').'</span>';
					$out.= '<div id="map_main_div" class="ui-grid-a">';
						$out.= '<div class="ui-block-a" id="WarnDetailDIV" style="width: 30%;min-width: 280px;">';
							$out.= '<div class="ui-bar" id="AreaDetailDIV" style="background-color: #cccccc;">';
								// Info
								$out.= '<ul data-role="listview" data-divider-theme="b" style="opacity: 0.5; pointer-events: none;" id="AreaDetailUL">'; // as long as it is without Area show 50% alpha
									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">';
										$out.= 'Area: ';
										$out.= '<span id="left_area_name"></span>';
										$out.= '<span id="select_area_name" style="position: absolute;top: 10px;width: 43%;margin-left: 10px;">';
										//$out.= 'None';
											foreach($AreaCodesArray as $key => $area_arr)
											{
												$S_Area[$area_arr['aid']] = $area_arr['AreaCaption'];
											}

											if(is_array($S_Area))
											{
												$out.= '<select name="emmaid" id="emmaid_select" data-native-menu="false" multiple="multiple" class="" tabindex="-1" data-mini="true">';
													$out.= '<option data-placeholder="true"></option>';
													foreach($S_Area as $eid => $areaname)
													{
														$out.= '<option value="'.$eid.'">'.$areaname.'</option>';
													}
												$out.= '</select>';
											}

										$out.= '</span>';
										$out.= '<span id="right_area_type"></span>';
										$out.= '</h1>';
									$out.= '</li>';
									//if(empty($plugin->name))
									//{
									//	$out.= '<li data-role="list-divider" data-theme="b" >'; // style="border: 1px solid #dddddd; border-bottom: none;"
									//		$out.= '<legend>'.$langs->trans('MultiAreaSelectLabel').': '.$this->tooltip('radio', $langs->trans("DESC_MultiAreaSelectLabel")).'</legend>';
									//		$out.= '<span id="multisel_area_name" style="position: absolute;top: 3px;right: 9px;">';
									//			$out.= '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
									//				$out.= '<input type="radio" name="multisel_area_name_bool" id="radio-choice-h-2a" value="1" checked="checked">';
									//				$out.= '<label for="radio-choice-h-2a">'.$langs->trans('ON').'</label>';
									//				$out.= '<input type="radio" name="multisel_area_name_bool" id="radio-choice-h-2b" value="0">';
									//				$out.= '<label for="radio-choice-h-2b">'.$langs->trans('OFF').'</label>';
									//			$out.= '</fieldset>';
									//		$out.= '</span>';
									//	$out.= '</li>';
									//}
									$i = 0;
									foreach($langs_keys as $key => $lang_val)
									{
										//$out.= '<li style="border: 1px solid #dddddd; border-bottom: none;">';
										$not_collaps = '';
										if($i <= 1) $not_collaps = 'data-collapsed="false"';
										$out.= '<li data-role="collapsible" '.$not_collaps.' data-iconpos="right" data-inset="false" class="lang_collaps">';
											$out.= '<legend>'.$langs->trans($langs_arr[$lang_val]).': </legend>';
										//$out.= '</li>';
										//$out.= '<li style="border: 1px solid #dddddd; border-bottom: none;">';
											//$out.= $this->tooltip($lang_val, $langs->trans("LabelLanguage"));
											if($key == 0) $requir = 'class="required"';
											else $requir = "";
											$out.= '<div class="lang_input" id="'.$lang_val.'">';
													$out.= '<textarea id="desc_'.$key.'" placeholder="description" name="description['.$lang_val.']" '.$requir.'></textarea>';
													$out.= '<textarea id="inst_'.$key.'" placeholder="instruction" name="instruction['.$lang_val.']"></textarea>';
											$out.= '</div>';
										$out.= '</li>';
										$i++;
									}
									/*
									$out.= '<li style="border: 1px solid #dddddd; border-bottom: none;">';
										$out.= '<legend>'.$langs->trans($langs_arr[$langs_keys[1]]).': '.$this->tooltip($langs_keys[0], $langs->trans("LabelLanguage")).'</legend>';
									$out.= '</li>';
									$out.= '<li style="border: 1px solid #dddddd; border-bottom: none;">';

										$out.= '<div class="lang_input" id="'.$langs_keys[1].'">';
												$out.= '<textarea id="desc_1" placeholder="description" name="description['.$langs_keys[1].']">'.$this->description[$i].'</textarea>';
												$out.= '<textarea id="inst_1" placeholder="instruction" name="instruction['.$langs_keys[1].']">'.$this->instruction[$i].'</textarea>';
										$out.= '</div>';
									$out.= '</li>';
									*/
									$out.= '<li style="border: 1px solid #dddddd; border-bottom: none;border-top: none; border-right: none;">';
										$out.= '<div class="ui-grid-a">';
											$out.= '<div class="ui-block-a">';
												$out.= '<legend>'.$langs->trans("From").': '.$this->tooltip('From', $langs->trans("LabelEffectivePaintAndAlertDesc")).'</legend>';
												$out.= '<div class="input-group clockpicker" data-autoclose="true">';
													$out.= '<input '.$status_theme.' id="from_0" type="text" name="effective[time]" step="1" value="00:00">';
													$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
														$out.= '<span class="glyphicon glyphicon-time"></span>';
													$out.= '</span>';
												$out.= '</div>';
												$out.= '<span style="font-size: 10px;color: #8d8d8d;">'.$timezone_name.'</span>';
											$out.= '</div>';
											$out.= '<div class="ui-block-b">';
												$out.= '<legend>'.$langs->trans("To").': '.$this->tooltip('To', $langs->trans("LabelExpiresPaintAndAlertDesc")).'</legend>';
												$out.= '<div class="input-group clockpicker" data-autoclose="true">';
													$out.= '<input '.$status_theme.' id="to_0" type="text" name="expires[time]" step="1" value="23:59">';
													$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
														$out.= '<span class="glyphicon glyphicon-time"></span>';
													$out.= '</span>';
												$out.= '</div>';
											$out.= '</div>';
										$out.= '</div><!-- /grid-a -->';
									$out.= '</li>';

									$out.= '<li id="date_collaps" data-role="collapsible" data-collapsed="true" data-iconpos="right" data-inset="false" class="lang_collaps">';
										$out.= '<legend>';
											$out.= '<div class="ui-grid-a">';
												$out.= '<div class="ui-block-a">';
													$out.= '<span style="font-size: 10px;color: #8d8d8d;" id="legdatefrom">'.date("Y-m-d").'</span>';
												$out.= '</div>';
												$out.= '<div class="ui-block-b">';
													$out.= '<span style="font-size: 10px;color: #8d8d8d;" id="legdateto">'.date("Y-m-d").'</span>';
												$out.= '</div>';
											$out.= '</div>';
										$out.= '</legend>';
										$out.= '<div class="ui-grid-a">';
											$out.= '<div class="ui-block-a">';
												$out.= '<legend>'.$langs->trans("From").' '.$langs->trans("Date").': '.$this->tooltip('From', $langs->trans("LabelEffectivePaintAndAlertDesc")).'</legend>';
												$out.= '<input '.$status_theme.' id="from_date" type="text" name="effective[date]" step="1" value="'.date("Y-m-d").'">';
											$out.= '</div>';
											$out.= '<div class="ui-block-b">';
												$out.= '<legend>'.$langs->trans("To").' '.$langs->trans("Date").': '.$this->tooltip('To', $langs->trans("LabelExpiresPaintAndAlertDesc")).'</legend>';
												$out.= '<input '.$status_theme.' id="to_date" type="text" name="expires[date]" step="1" value="'.date("Y-m-d").'">';
											$out.= '</div>';
										$out.= '</div><!-- /grid-a -->';
									$out.= '</li>';

									$out.= '<li style="border: 1px solid #dddddd;margin-bottom:10px; border-right: none;">'; // solfe border problem: margin-bottom:10px;
										$out.= '<div class="ui-grid-a">';
											$out.= '<div class="ui-block-a">';
												$out.= '<a id="del_war" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #ff3f3f;color: black;text-shadow: none;border: 1px solid black;">';
													$out.= $langs->trans('Delete');
												$out.= '</a>';
											$out.= '</div>';
											$out.= '<div class="ui-block-b">';
												$out.= '<a id="sav_war" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #065c00;color: white;text-shadow: none;border: 1px solid black;">';
													$out.= $langs->trans('Save');
												$out.= '</a>';
											$out.= '</div>';
										$out.= '</div><!-- /grid-a -->';
									$out.= '</li>';

								$out.= '</ul>';
							$out.= '</div>';
						$out.= '</div>';
						$out.= '<div class="ui-block-b" id="MapDiv" style="width: 70%;">';
							$out.= '<div class="ui-bar noselect">';
								// Map
								$out.= '<ul data-role="listview" data-divider-theme="b">';
									$out.= '<li data-role="list-divider" data-theme="b">';
										if(empty($plugin->name)) $countryName = '';
										$out.= '<h1 style="font-size:22px;float:left;"><span id="CountryInfo">'.$countryName.'</span> '.date('Y-m-d', strtotime('now + '.$_GET['data'].' days')).' <span id="mk_process_info" style="color: #ffff00;"></span></h1>';
									$out.= '</li>';

									$out.= '<li style="border: 1px solid #dddddd; border-bottom: none; padding: 0px;" id="map-container">';
										if(!empty($soap_SVG))
										{
											// dddddd, a4a4a4, 878787
											$out.= '<div id="awareness_toolbox" class="awareness_div">';
												if(is_array($ParameterArray['AWT']))
												foreach($ParameterArray['AWT'] as $id => $type_arr)
												{
													if(!empty($type_arr['img_src']))
													{
														$out.= '<div class="awareness" id="left_box_type_'.$id.'" aktive="1" type="'.$id.'"><img src="'.$type_arr['img_src'].'"></div>';
													}
													else
													{
														if($type_arr['id'] < 10) $tmpTID = '0'.$type_arr['id'];
														else $tmpTID = $type_arr['id'];
														$out.= '<div class="awareness" id="left_box_type_'.$type_arr['id'].'" aktive="'.$type_arr['aktive'].'" type="'.$type_arr['id'].'"><img src="includes/meteoalarm/warn-typs_'.$tmpTID.'.png"></div>';
													}
												}
											$out.= '</div>';

											$size = '';

											$out.= '<div id="awareness_color_toolbox" class="awareness_color_div" '.$size.'>';
												// 29d660, ffff00, fecb31, fe0104
												//print_r($ParameterArray['AWL']);
												if(is_array($ParameterArray['AWL']))
												foreach($ParameterArray['AWL'] as $key => $level_arr)
												{
													if($level_arr['id'] > 0) $out.= '<div class="awareness" style="background-color: '.$level_arr['hazard_level_color'].';" id="left_box_level_'.$level_arr['id'].'" aktive="1" level="'.$level_arr['id'].'"></div>';
												}

											$out.= '</div>';

											$exstyle = '';
											if(!empty($plugin->name)) $exstyle = 'style="padding-right: 15px;"';
											$out.= '<div id="meteo_toolbox" class="meteo_toolbox_div_1" '.$exstyle.'>';
												$day_text[0] = date('d.m.Y', strtotime('now'));
												$day_text[1] = date('d.m.Y', strtotime('now + 1 day'));
												$day_text[2] = date('d.m.Y', strtotime('now + 2 day'));
												$out.= $this->buildSelect("day", $day_text, "data-native-menu=\"false\" id=\"day\"", $langs->trans("Day"), $_GET['data']);
											$out.= '</div>';

											if(empty($plugin->name))
											{
												//$out.= '<div id="meteo_toolbox" class="meteo_toolbox_div_2">';
												//	$S_Param_AWT[0] = $langs->trans("All Types");
												//	if(is_array($ParameterArray['AWT']))
												//	foreach($ParameterArray['AWT'] as $key => $area_arr)
												//	{
												//		$S_Param_AWT[$area_arr['id']] = $area_arr['hazard_type_DESC'];
												//	}
												//	$out.= $this->buildSelect("type", $S_Param_AWT, "data-native-menu=\"false\" id=\"type\"", $langs->trans("Type"), $_GET['type']);
												//$out.= '</div>';

												$out.= '<div id="meteo_toolbox" class="meteo_toolbox_div_2">';
													$out.= '<div class="awareness" id="reload" aktive="1"><img src="includes/meteoalarm/reload.png"></div>';
												$out.= '</div>';
											}

											// TODO:
											//$out.= '<div id="work_toolbox" class="work_toolbox_div">';
											//	$out.= '<div class="awareness" id="Undo"><img src="includes/meteoalarm/undo.png"></div>';
											//	$out.= '<div class="awareness" id="Redo" aktive="0"><img src="includes/meteoalarm/redo.png"></div>';
											//	$out.= '<div class="awareness" id="Reset"><img src="includes/meteoalarm/reset.png"></div>';
											//$out.= '</div>';

											$out.= '<div id="process_toolbox" class="process_toolbox_div"><div id="process_toolbox_inner" class="process_toolbox_div_inner"></div></div>';

											if($AreaCodesArray[0][0] == "Error2" || count($AreaCodesArray) < 1 || $soap_SVG == "")
											{
												$out.= '<span id="info_text" class="area_deaktive" style="top: 0px;left: 0px;width: 100%;height: 100%;padding: 85px;">';
													//$out.= 'Something went wrong. Check your ISO code and configuration!';
													$out.= $langs->trans("WrongISOorConfiguration");
												$out.= '</span>';
											}

											$out.= substr($soap_SVG, 0, -6); // SVG from the SOAP
											//$out.= '<svg id="notme">';
											$out.= '<defs><filter id="css_brightness"><feComponentTransfer id="css_brightness"><feFuncR type="linear" slope="0.5"/><feFuncG type="linear" slope="0.5"/><feFuncB type="linear" slope="0.5"/></feComponentTransfer></filter></defs>';

//$out.= '<defs><filter id="css_test"><feComponentTransfer id="css_test"><feFuncR type="linear" slope="0.5"/><feFuncG type="linear" slope="0.5"/><feFuncB type="linear" slope="0.5"/></feComponentTransfer></filter></defs>';


											if(is_array($ParameterArray['AWT']))
											foreach($ParameterArray['AWT'] as $id => $type_arr)
											{
												$tmpID = intval($type_arr['id']);
												if($tmpID < 10) $tmpID = '0'.$tmpID;
												if(!empty($type_arr['img_src']) || file_exists('includes/meteoalarm/warn-typs_'.$tmpID.'.png'))
												{
													if(file_exists('includes/meteoalarm/warn-typs_'.$tmpID.'.png')) $id = $type_arr['id'];
													if(is_array($ParameterArray['AWL']))
													foreach($ParameterArray['AWL'] as $key => $level_arr)
													{
														if($level_arr['id'] > 0)
														{
															if(!empty($type_arr['img_src'])) $standard_map = getimagesize($type_arr['img_src']);
															else if(file_exists('includes/meteoalarm/warn-typs_'.$tmpID.'.png')) $standard_map = getimagesize('includes/meteoalarm/warn-typs_'.$tmpID.'.png');

															if($standard_map[0] == 0)
															{
																$standard_map[0] = "20";
																$standard_map[1] = "20";
															}
															$out.= '<pattern xmlns="http://www.w3.org/2000/svg" id="pattern_l'.$level_arr['id'].'t'.$id.'" width="'.($standard_map[0]*2).'" height="'.($standard_map[1]*2).'" patternUnits="userSpaceOnUse">';
																$out.= '<rect x="0" y="0" width="'.($standard_map[0]*2).'" height="'.($standard_map[1]*2).'" fill="'.$level_arr['hazard_level_color'].'"/>';
																if(!empty($type_arr['img_src'])) $out.= '<image xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="'.$type_arr['img_src'].'" id="pattern_regen_3_img" x="0" y="0" width="'.($standard_map[0]).'" height="'.($standard_map[1]).'" transform="scale(1, 1)"/>';
																else if(file_exists('includes/meteoalarm/warn-typs_'.$tmpID.'.png') && $level_arr['id'] > 1) $out.= '<image xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="includes/meteoalarm/warn-typs_'.$tmpID.'.png" id="pattern_regen_3_img" x="0" y="0" width="'.($standard_map[0]).'px" height="'.($standard_map[1]).'px" transform="scale(1, 1)"/>';
															$out.= '</pattern>';
														}
													}
												}
											}
											//$out.= '<pattern xmlns="http://www.w3.org/2000/svg" id="pattern_regen_3" width="93.33333333333333" height="93.33333333333333" patternUnits="userSpaceOnUse">';
											//	$out.= '<rect x="0" y="0" width="93.33333333333333" height="93.33333333333333" fill="#fb8c00"/>';
											//	$out.= '<image xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="http://www.dwd.de/DWD/warnungen/warnapp/viewer/img/warn_icons_pattern_regen.png" id="pattern_regen_3_img" x="0" y="0" width="66.66666666666667" height="66.66666666666667" transform="scale(1, 1)"/>';
											//$out.= '</pattern>';
											$out.= '</svg>';
										}
										else
										{
											$out.= '</form><form action="map.php" data-ajax="false" method="post" enctype="multipart/form-data" name="pluginZIPForm" style="padding: 15px;">';
												$out.= '<legend>'.$langs->trans("WebserviceNotConnectedReconnectOrUsePluginZIP").': '.$this->tooltip('jsonDESC', $langs->trans("WebserviceNotConnectedReconnectOrUsePluginZIPDESC")).'</legend>';
												$out.= '<input type="file" name="pluginZIP" id="pluginZIP" accept=".zip">';
												$out.= '<input type="submit" name="submitplugin" value="'.$langs->trans("Upload").'">';
											$out.= '</form><form action="map.php" method="get" name="pluginForm" style="padding: 15px;" data-ajax="false">';

												$plugin = new Plugin();
												$plugin->get_all_plugin();
												$out.= '<legend>'.$langs->trans("UsePlugin").': '.$this->tooltip('Plugin', $langs->trans("UsePluginDESC")).'</legend>';
												$out.= $this->buildSelect("use_plugin", $plugin->plugin_folder, "data-native-menu=\"false\" id=\"use_plugin\"", $langs->trans("Plugin"), $_GET['use_plugin']);
												$out.= '<input type="submit" value="'.$langs->trans("Use").'" data-ajax="false">';

											$out.= '</form>';
										}

									$out.= '</li>';

									//$out.= '<li style="border: 1px solid #dddddd; border-left: none; border-right: none;">';
									//$out.= '</li>';

								$out.= '</ul>';

							$out.= '</div>';
						$out.= '</div>';
					$out.= '</div><!-- /grid-a -->';

					$out.= '<div class="ui-bar">';
						$out.= '<ul data-role="listview" data-divider-theme="b">'; // as long as it is without Area show 50% alpha
							$out.= '<li data-role="list-divider" data-theme="b" style="padding: 25px;">';
								if(empty($plugin->name))
									$out.= '<a id="submit_cap" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="float: right; background-color: #065c00;color: white;text-shadow: none;border: 1px solid black;">';
								else
									$out.= '<a id="submit_cap" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="float: right; background-color: lightgray;color: black;text-shadow: none;border: 1px solid black;">';

									if(empty($plugin->name))
										$out.= $langs->trans("Submit");
									else
										$out.= $langs->trans("Produce CAPs");

								$out.= '</a>';
							$out.= '</li>';
						$out.= '</ul>';
					$out.= '</div>';


					$out.= '<div data-role="popup" id="CAPpopupDialog" data-overlay-theme="a" data-theme="a" data-dismissible="false" style="max-width:400px;">';
						$out.= '<div data-role="header" id="CAPpopupDialog_header" data-theme="a">';
							$out.= '<h2>'.$langs->trans('PaintGreen').'</h2>';
						$out.= '</div>';
						$out.= '<div role="main" id="CAPpopupDialog_main" class="ui-content">';
							$out.= $langs->trans('DESC_PaintGreen');
							$out.= '<div data-role="collapsibleset" data-content-theme="a" data-iconpos="right" id="set" style="max-height: 500px; overflow: auto;">';
							$out.= '</div>';

							// Dissmis or OK
							$out.= '<div class="ui-grid-a">';
								$out.= '<div class="ui-block-a">';
									$out.= '<a id="green_no" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #ff3f3f;color: black;text-shadow: none;border: 1px solid black;">';
										$out.= $langs->trans('No');
									$out.= '</a>';
								$out.= '</div>';
								//$out.= '<div class="ui-block-b">';
								//	$out.= '<a id="green_edit" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #fff700;color: black;text-shadow: none;border: 1px solid black;">';
								//		$out.= $langs->trans('edit');
								//	$out.= '</a>';
								//$out.= '</div>';
								$out.= '<div class="ui-block-b">';
									$out.= '<a id="green_yes" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #065c00;color: white;text-shadow: none;border: 1px solid black;">';
										$out.= $langs->trans('Yes');
									$out.= '</a>';
								$out.= '</div>';

							$out.= '</div><!-- /grid-a -->';
						$out.= '</div>';
					$out.= '</div>';

					$out.= '<div id="symbol" style="display:none;"><img src="icon.png" alt="symbol" class="symbol" style="height:30px;position:relative;margin-top:-55px;margin-left:5px;"></img></div>';

					$out.= '<div id="timer" style="display:none;position:relative;top:-67px;left:41px;color:white;font-size:16px;font-family:monospace;text-decoration:none;"></div>';

					$out.= '<div data-role="popup" id="CAP_Send_popupDialog" data-overlay-theme="a" data-theme="a" data-dismissible="false" style="max-width:400px;">';
						$out.= '<div data-role="header" id="CAP_Send_popupDialog_header" data-theme="a">';
							$out.= '<h2>'.$langs->trans('Send Caps').'</h2>';
						$out.= '</div>';
						$out.= '<div role="main" id="CAP_Send_popupDialog_main" class="ui-content">';
							$out.= $langs->trans('Send Caps to Meteoalarm?');
							// Dissmis or OK
							$out.= '<div class="ui-grid-a">';
								$out.= '<div class="ui-block-a">';
									$out.= '<a id="send_no" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #ff3f3f;color: black;text-shadow: none;border: 1px solid black;">';
										$out.= $langs->trans('No');
									$out.= '</a>';
								$out.= '</div>';
								$out.= '<div class="ui-block-b">';
									$out.= '<a id="send_yes" href="" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-btn-a" style="background-color: #065c00;color: white;text-shadow: none;border: 1px solid black;">';
										$out.= $langs->trans('Yes');
									$out.= '</a>';
								$out.= '</div>';
							$out.= '</div><!-- /grid-a -->';
						$out.= '</div>';
					$out.= '</div>';





					$out.= '<div data-role="popup" id="CAP_SOAP_popupDialog" data-overlay-theme="a" data-theme="a" data-dismissible="true" style="max-width:560px;">';
						$out.= '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>';
						$out.= '<div data-role="header" id="CAP_SOAP_popupDialog_header" data-theme="a">';
							if(!empty($plugin->name)) $out.= '<h2>'.$langs->trans('ProducedCaps').'</h2>';
							else $out.= '<h2>'.$langs->trans('Success!').'</h2>';
						$out.= '</div>';
						$out.= '<div role="main" id="CAP_SOAP_popupDialog_main" class="ui-content" style="max-height: 500px; overflow: auto;">';
						$out.= '<p class="success_message" style="text-align:center;border:5px solid green;padding:10px 10px 10px 10px;font-size:18px;">Your warnings were successfully sent to Meteoalarm</p>';
							$out.= '<ul data-role="listview" data-inset="true" data-shadow="false" id="SOAPUL">';
							$out.= '</ul>';
						$out.= '</div>';
					$out.= '</div>';

					$out.= '<div data-role="popup" id="Error_popupDialog" data-overlay-theme="a" data-theme="a" data-dismissible="true" style="max-width:400px;">';
						$out.= '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>';
						$out.= '<div data-role="header" id="CAP_SOAP_popupDialog_header" data-theme="a">';
							$out.= '<h2 style="background-color: red;">'.$langs->trans('Error').'</h2>';
						$out.= '</div>';
						$out.= '<div role="main" id="CAP_SOAP_popupDialog_main" class="ui-content" style="max-height: 500px; overflow: auto;">';
							$out.= $langs->trans('err_cap_not_complete01');
						$out.= '</div>';
					$out.= '</div>';

/*
					$out.= '<div data-role="popup" id="MeteoalarmCalc_popupDialog" data-overlay-theme="a" data-theme="a" data-dismissible="false" style="max-width:400px;">';
						$out.= '<div data-role="header" id="MeteoalarmCalc_popupDialog_header" data-theme="a">';
							$out.= '<h2 style="color: yellow;">'.$langs->trans('Meteoalarm Soap').'</h2>';
						$out.= '</div>';
*/
/*
						$out.= '<div role="main" id="MeteoalarmCalc_popupDialog_main" class="ui-content" style="max-height: 500px; overflow: auto;">';
							$out.= $langs->trans('MeteoalarmCalc');
						$out.= '</div>';
*/
					$out.= '</div>';
					break;

				case 'CapButton':
					// TODO SELECT FOR TEMPLATE HERE
					$out = '</li></ul></div>'; // exit li
					$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
					$out.= '<div data-role="listview" class="ui-grid-a" style="height: 200px;margin: 0px 0px 0px 0px;">';
						$out.= '<div class="ui-block-a" style="padding-right: 7.5px;">';
							$out.= '<a href="#alert" style="text-decoration: none;"><div class="ui-btn ui-input-btn ui-btn-b ui-corner-all ui-shadow" style="height: 85px;padding-top: 85px;">';
								$out.=  $langs->trans("NewCap");
							$out.= '</div></a>';
						$out.= '</div>';
						$out.= '<div class="ui-block-b" style="padding-left: 7.5px;">';
							$out.= '<a href="index.php?read=1#alert" data-ajax="false" style="text-decoration: none;"><div class="ui-btn ui-input-btn ui-btn-b ui-corner-all ui-shadow" style="height: 85px;padding-top: 85px;">';
								$out.=  $langs->trans("ReadCap");
							$out.= '</div></a>';
						$out.= '</div>';
					$out.= '</div>';
					$out.= '<ul data-role="listview" style="margin-top: 7.5px;"><li>'; // enter li
						$out.= '<a href="#conf" >'.$langs->trans("TitleConfig").'</a>';
					$out.= '</li><li>';
					break;

				case 'identifier':

					if(!empty($this->identifier[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);

					$out = '<div id="Identapend">';
						$out.= '<label>'.$langs->trans("LabelIdentifier").': '.$this->tooltip($type, $langs->trans("LabelIdentifierDesc")).'</label>';
						if($this->identifier[0] == "" | !is_array($this->identifier))
						{
							$status_theme_wm = $status_theme;
							$status_theme_is = $status_theme;
							$status_theme_ti = $status_theme;
							$status_theme_id = $status_theme;

							if(!empty($configuration->conf["identifier"]["WMO_OID"])) 	$status_theme_wm = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							if(!empty($configuration->conf["identifier"]["ISO"])) 			$status_theme_is = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							if(!empty($configuration->conf["identifier"]["ID_ID"])) 		$status_theme_ti = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							if($configuration->conf["identifier"]["time_on"] == true) $status_theme_id = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);

							$out.= '<div class="ui-grid-c">';
								if(!empty($configuration->conf["identifier"]["WMO_OID"]))	$out.= '<div class="ui-block-a" style="width: 200px;"><input placeholder="WMO OID" '.$status_theme_wm.' type="text" maxlength="22" name="identifier[WMO]"  value="'.$configuration->conf["identifier"]["WMO_OID"].'"></div>';
								if(!empty($configuration->conf["identifier"]["ISO"]))			$out.= '<div class="ui-block-b" style="width: 45px;"><input '.$status_theme_is.' placeholder="ISO" type="text" maxlength="4" name="identifier[ISO]"  value="'.$configuration->conf["identifier"]["ISO"].'"></div>';
								if($configuration->conf["identifier"]["time_on"] == true) $out.= '<div class="ui-block-c" style="width: 160px;"><input '.$status_theme_ti.' placeholder="YYMMDDHHMMSS" type="text" maxlength="14" name="identifier[time]" value="'.date('ymdHis').'"></div>'; // YYMMDDHHMMSS
								if(!empty($configuration->conf["identifier"]["ID_ID"]))	 	$out.= '<div class="ui-block-d" style="width: 200px;"><input '.$status_theme_id.' placeholder="Warning ID" type="text" maxlength="22" name="identifier[ID]" value="'.$configuration->conf["identifier"]["ID_ID"].'"></div>';
								if(empty($configuration->conf["identifier"]["ID_ID"])) 		$out.= '<div class="ui-block-d"><input '.$status_theme.' placeholder="Warning ID" type="text" name="identifier[ID]"  value="'.$this->identifier[0].'"></div>';
							$out.= '</div>';
						}
						else
						{
							$out.= '<input '.$status_theme.' placeholder="" type="text" name="identifier[ID]"  value="'.$this->identifier[0].'">';
						}
					$out.= '</div>';
					break;

				case 'sender':
					if(!empty($this->sender[0])) $status_theme = 'data-theme="f"';
					$out = '<legend>'.$langs->trans("Labelsender").': '.$this->tooltip($type, $langs->trans("LabelsenderDesc")).'</legend>';
					$out.= '<input '.$status_theme.' placeholder="sender" type="text" name="sender" value="'.$this->sender[0].'">';
					break;

				case 'sent':
					if(!empty($this->sent[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if($this->sent[0]) $st = $this->make_cap_time($this->sent[0]);
					$out = '<div id="Sentapend">';
						$out.= '<label>'.$langs->trans("LabelSent").': '.$this->tooltip($type, $langs->trans("LabelSentDesc")).'</label>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input '.$status_theme.' type="text" name="sent[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input '.$status_theme.'  type="text" name="sent[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 24px;">';
								$out.= '<input type="text" max-size="1" value="+" name="sent[plus]" style="height: 37px;">';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="sent[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
						$out.= '<span style="font-size: 10px;color: #8d8d8d;">'.$timezone_name.'</span>';
					$out.= '</div>';
					break;

				case 'references':
					if(!empty($this->references[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$out = '<legend>'.$langs->trans("Labelreferences").': '.$this->tooltip($type, $langs->trans("LabelreferencesDesc")).'</legend>';
					$out.= '<input '.$status_theme.'  placeholder="references" type="text" name="references" value="'.$this->references[0].'">'; // web / identifier / sent
					break;

				case 'status':
					$status_theme_st = $status_theme;
					$status_theme_ms = $this->GetTypeStatusFromArray($status_arr['msgType']);
					$status_theme_sc = $this->GetTypeStatusFromArray($status_arr['scope']);

					if(!empty($this->status[0])) $status_theme_st = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if(!empty($this->msgType[0])) $status_theme_ms = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['msgType'], 1);
					if(!empty($this->scope[0])) $status_theme_sc = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['scope'], 1);
					// Actual / Test / Exercise / System / Test / Draft
					$status = $this->buildSelect("status", array( "Actual" => "Actual", "Test" => "Test", "Exercise" => "Exercise", "System" => "System", "Test" => "Test", "Draft" => "Draft" ), " ".$status_theme_st." data-native-menu=\"false\"", "Status", $this->status[0]);

					// Alert / Update / Cancel / Ack / Error
					$msgType = $this->buildSelect("msgType", array( "Alert" => "Alert", "Update" => "Update", "Cancel" => "Cancel", "Ack" => "Ack", "Error" => "Error" ), " ".$status_theme_ms." data-native-menu=\"false\" id=\"msgType\"", "MsgType", $this->msgType[0]);

					// Public / Restricted / Private
					$scope = $this->buildSelect("scope", array( "Public" => "Public", "Restricted" => "Restricted", "Private" => "Private" ), " ".$status_theme_sc." data-native-menu=\"false\"", "Scope", $this->scope[0]);

						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="TypeMessage">';
							$out.= '<legend>'.$langs->trans("LabelSetTheTypesOfTheMessage").': '.$this->tooltip($type, $langs->trans("LabelSetTheTypesOfTheMessageDesc")).'</legend>';
								$out.= $status;
								$out.= $msgType;
								$out.= $scope;
						$out.= '</fieldset>';

					if(!empty($this->references[0]))
					{
						$out.= '<li id="LIreferences" class="ui-li-static ui-body-inherit ui-last-child">';
							//$out.= '<div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset">';
								$out.= '<input placeholder="references" type="text" name="references" value="'.$this->references[0].'">';
							//$out.= '</div>';
						$out.= '</li>';
					}
				break;

				case 'category':
					$status_theme_ca = $status_theme;
					$status_theme_re = $this->GetTypeStatusFromArray($status_arr['responseType']);
					if(!empty($this->category[0])) $status_theme_ca = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['category'], 1);
					if(!empty($this->responseType[0])) $status_theme_re = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['responseType'], 1);
					// Geo / Met / Safety / Security / Rescue / Fire / Health / Env / Transport / Infra / CBRNE / Other
					$category = $this->buildSelect("category", array( "Geo" => $langs->trans("Geo"), "Met" => $langs->trans("Met"), "Safety" => $langs->trans("Safety"), "Security" => $langs->trans("Security"), "Rescue" => $langs->trans("Rescue"), "Fire" => $langs->trans("Fire"), "Health" => $langs->trans("Health"), "Env" => $langs->trans("Env"), "Transport" => $langs->trans("Transport"), "Infra" => $langs->trans("Infra"), "CBRNE" => $langs->trans("CBRNE"), "Other" => $langs->trans("Other") ), " ".$status_theme_ca." data-native-menu=\"false\"", "Category", $this->category[0]);
					// Shelter / Evacuate / Prepare / Execute / Avoid / Monitor / Assess / AllClear / None
					$responseType = $this->buildSelect("responseType", array( "Shelter" => $langs->trans("Shelter"), "Evacuate" => $langs->trans("Evacuate"), "Prepare" => $langs->trans("Prepare"), "Execute" => $langs->trans("Execute"), "Avoid" => $langs->trans("Avoid"), "Monitor" => $langs->trans("Monitor"), "Assess" => $langs->trans("Assess"), "AllClear" => $langs->trans("AllClear"), "None" => $langs->trans("None") ), " ".$status_theme_re." data-native-menu=\"false\"", "Response Type", ($this->responseType[0][0]));

						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
							$out.= '<legend>'.$langs->trans("LabelSetTheHazardType").': '.$this->tooltip($type, $langs->trans("LabelSetTheHazardTypeDesc")).'</legend>';
								$out.= $category;
								$out.= $responseType;
						$out.= '</fieldset>';

				break;

				case 'urgency':
					$status_theme_ur = $status_theme;
					$status_theme_se = $this->GetTypeStatusFromArray($status_arr['severity']);
					$status_theme_ce = $this->GetTypeStatusFromArray($status_arr['certainty']);

					if(!empty($this->urgency[0])) $status_theme_ur = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if(!empty($this->severity[0])) $status_theme_se = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['severity'], 1);
					if(!empty($this->certainty[0])) $status_theme_ce = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['certainty'], 1);
					// Immediate / Expected / Future / Past
					$urgency = $this->buildSelect("urgency", array( "Immediate" => "Immediate", "Expected" => "Expected", "Future" => "Future", "Past" => "Past" ), " ".$status_theme_ur." data-native-menu=\"false\"", "Urgency", $this->urgency[0]);

					// Extreme / Severe / Moderate / Minor / Unknown
					$severity = $this->buildSelect("severity", array( "Minor" => "Minor", "Moderate" => "Moderate", "Severe" => "Severe", "Extreme" => "Extreme", "Unknown" => "Unknown" ), " ".$status_theme_se." data-native-menu=\"false\"", "Severity", $this->severity[0]);

					// Observed / Likely / Possible/ Unlikely / Unknown
					$certainty = $this->buildSelect("certainty", array( "Unlikely" => "Unlikely", "Possible" => "Possible", "Likely" => "Likely", "Observed" => "Observed", "Unknown" => "Unknown" ), " ".$status_theme_ce." data-native-menu=\"false\"", "Certainty", $this->certainty[0]);

						$out = '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">';
							$out.= '<legend>'.$langs->trans("LabelSetThePriorityOfTheMessage").': '.$this->tooltip($type, $langs->trans("LabelSetThePriorityOfTheMessageDesc")).'</legend>';
								$out.= $urgency;
								$out.= $severity;
								$out.= $certainty;
						$out.= '</fieldset>';

				break;

				case 'audience':
					if(!empty($this->audience[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$out = '<legend>'.$langs->trans("Labelaudience").': '.$this->tooltip($type, $langs->trans("LabelaudienceDesc")).'</legend>';
					$out.= '<input '.$status_theme.'  placeholder="audience" type="text" name="audience" value="'.$this->audience[0].'">';
					break;

				case 'eventCode':
					if(!empty($this->eventCode[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$out = '<div id="Eventappend">';
						$out.= '<legend>'.$langs->trans("LabelEventCode").': '.$this->tooltip($type, $langs->trans("LabelEventCodeDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
						if(is_array($this->eventCode[0]))
						{
							foreach($this->eventCode[0] as $key => $eventCode)
							{
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input '.$status_theme.'  placeholder="Valuename" type="text" name="eventCode[valueName][]" value="'.$eventCode['valueName'].'"></div>';
									$out.= '<div class="ui-block-b"><input '.$status_theme.'  placeholder="Value Value" type="text" name="eventCode[value][]" value="'.$eventCode['value'].'"></div>';
								$out.= '</div>';
							}
						}
						$out.= '<div class="ui-block-a"><input '.$status_theme.'  placeholder="Valuename" type="text" name="eventCode[valueName][]"></div>';
						$out.= '<div class="ui-block-b"><input '.$status_theme.'  placeholder="Value" type="text" name="eventCode[value][]"></div>';
						$out.= '<div class="ui-block-c"><input '.$status_theme.'  type="button" onclick="plusEventCodeInput()" value="+"></div>';
						$out.= '</div>';
					$out.= '</div>';
					break;

				case 'effective-Clock':
						$out.= '<div class="input-group clockpicker" data-autoclose="true">';
							$out.= '<input type="text" class="form-control" value="09:30" step="2">';
							$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
								$out.= '<span class="glyphicon glyphicon-time"></span>';
							$out.= '</span>';
						$out.= '</div>';
					break;

				case 'effective':
					if(!empty($this->effective[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if($this->effective[0]) $st = $this->make_cap_time($this->effective[0]);
					$out = '<div id="Effectiveapend">';
						$out.= '<legend>'.$langs->trans("LabelEffective").': '.$this->tooltip($type, $langs->trans("LabelEffectiveDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="effective[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input '.$status_theme.'  type="text" name="effective[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 24px;">';
								$out.= '<input type="text" max-size="1" value="+" name="effective[plus]" style="height: 37px;">';
							$out.= '</div>';
							$out.= '<div class="ui-block-d" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="effective[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
						$out.= '<span style="font-size: 10px;color: #8d8d8d;">'.$timezone_name.'</span>';
					$out.= '</div>';
					break;

				case 'onset':
					if(!empty($this->onset[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if($this->onset[0]) $st = $this->make_cap_time($this->onset[0]);
					$out = '<div id="Onsetapend">';
						$out.= '<legend>'.$langs->trans("LabelOnset").': '.$this->tooltip($type, $langs->trans("LabelOnsetDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="onset[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input '.$status_theme.'  type="text" name="onset[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 24px;">';
								$out.= '<input type="text" max-size="1" value="+" name="onset[plus]" style="height: 37px;">';
							$out.= '</div>';
							$out.= '<div class="ui-block-d" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="onset[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
						$out.= '<span style="font-size: 10px;color: #8d8d8d;">'.$timezone_name.'</span>';
					$out.= '</div>';
					break;

				case 'expires':
					if(!empty($this->expires[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if($this->expires[0]) $st = $this->make_cap_time($this->expires[0]);
					$out = '<div id="Expieresapend">';
						$out.= '<legend>'.$langs->trans("LabelExpires").': '.$this->tooltip($type, $langs->trans("LabelExpiresDesc")).'</legend>';
						$out.= '<div class="ui-grid-b">';
							$out.= '<div class="ui-block-a" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="expires[date]" value="'.$st['date'].'">';
							$out.= '</div>';
							$out.= '<div class="ui-block-b" style="width: 155px;">';
								$out.= '<div class="input-group clockpicker" data-autoclose="true">';
									$out.= '<input '.$status_theme.'  type="text" name="expires[time]" step="1" value="'.$st['time'].'">';
									$out.= '<span class="input-group-addon" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;">';
										$out.= '<span class="glyphicon glyphicon-time"></span>';
									$out.= '</span>';
								$out.= '</div>';
							$out.= '</div>';
							$out.= '<div class="ui-block-c" style="width: 24px;">';
								$out.= '<input type="text" max-size="1" value="+" name="expires[plus]" style="height: 37px;">';
							$out.= '</div>';
							$out.= '<div class="ui-block-d" style="width: 155px;">';
								$out.= '<input '.$status_theme.'  type="text" name="expires[UTC]" value="'.$st['zone'].'">';
							$out.= '</div>'; // <yyyy>-<MM>-T<HH>:<mm>:<ss>+<hour>:<min>
						$out.= '</div>';
						$out.= '<span style="font-size: 10px;color: #8d8d8d;">'.$timezone_name.'</span>';
					$out.= '</div>';
					break;

				case 'senderName':
					if(!empty($this->senderName[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$out = '<legend>'.$langs->trans("LabelsenderName").': '.$this->tooltip($type, $langs->trans("LabelsenderNameDesc")).'</legend>';
					$out.= '<input '.$status_theme.'  placeholder="senderName" type="text" name="senderName" value="'.$this->senderName[0].'">';
					break;

				case 'info':
					$out = 'TEST';
					break;

				case 'lang':
					if(!empty($this->language[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$langs_arr = $this->getlang();

					foreach($langs_arr as $key_l => $val_l)
					{
						if(in_array($key,$this->language)) unset($langs_arr[$key]);
					}

					$lang_S = $this->buildSelect("language_select", $langs_arr, "data-native-menu=\"false\" id=\"language\"", $langs->trans("LabelLanguage"));

					$extralang = '<div data-role="controlgroup" data-type="horizontal">';

					$styleD[true] = "";
					$styleD[false] = "display:none;";
					//if(is_array($this->language)) die(print_r($this->language)); // Array ( [0] => en-GB [1] => de-DE ) 1

					foreach($langs_arr as $key => $langs_val)
					{
						if(in_array($key,$this->language)) $display = true; else $display = false;
						$extralang.= '<a href="#" class="ui-btn Lang_Button" role="button" id="'.$key.'_Button" style="'.$styleD[$display].' border-right: 1px solid #dddddd;">'.$langs_val.' <span id="'.$key.'_Remove_Button" style="color:red; padding-left: 5px;">X</span><input type="hidden" name="language[]" id="'.$key.'_language_input" value=""></a>';
						if($display == true) $extralang.= '<input '.$status_theme.'  type="hidden" value="'.$key.'" name="language[]">';
					}
					$extralang.= '</div>';

					$out = $lang_S;
					$out.= $extralang;
					break;

				case 'event':
					$status_theme_ev = $status_theme;
					$status_theme_he = $this->GetTypeStatusFromArray($status_arr['headline']);
					$status_theme_de = $this->GetTypeStatusFromArray($status_arr['description']);
					$status_theme_in = $this->GetTypeStatusFromArray($status_arr['instruction']);

					if(!empty($this->event[0])) $status_theme_ev = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					if(!empty($this->headline[0])) $status_theme_he = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['headline'], 1);
					if(!empty($this->description[0])) $status_theme_de = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['description'], 1);
					if(!empty($this->instruction[0])) $status_theme_in = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr['instruction'], 1);
					$langs_arr = $this->getlang();
					$extralang = "";
					$styleD[true] = "";
					$styleD[false] = "display:none;";
					$i = 0;

					foreach($langs_arr as $key => $langs_val)
					{

						if(in_array($key,$this->language) && $i < 1) $display = true; else $display = false;

						$extralang.= '<div class="lang_input" id="'.$key.'" style="'.$styleD[$display].'">';

								$extralang.= '<input '.$status_theme_ev.'  placeholder="event" type="text" name="event['.$key.']" value="'.$this->event[$i].'">';

								$extralang.= '<input '.$status_theme_he.'  placeholder="headline" type="text" name="headline['.$key.']" value="'.$this->headline[$i].'">';

								$extralang.= '<textarea '.$status_theme_de.'  placeholder="description" name="description['.$key.']">'.$this->description[$i].'</textarea>';

								$extralang.= '<input '.$status_theme_in.'  placeholder="instruction" type="text" name="instruction['.$key.']" value="'.$this->instruction[$i].'">';

						$extralang.= '</div>';

						$i++;
					}

					$out = $extralang;
					break;

				case 'web':
					if(!empty($this->web[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$out = '<legend>'.$langs->trans("Labelweb").': '.$this->tooltip($type, $langs->trans("LabelwebDesc")).'</legend>';
					$out.= '<input '.$status_theme.'  placeholder="web" type="text" name="web" value="'.$this->web[0].'">';
					break;

				case 'contact':
					if(!empty($this->contact[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$out = '<legend>'.$langs->trans("Labelcontact").': '.$this->tooltip($type, $langs->trans("LabelcontactDesc")).'</legend>';
					$out.= '<input '.$status_theme.'  placeholder="contact" type="text" name="contact" value="'.$this->contact[0].'">';
					break;

				case 'parameter':
					if(!empty($this->parameter[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
					$l_level = array( "Unknown", "Minor", "Moderate", "Severe", "Extreme"  );

					if(is_array($ParameterArray['AWT']))
					foreach($ParameterArray['AWT'] as $key => $area_arr)
					{
						$S_Param_AWT[$area_arr['id'].'&#59; '.$area_arr['hazard_type']] = $area_arr['hazard_type_DESC'];
						$G_Param_AWT[$area_arr['id'].'&#59; '.$area_arr['hazard_type']] = 'awareness_type'; //awareness_type awareness_level
					}

					if(is_array($ParameterArray['AWL']))
					foreach($ParameterArray['AWL'] as $key => $area_arr)
					{
						$S_Param_AWL[$area_arr['id'].'&#59; '.$area_arr['hazard_level'].'&#59; '.$l_level[$area_arr['id']]] = $area_arr['hazard_level'];
						$G_Param_AWL[$area_arr['id'].'&#59; '.$area_arr['hazard_level'].'&#59; '.$l_level[$area_arr['id']]] = 'awareness_level'; //awareness_type awareness_level
						$this->level_color[$area_arr['hazard_level']] = $area_arr['hazard_level_color'];
					}
					//die(print_r($S_Param_AWL));
					if(is_array($S_Param_AWT) && is_array($S_Param_AWT))
					{
						$out.= '<legend>'.$langs->trans("LabelAwarenessTypeWebservice").': '.$this->tooltip($type, $langs->trans("LabelAwarenessTypeWebserviceDesc")).'</legend>';
						$out.= $this->buildSelectValueName('parameter[value][]', 'parameter[valueName][]', 'parameter_awt',$S_Param_AWT, $G_Param_AWT, $this->parameter[0]);

						$out.= '<legend>'.$langs->trans("LabelAwarenessLevelWebservice").': '.$this->tooltip($type, $langs->trans("LabelAwarenessLevelWebserviceDesc")).'</legend>';
						$out.= $this->buildSelectValueName('parameter[value][]', 'parameter[valueName][]', 'parameter_awl',$S_Param_AWL, $G_Param_AWL, $this->parameter[0]);
					}
					else
					{
						$out = '<div id="Parameterappend">';
							$out.= '<legend>'.$langs->trans("LabelParameter").': '.$this->tooltip($type, $langs->trans("LabelParameterDesc")).'</legend>';
							$out.= '<div class="ui-grid-b">';
								foreach($this->parameter[0] as $key => $parameter)
								{
									$out.= '<div class="ui-grid-b">';
										$out.= '<div class="ui-block-a"><input '.$status_theme.'  placeholder="Valuename" type="text" name="parameter[valueName][]" value="'.$parameter['valueName'].'"></div>';
										$out.= '<div class="ui-block-b"><input '.$status_theme.'  placeholder="Value Value" type="text" name="parameter[value][]" value="'.$parameter['value'].'"></div>';
									$out.= '</div>';
								}
								$out.= '<div class="ui-block-a"><input '.$status_theme.'  placeholder="Valuename" type="text" name="parameter[valueName][]"></div>';
								$out.= '<div class="ui-block-b"><input '.$status_theme.'  placeholder="Value" type="text" name="parameter[value][]"></div>';
								$out.= '<div class="ui-block-c"><input '.$status_theme.'  type="button" onclick="plusParameterInput()" value="+"></div>';
							$out.= '</div>';
						$out.= '</div>';
					}
					break;

				/*
				 * Area
				 */
					case 'areaDesc':
						if(!empty($this->areaDesc[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						$out = '<legend>'.$langs->trans("LabelareaDesc").': '.$this->tooltip($type.'tool', $langs->trans("LabelareaDescDesc")).'</legend>';
						$out.= '<input '.$status_theme.'  placeholder="areaDesc" type="text" name="areaDesc" id="areaDesc" value="'.$this->areaDesc[0].'">';
						break;

					case 'polygon':
						if(!empty($this->polygon[0][0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						$out = '<legend>'.$langs->trans("Labelpolygon").': '.$this->tooltip($type.'tool', $langs->trans("LabelpolygonDesc")).'</legend>';
						$out.= '<input '.$status_theme.'  placeholder="polygon" type="text" name="polygon" id="polygon" value="'.$this->polygon[0][0].'">';
						break;

					case 'circle':
						if(!empty($this->circle[0][0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						$out = '<legend>'.$langs->trans("Labelcircle").': '.$this->tooltip($type.'tool', $langs->trans("LabelcircleDesc")).'</legend>';
						$out.= '<input '.$status_theme.'  placeholder="circle" type="text" name="circle" id="circle" value="'.$this->circle[0][0].'">';
						break;

					case 'map':
						// GOOGLE MAP
							if(!empty($configuration->conf["GoogleMap"]["APIkey"]))
								$out = '<div id="map" style="height: 480px;" class="map"></div>';
							//$out.= '<div id="mapinfo" class="mapinfo">';
							//	$out.='<ul data-role="listview">';
							//		$out.='<li>';
							//			$out.='<label for="dragCircle">'.$langs->trans("Labelpolygon").':</label><select name="drawPolygon" id="drawPolygon" data-role="slider" data-theme="b" data-mini="true"><option value="0">Off</option><option value="1">On</option></select>';
							//		$out.='</li>';
							//		$out.='<li>';
							//			$out.='<label for="dragCircle">'.$langs->trans("Labelcircle").':</label><select name="dragCircle" id="dragCircle" data-role="slider" data-theme="b" data-mini="true"><option value="0">Off</option><option value="1">On</option></select>';
							//		$out.='</li>';
							//	$out.='</ul>';
							//$out.= '</div>';
						break;

					case 'geocode':
						if(!empty($this->geocode[0])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						// $out.= $AreaCodesArray;
						foreach($AreaCodesArray as $key => $area_arr)
						{
							if(!empty($area_arr['geotype']) && $area_arr['geotype'] == $this->geocod[0]['valueName'])
							{
								$S_Area[$area_arr['geocode']] = $area_arr['AreaCaption'];
								$G_Area[$area_arr['geocode']] = $area_arr['geotype'];
							}
							else
							{
								// $S_Area[$area_arr['EMMA_ID']] = $area_arr['AreaCaption'];
								// $G_Area[$area_arr['EMMA_ID']] = "EMMA_ID";
							}
						}

						if(is_array($S_Area))
						{
							$out = '<legend>'.$langs->trans("LabelGeocodeWebservice").': '.$this->tooltip($type, $langs->trans("LabelGeocodeWebserviceDesc")).'</legend>';
							$out.= $this->buildSelectValueName('geocode[value][]', 'geocode[valueName][]', 'geocode',$S_Area, $G_Area, $this->geocode[0]);
							foreach($this->geocode[0] as $key => $geocode)
							{
								$out.= '<input type="text" name="geocode[value][]" value="'.$geocode['value'].'<|>'.$geocode['valueName'].'">';
							}
						}
						else
						{
							$out = '<div id="Geocodeappend">';
								$out.= '<legend>'.$langs->trans("LabelGeocode").': '.$this->tooltip($type, $langs->trans("LabelGeocodeDesc")).'</legend>';
								foreach($this->geocode[0] as $key => $geocode)
								{
									$out.= '<div class="ui-grid-b">';
										$out.= '<div class="ui-block-a"><input '.$status_theme.'  placeholder="Valuename" type="text" name="geocode[valueName][]" value="'.$geocode['valueName'].'"></div>';
										$out.= '<div class="ui-block-b"><input '.$status_theme.'  placeholder="geocode Value" type="text" name="geocode[value][]" value="'.$geocode['value'].'"></div>';
									$out.= '</div>';
								}
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input '.$status_theme.'  placeholder="Valuename" type="text" name="geocode[valueName][]"></div>';
									$out.= '<div class="ui-block-b"><input '.$status_theme.' placeholder="Value" type="text" name="geocode[value][]"></div>';
									$out.= '<div class="ui-block-c"><input '.$status_theme.' type="button" onclick="plusGeocodeInput()" value="+"></div>';
								$out.= '</div>';
							$out.= '</div>';
						}
						break;

					/*
					 * Conf input => [conf]
					 */

					case 'cap_save':
						if(!empty($configuration->conf["cap"]["save"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						if($configuration->conf["cap"]["save"] == 1) $onoroff = 'checked=""';
						else $onoroff = '';
						//$out = '<label for="identifier_time">'.$langs->trans("LabelSaveCapsInOutputFolder").':</label>';
						$out = '<legend>'.$langs->trans("LabelSaveCapsInOutputFolder").': '.$this->tooltip($type.'tool', $langs->trans("LabelSaveCapsInOutputFolderDesc")).'</legend>';
						$out.= '<input '.$status_theme.' type="checkbox" data-role="flipswitch" name="conf[cap][save]" id="cap_save" '.$onoroff.' data-theme="b">';
						break;

					case 'cap_output':
							if(!empty($configuration->conf["cap"]["output"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							$out = '<legend>'.$langs->trans("Labelcap_output").': '.$this->tooltip($type.'tool', $langs->trans("Labelcap_outputDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" placeholder="Cap Output" name="conf[cap][output]" value="'.$configuration->conf["cap"]["output"].'">';
						break;

					case 'ID_ID':
							if(!empty($configuration->conf["identifier"]["ID_ID"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							$out = '<legend>'.$langs->trans("LabelIdentifierNumber").': '.$this->tooltip($type.'tool', $langs->trans("LabelIdentifierNumberDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="number" placeholder="Identifier Number" name="conf[identifier][ID_ID]" value="'.$configuration->conf["identifier"]["ID_ID"].'">';
						break;

					case 'WMO_OID':
							if(!empty($configuration->conf["identifier"]["WMO_OID"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							$out = '<legend>'.$langs->trans("LabelWMO_OID").': '.$this->tooltip($type.'tool', $langs->trans("LabelWMO_OIDDesc")).'</legend>';
							$out.= '<input '.$status_theme.'  type="text" placeholder="WMO OID" name="conf[identifier][WMO_OID]" value="'.$configuration->conf["identifier"]["WMO_OID"].'">';
						break;

					case 'ISO':
						if(!empty($configuration->conf["identifier"]["ISO"])) $status_theme = 'data-theme="f"';
						$out = '<legend>'.$langs->trans("LabelISO").': '.$this->tooltip($type.'tool', $langs->trans("LabelISODesc")).'</legend>';
						$out.= '<input '.$status_theme.' type="text" maxsize="2" placeholder="ISO" name="conf[identifier][ISO]" value="'.$configuration->conf["identifier"]["ISO"].'">';
						break;

					case 'GoogleMapAPIkey':
						if(!empty($configuration->conf["GoogleMap"]["APIkey"])) $status_theme = 'data-theme="f"';
						$out = '<legend>Google Maps API key: '.$this->tooltip($type.'tool', $langs->trans("LabelGoogleMapsAPIkeyDesc")).'</legend>';
						$out.= '<input '.$status_theme.' type="text" maxsize="2" placeholder="Google Map API key" name="conf[GoogleMap][APIkey]" value="'.$configuration->conf["GoogleMap"]["APIkey"].'">';
						break;

					case 'CAPValidatorUrl':
							if(!empty($configuration->conf["Validator"]["url"])) $status_theme = 'data-theme="f"';
							$out = '<legend>'.$langs->trans("LabelCAPValidatorUrl").': '.$this->tooltip($type.'tool', $langs->trans("LabelCAPValidatorUrlDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" maxsize="2" placeholder="'.$langs->trans("Url").'" name="conf[Validator][url]" value="'.$configuration->conf["Validator"]["url"].'">';
							break;

					case 'timezone':
							$timezone = $this->timezone_list();
							$aktive_timezone = date_default_timezone_get();
							$out = '<legend>'.$langs->trans("LabelTimezone").': '.$this->tooltip($type.'tool', $langs->trans("LabelTimezoneDesc")).'</legend>';
							$out.= $this->buildSelect('conf[timezone]', $timezone, "data-native-menu=\"false\"", "timezone", $aktive_timezone );
						break;

					case 'identifier_time':
						if(!empty($configuration->conf["identifier"]["time_on"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						if($configuration->conf["identifier"]["time_on"] == 1) $onoroff = 'checked=""';
						else $onoroff = '';
						$out = '<legend>'.$langs->trans("LabelAutomaticIdentifierTime").': '.$this->tooltip($type.'tool', $langs->trans("LabelAutomaticIdentifierTimeDesc")).'</legend>';
						//$out = '<label for="identifier_time">'.$langs->trans("LabelAutomaticIdentifierTime").':</label>';
						$out.= '<input '.$status_theme.' type="checkbox" data-role="flipswitch" name="conf[identifier][time][on]" id="identifier_time" '.$onoroff.' data-theme="b">';
						break;

					case 'template':
						if(file_exists('conf/template.cap')) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);

						foreach(scandir($configuration->conf["cap"]["output"]) as $num => $capfilename)
						{
							if($capfilename != '.' && $capfilename != '..' && $capfilename != '.cap' && $capfilename != '.conv.cap')
							{
								$files[$capfilename] = $capfilename;
							}
						}

						$out = '<legend>'.$langs->trans("LabelTemplate").': '.$this->tooltip($type.'tool', $langs->trans("LabelTemplateDesc")).'</legend>';
						if(file_exists('conf/template.cap')) $onoroff = 'checked=""'; else $onoroff = '';


							if(file_exists('conf/template.cap'))
							{
								require_once 'lib/cap.read.class.php';
								$alert = new alert('conf/template.cap');
								$template = $alert->output();

								$files['template.cap'] = 'template.cap';
							}

							if(count($files) < 1) $cssdisable = 'disabled="disabled"';
							else $cssdisable = '';

							$out.= '<input '.$status_theme.' type="checkbox" data-role="flipswitch" name="template_on" id="template_on" '.$onoroff.' data-theme="b" '.$cssdisable.'>';
							if(count($files) > 0) $out.=  $this->buildSelect("Template", $files, "data-native-menu=\"false\"", "Template", 'template.cap' );

						break;

					case 'lang_conf':
						if(!empty($configuration->conf["selected_language"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
						//$out = '<label for="lang_conf">'.$langs->trans("LabelUsableLanguages").':</label>';
						$out = '<legend>'.$langs->trans("LabelUsableLanguages").': '.$this->tooltip($type.'tool', $langs->trans("LabelUsableLanguagesDesc")).'</legend>';
						$out.= '<select name="conf[select][lang][]" id="lang_conf" data-native-menu="false" multiple="multiple" data-iconpos="left">';

						foreach($configuration->conf["language"] as $key => $lang_name)
						{
							if($configuration->conf["selected_language"][$key] == false)
							{
								$out.= '<option value="'.$key.'">'.$lang_name.'</option>';
							}
							else
							{
								$out.= '<option value="'.$key.'" selected="selected">'.$lang_name.'</option>';
							}
						}

						$out.= '</select>';
						break;

					case 'lang_conf_use':
							if(!empty($configuration->conf["translation"])) $status_theme = 'data-theme="f" '.$this->GetTypeStatusFromArray($status_arr[$type], 1);
							//$out = '<label for="lang_conf_use">'.$langs->trans("Labellang_conf_use").':</label>';
							$out = '<legend>'.$langs->trans("Labellang_conf_use").': '.$this->tooltip($type.'tool', $langs->trans("Labellang_conf_useDesc")).'</legend>';
							$out.= '<select name="conf[user][lang]" id="lang_conf_use" data-native-menu="false" data-iconpos="left">';
							foreach($configuration->conf["translation"] as $key => $lang_name)
							{
								if($configuration->conf["user"]["language"] != $key)
								{
									$out.= '<option value="'.$key.'">'.$lang_name.'</option>';
								}
								else
								{
									$out.= '<option value="'.$key.'" selected="selected">'.$lang_name.'</option>';
								}
							}

							$out.= '</select>';
						break;

					case 'lang_conf_plus':
							$out = '<div id="LangAappend">';
								//$out.='<label for="sent[date]">'.$langs->trans("LabelAddLanguage").': </label>';
								$out.= '<legend>'.$langs->trans("Labellang_conf_plus").': '.$this->tooltip($type.'tool', $langs->trans("Labellang_conf_plusDesc")).'</legend>';
								$out.= '<div class="ui-grid-b">';
									$out.= '<div class="ui-block-a"><input '.$status_theme.' type="text" maxsize="5" placeholder="RFC 3066" name="conf[lang][key]" id="lang_conf_plus_key"></div>';
									$out.= '<div class="ui-block-b"><input '.$status_theme.' type="text" name="conf[lang][name]" placeholder="Name" id="lang_conf_plus_name"></div>';
									$out.= '<div class="ui-block-c" style="width: 54px;"><input '.$status_theme.' type="button" onclick="plusLangInput()" value="+" data-theme="b"></div>';
									$out.= '</div>';
							$out.= '</div>';
						break;

					case 'lang_conf_remove':
							$out = '<div id="LangRappend">';
								//$out.='<label for="sent[date]">'.$langs->trans("LabelRemoveLanguage").': </label>';
								$out.= '<legend>'.$langs->trans("Labellang_conf_remove").': '.$this->tooltip($type.'tool', $langs->trans("Labellang_conf_removeDesc")).'</legend>';
								$out.= '<div class="ui-grid-a">';
									$out.= '<div class="ui-block-a">';
										$out.= '<select id="lang_remove" data-native-menu="false" data-iconpos="left">';
										foreach($configuration->conf["language"] as $key => $lang_name)
										{
											$out.= '<option value="'.$key.'">'.$lang_name.'</option>';
										}
										$out.= '</select>';
									$out.= '</div>';
									$out.= '<div class="ui-block-b" style="width: 54px;">';
										$out.= '<input '.$status_theme.' type="button" onclick="minusLangInput()" value="-" id="lang_remove_input_button" data-theme="b">';
										$out.= '<input '.$status_theme.' type="hidden" id="lang_remove_input" value="remove">';
									$out.= '</div>';
								$out.= '</div>';
							$out.= '</div>';
						break;

					case 'webservice_on':
							if($configuration->conf["webservice"]["service_on"] == 1) $onoroff = 'checked=""';
							else $onoroff = '';
							//$out = '<label for="webservice_switch">'.$langs->trans("Webservice").':</label>';							
							$out = '<legend>'.$langs->trans("LabelWebservice1").': '.$this->tooltip($type.'tool', $langs->trans("LabelWebserviceDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="checkbox" data-role="flipswitch" name="conf[webservice][on]" id="webservice_switch" '.$onoroff.' data-theme="b">';
							$out.= '<br>';
							$out.= '<legend>'.$langs->trans("LabelWebservice2").': '.$this->tooltip($type.'tool', $langs->trans("LabelWebserviceDesc")).'</legend>';
							$out.= '<input type="checkbox" data-role="flipswitch" name="conf[webservice][on]" id="webservice_2_switch">';
							$out.= '<br>';
							$out.= '<p style="font-size:16px;">'.$langs->trans("UserInfoLoginPassword").'</p>';
							break;

					case 'webservice_password':
							$out = '<legend>'.$langs->trans("Labelwebservice_password").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_passwordDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][password]" value="'.$configuration->conf["webservice"]["password"].'">';
						break;

					case 'webservice_securitykey':
							$out = '<legend>'.$langs->trans("Labelwebservice_securitykey").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_securitykeyDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][securitykey]" value="'.$configuration->conf["webservice"]["securitykey"].'">';
						break;

					case 'webservice_sourceapplication':
							$out = '<legend>'.$langs->trans("Labelwebservice_sourceapplication").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_sourceapplicationDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][sourceapplication]" value="'.$configuration->conf["webservice"]["sourceapplication"].'">';
						break;

					case 'webservice_login':
							$out = '<legend>'.$langs->trans("Labelwebservice_login").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_loginDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][login]" value="'.$configuration->conf["webservice"]["login"].'">';
						break;

					case 'webservice_entity':
							$out = '<legend>'.$langs->trans("Labelwebservice_entity").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_entityDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][entity]" value="'.$configuration->conf["webservice"]["entity"].'">';
						break;

					case 'webservice_WS_METHOD':
							$out = '<legend>'.$langs->trans("webservice_WS_METHOD").': '.$this->tooltip($type.'tool', $langs->trans("webservice_WS_METHODDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][WS_METHOD]" value="'.$configuration->conf["webservice"]["WS_METHOD"].'">';
						break;

					case 'webservice_ns':
							$out = '<legend>'.$langs->trans("Labelwebservice_ns").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_nsDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][ns]" value="'.$configuration->conf["webservice"]["ns"].'">';
						break;

					case 'webservice_WS_DOL_URL':
							$out = '<legend>'.$langs->trans("Labelwebservice_WS_DOL_URL").': '.$this->tooltip($type.'tool', $langs->trans("Labelwebservice_WS_DOL_URLDesc")).'</legend>';
							$out.= '<input '.$status_theme.' type="text" name="conf[webservice][WS_DOL_URL]" value="'.$configuration->conf["webservice"]["WS_DOL_URL"].'">';
						break;

					case 'proxy_conf':
							if($configuration->conf["proxy"]["proxyOn"] == 1) $onoroff = 'checked=""';
							else $onoroff = '';
							
							$out.= '<div data-role="collapsible" id="conf-proxy-detail" data-theme="b" data-content-theme="a">';
								$out.= '<h2>'.$langs->trans("ProxyConfiguration").'</h2>';
								$out.= '<ul data-role="listview">';

									// switch On
									$out.= '<li id="proxy_switchDIV" class="ui-field-contain">';
										$out.= '<legend>'.$langs->trans("LabelProxy").': '.$this->tooltip($type.'tool', $langs->trans("LabelProxyDesc")).'</legend>';
										$out.= '<input '.$status_theme.' type="checkbox" data-role="flipswitch" name="conf[proxy][proxyOn]" id="proxy_switch" '.$onoroff.' data-theme="b">';
									$out.= '</li>';

									// IP
									$out.= '<li id="proxyIPDIV" class="ui-field-contain ProxyInput">';
										$out.= '<legend>'.$langs->trans("LabelProxy_ip").': '.$this->tooltip($type.'IPtool', $langs->trans("LabelProxy_ipDesc")).'</legend>';
										$out.= '<input '.$status_theme.' type="text" name="conf[proxy][proxyIP]" value="'.$configuration->conf["proxy"]["proxyIP"].'">';
									$out.= '</li>';

									// Port
									$out.= '<li id="proxyPortDIV" class="ui-field-contain ProxyInput">';
										$out.= '<legend>'.$langs->trans("LabelProxy_port").': '.$this->tooltip($type.'Porttool', $langs->trans("LabelProxy_portDesc")).'</legend>';
										$out.= '<input '.$status_theme.' type="text" name="conf[proxy][proxyPort]" value="'.$configuration->conf["proxy"]["proxyPort"].'">';
									$out.= '</li>';	

									// UserName
									$out.= '<li id="proxyUserNameDIV" class="ui-field-contain ProxyInput">';
										$out.= '<legend>'.$langs->trans("LabelProxy_username").': '.$this->tooltip($type.'UserNametool', $langs->trans("LabelProxy_usernameDesc")).'</legend>';
										$out.= '<input '.$status_theme.' type="text" name="conf[proxy][proxyUserName]" value="'.$configuration->conf["proxy"]["proxyUserName"].'">';
									$out.= '</li>';	

									// UserPass
									$out.= '<li id="proxyUserPassDIV" class="ui-field-contain ProxyInput">';
											$out.= '<legend>'.$langs->trans("LabelProxy_password").': '.$this->tooltip($type.'UserPasstool', $langs->trans("LabelProxy_passwordDesc")).'</legend>';
											$out.= '<input '.$status_theme.' type="password" name="conf[proxy][proxyUserPass]" value="'.$configuration->conf["proxy"]["proxyUserPass"].'">';
									$out.= '</li>';

								$out.= '</ul>';
							$out.= '</div>'; // DETAILS
							
						break;

					case 'capview':
							$out = '<textarea id="capviewtextarea" readonly name="capeditfield"></textarea>';
							$out.= '<input type="button" value="edit" onclick="$(\'#capviewtextarea\').prop(\'readonly\', \'\'); $(\'#capedit\').val(true)">';
							$out.= '<input type="hidden" name="capedit" id="capedit" value="false">';
							$out.= '<input type="button" value="validate" onclick="validateCap()">';
							$out.= '</li><li id="resultValidate" style="text-shadow: none;"></li>';
							if($configuration->conf["webservice_aktive"] == 1) $out.= '<input type="hidden" name="webservice_aktive" id="webservice_aktive" value="1">';
						break;

					case 'caplist':
						$out = '</form><form method="POST" id="capform2" name="capform2" action="index.php?read=1" enctype="multipart/form-data" data-ajax="false">';
						$out.= '<input type="file" name="uploadfile" id="uploadfile"><input type="submit" value="'.$langs->trans('LabelUpload').'" name="upload" data-ajax="false">';

						$out.= '<fieldset data-role="controlgroup">';
								foreach(scandir($configuration->conf["cap"]["output"]) as $num => $capfilename)
								{
									if($num > 1)
									{
										$out.= '<div class="ui-grid-a">';
											$out.= '<div class="ui-block-a" style="width:90%">';
												$out.= '<input type="radio" name="location" id="cap_file_'.$num.'" value="'.urlencode($capfilename).'">';
												$out.= '<label for="cap_file_'.$num.'">'.$capfilename.' <span style="font-size: 12px;color: #5A5A5A;">('.filesize($configuration->conf["cap"]["output"].'/'.$capfilename).'b | '.date('d.m.Y H:i:s',filectime($configuration->conf["cap"]["output"].'/'.$capfilename)).')</span> </label>';
											$out.= '</div>';
											$out.= '<div class="ui-block-b" style="width:10%">';
												$out.= '<a href="#cap_file_'.$num.'_delete" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini"><span style="color:#ff0000">X</span></a>';
											$out.= '</div>';
										$out.= '</div>';

										$out.= '<div data-role="popup" id="cap_file_'.$num.'_delete" data-theme="a" data-overlay-theme="b" class="ui-content" style="max-width:340px; padding-bottom:2em;">';
											$out.= '<h3>Delete File?</h3>';
											$out.= '<a href="index.php?delete='.urlencode($capfilename).'" data-ajax="false" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini">Delete</a>';
											$out.= '<a href="#" data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini">Cancel</a>';
										$out.= '</div>';
									}
								}
						$out.= '</fieldset>';

						$out.= '<input type="submit" value="<h1>'.$langs->trans("Read").'</h1>" data-ajax="false">';
						$out.= '</form><form method="POST" id="capform" name="capform" action="index.php" enctype="multipart/form-data" data-ajax="false">';
						break;

					case 'login_popup':
						if($configuration->conf["webservice"]["service_on"] == 1)
						{
							$this->login_id++;

							if($configuration->conf["webservice_aktive"] == 1) // Logout
							{
								$out.= '<ul data-role="listview" data-divider-theme="b">';
									$out.= '<li>'.$langs->trans("Service").': '.$_SESSION['ServiceHost'].'</li>';
									$out.= '<li>'.$langs->trans("User").': '.$configuration->conf["webservice"]["login"].'</li>';
									$out.= '<li>'.$langs->trans("LoginDate").': '.date('d.m.Y H:i:s', $_SESSION['timestamp']).'</li>';
									$out.= '<li><input '.$status_theme.' type="submit" name="send-logout['.$this->login_id.']" value="'.$langs->trans('Logout').'" data-theme="b"></li>';
									$out.= '<input type="hidden" class="login_sended" name="login_sended" id="logout_sended" value="0">';
								$out.= '</ul>';
							}
							else // Login
							{
								$out = '<h3>'.$langs->trans("LoginToYourWebservice").'</h3>';

								if(!empty($login_error)){
									foreach ($login_error as $key => $msg) {
										$out.= "<textarea readonly>".$msg."</textarea><br>";
									}
								}
								if(!empty($login_error_html)){
									foreach ($login_error_html as $key => $msg) {
										$out.= "".$msg."<br>";
									}
								}

								if($login_to_webservice_faild == true && empty($login_error) && empty($login_error_html))
								{
									$out.= $langs->trans('error_wrong_login');
								}

								$out.= '<label for="un" class="ui-hidden-accessible">'.$langs->trans("Labelwebservice_login").':</label>';
									$out.= '<input '.$status_theme.' type="text" name="Session_login_name['.$this->login_id.']" value="'.$configuration->conf["webservice"]["login"].'">';

								$out.= '<label for="pw" class="ui-hidden-accessible">'.$langs->trans("Labelwebservice_password").':</label>';
									$out.= '<input '.$status_theme.' type="password" name="Session_login_pass['.$this->login_id.']" value="'.$configuration->conf["webservice"]["password"].'">';

								$out.= '<label><input '.$status_theme.' type="checkbox" name="savepass[]">'.$langs->trans("SaveWebservicePass").'</label>';
								$out.= '<input id="submit_login_button" '.$status_theme.' type="submit" name="send-login['.$this->login_id.']" value="'.$langs->trans('Login').'" data-theme="b">';
								$out.= '<input type="hidden" class="login_sended" name="login_sended" id="login_sended" value="0">';
							}

							if((empty($configuration->conf["webservice_aktive"]) || $configuration->conf["webservice_aktive"] == -1) && $configuration->conf["webservice"]["service_on"] == 1 && $this->login_id == 1)
							{
							$out.= 			'
													<script>
													$( document ).ready(function(){
														$(".Login input").on("keyup",function(event){
															event.preventDefault();
															if ( event.which == 13 )
															{
																$( "#submit_login_button" ).trigger( "click" );
															}
														});

														$( "#submit_login_button" ).on("click", function(){
															$("#action").remove();
															$(".login_sended").val(1);
															console.log("TEST");
														});
													});

													$(document).on("pageshow", "#alert" ,function ()
													{
								  						$( "#Login-alert" ).popup();
														setTimeout( function(){ $( "#Login-alert" ).popup("open"); }, 500 );
													});
													</script>
														';
							}
						}
						break;
					/*
					 * Default
					 */
					default:

							$out = '<div>';
								$out.= '<label for="'.$type.'">'.$langs->trans("Label".$type).': '.$this->tooltip($type, $langs->trans("Info".$type)).'</label>';
								if(!is_array($this->{$type}[0])){
									$out.= '<input '.$status_theme.' type="text" placeholder="'.$type.'" name="'.$type.'" value="'.$this->{$type}[0].'">';
								}else{
									foreach($this->{$type}[0] as $key => $val){
										$out.= '<input '.$status_theme.' type="text" placeholder="'.$type.'" name="'.$type.'['.$key.']" value="'.$val.'">';
									}
								}
								//$out.= print_r($this, true);
							$out.= '</div>';
						break;
			}
			//$out.= $this->InputStandard('sent');
			return $out;
		}

		/**
		 * Output HTML Info field
		 *
		 * @param string $name 						The name in the info field
		 * @param string $info 						The info in the info field
		 * @return string 								HTML select field
		 */

		 function tooltip($name, $info, $alttext='ToolboxInfo')
		 {
				$out = '<a href="#'.$name.'" data-rel="popup" data-transition="pop" class="my-tooltip-btn ui-btn ui-alt-icon ui-nodisc-icon ui-btn-inline ui-icon-info ui-btn-icon-notext" title="'.$alttext.'">'.$name.'</a>';
				$out.= '<div data-role="popup" id="'.$name.'" class="ui-content" data-theme="a" style="max-width:100%;">';
				$out.= $info;
				$out.= '</div>';

			return $out;
		 }

		var $script = "";
		/**
	 * Output Html select
	 *
	 * @param   string	$name					The POST/GET name of the select
	 * @param   array		$data					the content of the select array("option value" => "option Name")
	 * @param   string  $option
	 * @param   string  $placeholder
	 * @param 	int 		$empty 				if 1 then make a empty value
	 * @return	string								HTML select field
	 */
		function buildSelectValueName($name, $name2, $name3, $S_Area, $G_Area, $select = array(), $extclass = "")
		{
			$style_color = "";
			if($name3 == "geocode") $multi = 'multiple="multiple"';
			$out = '<select name="'.$name.'" id="'.$name3.'-select" data-native-menu="false" '.$multi.' class="'.$extclass.'">';
				if(!$extclass) $out.='<option></option>';
				foreach($S_Area as $data_val => $data_name)
				{
					$sel = false;

					foreach($select as $key => $select_code)
					{
						$select_code['value'] = str_replace(';', '&#59;', $select_code['value']);

						if( $data_val == $select_code['value'] )
						{
							$sel = true;
							$sel_tmp = "selected";
							if($name3 == "parameter_awt" || $name3 == "parameter_awl") $check = 'checked="checked"';
						}
					}

					if($name3 == "geocode")
					{
						if($sel == true)
						{
							$out.= '<option '.$style_color.' value="'.$data_val.'<|>'.$G_Area[$data_val].'" selected>';
						}
						else
						{
							$out.= '<option '.$style_color.' value="'.$data_val.'<|>'.$G_Area[$data_val].'">';
						}
					}
					else
					{
						if($sel == true)
						{
							$out.= '<option '.$style_color.' value="'.$data_val.'" selected>';
						}
						else
						{
							$out.= '<option '.$style_color.' value="'.$data_val.'">';
						}
					}

					$out.= $data_name;
					$out.= '</option>';
				}
			$out.= '</select>';

			if($name3 == "parameter_awt" || $name3 == "parameter_awl")
			{
				$out.= '<input type="checkbox" class="'.$name3.'" name="'.$name2.'" value="'.$G_Area[$data_val].'" id="'.$data_name.'" style="display: none;" '.$sel_tmp.' '.$check.'>';

				$this->script.= 	'
									$( "#'.$name3.'-select" ).change(function() {
										var res = $( "#'.$name3.'-select" ).val();
										if(res)
										{
											$(".'.$name3.'").prop("checked", true);
										}
										else
										{
											$(".'.$name3.'").prop("checked", false);
										}
									});
								';
			}


			return $out;
		}

		/**
	 * Output Html select
	 *
	 * @param   string	$name					The POST/GET name of the select
	 * @param   array		$data					the content of the select array("option value" => "option Name")
	 * @param   string  $option
	 * @param   string  $placeholder
	 * @param 	int 		$empty 				if 1 then make a empty value
	 * @return	string								HTML select field
	 */
		function buildSelect($name= "", $data = array(), $option = "", $placeholder = "", $selected="", $empty=0)
		{
			$out = '<select onchange="get_date();" name="'.$name.'" '.$option.'>';

				if($empty == 1)
				{
					$out.='<option></option>';
				}

				if($placeholder)
				{
					$out.= '<option value="#" data-placeholder="true">'.$placeholder.'</option>';
				}

				foreach($data as $data_val => $data_name)
				{
					if($selected == $data_val)
					{
						$out.= '<option value="'.$data_val.'" selected>';
					}
					else
					{
						$out.= '<option value="'.$data_val.'">';
					}
					$out.= $data_name;
					$out.= '</option>';
				}

			$out.= '</select>';
			return $out;
		}

		function make_cap_time($time)
		{
			$time_arr = explode("T", $time);
			$ctime['date'] = $time_arr[0];
			$time_arr_time = explode("+", $time_arr[1]);
			$ctime['time'] = $time_arr_time[0];
			$ctime['zone'] = $time_arr_time[1];

			return $ctime;
		}


		/**
		* encrypt and decrypt function for passwords
		*
		* @return	string
		*/
		function encrypt_decrypt($action, $string, $key = "")
		{
			global $configuration;

			$output = false;

			$encrypt_method = "AES-256-CBC";
			$secret_key = ($key?$key:'NjZvdDZtQ3ZSdVVUMXFMdnBnWGt2Zz09');

			$secret_iv = ($configuration->conf["webservice"]["securitykey"] ? $configuration->conf["webservice"]["securitykey"] : 'WebTagServices#hash');

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

		/**
	 * Output RFC 3066 Array
	 *
	 * @return	string						Array with RFC 3066 Array
	 */
		function getlang($config = false){
			global $configuration;

			if(is_array($this->language))
			{
				foreach($this->language as $key => $lang_name)
				{
					$out[$lang_name] = $lang_name;
				}
			}

			$out_tmp = $configuration->conf["language"];

			foreach($out_tmp as $key => $lang_name)
			{
				if($configuration->conf["selected_language"][$key] == true) $out[$configuration->conf["language_RFC3066"][$key]] = $out_tmp[$key];
			}

			return $out;
		}

	  /**
	 * Output Html Head
	 *
	 * @return	string						HTML Head
	 */
		function Header_llx()
		{
			global $configuration, $SVLdetail;

			$out = '<head>';
				$out.= '<meta charset="UTF-8">';
				$out.= '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">';
				//$out.= '<script type="text/javascript" src="includes/jquery/jquery-3.0.0.js"></script>';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery.min.js"></script>';
				//$out.= '<script src="https://code.jquery.com/jquery-2.2.4.js"   integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="crossorigin="anonymous"></script>';
				$out.= '<script type="text/javascript" src="includes/jquery/jquery-ui.min.js"></script>';
				$out.= '<script type="text/javascript" src="includes/d3/d3.v3.min.js"></script>';


				$out.= '<link rel="stylesheet" type="text/css" href="css/cap_form.css">';

				$out.= '<link rel="icon" type="image/png" href="conf/logo.jpg">';

				if(!empty($SVLdetail)) $out.= $SVLdetail;

				$out.= '<script type="text/javascript" src="js/form.js"></script>';
				$out.= '<script type="text/javascript" src="js/map.js"></script>';

				$out.= '<link rel="stylesheet" href="includes/jquery.mobile/jquery.mobile-1.4.5.min.css" />';
				$out.= '<script src="includes/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>';

				if( $configuration->conf["webservice_aktive"] == 1 )
				{
					$out.= '<link rel="stylesheet" href="css/MeteoalarmMobile.css" />';
				}
				else
				{
					$out.= '<link rel="stylesheet" href="css/BackboneMobile.css" />';
				}

				if(basename($_SERVER['PHP_SELF']) == "map.php")
				{
					$out.= '<script type="text/javascript" src="js/svg-pan-zoom.js"></script>';
				}

				$out.= '<link rel="stylesheet" href="css/jquery.mobile.icons.min.css" />';
				// OpenStreetMap
				$out.= '<script src="includes/jquery/jquery.geo.min.js"></script>';

				// Clockpicker Addon
				$out.= '<link rel="stylesheet" type="text/css" href="includes/plugin/jquery-clockpicker.min.css">';
				$out.= '<script type="text/javascript" src="includes/plugin/jquery-clockpicker.min.js"></script>';

				$out.= '<!--[if lt IE 9]>
    							<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
								<![endif]-->';
				$out.= '<title>Cap Creator</title>';

			$out.= '</head>';

			return $out;
		}


		/**
	 * Output Html Form
	 *
	 * @return	string						HTML Form
	 */
		function Form()
		{
			global $configuration, $langs, $login_to_webservice_faild, $nogooglemap;


			if(file_exists('menu/standard_menu.lib.php') && empty($configuration->conf["optional_menu"]))
			{
				include 'menu/standard_menu.lib.php';
			}
			elseif(!empty($configuration->conf["optional_menu"]))
			{
				include addslashes($configuration->conf["optional_menu"]);
			}
			else
			{
				die($langs->trans("standard_menuLibPhpIsCurupt"));
				//die('Can\'t load standard_menu.lib.php please download menu/standard_menu.lib.php from https://github.com/AT-backbone/Cap-PHP-library');
			}

			$Type_arr = Types(); // TYPES FOR PAGES
			$Pages_arr = Pages(); // PAGES
			$Type_Status_arr = TypeStatus(); // Type Status (Like Required)

			$out = $this->Header_llx();

			$out.= '<body>';
			$out.= '<form method="POST" id="capform" name="capform" action="index.php" enctype="multipart/form-data" data-ajax="false">';
				$out.= '<input type="hidden" name="action" id="action" value="create">';

					foreach($Type_arr as $pagename => $TypePage)
					{
						if(!in_array($pagename, $Pages_arr['popup']))
						{
							$out.= '<div data-role="page" id="'.$pagename.'">';

								// PANEL
								$out.= '<div data-role="panel" data-display="push" id="'.$pagename.'_panel">';
								$out.= '<!-- panel content goes here -->';
								$out.= '<ul data-role="listview">';

									$out.= '<li style="height: 91px;">';
										$out.= '<img src="conf/logo.jpg" style="border: 1px solid black;border-radius: 45px;width: 20%;margin: 10px 0px 0px 10px;">';
										$out.= '<h1>';
											$out.= $langs->trans('Cap Creator');
										$out.= '</h1>';
										$out.= '<br>';
										$out.= '<span style="font-size: 10px;">';
											$out.= 'v'.$this->version;
										$out.= '</span>';
									$out.= '</li>';

										foreach($Pages_arr as $link => $Page_Name)
										{
											if($link != 'popup' && $link != 'next' && $link != 'notitle' && $link != 'header')
											{
												if(!in_array($link, $Pages_arr['popup'])) // a dialog shoud not be in the panel !
												{
													$data = "";
													if(in_array($link, $Pages_arr['noajax']) || $pagename == "conf") $data = 'data-ajax="false"';
													if($link != 'noajax')
													{
														if($link == '#'.$pagename) 	$out.= '<li data-theme="b"><a href="'.$link.'" '.$data.'>'.$Page_Name.'</a></li>';
														else 										$out.= '<li><a href="'.$link.'" '.$data.'>'.$Page_Name.'</a></li>';
													}
													unset($data);
												}
											}
										}

									$out.= '</ul>';
								$out.= '</div>'; // PANEL

								if($configuration->conf["webservice"]["login"] && $configuration->conf["webservice_aktive"]) $login_show_name = $configuration->conf["webservice"]["login"];
								else $login_show_name = $langs->trans('Login');
								$out.= '<div data-theme="b" data-role="header">';
									$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
										$out.= '<h1>';
											$out.= $Pages_arr['#'.$pagename];
											if(phpversion() < $configuration->conf["PHPVersion"]["min"] || phpversion() > $configuration->conf["PHPVersion"]["max"]){
												$out.= ' <br><span style="color:yellow">'.$langs->trans('php_version_is_not_tested',phpversion()).' ';
												$out.= $langs->trans('php_min_max_version',$configuration->conf["PHPVersion"]["min"] , $configuration->conf["PHPVersion"]["max"] );
												$out.= '</span>';
											}
										$out.= '</h1>';
									if($configuration->conf["webservice"]["service_on"] == 1)
									{
										if($login_to_webservice_faild == true)
										{
											$out.= '<a href="#Login-'.$pagename.'" data-rel="popup" data-position-to="window" data-transition="pop" style="background-color: red;color: black;text-shadow: none;">'.$login_show_name.'</a>';
										}
										else
										{
											$out.= '<a href="#Login-'.$pagename.'" data-rel="popup" data-position-to="window" data-transition="pop">'.$login_show_name.'</a>';
										}
									}
								$out.= '</div>'; // HEADER

								// Main
								$out.= '<div id="main-div" class="ui-content ui-page-theme-a" data-form="ui-page-theme-a" data-theme="a" role="main">';

									/* TODO: 1.4 HEADERS
									if(basename($_SERVER['PHP_SELF']) == "map.php")  $css_paint_top = 'style="background-color:#065c00;"';
									elseif($pagename == "alert") $css_alert_top = 'style="background-color:#065c00;"';
									elseif($pagename == "conf") $css_conf_top = 'style="background-color:#065c00;"';
									// HEADER
									$out.= '<div class="top_menu">';
										$out.= '<a href="index.php#alert" data-ajax="false" '.$css_alert_top.'><img width"20px" src="css/images/form_edit_w.png"><span>'.$langs->trans('Alert').'</span></a>';
										$out.= '<a href="map.php" data-ajax="false" '.$css_paint_top.'><img width"20px" src="css/images/paint_edit_w.png"><span>'.$langs->trans('Paint').'</span></a>';
										$out.= '<a href="index.php#conf" data-ajax="false" '.$css_conf_top.'><img width"20px" src="css/images/conf_edit_w.png"><span>'.$langs->trans('Conf').'</span></a>';
									$out.= '</div>';
									*/

									if(!isset($Pages_arr['notitle']['#'.$pagename]))
									$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
										if(!isset($Pages_arr['notitle']['#'.$pagename]))
										$out.= '<ul data-role="listview" data-divider-theme="b">';

										if(!isset($Pages_arr['notitle']['#'.$pagename]))
										$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$Pages_arr['#'.$pagename].'</h1></li>';

											foreach($TypePage as $key => $type)
											{
												if(is_numeric($key))
												{
													if(!isset($Pages_arr['notitle']['#'.$pagename])) $out.= '<li>';
														$out.= $this->InputStandard($type, $Type_Status_arr);
													if(!isset($Pages_arr['notitle']['#'.$pagename])) $out.= '</li>';
												}
											}

										if(!isset($Pages_arr['notitle']['#'.$pagename]))
										$out.= '</ul>';
									if(!isset($Pages_arr['notitle']['#'.$pagename]))
									$out.= '</div>';	 // UI_BODY_A

									// DETAILS
									if(count ($TypePage['detail']['value']) >= 1 && $TypePage['detail']['value'] != -1) {
										$visibl = "";
										if($configuration->conf["webservice"]["service_on"] == 0 && $pagename == "conf") $visibl = 'style="display:none;"';
										$out.= '<div data-role="collapsible" id="'.$pagename.'-detail" data-theme="b" data-content-theme="a" '.$visibl.'>';
											$out.= '<h2>'.$TypePage['detail']['name'].'</h2>';
											$out.= '<ul data-role="listview" id="first_interface">';

												if(is_array($TypePage['detail']['value'])) {
													foreach($TypePage['detail']['value'] as $key_ex => $type_ex) {
														if($key_ex != 'name') {
															$out.= '<li id="'.$type_ex.'DIV" class="ui-field-contain">'.$this->InputStandard($type_ex, $Type_Status_arr).'</li>';
														}
													}
												}									

											$out.= '</ul>';					
										$out.= '</div>';
										$out.= '<div style="display:none;" data-role="collapsible" id="'.$pagename.'-detail-2" data-theme="b" data-content-theme="a" '.$visibl.'>';
											$out.= '<h2 class="ui-collapsible-heading ui-collapsible-heading-collapsed">';
												// $out.= '<a href="#" class="ui-collapsible-heading-toggle ui-btn ui-icon-plus ui-btn-icon-left ui-btn-b">';
													$out.= $langs->trans("WebserviceConfiguration2");
													$out.= '<span class="ui-collapsible-heading-status">';
														$out.= 'click to expand contents';
													$out.= '</span>';
												// $out.= '</a>';
											$out.= '</h2>';
											$out.= '<ul data-role="listview" id="second_interface">';
												$out.= '<li class="ui-field-contain ui-li-static ui-body-inherit ui-first-child">';
													$out.= '<legend>'.$langs->trans("Labelwebservice_login2").':</legend>';
													$out.= '<div class="ui-body-inherit ui-corner-all ui-shadow-inset">';
														$out.= '<input type="text" name="conf[webservice][login_2]" value="'.$configuration->conf['webservice']['login_2'].'">';
													$out.= '</div>';
												$out.= '</li>';
												$out.= '<li class="ui-field-contain ui-li-static ui-body-inherit ui-first-child">';
													$out.= '<legend>'.$langs->trans("Labelwebservice_password2").':</legend>';
													$out.= '<div class="ui-body-inherit ui-corner-all ui-shadow-inset">';
														// $out.= '<input type="password" name="conf[webservice][password_2]" value="'.encrypt_decrypt(1, $configuration->conf['webservice']['password_2']).'">';
														$out.= '<input type="password" name="conf[webservice][password_2]" value="'.$configuration->conf['webservice']['password_2'].'">';
													$out.= '</div>';
												$out.= '</li>';
												// $out.= encrypt_decrypt(2, $configuration->conf['webservice']['password_2']);
												$out.= '<li class="ui-field-contain ui-li-static ui-body-inherit ui-first-child">';
													$out.= '<legend>'.$langs->trans("Labelwebservice_securitykey").':</legend>';
													$out.= '<div class="ui-body-inherit ui-corner-all ui-shadow-inset">';
														$out.= '<input type="text" name="conf[webservice][securitykey_2]" value="'.$configuration->conf['webservice']['securitykey_2'].'">';
													$out.= '</div>';
												$out.= '</li>';
												$out.= '<li class="ui-field-contain ui-li-static ui-body-inherit">';
													$out.= '<legend>'.$langs->trans("webservice_WS_METHOD").':</legend>';
													$out.= '<div class="ui-body-inherit ui-corner-all ui-shadow-inset">';
														$out.= '<input type="text" name="conf[webservice][WS_METHOD_2]" value="'.$configuration->conf['webservice']['WS_METHOD_2'].'">';
													$out.= '</div>';
												$out.= '</li>';
												$out.= '<li class="ui-field-contain ui-li-static ui-body-inherit">';
													$out.= '<legend>'.$langs->trans("Labelwebservice_WS_DOL_URL").':</legend>';
													$out.= '<div class="ui-body-inherit ui-corner-all ui-shadow-inset">';
														$out.= '<input type="text" name="conf[webservice][WS_DOL_URL_2]" value="'.$configuration->conf['webservice']['WS_DOL_URL_2'].'">';
														$out.= '<input type="hidden" name="conf[webservice][ns_2]" value="'.$configuration->conf['webservice']['WS_DOL_URL_2'].'">';
													$out.= '</div>';
												$out.= '</li>';	
												if(is_array($TypePage['detail']['value'])) {
													foreach($TypePage['detail']['value'] as $key_ex => $type_ex) {
														if($key_ex != 'name') {
															if($type_ex == 'proxy_conf') {
																$out.= '<li id="'.$type_ex.'DIV" class="ui-field-contain">'.$this->InputStandard($type_ex, $Type_Status_arr).'</li>';
															}
														}
													}
												}											
											$out.= '</ul>';
										$out.= '</div>';
									}

								$out.= '</div>'; // MAIN CONTENT

								$out.= '<div data-role="footer" data-theme="b">';
									//if($Pages_arr[$pagename]['next'] == true) $out.= '<ul data-role="listview" data-inset="true"><li><a href="#info"><h1>Next</h1></a></li></ul>';
									if(!empty($Pages_arr['next']['name'][$pagename]) || !empty($Pages_arr['next']['nolink'][$pagename]))
									{
										if(!empty($Pages_arr['next']['nolink'][$pagename]))
										{
											$out.= $Pages_arr['next']['nolink'][$pagename];
										}
										else
										{
											$out.= '<ul data-role="listview" data-inset="true">';
												$out.= '<li>';
													$out.= '<a href="#'.$Pages_arr['next']['name'][$pagename].'">';
														$out.= '<h1>'.$langs->trans('Next').'</h1>';
													$out.= '</a>';
												$out.= '</li>';
											$out.= '</ul>';
										}
									}

								$out.= '</div>'; // FOOTER

								// POPUP
								foreach($Pages_arr['popup'] as $key => $popupname)
								{
									$TypePopup = $Type_arr[$popupname];

									$out.= '<div data-role="popup" class="'.$popupname.'" id="'.$popupname.'-'.$pagename.'" data-theme="a" class="ui-corner-all" style="width: 100%;">';
										//$out.= '<form>';

												//$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
													$out.= '<ul data-role="listview" data-divider-theme="b">';

													if($popupname == 'Login' && $login_to_webservice_faild == true)
													{
														$out.= '<li data-role="list-divider" data-theme="b" style="background-color: red;border-top: none;"><h1 style="font-size:22px;">'.$langs->trans('Title'.$popupname).'</h1></li>';
													}
													else
													{
														$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$langs->trans('Title'.$popupname).'</h1></li>';
													}

														foreach($TypePopup as $key => $type)
														{
															if(is_numeric($key))
															{
																$out.= '<li>';
																	$out.= $this->InputStandard($type, $Type_Status_arr);
																$out.= '</li>';
															}
														}

													$out.= '</ul>';
												//$out.= '</div>';	 // UI_BODY_A

										//$out.= '</form>';
									$out.= '</div>';
								}

							$out.= '</div>'; // PAGE END
						}
					}

			$out.= '</form>'; // FORM

			$out.= '<script>
							'.$this->script.'
							</script>';
			// GOOGLE MAP
			//$out.= '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJNLZq13zNbYh6yx4C6dQXoxZVGTnCFKE&callback=initMap" async defer></script>';
			if(!empty($configuration->conf["GoogleMap"]["APIkey"]) && $nogooglemap != 1){
				$out.= '<script src="https://maps.googleapis.com/maps/api/js?key='.$configuration->conf["GoogleMap"]["APIkey"].'&signed_in=true&libraries=drawing&callback=initMap" async defer></script>';
			}
			$out.= '</body>';
			$out.=
			'
			<script>
				';

					$depends = dependencies(); // from menu php

					$out.= '
									function dependencies_js()
									{';

						foreach($depends as $is_object => $depends_array)
						{
							foreach($depends_array as $name_or_id => $depends_array_next)
							{
								foreach($depends_array_next as $object_name => $obj_arr)
								{
									foreach($obj_arr as $condition => $condition_arr)
									{
										foreach($condition_arr as $change_is => $change_to_arr)
										{
											foreach($change_to_arr as $change_name_or_id => $change_to_array)
											{
												foreach($change_to_array as $change_name => $change_to_val)
												{
													if($name_or_id == "name")
													{
														$out.= 	'
																			if( $( "'.$is_object.'[name=\''.$object_name.'\']" ).val() == "'.$condition.'" )
																			{
																		';
													}
													else
													{
														$out.= 	'
																			if( $( "#'.$object_name.'" ).val() == "'.$condition.'" )
																			{
																		';
													}
													if($change_name_or_id == "name")
													{
														$out.= 	'
																			 $( "'.$change_is.'[name=\''.$change_name.'\']" ).val( "'.$change_to_val.'" );
																			 $( "'.$change_is.'[name=\''.$change_name.'\']" ).selectmenu().selectmenu("refresh");
																			 if($( "'.$change_is.'[name=\''.$change_name.'\']" ).is("select"))
																			 {
																				$( "'.$change_is.'[name=\''.$change_name.'\']" ).parent( ).find("a").addClass( "ui-btn-f" ); // its a select
																			 }
																			 else
																			 {
																				 $( "'.$change_is.'[name=\''.$change_name.'\']" ).parent( ).addClass( "ui-body-f" ); // its a input
																			 }
																	   }
																	';
													}
													else
													{
														$out.= 	'
																			 $( "#'.$change_name.'" ).val( "'.$change_to_val.'" );
																			 $( "#'.$change_name.'" ).selectmenu().selectmenu("refresh");
																			 if($( "#'.$change_name.'" ).is("select"))
																			 {
																				$( "#'.$change_name.'" ).parent( ).find("a").addClass( "ui-btn-f" ); // its a select
																			 }
																			 else
																			 {
																				 $( "#'.$change_name.'" ).parent( ).addClass( "ui-body-f" ); // its a input
																			 }
																	   }
																	';
													}
												}
											}
										}
									}
								}
							}
						}
					$out.= '}';

					$langs_arr = $this->getlang();

					foreach($langs_arr as $key => $langs_val)
					{

						$out.= '
										$( "#'.$key.'_Remove_Button" ).click(function() {
											$(this).parent("a").hide();
											$(\'input[name="event['.$key.']"]\').val("");
											$(\'input[name="headline['.$key.']"]\').val("");
											$(\'input[name="description['.$key.']"]\').val("");
											$(\'input[name="instruction['.$key.']"]\').val("");
											$( "#'.$key.'_language_input" ).val("delete");
										});
									 ';
					}

					foreach($langs_arr as $key => $langs_val)
					{

						$out.= '$( "#'.$key.'_Button" ).click(function() {';
							$out.= '
											if($( "#'.$key.'_language_input" ).val() != "delete")
											{
												$( "#'.$key.'_language_input" ).val("'.$key.'");
											}
											else
											{
												$( "#'.$key.'_language_input" ).val("");
											}

											$( ".Lang_Button" ).each(function( index )
											{
												if( "'.$key.'_Button" == $(this).attr("id"))
												{
													$(this).css("box-shadow", "0px 0px 11px rgb(0, 126, 255)");
												}
												else
												{
													$(this).css("box-shadow", "");
												}
											});

											$( ".lang_input" ).each(function( index )
											{
												if( "'.$key.'" == $(this).attr("id"))
												{
													$(this).show();
												}
												else
												{
													$(this).hide();
												}
											});
							';
						$out.= '});';
					}

				$out.= '

			</script>';

			return $out;
		}

		/**
		 * Function to install the interface of the Cap PHP library
		 *
		 * @return	string 	$out
		 */
		function CapView($content, $ID, $extrahtml = "")
		{
			global $configuration, $langs;

			$out = $this->Header_llx();

			$out.= '<body>';
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?webservice=1" enctype="multipart/form-data" data-ajax="false">';
					$out.= '<input type="hidden" name="filename" value="'.$ID.'">';
					$out.= '<div data-role="page" id="capview">';

						$out.= '<div data-theme="b" data-role="header">';
							//$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
							$out.= '<a href="index.php" data-ajax="false" data-theme="b" class="ui-btn ui-icon-delete ui-btn-icon-notext" style="border: none;">'.$langs->trans("Cancel").'</a>';
							$out.= '<h1>'.$ID.'</h1>';
						$out.= '</div>';

						$out.= '<div role="main" class="ui-content">';

							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
								$out.= '<ul data-role="listview" data-divider-theme="b">';

									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">'.$langs->trans("LabelCapViewOf").': '.$ID.'</h1></li>';

									if($configuration->conf["cap"]["save"] == 1) $out.= '<li><a href="'.$configuration->conf["cap"]["output"].'/'.$ID.'" download data-ajax="false">Download '.$ID.'</a></li>';

									if($configuration->conf["webservice"]["service_on"] == 1) $out.= '<li><input type="submit" value="<h1>'.$langs->trans("sendviaSoap").'</h1>" data-ajax="false"></li>';

									$out.= '<li>';
										$out.= '<textarea readonly>';
											$out.= $content;
										$out.= '</textarea>';
									$out.= '</li>';

									if(!empty($extrahtml)) {
										$out.= '<li>';
											$out.= $extrahtml;
										$out.= '</li>';
									}

								$out.= '</ul>';
							$out.= '</div>';

						$out.= '</div><!-- /content -->';

						$out.= '<div data-role="footer" data-theme="b">';

						$out.= '</div><!-- /footer -->';

					$out.= '</div><!-- /page -->';

				$out.= '</form>';
			$out.= '</body>';

			return $out;
		}

		function ListCap()
		{
			global $configuration, $langs;

			if(file_exists('menu/standard_menu.lib.php') && empty($configuration->conf["optional_menu"]))
			{
				include 'menu/standard_menu.lib.php';
			}
			elseif(!empty($configuration->conf["optional_menu"]))
			{
				include addslashes($configuration->conf["optional_menu"]);
			}
			else
			{
				die($langs->trans("standard_menuLibPhpIsCurupt"));
				//die('Can\'t load standard_menu.lib.php please download menu/standard_menu.lib.php from https://github.com/AT-backbone/Cap-PHP-library');
			}

			$out = $this->Header_llx();

			$Type_arr = Types(); // TYPES FOR PAGES
			$Pages_arr = Pages(); // PAGES

			$out.= '<body>';
			$out.= '<form method="POST" id="capform" name="capform" action="index.php?conv=1" enctype="multipart/form-data" data-ajax="false">';
				//$out.= '<input type="hidden" name="action" id="action" value="create">';
						$out.= '<div data-role="page" id="'.$pagename.'">';

							$out.= '<div data-role="panel" data-display="push" id="'.$pagename.'_panel">';
							$out.= '<!-- panel content goes here -->';
							$out.= '<ul data-role="listview">';

								$out.= '<li style="height: 91px;">';
									$out.= '<img src="conf/logo.jpg" style="border: 1px solid black;border-radius: 45px;width: 20%;margin: 10px 0px 0px 10px;">';
									$out.= '<h1>';
										$out.= $langs->trans('Cap Creator');
									$out.= '</h1>';
									$out.= '<br>';
									$out.= '<span style="font-size: 10px;">';
										$out.= 'v'.$this->version;
									$out.= '</span>';
								$out.= '</li>';

									foreach($Pages_arr as $link => $Page_Name)
									{
										if($link != 'popup' && $link != 'next' && $link != 'notitle' && $link != 'header')
										{
											if(!in_array($link, $Pages_arr['popup'])) // a dialog shoud not be in the panel !
											{
												$data = "";
												if(in_array($link, $Pages_arr['noajax'])) $data = 'data-ajax="false"';
												if($link != 'noajax')
												{
													if($link == '#'.$pagename) 	$out.= '<li data-theme="b"><a href="'.$link.'" '.$data.' data-ajax="false">'.$Page_Name.'</a></li>';
													elseif($link != 'map.php') 	$out.= '<li><a href="index.php'.$link.'" '.$data.' data-ajax="false">'.$Page_Name.'</a></li>';
													else 						$out.= '<li><a href="'.$link.'" '.$data.' data-ajax="false">'.$Page_Name.'</a></li>';
												}
												unset($data);
											}
										}
									}

								$out.= '</ul>';
							$out.= '</div>'; // PANEL

							$out.= '<div data-theme="b" data-role="header">';
								$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
								$out.= '<h1>Cap Converter</h1>';
							$out.= '</div>';

						$out.= '<div role="main" class="ui-content">';

							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
								/*
								// get all convert files
								$std_tmp = scandir('convert/');
								foreach($std_tmp as $num => $filename)
								{
									if(substr($filename, 0, 4) != "std_")
									{
										unset($std_tmp[$num]);
									}
									else
									{
										$std_converter[substr($filename, 4, -9)] = substr($filename, 4, -9);
									}
								}

								$area_tmp = scandir('convert/');
								foreach($area_tmp as $num => $filename)
								{
									if(substr($filename, 0, 5) != "area_")
									{
										unset($area_tmp[$num]);
									}
									else
									{
										$area_converter[substr($filename, 5, -9)] = substr($filename, 5, -9);
									}
								}


								$std = $this->buildSelect("stdconverter", $std_converter, "data-native-menu=\"false\"", "", "standard");
								$area = $this->buildSelect("areaconverter", $area_converter, "data-native-menu=\"false\"", "", "standard");

									$out.= '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="converter">';
										$out.= '<legend>'.$langs->trans("SelectStdAreaCap").': '.$this->tooltip('SelectStdAreaCaptool', $langs->trans("InfoSelectStdAreaCap")).'</legend>';
											$out.= $std;
											$out.= $area;
									$out.= '</fieldset>';
								*/

								// get all convert files
								$converter_tmp = scandir('convert/');
								foreach($converter_tmp as $num => $filename)
								{
									if(substr($filename, 0, 5) != "conv_")
									{
										unset($converter_tmp[$num]);
									}
									else
									{
										$converter[substr($filename, 5, -9)] = substr($filename, 5, -9);
									}
								}

								$input = $this->buildSelect("inputconverter", $converter, "data-native-menu=\"false\"", "", "standard");
								$output = $this->buildSelect("outputconverter", $converter, "data-native-menu=\"false\"", "", "standard");

									$out.= '<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" id="converter">';
										$out.= '<legend>'.$langs->trans("SelectInputandOutputCap").': '.$this->tooltip('SelectInputandOutputCaptool', $langs->trans("InfoSelectInputandOutputCap")).'</legend>';
											$out.= $input;
											$out.= $output;
									$out.= '</fieldset>';


									$out.= '<input type="file" name="uploadfile" id="uploadfile">';
									// or
									$out.= '<fieldset data-role="controlgroup">';
										$out.= '<legend>'.$langs->trans("SelectCaps").':</legend>';
											foreach(scandir($configuration->conf["cap"]["output"]) as $num => $capfilename)
											{
												if($num > 1)
												{
													$out.= '<input type="radio" name="location" id="cap_file_'.$num.'" value="'.urlencode($capfilename).'">';
													$out.= '<label for="cap_file_'.$num.'">'.$capfilename.'</label>';
												}
											}
									$out.= '</fieldset>';

									$out.= '<input type="submit" value="<h1>'.$langs->trans("Convert").'</h1>" data-ajax="false">';

							$out.= '</div>';

						$out.= '</div><!-- /content -->';

						$out.= '<div data-role="footer" data-theme="b">';

						$out.= '</div><!-- /footer -->';

					$out.= '</div><!-- /page -->';

				$out.= '</form>';
			$out.= '</body>';

			return $out;
		}

		function Webservice($ID)
		{
			global $configuration, $langs, $out;

			$out = $this->Header_llx();

			$out.= '<body>';
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?conf=1" enctype="multipart/form-data" >';
					/**
					 *
					 *  WEBSERVICE
					 *
					 */
					 if($configuration->conf["webservice"]["service_on"] == 1)
					 {

						$out.= '<div data-role="page" id="capview">';

							// HEADER
							$out.= '<div data-theme="b" data-role="header">';
								//$out.= '<a href="#'.$pagename.'_panel" class="ui-btn ui-icon-bars ui-btn-icon-notext" style="border: none;"></a>';
								$out.= '<a href="index.php" data-ajax="false" data-theme="b" class="ui-btn ui-icon-delete ui-btn-icon-notext" style="border: none;">'.$langs->trans("Cancel").'</a>';
								$out.= '<h1>'.$ID.'</h1>';
							$out.= '</div>';

							// MAIN
							$out.= '<div role="main" class="ui-content">';

								$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';

									// decryp password
									$configuration->setValue("webservice", "password", $this->encrypt_decrypt(2, $configuration->conf["webservice"]["password"]));

									include("lib/cap.webservices.php");

									$configuration->setValue("webservice", "password",$this->encrypt_decrypt(1, $configuration->conf["webservice"]["password"]));

								$out.= '</div>';

							$out.= '</div><!-- /content -->';

							// FOOTER
							$out.= '<div data-role="footer" data-theme="b">';

							$out.= '</div><!-- /footer -->';

						 $out.= '</div><!-- /page -->';
					 }
					/**
					 *
					 *  WEBSERVICE
					 *
					 */
					$out.= '</form>';
				$out.= '</body>';

			return $out;
		}

		/**
		 * Function to conect the identifier to one string
		 *
		 * @return	array 	$_POST
		 */
		function MakeIdentifier($post)
		{
			if(!empty($post[identifier][WMO]) || ! empty($post[identifier][ISO]) || ! empty($post[identifier][time]))
			{
				$temp = "";
				$i = 0;
				foreach($post[identifier] as $id_val)
				{
					if($i == 0)
					{
						$temp.= $id_val;
					}
					else
					{
						$temp.= ".".$id_val;
					}
					if(!empty($id_val)) $i++;
				}

				unset($post[identifier]);
				$post[identifier] = $temp;
			}
			else
			{
				$temp = $post[identifier][ID];
				unset($post[identifier]);
				$post[identifier] = $temp;
			}
			return $post;
		}

		/*
		function login_page()
		{
			global $configuration, $langs;

				//$out.= '<form method="POST" id="login_form" name="login_form" action="index.php?login=1" enctype="multipart/form-data" >';

					$out.= '<div data-role="page" id="login">';

						$out.= '<div data-role="header">';
							$out.= '<h1>Login to an Webservice</h1>';
						$out.= '</div><!-- /header -->';

							$out.= '<div role="main" class="ui-content">';

								$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
									$out.= '<ul data-role="listview" data-divider-theme="b">';

										$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">Configuration</h1></li>';



									$out.= '</ul>';
								$out.= '</div>';

								$out.= '<input type="submit" value="Submit" data-theme="a">';

							$out.= '</div><!-- /content -->';

						$out.= '<div data-role="footer">';
						$out.= '</div><!-- /footer -->';

					$out.= '</div><!-- /page -->';

				//$out.= '</form>';

			return $out;
		}
		*/

		/**
		 * Function to install the interface of the Cap PHP library
		 *
		 * @return	string 	$out
		 */
		function install()
		{
			include 'menu/standard_menu.lib.php';

			$out = $this->Header_llx();

			$out.= '<body>';
				$out.= '<form method="POST" id="capform" name="capform" action="index.php?conf=1" enctype="multipart/form-data" >';

					$out.= '<div data-role="page">';

						$out.= '<div data-role="header">';
							$out.= '<h1>Install Cap PHP Library Interface</h1>';
						$out.= '</div><!-- /header -->';

						$out.= '<div role="main" class="ui-content">';

							$out.= '<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">';
								$out.= '<ul data-role="listview" data-divider-theme="b">';

									$out.= '<li data-role="list-divider" data-theme="b"><h1 style="font-size:22px;">Configuration</h1></li>';

									$Type_arr = $this->Types();
									foreach($Type_arr['conf'] as $key => $type)
									{
										if(is_numeric($key))
										{
											$out.= '<li>';
												$out.= $this->InputStandard($type);
											$out.= '</li>';
										}
									}

								$out.= '</ul>';
							$out.= '</div>';

								if(count ($Type_arr['conf']['detail']) >= 1)
								{
									$out.= '<div data-role="collapsible" data-theme="b" data-content-theme="a">';
										$out.= '<h2>Webservice</h2>';
										$out.= '<ul data-role="listview">';

											foreach($Type_arr['conf']['detail'] as $key_ex => $type_ex)
											{
												$out.= '<li id="'.$type_ex.'DIV" class="ui-field-contain">'.$this->InputStandard($type_ex).'</li>';
											}

										$out.= '</ul>';
									$out.= '</div>';
								}

							$out.= '<input type="submit" value="Submit" data-theme="a">';

						$out.= '</div><!-- /content -->';

						$out.= '<div data-role="footer">';
							$out.= '<h4>office@backbone.co.at</h4>';
						$out.= '</div><!-- /footer -->';

					$out.= '</div><!-- /page -->';

				$out.= '</form>';
			$out.= '</body>';

			return $out;
		}

		/**
		 * Function to save the conf post information to the conf
		 *
		 * @return	null
		 */
		function PostToConf($post)
		{
			global $configuration, $langs;

			if(! is_dir($post['cap']['output']) && $post['cap']['output'] != "")
			{
				//mkdir($post['cap']['output'], 0775); // check security!
			}

			/*
			 * Special
			 */
		 	if(!empty($post['user']['lang']))
 			{
 				$configuration->setValue("user", "language", $post['user']['lang']);
 			}

			 // set langs
			$lang_arr = $post['lang'];
			unset($post['lang']);
			foreach($lang_arr as $lang_key => $lang_name)
			{
				if($lang_key != "key" && $lang_name != "name" && $lang_key != "remove")
				{
					$configuration->conf["language"][$lang_key] = $lang_name;
				}
			}

				// conf[lang][remove][en-GB]:remove -> conf[lang][remove][remove]:en-GB
			$rmv_lang_arr = array_flip($lang_arr['remove']);
			unset($post['lang']['remove']);
			foreach($configuration->conf["language"] as $lang_key => $lang_name)
			{
				if(in_array($lang_key, $rmv_lang_arr))
				{
					unset($configuration->conf["language"][$lang_key]);
				}
			}

			// set visible langs
			$lang_arr = $post['select']['lang'];
			unset($post['select']);
			foreach($configuration->conf["language"] as $lang_name => $lang_boolen)
			{
				if(in_array($lang_name, $lang_arr))
				{
					$configuration->conf["selected_language"][$lang_name] = 1;
				}
				else
				{
					$configuration->conf["selected_language"][$lang_name] = 0;
				}
			}
			// specifie the automatic time set
			if($post['timezone'] != "")
			{
				$configuration->conf["timezone"] = $post['timezone'];
			}
			unset($post['timezone']);
			// specifie the automatic time set
			if($post['identifier']['time']['on'] == "on")
			{
				$configuration->setValue("identifier", "time_on", 1);
			}
			else
			{
				$configuration->setValue("identifier", "time_on", 0);
			}
			unset($post['identifier']['time']);

			if($post['cap']['save'] == "on")
			{
				$configuration->setValue("cap", "save", 1);
			}
			else
			{
				$configuration->setValue("cap", "save", 0);
			}
			unset($post['cap']['save']);

			if($post['webservice']['on'] == "on")
			{
				$configuration->setValue("webservice", "service_on", 1);
			}
			else
			{
				$configuration->setValue("webservice", "service_on", 0);
			}
			unset($post['webservice']['on']);

			// crypt pass
			if($configuration->conf["webservice"]["password"] == $post['webservice']['password'])
			{

			}
			else
			{
				$configuration->setValue("webservice", "password", $this->encrypt_decrypt(1, $post['webservice']['password']));
				unset($post['webservice']['password']);
			}

			if($post['proxy']['proxyOn'] == "on")
			{
				$configuration->setValue("proxy", "proxyOn", 1);
			}
			else
			{
				$configuration->setValue("proxy", "proxyOn", 0);
			}
			unset($post['proxy']['proxyOn']);

			// crypt pass
			if($configuration->conf["proxy"]["proxyUserPass"] != $post['proxy']['proxyUserPass'])
			{
				$configuration->setValue("proxy", "proxyUserPass", $post['proxy']['proxyUserPass']);
				unset($post['proxy']['proxyUserPass']);
			}

			if(!empty($post['timezone']))
 			{
 				$configuration->setValue("installed", "timezone", $post['timezone']);
 			}

			/*
			 * Reguler
			 */
			if(is_array($post))
			{
				foreach($post as $obj_name => $obj_val)
				{
					if(is_array($obj_val))
					{
						foreach($obj_val as $obj_2_name => $obj_2_val)
						{
							if(is_array($obj_2_val))
							{
								foreach($obj_2_val as $obj_3_name => $obj_3_val)
								{
									$configuration->setValue($obj_name, $obj_2_name."_".$obj_3_name, $obj_3_val);
								} // Level 2
							}
							else
							{
								$configuration->setValue($obj_name, $obj_2_name, $obj_2_val);
							}

						} // Level 1
					}
					else
					{
						$configuration->setValue($obj_name, $obj_1_name, $obj_1_val);
					}

				} // Base
			}
			else
			{
				$configuration->setValue($obj_name, "", $obj_val);
			}

		}

		/**
		 * Function to change Configuration in the conf.php file
		 *
		 * @return	null
		 */
		function WriteConf($write = true)
		{
			global $configuration;

			if($write == true)
			{
				$configuration->write_php_ini();
			}
			else
			{
				print ($configuration->conf);
			}
		}

		function ConfSpaces($string)
		{
			//$space = '                                                     '; // 55 spaces Standard
			$space = '';
			$i = (55 - strlen($string));
			while( $i > 0 )
			{
				$space.= ' ';
				$i--;
			}
			return $space;
		}

		function timezone_list() {
			static $timezones = null;

			if ($timezones === null) {
				$timezones = array();
				$offsets = array();
				$now = new DateTime();

				foreach (DateTimeZone::listIdentifiers() as $timezone) {
					$now->setTimezone(new DateTimeZone($timezone));
					$offsets[] = $offset = $now->getOffset();
					$timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
				}

				array_multisort($offsets, $timezones);
			}

			return $timezones;
		}

		function format_GMT_offset($offset) {
			$hours = intval($offset / 3600);
			$minutes = abs(intval($offset % 3600 / 60));
			return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
		}

		function format_timezone_name($name) {
			$name = str_replace('/', ', ', $name);
			$name = str_replace('_', ' ', $name);
			$name = str_replace('St ', 'St. ', $name);
			return $name;
		}
	}

?>