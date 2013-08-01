curl -X DELETE 'http://127.0.0.1:9200/_river'
#{"ok":true,"acknowledged":true}
                                                                                                                                                                            
curl -X DELETE 'http://127.0.0.1:9200/es_admin_cit_product'
#{"ok":true,"acknowledged":true}

curl -X DELETE 'http://127.0.0.1:5984/es_admin_cit_product'
#{"ok":true}

curl -X PUT 'http://127.0.0.1:5984/es_admin_cit_product'
#{"ok":true}

curl -X PUT 'http://127.0.0.1:5984/es_admin_cit_product/afe8eb657145b3d0e6a197101758f6ff' -d '{"name":"test name","description":"test description"}'
#{"ok":true,"id":"afe8eb657145b3d0e6a197101758f6ff","rev":"3-2cc01e2240c1ec39477ac81db2bb2849"}

curl -X PUT 'http://127.0.0.1:5984/es_admin_cit_product/afe8eb657145b3d0e6a197101758f6ff' -d '{"_id": "afe8eb657145b3d0e6a197101758f6ff", "_rev": "3-2cc01e2240c1ec39477ac81db2bb2849", "name":"test name", "description":"test description", "last_updated": "2013-06-18 17:06:00"}'
#{"ok":true,"id":"afe8eb657145b3d0e6a197101758f6ff","rev":"4-6238d2eabff57421bec7681c1d025533"}

curl -X PUT 'http://127.0.0.1:9200/_river/es_admin_cit_product/_meta' -d '{ "type" : "couchdb", "couchdb" : { "host" : "127.0.0.1", "port" : 5984, "db" : "es_admin_cit_product", "filter" : null }, "index" : { "index" : "es_admin_cit_product", "type" : "es_admin_cit_product", "bulk_size" : "100", "bulk_timeout" : "10ms" } }'
#{"ok":true,"_index":"_river","_type":"es_admin_cit_product","_id":"_meta","_version":1}

sudo /etc/init.d/couchdb restart
# * Restarting database server couchdb [ OK ] 

sudo /etc/init.d/couchdb status       
#Apache CouchDB is running as process 17747, time to relax.

sudo /etc/init.d/elasticsearch restart
# * Stopping ElasticSearch Server [ OK ] 
# * Starting ElasticSearch Server [ OK ] 

sudo /etc/init.d/elasticsearch status
#ElasticSearch Server is running with pid 17826

curl -X GET 'http://127.0.0.1:9200/_search' -d '{"query":{"bool":{"must":[{"query_string":{"default_field":"name","query":"test"}},{"query_string":{"default_field":"description","query":"test"}},{"query_string":{"default_field":"last_updated","query":"*2013*"}}],"must_not":[],"should":[]}},"from":0,"size":50,"sort":[],"facets":{}}'
#{"took":90,"timed_out":false,"_shards":{"total":15,"successful":15,"failed":0},"hits":{"total":1,"max_score":0.2712221,"hits":[{"_index":"es_admin_cit_product","_type":"es_admin_cit_product","_id":"afe8eb657145b3d0e6a197101758f6ff","_score":0.2712221, "_source" : {"_rev":"1-6c2eddd8af03311a2f0c07cc6eb1d5bf","_id":"afe8eb657145b3d0e6a197101758f6ff","description":"test description","name":"test name"}}]}}
