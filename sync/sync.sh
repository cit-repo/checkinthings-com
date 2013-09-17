cd /var/www/vhosts/checkinthings.com/httpdocs/sync/

# Customer
/usr/bin/php /var/www/vhosts/checkinthings.com/httpdocs/sync/mysql-to-couchdb.php customer 0 1 0 2 >> mysql-to-couchdb.log
/usr/bin/php /var/www/vhosts/checkinthings.com/httpdocs/sync/couchdb-to-mysql.php customer >> couchdb-to-mysql.log

# Product
/usr/bin/php /var/www/vhosts/checkinthings.com/httpdocs/sync/mysql-to-couchdb.php product 0 1 0 2 >> mysql-to-couchdb.log

# Track
/usr/bin/php /var/www/vhosts/checkinthings.com/httpdocs/sync/couchdb-to-mysql.php track >> couchdb-to-mysql.log
