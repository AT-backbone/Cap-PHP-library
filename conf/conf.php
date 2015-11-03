<?php
date_default_timezone_set('Europe/Vienna');
$conf->timezone->set_default                           = 'Europe/Vienna';

$conf->user->lang                                      = 'en_US';

$conf->cap->save                                       = 1;
$conf->cap->output                                     = 'output';

$conf->identifier->WMO_OID                             = '2.49.0.20.0';
$conf->identifier->ISO                                 = '';
$conf->identifier->time->on                            = 1;
$conf->identifier->ID_ID                               = 140;

$conf->lang['en-GB']                                   = 'english';
$conf->lang['ca']                                      = 'català';
$conf->lang['cs']                                      = 'ceština';
$conf->lang['da-DK']                                   = 'dansk';
$conf->lang['de-DE']                                   = 'deutsch';
$conf->lang['es-ES']                                   = 'español';
$conf->lang['et']                                      = 'eesti';
$conf->lang['eu']                                      = 'euskera';
$conf->lang['fr-FR']                                   = 'français';
$conf->lang['gl']                                      = 'galego';
$conf->lang['hr-HR']                                   = 'hrvatski';
$conf->lang['is']                                      = 'íslenska';
$conf->lang['it-IT']                                   = 'italiano';
$conf->lang['lt']                                      = 'lietuviu';
$conf->lang['lv']                                      = 'latviešu';
$conf->lang['hu-HU']                                   = 'magyar';
$conf->lang['mt']                                      = 'malti';
$conf->lang['nl-NL']                                   = 'nederlands';
$conf->lang['no']                                      = 'norsk';
$conf->lang['pl']                                      = 'polski';
$conf->lang['pt-PT']                                   = 'português';
$conf->lang['ro']                                      = 'româna';
$conf->lang['sr']                                      = 'српски';
$conf->lang['sl']                                      = 'slovenšcina';
$conf->lang['sk']                                      = 'slovencina';
$conf->lang['fi-FI']                                   = 'suomi';
$conf->lang['sv-SE']                                   = 'svenska';
$conf->lang['el-GR']                                   = 'Ελληνικά';
$conf->lang['bg']                                      = 'bulgarian';
$conf->lang['mk']                                      = 'македонски';
$conf->lang['name']                                    = '';

$conf->select->lang['en-GB']                           = 1;
$conf->select->lang['ca']                              = 0;
$conf->select->lang['cs']                              = 0;
$conf->select->lang['da-DK']                           = 0;
$conf->select->lang['de-DE']                           = 1;
$conf->select->lang['es-ES']                           = 0;
$conf->select->lang['et']                              = 0;
$conf->select->lang['eu']                              = 0;
$conf->select->lang['fr-FR']                           = 0;
$conf->select->lang['gl']                              = 0;
$conf->select->lang['hr-HR']                           = 0;
$conf->select->lang['is']                              = 0;
$conf->select->lang['it-IT']                           = 0;
$conf->select->lang['lt']                              = 0;
$conf->select->lang['lv']                              = 0;
$conf->select->lang['hu-HU']                           = 0;
$conf->select->lang['mt']                              = 0;
$conf->select->lang['nl-NL']                           = 0;
$conf->select->lang['no']                              = 0;
$conf->select->lang['pl']                              = 0;
$conf->select->lang['pt-PT']                           = 0;
$conf->select->lang['ro']                              = 0;
$conf->select->lang['sr']                              = 0;
$conf->select->lang['sl']                              = 0;
$conf->select->lang['sk']                              = 0;
$conf->select->lang['fi-FI']                           = 0;
$conf->select->lang['sv-SE']                           = 0;
$conf->select->lang['el-GR']                           = 0;
$conf->select->lang['bg']                              = 0;
$conf->select->lang['mk']                              = 0;

$conf->webservice->on                                  = 1;
$conf->webservice->sourceapplication                   = 'putCap';
$conf->webservice->login                               = 'googletest';
$conf->webservice->password                            = 'SHlvRkt6bGRESENKRXQ4eEg0Qmd0UT09';
$conf->webservice->entity                              = 1;
$conf->webservice->WS_METHOD                           = 'putCap';
$conf->webservice->ns                                  = 'http://www.meteoalarm.eu:8080/functions/webservices/';
$conf->webservice->WS_DOL_URL                          = 'http://www.meteoalarm.eu:8080/functions/webservices/capimport.php';
$conf->webservice->securitykey                         = '5c2947c0c574e56ac11a4cf8f410d40b';

$conf->conf->output                                    = '';

$conf->trans['en_US']                                  = 'english';
$conf->trans['de_DE']                                  = 'deutsch';
$conf->trans['fr_FR']                                  = 'français';
$conf->trans['es_ES']                                  = 'Español';

?>