<?php
    class db{
        // Properties
        private $dbhost = 'asedb.mysql.database.azure.com';
        private $dbuser = 'aseadmin@asedb';
        private $dbpass = 'Pa$$w0rd';
        private $dbname = 'ase';
        // Connect
        public function connect(){
            $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
            $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }
    }

    ?>