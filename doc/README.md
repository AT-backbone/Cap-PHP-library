#PHP CAP-PHP-LIBRARY Version 1.1
 
The CAP-PHP Library was developed to easily edit and manage CAPs on every device without knowing the depths of the OASIS CAP Protocol.

The developed forms work on every Device - including smartphones.

![Alert](img/alerten.png?raw=true "Alert")


[more Pictures](MorePic.md)

You can install and use the CAP-PHP-Library on a Apache Webserver or on your local PC with LAMP or WAMP.
The Library is open-source therefore you may freely alter it or include functions in your own application.

#Features in Version 1.1

1. **Converting between different CAP formats**
<br>You can include convert-files which enable you to alter tags and values (like Eventcode / Parameter/ Areas) to your choosen style.
<br>For instance: change value "hazard-type" to "kind of alert" or  "geocode" to "areacode" or "Deutschland" to "Germany"

2. **The export via SOAP-webservice was refined**
<br>Integration of the Meteoalarm webservice. This function makes it fairly easy to send a CAP to another webservice too.

3. **Forms were refined for more usability**
<br>Matching values were grouped together.

4. **Added an error- and debug-log**

5. **Online-help was created**

6. **Translations**
<br>the following languages are available: english, german, espanol, french

#Outlook on Version 1.2

1. **GeoAdminTool for multiple Warnings**
<br>A form for multiple Warnings. With one click you can create multiple CAPs and an atom-feed.

2. **Automatic converting**
<br>Automatic conversion of CAPs added to an input folder.

3. **Display of CAPs as a news-report**

If we spiked your interest you may test the application on our test-server:
<br>https://dolibarr-demo.companyweb.at/public/webservices/cap1.1/

If you want to install this application or want to have a look at the sources visit us on Github <br>https://github.com/AT-backbone/Cap-PHP-library

(*) PHP Version 5.2.+ 
