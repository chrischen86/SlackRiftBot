<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace dal;

/**
 * Description of DataAccessAdapter
 *
 * @author chris
 */
class DataAccessAdapter {
    private $conn;
    
    function __construct() {
        $this->conn = new \mysqli(\Config::$Servername, \Config::$Username, \Config::$Password, \Config::$Dbname);
    }
        
    public function GetRifts()
    {
        $sql = "SELECT * FROM test";
	$result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc())
            {
                $data[] = $row;
            }
            
            var_dump($data);
            return $data;
        }
	else {
            echo "0 results";
            return "OK";
	}
    }
    
    public function CreateRift()
    {
        $sql = "INSERT INTO test(`username`, `date_created`) VALUES ('test', UTC_TIMESTAMP())";
        $this->conn->query($sql);
    }
    
    public function UpsertUser($id, $userName, $vip=0)
    {
        $sql = "INSERT INTO users(`id`, `name`, `vip`) VALUES ('$id', '$userName', $vip)" .
                " ON DUPLICATE KEY UPDATE name='$userName', vip=$vip";
        $result = $this->conn->query($sql);        
    }
    
    public function GetUser($id)
    {
        $sql = "SELECT * from users WHERE id='$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
