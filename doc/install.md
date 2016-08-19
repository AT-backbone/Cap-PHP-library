[Back](../README.md)

#Installation

Download the Version Cap-PHP-library 1.3 [Version 1.3.zip](https://github.com/AT-backbone/Cap-PHP-library/archive/1.3.zip) and extract the zip

## Windows
Download XAMPP with PHP Version 5.6.23 for Windows(PHP 7 is not tested at the moment but will be supported in future releases) [XAMPP](https://www.apachefriends.org/xampp-files/5.6.23/xampp-win32-5.6.23-0-VC11-installer.exe)
* Install XAMPP
* In the XAMPP Control Panel start Apache
* On the right click the button explorer. In the now open Window are several folders one of them is htdocs
* Copy the content of the extracted zip into the htdocs folder
* The Cap-PHP-library is now reachable in your Browser with http://127.0.0.1/

## Linux local (with XAMPP)
Download XAMPP for Linux with Version 5.6.23 32bit or 64bit depending on your OS(PHP 7 is not tested at the moment but will be supported in future releases) [XAMPP](https://www.apachefriends.org/de/download.html)

* Open a terminal
* Make the Installer executeable:<br> 
 `sudo chmod 775 /home/<youruser>/Downloads/xampp-linux-x64-5.6.23-0-installer.run`
* Run the XAMPP Installer as superuser:<br>
 `sudo /home/<youruser>/Downloads/xampp-linux-x64-5.6.23-0-installer.run`
* XAMPP will usually be installed in /opt/htdocs
*Copy the extracted Cap-PHP-library files to /opt/lampp/htdocs/ :<br>
 `sudo cp -R /home/<youruser>/Downloads/Cap-PHP-library-1.3/* /opt/lampp/htdocs/`
* Modify the permissions of the copied content:<br>
 `sudo find /opt/lampp/htdocs -type d -exec chmod 755 {} \;`<br>
 `sudo find /opt/lampp/htdocs -type f -exec chmod 644 {} \;`
* Make the folders /conf and /output writeable:<br>
 `sudo chmod 777 /opt/lampp/htdocs/conf`<br>
 `sudo chmod 777 /opt/lampp/htdocs/output`
* The Cap-PHP-library is now reachable in your Browser with http://127.0.0.1/

## Webspace

* Access you webspace via ftp
* Transfer the content of the extracted zip into the webroot of you webspace. The webroot is often called /web or /htdocs
* Make the folders /conf and /output writeable by giving them permissions 775  (In filezilla for example via: rightclick --> File permissions...)
* The Cap-PHP-library is now reachable in your Browser with http://<yourDomain>/
