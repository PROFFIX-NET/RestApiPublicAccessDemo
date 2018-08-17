PROFFIX REST API PublicAccessDemo
=================================

Einleitung
----------
Die PROFFIX REST API ist für die Verwendung von clientseitigen Webapplikationen optimiert, jedoch ungeeignet um Daten für eine öffentliche Webseite direkt bereitzustellen.
Aufgrund der clientseitigen Authentifizierung bei der PROFFIX REST API, sowie der generalistischen Endpunkte empfiehlt sich für die Verwendung von PROFFIX-Daten auf einer öffentlichen Webseite, eine eigene REST API dazwischen zu schalten.

Weshalb eine eigene REST API?
-----------------------------
Eine eigene REST API kann projektspezifisch für die Verwendung auf einer öffentlichen Webseite aufbereitete Daten übermitteln. Die Endpunkte der eigenen REST API sind hochspezifisch für die Aktionen auf der Webseite optimiert. Ausserdem kann eine eigene REST API die Zugriffe auf die PROFFIX REST API cachen, um Ressourcen zu sparen und die Geschwindigkeit zu erhöhen.
Die PROFFIX REST API muss maskiert werden, um den clientseitigen Loginvorgang zu umgehen (dann befinden sich auch keine Zugangsdaten im öffentlichen Client). Ausserdem ist die PROFFIX REST API mit ihren generalistischen Endpunkten ungeeignet, da höchstwahrscheinlich pro ausgelöster Aktion auf der Webseite mehrere Anfragen zur PROFFIX REST API nötig wären.

Umsetzung
---------
In unserer Demo wird die REST API mit PHP ([Flight](http://flightphp.com/)) und die Webseite mit HTML, CSS ([Bootstrap](https://getbootstrap.com/)) und [jQuery](https://jquery.com/) umgesetzt.
Die REST API welche die Aufrufe zur PROFFIX REST API handelt, wird auf dem Webserver der öffentlichen Webseite in einem Unterordner /api gehostet.
Die PROFFIX REST API läuft als Service ganz normal in einer DMZ auf einem Server.

Inbetriebnahme Entwicklungumgebung
----------------------------------
1. [VisualStudio Code](http://code.visualstudio.com) installieren
    - Workspace in VSCode öffnen
2. Empfohlene Erweiterungen in VSCode installieren: ```> Extensions: Show Workspace Recommend Extensions```
3. Lokaler Apache Webserver mit PHP und MariaDB/MySQL installieren (z.B [MAMP](http://mamp.info) oder [XAMPP](https://www.apachefriends.org))
    - Datenbankserver auf Port 8889 betreiben
    - Standardport für Apache ist egal, da ein virtueller Host definiert wird
4. Lokaler virtueller Host auf Projektverzeichnis konfigurieren
    - In der Apache Konfiguration (*httpd.conf*, bei MAMP für macOS unter */Applications/MAMP/conf/apache/*) eine neuer virtueller Host am Ende des Files hinzufügen:
        ```
        # Virtueller Host RestApiPublicAccessDemo
        Listen 10081
        <VirtualHost *:10081>
            ServerName RestApiPublicAccessDemo.local
            DocumentRoot "Path to repository/RestApiPublicAccessDemo"
        </VirtualHost>
        <Directory "Path to repository/RestApiPublicAccessDemo">
            AllowOverride All
        </Directory>
        ```
    - Es kann jeder beliebige freie Port verwendet werden. Dazu muss nur in der Apache-Konfiguration ein anderer Port verwendet werden.
5. PHP konfigurieren (*php.ini*, bei MAMP für macOS unter */Applications/MAMP/bin/php/php7.1.0/conf*)
    - Error Reporting aktivieren: ```error_reporting  =  E_ALL```
    - Ausgabe von Fehlern aktivieren: ```display_errors = On```
    - XDebug-Erweiterung aktivieren (bei MAMP gefindet sich die Extension bereits in der *php.ini*, jedoch mit einem ```;``` auskommentiert, kontollieren ob der Pfad stimmt)
    - XDebug konfigurieren: In der Section *[xdebug]* folgendes hinzufügen:
        ```
        xdebug.remote_enable = 1
        xdebug.remote_autostart = 1
        ```
6. /api/config.php auf eigene PROFFIX REST API umkonfigurieren (aktuell ist die PROFFIX Online Demo konfiguriert)


Debugging
---------
Die Debug-Konfiguration ***Listen vor XDebug*** kann gestartet werden, dann kann mit einem Webbrowser ein Request auf ein Script gemacht werden,
und bei einem Breakpoint wird angehalten. Eine Voraussetzung ist, dass XDebug aktiviert ist.

Veröffentlichen
---------------
Alles auf den Webserver kopieren, danach folgende Dateien/Verzeichnisse löschen:
- .vscode
- README.md