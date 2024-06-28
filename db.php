<?php

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class database {

private $servername = "127.0.0.1";
private $username   = "root";
private $password  = "";
private $dbname = "blog";
public $secret_key="F2CkPhPANDFU511nGLA1aV%lFROMNOWONIWILLUSEJAVAANDJAVASCRIPTANDGOMAYBEPYTHONdjangoalso";
public $conn;

public function __construct() {
    $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
    }
}

public function authentication(){
  $headers = apache_request_headers();
  if (isset($headers['Authorization'])) {
          $token = str_replace('Bearer ', '', $headers['Authorization']);
          $key = base64_decode($this->secret_key);
    try {
        $decoded = JWT::decode($token, new key($key,'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        return null;
    }
    }
return null;

    }
}
?>
