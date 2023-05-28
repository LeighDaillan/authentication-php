<?php

class dbConn
{
    private $db_host = 'localhost';
    private $db_root = 'root';
    private $db_password = '';
    private $db_name = 'authentication';

    public function connect()
    {
        try {
            $conn = mysqli_connect($this->db_host, $this->db_root, $this->db_password, $this->db_name);
            return $conn;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}