cd /var/www/vhosts/admin/cit/sync/

/usr/bin/php /var/www/vhosts/admin/cit/sync/mysql-to-couchdb.php customer 0 1 0 2 >> mysql-to-couchdb.log
/usr/bin/php /var/www/vhosts/admin/cit/sync/couchdb-to-mysql.php customer >> couchdb-to-mysql.log

/usr/bin/php /var/www/vhosts/admin/cit/sync/mysql-to-couchdb.php product 0 1 0 2 >> mysql-to-couchdb.log

/usr/bin/php /var/www/vhosts/admin/cit/sync/couchdb-to-mysql.php track >> couchdb-to-mysql.log

#curl -X DELETE 'http://127.0.0.1:9200/_river'

#curl -X DELETE 'http://127.0.0.1:9200/es_admin_cit_product'

#curl -X PUT 'http://127.0.0.1:9200/_river/es_admin_cit_product/_meta' -d '{ "type" : "couchdb", "couchdb" : { "host" : "127.0.0.1", "port" : 5984, "db" : "es_admin_cit_product", "filter" : null }, "index" : { "index" : "es_admin_cit_product", "type" : "es_admin_cit_product", "bulk_size" : "100", "bulk_timeout" : "10ms" } }'
