<?php //databases/MariaDB.php

class MariaDB
{
    private $host = 'localhost';
    private $db_name = 'webservices';
    private $username = 'senac';
    private $password = 'senac123';
    public $connect;

    public function dbConnection(){
        $this-> connect = null;
        try {
            $this-> connect = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this-> connect->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $exception){
            echo 'Connection error: ' . $exception->getMessage();
        }
        return $this-> connect;
    }
}