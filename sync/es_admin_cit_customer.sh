curl -X DELETE 'http://127.0.0.1:9200/_river'
#{"ok":true,"acknowledged":true}

curl -X DELETE 'http://127.0.0.1:9200/es_admin_cit_customer'
#{"ok":true,"acknowledged":true}

curl -X DELETE 'http://127.0.0.1:5984/es_admin_cit_customer'
#{"ok":true}

curl -X PUT 'http://127.0.0.1:5984/es_admin_cit_customer'
#{"ok":true}

curl -X PUT 'http://127.0.0.1:9200/_river/es_admin_cit_customer/_meta' -d '{ "type" : "couchdb", "couchdb" : { "host" : "127.0.0.1", "port" : 5984, "db" : "es_admin_cit_customer", "filter" : null }, "index" : { "index" : "es_admin_cit_customer", "type" : "es_admin_cit_customer", "bulk_size" : "100", "bulk_timeout" : "10ms" } }'
#{"ok":true,"_index":"_river","_type":"es_admin_cit_customer","_id":"_meta","_version":1}

sudo /etc/init.d/couchdb restart
# * Restarting database server couchdb [ OK ] 

sudo /etc/init.d/couchdb status       
#Apache CouchDB is running as process 17747, time to relax.

sudo /etc/init.d/elasticsearch restart
# * Stopping ElasticSearch Server [ OK ] 
# * Starting ElasticSearch Server [ OK ] 

sudo /etc/init.d/elasticsearch status
#ElasticSearch Server is running with pid 17826
