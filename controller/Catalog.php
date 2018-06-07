<?php
namespace controller;

class Catalog extends Controller
{
    //search list catalog
    public function list() {
        $page = $this->request('page', 1);
        $size = $this->request('size', 10);
        
        //search database
        $catalogs = db('catalog')->offset(($page-1)*$size)->limit($size)->queryAll();
        //search total
        $total = db('catalog')->count();
        
        return $this->ajaxSucc([
            'data'=>$catalogs,
            'total'=>$total
        ]);
    }
    
    //save catalog
    public function save() {
        $data = $this->request();
        
        //find catalog exist
        $catalog = db('catalog')->where(['number', $data['number']])->queryOne();
        if ($catalog) {
            $data['update_time'] = date('Y-m-d H:i:s');
            $result = db('catalog')->where(['id'=>$catalog['id']])->update($data);
        } else {
            $result = db('catalog')->insert($data);
        }
        if (empty($result)) {
            return $this->ajaxFail('save catalog fail');
        }
        return $this->ajaxSucc(null, 'save catalog success');
    }
}

