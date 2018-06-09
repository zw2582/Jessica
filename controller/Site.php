<?php
namespace controller;


use Rakit\Validation\Validator;

class Site extends Controller
{
    public function index() {
        $validator = new Validator();
        $validation = $validator->validate($_POST, [
            'id'=>'required|numeric',
            'msg'=>'required|defaults:23'
        ]);
        
        if ($validation->fails()) {
            $errors = $validation->errors();
            print_r($errors->firstOfAll());
        } else {
            print_r('success');
        }
        print_r($validation->getValidatedData());
        print_r($validation->getValidData());
        print_r($validation->getInvalidData());
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

