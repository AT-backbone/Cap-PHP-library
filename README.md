# Cap-PHP Library / Cap Creator
A common alerting protocol library for processing and producing XML-CAP's from a JQuery HTML form.

It's an Open Source software (written in PHP) designed for users of the [OASIS common alerting protocol] (http://docs.oasis-open.org/emergency/cap/v1.2/CAP-v1.2-os.html) like
* software developers, 
* hazard authorities for any warnings
* CAP - Starters

You can use it as a standalone application or as a web application accessible via the Internet or via LAN.

You can freely use, study, modify or distribute it according to it's Software licence.

[**more Information**](doc/)

## Features

* Create valid CAPs Files from scratch with a form
* Read and modify existing CAPs
* Send these CAPs via SOAP Webservice
* Convert CAPs between different CAP implementations
* Create CAPs with an easy to use, paint-like interface called [**Paint and Alert**](doc/PaintandAlert.md)


## Install

We provide the following install instructions:
* XAMPP on Windows
* XAMPP on Linux and 
* Normal Webspace

You can find the instructions [here](doc/install.md)

## Paint and Alert

The new addition to the Cap-PHP-Library, Paint and Alert, was developed for Meteorological Forecast-Institutes without softwaredevelopement.

The Cap-PHP-Library was extended so that...
a user which is logged in to the Meteoalarm webservice
and has the permissions to warn a certain country via CAP-Import
... is shown an additional application called Paint and Alert in the menu.
The visual interface eases the input and saves a lot of hassle with the necessities of the Cap-Standard. <br>We spare the user every setting that is not absolutely necessary. Paint and Alert was developed with a responsive design that is fully supported by tablets. The interface resembles a paint program.

[**more Information**](doc/PaintandAlert.md)



## Utilized Third Party Software

The user interface is written using PHP. Dynamic interaction is written in [Javascript](http://en.wikipedia.org/wiki/JavaScript) using the [jQuery Mobile](http://jquerymobile.com/) framework.
 
**NuSOAP - Web Services Toolkit for PHP**

NuSOAP is a set of PHP classes - no PHP extensions required - that allow developers to create and consume web services based on SOAP 1.1, WSDL 1.1 and HTTP 1.0/1.1.
<br>*http://sourceforge.net/projects/nusoap/*

**jQuery Mobile** 

JQuery Mobile is a HTML5 based user interface system designed to make responsive web sites and apps that are accessible in the vast majority of devices
<br>*http://jquerymobile.com/*

**OpenStreetMap**

An openly licensed map of the world being created by volunteers using local knowledge, GPS tracks and donated sources.
<br>*https://www.openstreetmap.org/*

## License

The Cap-PHP Library released under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version (GPL-3+).

See the [COPYING](COPYING) file for a full copy of the license.

Copyright (C) 2016 Guido Schratzer <guido.schratzer@backbone.co.at>

Copyright (C) 2016 Niklas Spanring <n.spanring@backbone.co.at>
