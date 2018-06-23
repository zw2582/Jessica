<?php
namespace controller;

use utils\db\DbUtils;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class Catalog extends Controller
{
    //search list catalog
    public function list() {
        $page = $this->request('page', 1);
        $size = $this->request('size', 10);
        
        //search database
        $catalogs = db('catalog')->queryAll();
        //search total
        $total = db('catalog')->count();
        
        return $this->ajaxSucc([
            'data'=>$catalogs,
            'total'=>$total
        ]);
    }
    
    public function add() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'name'=>'required',
            'number'=>'required',
            'blanguage'=>'max:32',
            'sort'=>'numeric|max:99999',
            'status'=>'default:1|in:1,2',
            'parent'=>'max:32',
            'memo'=>'max:255'
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data = $validation->getValidData();
        $data = array_filter($data);
        //find catalog exist
        $catalog = db('catalog')->where(['number'=>$data['number']])->queryOne();
        if ($catalog) {
            return $this->ajaxFail('编码已存在');
        } else {
            $result = db('catalog')->insert($data);
        }
        if (empty($result)) {
            return $this->ajaxFail('add catalog fail');
        }
        return $this->ajaxSucc($result, 'add catalog success');
    }
    
    //update catalog
    public function update() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'id'=>'required',
            'name'=>'required',
            'number'=>'required',
            'blanguage'=>'max:32',
            'sort'=>'numeric|max:99999',
            'status'=>'default:1|in:1,2',
            'parent'=>'max:32',
            'memo'=>'max:255'
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data = $validation->getValidData();
        //find catalog exist
        $catalog = db('catalog')->where(['number'=>$data['number']])->queryOne();
        if ($catalog && $catalog['id'] != $data['id']) {
            return $this->ajaxFail('编码已存在');
        }
        $result = db('catalog')->where(['id'=>$data['id']])->update($data);
        if (empty($result)) {
            return $this->ajaxFail('save catalog fail');
        }
        return $this->ajaxSucc(null, 'save catalog success');
    }
    
    //删除
    public function del() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'number'=>'required',
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data = $validation->getValidData();
        
        $catalog = db('catalog')->where(['number'=>$data['number']])->queryOne();

        if ($catalog) {
            $result = db('catalog')->where(['id'=>$catalog['id']])->delete();
            return $this->ajaxSucc(null, '删除成功');
        } 
        return $this->ajaxFail('删除失败');
    }
    
    public function test() {
        $sql= 'update catalog set id=?,name=?,blanguage=?,number=?,parent=?,sort=?,memo=?,user_id=?,supp_id=?,create_time=?,update_time=?,status=?,key=? where id=?';
        $params = [
            '1','实验','','00001','','1','','0','0','2018-06-07 16:29:44',' 2018-06-21 15:00:20',' 1','1','1'
        ];
        
        printf(strtr($sql, ['?'=>"'%s'"]), ...$params);
        
        $row = DbUtils::update($sql, $params);
        
        var_dump($row);
    }
}

