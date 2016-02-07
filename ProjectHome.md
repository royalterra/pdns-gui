Web based GUI which aids in administering domains and records for the [PowerDNS](http://www.powerdns.com/) name server software with MySQL backend.

## Installation ##

1. Install Apache, PHP, MySQL and PowerDNS server - on Ubuntu/Debian Linux:
```
apt-get install apache2 php5 php5-cli php5-mysql php5-xsl mysql-server pdns-server pdns-backend-mysql
```

2. Download PowerDNS GUI from this page and decompress
```
tar xvf pdns-gui.x.x.tgz
```

3. Run install script
```
cd /var/www/pdns-gui.x.x/batch

./install.sh
```

If you want to upgrade existing PowerDNS GUI installation follow this instructions: [Upgrade](Upgrade.md)

## Demo ##
http://www.powerdns-gui.org/


## Screenshot ##
![http://level7systems.co.uk/images/blog/powerdns-gui-screenshot.png](http://level7systems.co.uk/images/blog/powerdns-gui-screenshot.png)