#!/bin/bash

echo ""
echo ""
echo "  ************************************"
echo "  ******* W A R N I N G ! ! ! ********"
echo "  ************************************"
echo "  *                                  *"
echo "  * All data will be destroyed!      *"
echo "  *                                  *"
echo "  * Type uppercase 'yes' to continue *"
echo "  *                                  *"
echo "  ************************************"
echo ""
echo -n "  Do you want to continue [no]: "
read confirm

if [[ "$confirm" != "YES" ]]
then
  exit 0
fi

./symfony propel-insert-sql
php batch/load_data.php
