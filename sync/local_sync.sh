/usr/bin/php /var/www/vhosts/admin/cit/sync/mysql-to-couchdb.php 0 1 0 2

#curl -X DELETE 'http://127.0.0.1:9200/_river'

#curl -X DELETE 'http://127.0.0.1:9200/es_admin_cit_product'

#curl -X PUT 'http://127.0.0.1:9200/_river/es_admin_cit_product/_meta' -d '{ "type" : "couchdb", "couchdb" : { "host" : "127.0.0.1", "port" : 5984, "db" : "es_admin_cit_product", "filter" : null }, "index" : { "index" : "es_admin_cit_product", "type" : "es_admin_cit_product", "bulk_size" : "100", "bulk_timeout" : "10ms" } }'
