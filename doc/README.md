#PHP CAP-PHP-LIBRARY Version 1.1
 
Die CAP-PHP Library wurde entwickelt um Cap Dateien einfach auf jedem Computer zu erstellen, zu editieren 
und zu verwalten - ohne sich zuvor intensiv mit dem Cap Profil von OASIS beschäftigt zu haben.

Die dazu entwickelten Formulare können mit jedem Browser - auch auf jedem Smartphone - benützt werden.

![Alert](img/alerten.png?raw=true "Alert")



[more Pictures](MorePic.md)


Die CAP-PHP-Library kann entweder auf einem Apache Webserver(*) oder auf einem lokalen Computer mittels LAMP oder WAMP installiert und betrieben werden.
Durch die Open-Source Lizensierung können die beinhalteten Funktionen nach belieben in eigene Applikationen eingebunden und weiterentwickelt werden.
 
#Erweiterung in der Version 1.1 

1. Konvertieren von verschiedenen CAP Formaten
Über Konfigurationsdateien können Tags und Werte (z.B. Eventcode / Parameter/ Areas) in eine andere gewünschte Formatierung gebracht werden.
 
Aufgrund von Einstellungen bzw. Inhalten im Quell-CaP können automatisiert Tags in ein Ziel-Cap erzeugt werden.
z.B. change value "hazard-type" to "kind of alert" or  "geocode" to "areacode" or "Deutschland" to "Germany"
 
2. Der Export per SOAP Webservice wurde weiterentwickelt
Integration auf meteoalarm webservice. Diese Funktion sollte es auch einfach machen eine Cap Datei über ein anderes Webservice zu versenden.
 
3. Formulare wurden benutzerfreundlich gestaltet.
Werte die Inhaltlich zusammen passen wurden gezielt gruppiert.
 
4. Error bzw. Debug Log wurde erstellt
um die Funktionalität zu kontrollieren. 
 
5. Online-Hilfe wurde erstellt
 
6. Übersetzungen 
folgende Sprachen sind verfügbar: english, german, espanol, french
 
 
#Aussichten auf Version 1.2

In der RoadMap für die Version 1.2 stehen bisher folgende Funktionserweiterungen:
 
**1.GeoAdminTool für mehrfache Meldungen erstellen**
Über ein Formular können mehrere Warnungen erfasst werden. Dafür wird ein GeoAdminTool erstellt.
Mit einem Klick werden dann die benötigten CaP Dateien sowie ein atom-feed erstellt und auf wunsch komprimiert angeboten.
 
**2.Automatische Konvertierung**
Über ein Input und Output Formular werden automatisiert alle Warnungen die neu ins Inputverzeichnis kopiert werden, konvertiert und ins Output Verzeichnis gestellt.
Die verarbeiteten Dateien werden in das Verzeichnis Input.Finished oder bei einem Fehler in das Verzeichnis Input.SyntaxError verschoben.
 
**3.Cap Anzeige in einer News-Box**
Die Warnung wird in Form von einer News-Anzeige angezeigt
 
Wann Sie jetzt Interesse habe können sie die Anwendung gerne in unserer Demoumgebung ausprobieren.
https://dolibarr-demo.companyweb.at/public/webservices/cap1.1/
 
Falls Sie die Anwendung installieren wollen oder Sie sich für die Sourcen interessieren können Sie unter folgenden Link https://github.com/AT-backbone/Cap-PHP-library von GitHub die Sourcen herunter laden.
 
(*) PHP Version 5.2.+ 
 



