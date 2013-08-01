<?php

    class SimpleMysql {

        public $link;

        public $username;
        public $password;

        public $dbname;

        public $host;
        public $port;

        /**
         * @param object $link
         * @param array $options
         */
        public function __construct($link=false, $options=false)
        {
            if ($link) {
                $this->link = $link;
            } else if ($options) {
                foreach($options AS $key => $value) {
                    $this->$key = $value;
                }
                $this->link = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);

                if (!$this->link) {
                    die(mysqli_error($this->link));
                }

                $names = "SET NAMES '".$this->charset."';";
                mysqli_query($this->link, $names);

                $charset = "SET CHARSET '".$this->charset."';";
                mysqli_query($this->link, $charset);
            }
        }

        /**
         * READ Status
         *
         */
        public function readStatus()
        {

        }

        /**
         * CREATE Database
         *
         */
        public function createDatabase()
        {

        }

        /**
         * READ ALL Databases
         *
         */
        public function readAllDatabases()
        {
            $sql = "SHOW DATABASES";
            return $this->query($sql);
        }

        /**
         * DELETE Database
         *
         */
        public function deleteDatabase()
        {

        }

        /**
         * GET LastInsertID
         *
         */
        public function getLastInsertID()
        {
            return mysqli_insert_id($this->link);
        }

        /**
         * CREATE Row
         *
         */
        public function createRow()
        {

        }

        /**
         * READ Row
         *
         */
        public function readRow($id)
        {
            $sql = "SELECT * FROM ".$this->database.".".$this->table." WHERE 1 AND ".$this->table."_id = $id";
            return $this->query($sql);
        }

        /**
         * READ ALL Rows
         *
         */
        public function readAllRows()
        {
            $sql = "SELECT * FROM ".$this->database.".".$this->table;
            return $this->query($sql);
        }

        public function readEav($table, $id, $filters=false)
        {
            $and = "";

            if ($filters) {
                foreach ($filters as $key => $value) {
                    $and .= " $key = '$value'";
                }
            }

            $sql = "SELECT * FROM ".$table."_eav WHERE 1 AND ".$table."_id = $id $and";
            return $this->query($sql);
        }

        /**
         * READ ALL Rows
         *
         */
        public function readUpdatedRows($minute)
        {
            $sql = "SELECT * FROM ".$this->database.".".$this->table." WHERE 1 AND last_updated > DATE_SUB( NOW() , INTERVAL $minute MINUTE )";
            return $this->query($sql);
        }

        /**
         * UPDATE Row
         *
         */
        public function updateRow($params)
        {
            $id = $params['id'];

            unset($params['id']);

            foreach ($params as $key => $value) {
                $sql = "UPDATE ".$this->database.".".$this->table." SET $key = '$value' WHERE 1 AND ".$this->table."_id = $id";
                $this->query($sql);
            }
        }

        /**
         * DELETE Row
         *
         */
        public function deleteRow()
        {

        }

        /**
         * QUERY
         *
         */
        private function query($sql)
        {
            // echo $sql."\n";

            $res = mysqli_query($this->link, $sql);

            if (!$res) {
                return mysqli_error($this->link);
            }

            $arRows = array();

            if (strstr($sql, "SELECT ")) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $arRows[] = $row;
                }

                return $arRows;
            } else {
                return $res;
            }
        }

    }

?>
