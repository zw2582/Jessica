<?php
namespace controller;

class Site extends Controller
{
    public function index() {
        $a = 1;
        $b = &$a;
        $b = 2;
        
        var_dump($a, $b);
        return $this->ajaxFail('sdfsdf');
    }
    
    public function list() {
        $page = $this->request('page', 1);
        $size = $this->request('size', 10);
        
        $user = db('user2')->offset(($page-1)*$size)->limit($size)->queryAll();
        $total = db('user2')->count();
        return $this->ajaxSucc([
            'data'=>$user,
            'total'=>$total,
            'size'=>$size
        ]);
    }
}

