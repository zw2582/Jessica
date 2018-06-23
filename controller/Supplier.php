<?php
namespace controller;

use Rakit\Validation\Validator;

class Supplier extends Controller 
{
    
    public function list() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'page'=>'numeric|max:999999|min:1|defaults:1',
            'size'=>'numeric|defaults:10'
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data = $validation->getValidData();
        
        $suppliers = db('supplier')->offset(($data['page']-1)*$data['size'])->limit($data['size'])->queryAll();
        $total = db('supplier')->count();
        
        return $this->ajaxSucc(['rows'=>$suppliers, 'total'=>$total], '查询成功');
    }
    
    public function addLogo() {
        $basePath = $_SERVER['DOCUMENT_ROOT'];
        $savePath = 'img/src';
        if (!file_exists($basePath.'/'.$savePath)) {
            if (!mkdir($basePath.'/'.$savePath, '755', TRUE)) {
                return $this->ajaxFail('创建基础目录失败');
            }
        }
        if (!isset($_FILES['logo'])) {
            return $this->ajaxFail('请上传logo');
        }
        $logo = $_FILES['logo'];
        if (!in_array($logo['type'], ['image/jpeg','image/pjpeg','image/png'])) {
            return $this->ajaxFail('请上传图片');
        }
        
        $name = time().'.png';
        if (!move_uploaded_file($logo['tmp_name'], $basePath.'/'.$savePath.'/'.$name)) {
            return $this->ajaxFail('上传失败');
        }
        return $this->ajaxSucc([
            'logo_path'=>$savePath,
            'logo_name'=>$name
        ]);
    }
    
    public function save() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'id'=>'numeric',
            'name'=>'required|max:64',
            'logo_path'=>'max:255',
            'logo_name'=>'max:255',
            'contact'=>'max:255',
            'phone'=>'max:255',
            'email'=>'max:255',
            'address'=>'max:255',
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data = $validation->getValidData();
        $data = array_filter($data);
        
        if ($data['id']) {
            $rows = db('supplier')->where(['id'=>$data['id']])->update($data);
            return $rows ? $this->ajaxSucc(null, '修改成功') : $this->ajaxFail('修改失败');
        } else {
            $suppId = db('supplier')->insert($data);
            return $suppId ? $this->ajaxSucc($suppId, '新增成功') : $this->ajaxFail('新增失败');
        }
    }
    
    public function del() {
        $suppId = $this->request('id');
        
        $rows = db('supplier')->where(['id'=>$suppId])->delete();
        
        return $rows ? $this->ajaxSucc(null, '删除成功') : $this->ajaxFail('删除失败');
    }
    
    
}

