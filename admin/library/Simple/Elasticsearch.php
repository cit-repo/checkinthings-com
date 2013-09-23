<?php

    class SimpleElasticsearch {

        public $user;
        public $pass;

        public $host;
        public $port;
        public $errno;
        public $errstr;

        public $request;
        public $response;
        public $body;

        /**
         * CONSTRUCT
         *
         */
        public function __construct($options)
        {
            foreach($options AS $key => $value) {
                $this->$key = $value;
            }
        }

        /**
         * DELETE River
         *
         */
        public function deleteRiver()
        {
            return $this->send("DELETE", "/_river");
        }

        /**
         * DELETE River Index
         *
         */
        public function deleteIndex($index)
        {
            return $this->send("DELETE", "/$index");
        }

        /**
         * CREATE River Index
         */
        public function createRiverIndex($index)
        {
            $request = '{ "type" : "couchdb", "couchdb" : { "host" : "127.0.0.1", "port" : 5984, "db" : "'.$index.'", "filter" : null }, "index" : { "index" : "'.$index.'", "type" : "'.$index.'", "bulk_size" : "100", "bulk_timeout" : "10ms" } }';
            return $this->send("PUT", "/_river/$index/_meta", $request);
        }

        /**
         * SEARCH
         *
         */
        public function search($must, $must_not=false, $should=false, $from=0, $size=50, $sort=false, $index=false)
        {
            $arMust = array();
            if ($must)
                foreach ($must as $key => $value) {
                    if (isset($value) && $value != '') $arMust[] = '{"query_string":{"default_field":"'.$key.'","query":"'.$value.'"}}';
                }

            $arMustNot = array();
            if ($must_not)
                foreach ($must as $key => $value) {
                    if (isset($value) && $value != '') $arMustNot[] = '{"query_string":{"default_field":"'.$key.'","query":"'.$value.'"}}';
                }

            $arShould = array();
            if ($should)
                foreach ($must as $key => $value) {
                    if (isset($value) && $value != '') $arShould[] = '{"query_string":{"default_field":"'.$key.'","query":"'.$value.'"}}';
                }

            if ($sort)  {
                $sort = '{"_script":{"script":"Math.random()","type":"number","params":{},"order":"asc"}}';
            } else {
                $sort = '[]';
            }

            $request = '{"query":{"bool":{"must":['.implode(",",$arMust).'],"must_not":['.implode(",",$arMustNot).'],"should":['.implode(",",$arShould).']}},"from":'.$from.',"size":'.$size.',"sort":'.$sort.',"facets":{}}';

            // echo $request;

            if (!$index) {
                $url = "/_search";
                return $this->send("GET", $url, $request);
            } else {
                $url = "/$index/_search";
                return $this->send("GET", $url, $request);
            }

        }

        public function searchFirst($entity, $attribute=false, $value=false)
        {
            $request = '{"query":{"bool":{"must":[{"match_all":{}}],"must_not":[],"should":[]}},"from":0,"size":50,"sort":[],"facets":{}}';

            $index = "es_admin_cit_".$entity;

            $url = "/".$index."/_search";

            $res = $this->send("GET", $url, $request);

            $arRes = json_decode($res, true);

            $found = false;

            foreach ($arRes['hits']['hits'] as $hit) {
                foreach ($hit["_source"] as $k => $v) {
                    // echo $k." ".$v."\n";
                    if ($k == $attribute && $v == $value) {
                        $found = $hit;
                    }
                }
            }

            unset($arRes['hits']['hits']);
            $arRes['hits']['hits'][] = $found;

            return json_encode($arRes);
        }

        /**
         * SEND
         *
         */
        private function send($method, $url, $post_data = NULL)
        {
            $s = fsockopen($this->host, $this->port, $this->errno, $this->errstr);

            if(!$s) {
                echo "$this->errno: $this->errstr\n";
                return false;
            }

            $this->request = "$method $url HTTP/1.0\r\nHost: $this->host\r\n";

            if ($this->user) {
                $this->request .= "Authorization: Basic ".base64_encode("$this->user:$this->pass")."\r\n";
            }

            if($post_data) {
                $this->request .= "Content-Length: ".strlen($post_data)."\r\n\r\n";
                $this->request .= "$post_data\r\n";
            } else {
                $this->request .= "\r\n";
            }

            fwrite($s, $this->request);
            $this->response = "";

            while(!feof($s)) {
                $this->response .= fgets($s);
            }

            list($this->headers, $this->body) = explode("\r\n\r\n", $this->response);
            return $this->body;
        }

    }

?>
