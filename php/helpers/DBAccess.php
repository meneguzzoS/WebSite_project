<?php

namespace DB;

require_once("FatalError.php");


class DBAccess {
    
    private const HOST = "localhost";
    private const DB_NAME = "my_actorrearchimede";
    private const USERNAME = "root";
    private const PASSWORD = "";

    private $pdo;

    public function isConnected() {
        return (bool) $this->pdo;
    }

    public function openDBConnection() {

        try {

            $this->pdo = new \PDO( // TODO: define bd charset UTF-8
                'mysql:dbname=' . DBAccess::DB_NAME . ';host=' . DBAccess::HOST,
                DBAccess::USERNAME,
                DBAccess::PASSWORD
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
        } catch(\Throwable $ex) { \FatalError::load_fatal_error(); }

        return $this->isConnected();
    }

    public function closeDBConnection() {
        unset($this->pdo);
    }


    public function DBSelectQuery($query, $params = [], $errorFunctionPointer = null) {

        try {

            $stmt = $this->pdo->prepare($query);

            $stmt->execute($params);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch(\Throwable $ex) {

            if(isset($errorFunctionPointer)){
                $errorFunctionPointer();
            }
            else{
                echo $ex;
                return null;
            }
        }
    }

    public function DBQuery($query, $params = []) {

        try {

            $stmt = $this->pdo->prepare($query);

            $stmt->execute($params);

            return $stmt->rowCount() > 0;

        } catch(\Throwable $ex) {

            if(isset($errorFunctionPointer))
                $errorFunctionPointer();

            else
                return null;
        }
    }

    public function easyDBSelectQuery($query, $params = []) {

        $this->openDBConnection();

        try {

            $stmt = $this->pdo->prepare($query);

            $stmt->execute($params);

            $this->closeDBConnection();
    
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch(\Throwable $ex) {

            if(isset($errorFunctionPointer))
                $errorFunctionPointer();

            else
                return null;
        }
    }

    public function easyDBQuery($query, $param = []) {

        $this->openDBConnection();

        try {

            $stmt = $this->pdo->prepare($query);

            $stmt->execute($param);

            $this->closeDBConnection();

            return $stmt->rowCount() > 0;

        } catch(\Throwable $ex) {

            if(isset($errorFunctionPointer))
                $errorFunctionPointer();

            else
                return null;
            
        }
    }

}



?>