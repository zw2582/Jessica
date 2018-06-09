# 杰西卡 Jessica

#### 项目介绍
自己写的一个快速开发框架

#### 安装

```
composer create-project zw2582/jessica
```

#### 开发实例

> controller:控制器层

如果请求为：site/test,创建controller/Site.php,创建方法test()即可

Controller.php是基础控制器，不想继承也ok,控制器方法返回的字符串会渲染到页面，目前没有继承页面渲染框架，只能返回字符串

```
namespace controller;

class Site extends Controller {

	public function test(){
		
		return '{status:1}'
	}
}

```

> 数据库配置

数据库操作也很简单，基于pdo操作mysql，别的数据库都不支持

如果要修改配置直接去utils\db\Connection.php中去修改，不要觉得low，自己用够了

```
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
```

> 数据库简便操作

数据库的简单操作投提供了方法，对于复杂的链表查询自己写sql查询，目前只支持？占位符哦

querySql($sql, $data):直接sql查询，data使用占位符?传递参数

简单查询:

db($tablename):创建查询器

field(array $field):查询字段，默认全部

where(array $param):拼装条件

offset($offset)->limit($limit):分页

queryOne():查询一条

queryAll():查询多条

count(string $field):统计数量，field默认:"*"

insert(array $data):新增数据,返回最后一条插入的数据

update(array $data):修改数据，返回行数
