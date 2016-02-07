To upgrade existing installation of PowerDNS GUI:
  * copy `config/database.yml` to `/tmp/database.yml`
  * overwrite your existing `pdns-gui.x.x` directory with the latest version downloaded from http://code.google.com/p/pdns-gui/downloads/list
  * copy `/tmp/database.yml` to `config/database.yml`
  * run `./batch/upgrade.sh' script