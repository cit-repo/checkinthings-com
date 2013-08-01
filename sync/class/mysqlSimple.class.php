<?php

    class mysqlSimple {

        public $link;

        public $user;
        public $pass;

        public $host;
        public $port;

        /**
         * CONSTRUCT
         *
         */
        public function __construct($link, $options)
        {
            $this->link = $link;

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
         * GET Last Insert Id
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
         * READ Eav
         *
         */
        public function readEav($id)
        {
            $sql = "SELECT * FROM ".$this->database.".".$this->table."_eav WHERE 1 AND ".$this->table."_id = $id";
            return $this->query($sql);
        }

        /**
         * READ All Rows
         *
         */
        public function readAllRows()
        {
            $sql = "SELECT * FROM ".$this->database.".".$this->table;
            return $this->query($sql);
        }

        /**
         * READ Updated Rows
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
