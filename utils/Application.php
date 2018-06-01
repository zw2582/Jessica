<?php
namespace utils;

class Application
{
    public function run() {
        //绑定错误处理
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'fatlHandler']);
        
        //设置session名称
        session_name('job_id');
        //开启session
        session_start();
        
        header("content-type", "application/json;charset=utf-8");
        
        //执行路由
        $uri = $_SERVER['PATH_INFO'];
        list($controllerClass, $action) = explode('/', trim($uri, '/'));
        
        if (empty($action)) {
            echo ("404");
        } else {
            $controllerClass = '\\controller\\'.ucfirst($controllerClass);
            $controller = new $controllerClass();
            
            if (!empty($controller)) {
                $controller->init();
                $result = $controller->{$action}();
                echo $result;
            }
        }
    }
    
    public function exceptionHandler($e) {
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

