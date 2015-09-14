
Kiezatlas Wordpress Plugin
==========================

## Nutzungshinweise

### Kiezatlas Page

Dieses Vorgehen ermöglicht es einen gesamten Kiezatlas-Stadtplan anhand seiner Kriterien und Kategorien in einer eigenen (Wordpress-)_Page_ einzubinden.

1. Kopieren sie aus diesem Repository den Unterordner "kiezatlas-page" in den `wp-content/plugins/`-Ordner ihrer Wordpress-Installation.
2. Loggen sie sich in ihr Wordpress ein und aktivieren sie das "Kiezatlas Page" Plugin unter "Plugins".
3. Veröffentlichen sie eine Wordpress "Kiezatlas Page" unter _exakt_ der folgenden URL-Endung: `/kiezatlas-citymap/stadtplan`.
4. Ergänzen sie ihre neue "Kiezatlas Page" um zwei sog. "Custom Fields". Gehen Sie dazu folgendermaßen vor:<br/>
4.1 Wählen sie "Screen Options" (ganz oben rechts im Fenster) > "Custom Fields" auswählen/ankreuzen.<br/>
4.2 Klappen sie nun unterhalb des Blogtexts den "Custom Fields"-Bereich aus (nur anklicken reicht).<br/>
4.3 Beim erstmaligen Eintrag wählen sie hier "Enter new" > "Add custom field" und tragen dort exakt `Kiezatlas Citymap ID` ein und im Eingabefeld "Value" daneben die ID des gewünschten Kiezatlas-Stadtplans (z.B.: _t-1620631_).<br/>
4.4 Beim erstmaligen Eintrag wählen sie hier "Enter new" > "Add custom field" und tragen dort exakt `Kiezatlas Workspace ID` ein und im Eingabefeld "Value" daneben die ID des gewünschten Kiezatlas-Stadtplans (z.B.: _t-1620631_).<br/>
5. Veröffentlichen sie den Page-Beitrag und eine OpenStreetMap-Karte mit den Standortinformation zu ihrem Kiezatlas-Datensatz sollte automatisch am Ende des Blogbeitrags erscheinen.


### Kiezatlas Post

Dieses Vorgehen ermöglicht es einen einzelnen Kiezatlas-Datensatz in einen (Wordpress-Blog-)_Post_ mit einer OpenStreetMap Standort-Karte einzubetten.

Achtung: Sie müssen dazu ihre `post-template.php`-Datei manuell um PHP-Code ergänzen.

1. Kopieren sie aus diesem Repository den Unterordner "kiezatlas-post" in den `wp-content/plugins/`-Ordner ihrer Wordpress-Installation.
2. Loggen sie sich in ihr Wordpress ein und aktivieren sie das neue Plugin unter "Plugins".
3. Schreiben sie ihren Blogeintrag (_Post_) wie gewohnt nur tragen sie zusätzlich einen sog. "Custom Fields" ein um die Kiezatlas Karte für einen Kiezatlas-Datensatz einzurichten. Gehen Sie dazu folgendermaßen vor: 
3.1 "Add New" > Select "Screen Options" (ganz oben rechts im Fenster) > "Custom Fields" auswählen/ankreuzen.
3.2 Klappen sie nun unterhalb des Blogtexts den "Custom Fields"-Bereich aus (nur anklicken reicht).
3.3 Beim erstmaligen Eintrag wählen sie hier "Enter new" und tragen dort exakt "Kiezatlas Topic ID" ein und im Eingabefeld "Value" daneben die ID ihres gewünschten Kiezatlas-Datensatzes (z.B.: _t-1620631_).
4. Veröffentlichen sie ihren Beitrag und eine OpenStreetMap-Karte mit den Standortinformation zu ihrem Kiezatlas-Datensatz sollte automatisch am Ende des Blogbeitrags erscheinen.

## License & Third party notes (English) 

This repository additonally contains a copy of:
- PHP Pear HTTP Module released under the New BSD License (http://opensource.org/licenses/bsd-license.php)
- jQuery 1.9.1 released under the MIT License (http://en.wikipedia.org/wiki/MIT_License)

## License

Released under GNU GPL 3.0 and comes without any warrant.

## Version History

1.0-SNAPSHOT, 13 September 2015
- Updated README
- Fetches and renders a city-map as a wordpress-page
- Fetches and renders every single data-entry of a city-map as a wordpress-page

Author: Malte Reißig

