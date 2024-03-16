<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Header: *');
header('Access-Control-Allow-Methods: *');
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

class DbConnect
{
    private $server = "sql11.freesqldatabase.com";
    private $dbname = "sql11691767";
    private $user = "sql11691767";
    private $pass = "yj1MGZkUFu";

    public function connect()
    {
        try {
            $conn =  new PDO("mysql:host=" . $this->server . ";dbname=" . $this->dbname, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (\Exception $err) {
            echo "Database Error ! :" . $err->getMessage();
        }
    }
}
