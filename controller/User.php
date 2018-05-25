<?php
namespace controller;

use utils\db\DbUtils;

class User extends Controller
{
    
    //登陆
    public function login() {
        $name = $_POST['name'];
        $password = $_POST['password'];
        
        $user = db('user')->where(['name'=>$name])->queryOne();
        if (empty($user) || $user['password'] != $password) {
            return $this->ajaxFail('login fail');
        }
        
        $_SESSION['uid'] = $user['id'];
        
        return $this->ajaxSucc($user, 'login success');
    }
    
    //注册
    public function regist() {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $avatar = $_POST['avatar'];
        $type = $_POST['type'];
        
        //查询name是否已经存在
        $user = db('user')->where(['name'=>$name])->queryOne();
        if ($user) {
            return $this->ajaxFail("用户已存在");
        }
        $id = db('user')->insert(['name'=>$name, 'password'=>$password, 'avatar'=>$avatar, 'type'=>$type]);
        if ($id > 0) {
            $_SESSION['uid'] = $id;
            return $this->ajaxSucc([
                'id'=>$id,
                'name'=>$name,
                'avatar'=>$avatar,
                'type'=>$type
            ]);
        } 
        return $this->ajaxFail('注册失败');
    }
    
    //修改牛人信息
    public function saveGenius() {
        $uid = $this->uid;
        $salary = $_POST['salary'];//薪资
        $summary = $_POST['summary'];//个人介绍
        $avatar = $_POST['avatar'];
        
        if (!$uid) {
            return $this->ajaxFail('用户未登陆');
        }
        $user = db('user')->where(['id'=>$uid])->queryOne();

        if (!$user) {
            return $this->ajaxFail('用户不存在');
        }
        
        if ($user['type'] == 2) {
            return $this->ajaxFail('genius save,but boss');
        } 
        db('user')->where(['id'=>$uid])->update(['avatar'=>$avatar]);
        
        $genius = db('genius')->field('id')->where(['uid'=>$uid])->queryOne();
        if (!$genius) {
            $result = db('genius')->insert([
                'uid'=>$uid,
                'salary'=>$salary,
                'summary'=>$summary
            ]);
        } else {
            $result = db('genius')->where(['uid'=>$uid])->update([
                'salary'=>$salary,
                'summary'=>$summary
            ]);
        }
        if (!$result) {
            return $this->ajaxFail('save genius fail');
        }
        return $this->ajaxSucc(null, 'save success');
    }
    
    //保存boss
    public function saveBoss() {
        $uid = $this->uid;
        $position = $_POST['position'];
        $claim = $_POST['claim'];
        $avatar = $_POST['avatar'];
        
        if (!$uid) {
            return $this->ajaxFail('用户未登陆');
        }
        $user = db('user')->where(['id'=>$uid])->queryOne();
        
        if (!$user) {
            return $this->ajaxFail('用户不存在');
        }
        
        if ($user['type'] == 1) {
            return $this->ajaxFail('boss save,but genius');
        }
        db('user')->where(['id'=>$uid])->update(['avatar'=>$avatar]);
        
        $boss = db('boss')->field('id')->where(['uid'=>$uid])->queryOne();
        if (!$boss) {
            $result = db('boss')->insert([
                'uid'=>$uid,
                'position'=>$position,
                'claim'=>$claim
            ]);
        } else {
            $result = db('boss')->where(['uid'=>$uid])->update([
                'position'=>$position,
                'claim'=>$claim
            ]);
        }
        if (!$result) {
            return $this->ajaxFail('save boss fail');
        }
        return $this->ajaxSucc(null, 'save success');
    }
    
    //查询用户列表
    public function listUser() {
        $uid = $this->uid;
        if (!$uid) {
            return $this->ajaxFail('用户未登陆');
        }
        $user = db('user')->where(['id'=>$uid])->queryOne();
        
        if (!$user) {
            return $this->ajaxFail('用户不存在');
        }
        
        if ($user['type'] == 1) {
            //search boss
            $sql ="select * from user inner join boss on boss.uid=user.id";
        } else {
            //search genius
            $sql ="select * from user inner join genius on genius.uid=user.id";
        }
        $data = DbUtils::queryAll($sql);
        return $this->ajaxSucc($data);
    }
    
    //用户信息
    public function userinfo() {
        $uid = $this->uid;
        if (!$uid) {
            return $this->ajaxFail('用户未登陆');
        }
        $user = db('user')->where(['id'=>$uid])->queryOne();
        
        return $this->ajaxSucc($user);
    }
}

