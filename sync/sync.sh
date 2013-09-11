cd /var/www/vhosts/checkinthings.com/httpdocs/sync/

/usr/bin/php /var/www/vhosts/checkinthings.com/httpdocs/sync/mysql-to-couchdb.php 0 1 0 2 >> mysql-to-couchdb.log
/usr/bin/php /var/www/vhosts/checkinthings.com/httpdocs/sync/couchdb-to-mysql.php customer >> couchdb-to-mysql.log
