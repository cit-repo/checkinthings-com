<?php

    class couchdbSimple {

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
         * READ Status
         *
         */
        public function readStatus()
        {
            return $this->send("GET", "/");
        }

        /**
         * CREATE Database
         *
         */
        public function createDatabase()
        {
            return $this->send("PUT", "/$this->database/");
        }

        /**
         * READ ALL Databases
         *
         */
        public function readAllDatabases()
        {
            return $this->send("GET", "/_all_dbs");
        }

        /**
         * DELETE Database
         *
         */
        public function deleteDatabase()
        {
            return $this->send("DELETE", "/$this->database/");
        }

        /**
         * GET UUID
         *
         */
        public function getUUID()
        {
            $uuids = $this->send("GET", "/_uuids");

            $uuids = json_decode($uuids);

            return $uuids->uuids[0];
        }

        /**
         * CREATE Document
         *
         */
        public function createDocument($params)
        {
            $uuid = $this->getUUID();

            $arParams = array();

            foreach ($params as $key => $value) {
                if (isset($value) && $value != '') {
                    $arParams[] = '"'.(rtrim($key)).'"'.':"'.(rtrim($value)).'"';
                }
            }

            $request = '{'.implode(", ", $arParams).'}';

            return $this->send("PUT", "/$this->database/$uuid", $request);
        }

        /**
         * READ Document
         *
         */
        public function readDocument($uuid)
        {
            return $this->send("GET", "/$this->database/$uuid");
        }

        /**
         * READ ALL Documents
         *
         */
        public function readAllDocuments()
        {
            return $this->send("GET", "/$this->database/_all_docs");
        }

        /**
         * UPDATE Document
         *
         */
        public function updateDocument($uuid, $params_new)
        {
            $document = $this->readDocument($uuid);

            // var_dump($document);

            $params = json_decode($document, true);

            foreach ($params_new as $key => $value) {
                if (isset($params[$key]) && $params[$key] != $params_new[$key]) {
                    $params[$key] = $value;
                }
            }

            $arParams = array();

            foreach ($params as $key => $value) {
                if (isset($value) && $value != '') {
                    $arParams[] = '"'.(rtrim($key)).'"'.':"'.(rtrim($value)).'"';
                }
            }

            unset($arParams['uuid']);

            $request = '{'.implode(", ", $arParams).'}';

            return $this->send("PUT", "/$this->database/$uuid", $request);
        }

        /**
         * DELETE Document
         *
         */
        public function deleteDocument($uuid)
        {
            return $this->send("DELETE", "/$this->database/$uuid");
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
