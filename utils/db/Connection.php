<?php
namespace utils\db;

class Connection
{
    private $DB_driver='mysql';
    
    private $DB_host = '192.168.40.67';
    
    private $DB_database = 'job';
    
    private $user = 'root';
    
    private $password = 'Abc@123456';
    
    public function getConn() {
        $con = new \PDO($this->DB_driver.':host='.$this->DB_host.';dbname='.$this->DB_database, $this->user, $this->password);
        return $con;
    }
}

