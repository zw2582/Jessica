<?php
namespace utils\db;

class DbUtils
{
    
    static $conn = false;
    
    private static function getConn() {
        if (self::$conn === false) {
            $connection = new Connection();
            self::$conn = $connection->getConn();
        }
        return self::$conn;
    }
    
    public static function queryOne($sql, $params=null) {
        return self::query($sql, $params);
    }
    
    public static function queryAll($sql, $params=null) {
        return self::query($sql, $params, 'fetchAll');
    }
    
    private static function query($sql, $params=null, $fetType="fetch") {
        $conn = self::getConn();
        
        $statement = $conn->prepare($sql);
        
        if ($params) {
            foreach ($params as $k=>$v) {
                if (is_numeric($k)) {
                    $k++;
                }
                $statement->bindValue($k, $v);
            }
        }
        if ($statement->execute()) {
            if ($fetType == 'fetch') {
                $data = $statement->fetch(\PDO::FETCH_ASSOC);
            } else {
                $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            }
            return $data;
        } else {
            throw new \Exception("pdostatement error:{$statement->errorCode()}, errorinfo:{$statement->errorInfo()[2]}");
        }
    }
    
    private static function save($sql, $params, $type="insert") {
        $conn = self::getConn();
        
        $statement = $conn->prepare($sql);
        
        if ($params) {
            foreach ($params as $k=>$v) {
                if (is_numeric($k)) {
                    $k++;
                }
                $statement->bindValue($k, $v);
            }
        }
        if ($statement->execute()) {
            if ($type == 'insert') {
                return $conn->lastInsertId();
            } else {
                return $statement->rowCount();
            }
        } else {
            throw new \Exception("pdostatement error:{$statement->errorCode()}, errorinfo:{$statement->errorInfo()[2]}");
        }
    }
    
    public static function insert($sql, $params) {
        return self::save($sql, $params);
    }
    
    public static function update($sql, $params) {
        return self::save($sql, $params, 'update');
    }
}

