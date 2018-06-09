<?php
namespace utils\db;

class QueryBuilder
{
    
    private $_where = [];
    
    private $_table;
    
    private $_field = "*";
    
    private $_update = [];
    
    private $_insert = [];
    
    private $_offset = null;
    
    private $_limit = null;
    
    public function offset($offset) {
        $this->_offset = $offset;
        return $this;
    }
    
    public function limit($limit) {
        $this->_limit = $limit;
        return $this;
    }
    
    /**
     * @author zhouweiphp
     * @param array $data ['id'=>0]
     * 2018年5月25日 下午1:43:08
     */
    public function where($data) {
        $this->_where = array_merge($this->_where, $data);
        return $this;
    }
    
    public function table($table) {
        $this->_table = $table;
        return $this;
    }
    
    public function field($data) {
        if (is_array($data) && is_array($this->_field)) {
            $this->_field = array_merge($this->_field, $data);
        } else {
            $this->_field = $data;
        }
        
        return $this;
    }
    
    /**
     * return affected rows
     * @author zhouweiphp
     * @param array $data
     * @return string
     * 2018年6月7日 下午3:05:21
     */
    public function update($data):int {
        $this->_update= array_merge($this->_update, $data);
        return $this->execute();
    }
    
    /**
     * return last insert id
     * @author zhouweiphp
     * @param array $data
     * @return string
     * 2018年6月7日 下午3:05:11
     */
    public function insert($data):int {
        $this->_insert= array_merge($this->_insert, $data);
        return $this->execute();
    }
    
    private function execute() {
        if (!empty($this->_insert)) {
            $insertKeys = array_keys($this->_insert);
            $valzhan = str_pad("", count($insertKeys)*2, ",?");
            $valzhan = trim($valzhan, ',');
            
            $sql = "insert into {$this->_table} (".implode(",", $insertKeys).")value({$valzhan})";
            
            return DbUtils::insert($sql, array_values($this->_insert));
        }
        
        if (!empty($this->_update)) {
            $updateKeys = array_keys($this->_update);
            
            $updateStr = array_reduce($updateKeys, function($val1, $val2){
                return "$val1,$val2=?";
            });
            $updateStr = trim($updateStr, ',');
            $sql = "update {$this->_table} set $updateStr ";
            $updateData = array_values($this->_update);
            
            if ($this->_where) {
                list($whereStr, $whereData) = $this->resoleWhere();
                $sql .= $whereStr;
                $updateData = array_merge($updateData, $whereData);
            }
            
            return DbUtils::update($sql, $updateData);
        }
    }
    
    
    private function query($type="queryOne") {
        $fields = $this->_field;
        if (is_array($fields)) {
            $fields = implode(",", $fields);
        }
        $sql = "select {$fields} from {$this->_table} ";
        
        $whereData = [];
        if ($this->_where) {
            list($whereStr, $whereData) = $this->resoleWhere();
            $sql .= $whereStr;
        }
        if (isset($this->_offset) && $this->_limit) {
            $sql .= "limit {$this->_offset},{$this->_limit} ";
        }
        
        return DbUtils::$type($sql, $whereData);
    }
    
    public function querySql($sql, $data) {
        return DbUtils::$type($sql, $data);
    }
    
    public function queryOne() {
        return $this->query();
    }
    
    public function queryAll() {
        return $this->query("queryAll");
    }
    
    public function count($field='*') {
        $this->_field = "count({$field}) as count";
        $data = $this->query();
        return $data['count'];
    }
    
    private function resoleWhere() {
        $whereStr = "";
        $whereData = [];
        if ($this->_where) {
            foreach ($this->_where as $key=>$val) {
                if (is_array($val)) {
                    if (in_array($val[0], ['=','<>', '<', '>', '<=', '>=', 'like'])) {
                        $whereStr .= "$key {$val[0]} ? ";
                        $whereData[] = $val[1];
                    } elseif ($val[0] == 'in') {
                        if (!is_array($val[1])) {
                            throw new \Exception('in的params必须是数组');
                        }
                        $inzhan = trim(str_pad("", count($val[1])*2, ',?'), ',');
                        $whereStr .= "$key in ($inzhan) ";
                        $whereData = array_merge($whereData, $val[1]);
                    } else {
                        $inzhan = trim(str_pad("", count($val)*2, ',?'), ',');
                        $whereStr .= "$key in ($inzhan) ";
                        $whereData = array_merge($whereData, $val);
                    }
                } else {
                    $whereStr .= "$key=? ";
                    $whereData[] = $val;
                }
            }
        }
        if ($whereStr) {
            $whereStr = ' where '.$whereStr;
        }
        return [$whereStr, $whereData];
    }
}

