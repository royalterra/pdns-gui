# pdns-gui
Automatically exported from code.google.com/p/pdns-gui

Web based GUI which aids in administering domains and records for the PowerDNS name server software with MySQL backend.

Installation

Install Apache, PHP, MySQL and PowerDNS server - on Ubuntu/Debian Linux: apt-get install apache2 php5 php5-cli php5-mysql php5-xsl mysql-server pdns-server pdns-backend-mysql

Download PowerDNS GUI from this page and decompress tar xvf pdns-gui.x.x.tgz

Run install script ``` cd /var/www/pdns-gui.x.x/batch

./install.sh ```

If you want to upgrade existing PowerDNS GUI installation follow this instructions: Upgrade

Demo

http://www.powerdns-gui.org/

To upgrade existing installation of PowerDNS GUI: * copy config/database.yml to /tmp/database.yml * overwrite your existing pdns-gui.x.x directory with the latest version from github copy /tmp/database.yml to config/database.yml * run `./batch/upgrade.sh' script
