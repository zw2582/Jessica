<?php
namespace utils;

use controller\User;

class Application
{
    public function run() {
        //绑定错误处理
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'fatlHandler']);
        
        //开启session
        session_start();
        
        header("content-type", "application/json;charset=utf-8");
        
        //执行路由
        $uri = $_SERVER['PATH_INFO'];
        list($controllerClass, $action) = explode('/', trim($uri, '/'));
        
        if (empty($action)) {
            echo ("404");
        } else {
            if ($controllerClass == 'user') {
                $controller = new User();
            }
            if (!empty($controller)) {
                $controller->init();
                $result = $controller->{$action}();
                echo $result;
            }
        }
    }
    
    public function exceptionHandler(\Exception $e) {
        echo "file:{$e->getFile()},code:{$e->getCode()},line:{$e->getLine()},{$e->getMessage()}", PHP_EOL;
        echo($e->getTraceAsString());
    }
    
    public function fatlHandler() {
        $data = error_get_last();
        if ($data && $data['type'] != E_NOTICE) {
            print_r($data);
        }
    }
}

