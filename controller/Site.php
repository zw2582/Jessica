<?php
namespace controller;


use Rakit\Validation\Validator;

class Site extends Controller
{
    public function index() {
        $validator = new Validator();
        $validation = $validator->validate($_GET, [
            'id'=>'required|numeric',
            'msg'=>'defaults:23'
        ]);
        
        if ($validation->fails()) {
            $errors = $validation->errors();
            print_r($errors->firstOfAll());
            print_r($validation->getValidata());
        } else {
            print_r($validation->getValidata());
        }
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

