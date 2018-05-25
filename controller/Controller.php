<?php
namespace controller;

class Controller
{
    protected $uid = 0;
    
    
    public function init() {
        if (strpos($_SERVER['HTTP_CONTENT_TYPE'], 'json')) {
            $data = file_get_contents("php://input");
            $data = json_decode($data, true);
            
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $_GET = $data;
            } else {
                $_POST = $data;
            }
        }
        $this->uid = $_SESSION['uid'] ? : $this->request('uid', 0);
    }
    
    protected function ajaxSucc($data, $msg=null) {
        return $this->ajaxReturn(1, $data, $msg);
    }
    
    protected function ajaxFail($msg, $data=null) {
        return $this->ajaxReturn(0, $data, $msg);
    }
    
    protected function ajaxReturn($code, $data, $msg) {
        return json_encode([
            'code'=>$code,
            'data'=>$data,
            'msg'=>$msg
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
    
    protected function request($name=null, $default=null) {
        $data = array_merge($_GET, $_POST);
        if (empty($name)) {
            return $data;
        } else {
            return $data[$name] ?? $default;
        }
    }
}

