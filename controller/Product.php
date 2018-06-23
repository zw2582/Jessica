<?php
namespace controller;

use Rakit\Validation\Validator;
use utils\db\DbUtils;

class Product extends Controller
{
    
    public function list() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'page'=>'numeric|default:1',
            'size'=>'numeric|default:10'
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data= $validation->getValidData();
        
        $sql = 'select t.id proId,t.number,t.name proName,t.unit,t.catalog_num,t2.name catalog,t.brand_price,t.agency,t.barcode,t.user_id,
t.supp_id,t1.id packageId,t1.name packageName,t1.price packagePrice,t1.count,t1.freeze_count,t1.status,t.sort from product t 
inner join package t1 on t.id = t1.product_id 
left join catalog t2 on t.catalog_num = t2.number
limit ?,?';
        $products = DbUtils::queryAll($sql, [($data['page']-1)*$data['size'], $data['size']]);
        
        $total = DbUtils::queryOne('select count(t1.id) count from product t inner join package t1 on t.id = t1.product_id');
        
        return $this->ajaxSucc([
            'rows'=>$products,
            'total'=>$total['count']
        ]);
    }
    
    public function save() {
        $validator = new Validator();
        $validation = $validator->validate($this->request('product'), [
            'pro_id'=>'numeric',
            'number'=>'required|max:32',
            'name'=>'required|max:255',
            'unit'=>'required|max:255',
            'catalog_num'=>'required|max:32',
            'brand_price'=>'required|numeric',
            'sort'=>'numeric',
            'agency'=>'max:255',
            'barcode'=>'max:255',
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $product = $validation->getValidData();
        
        $rules = [
            'packageName'=>'required|max:255',
            'price'=>'numeric',
            'count'=>'numeric',
            'status'=>'numeric',
        ];
        foreach ($this->request('packages') as $package) {
            $validation = $validator->validate($package, $rules);
            if ($validation->fails()) {
                return $this->ajaxFail($validation->errors()->firstOfAll());
            }
            $packages[] = $validation->getValidData();
        }
        
        $cleanOldPackage = $this->request('clean', 1);
        
        DbUtils::startTrans();
        try {
            $product = array_filter($product);
            
            $existProduct = db('product')->where(['number'=>$product['number']])->queryOne();
            
            if ($product['pro_id']) {
                $proId = $product['pro_id'];
                if ($existProduct && $existProduct['id'] != $product['pro_id']) {
                    return $this->ajaxFail('number repeat');
                }
                $product['update_time'] = date('Y-m-d H:i:s');
                unset($product['pro_id']);
                $rows = db('product')->where(['id'=>$proId])->update($product);
                if (!$rows) {
                    return $this->ajaxFail('修改产品失败');
                }
            } else {
                if ($existProduct) {
                    return $this->ajaxFail('number repeat');
                }
                $proId = db('product')->insert($product);
                if (!$proId) {
                    return $this->ajaxFail('新增产品失败');
                }
            }
            foreach ($packages as $data) {
                $data['name'] = $data['packageName'];
                unset($data['packageName']);
                $package = db('package')->where(['product_id'=>$proId, 'name'=>$data['name']])->queryOne();
                if ($package) {
                    $data['update_time'] = date('Y-m-d H:i:s');
                    db('package')->where(['id'=>$package['id']])->update($data);
                } else {
                    $data['product_id'] = $proId;
                    db('package')->insert($data);
                }
            }
            $validPackageNames = array_column($packages, 'packageName');
            if ($cleanOldPackage && $validPackageNames) {
                //清楚其他package
                db('package')->where([
                    'product_id'=>$data['pro_id']?:$proId,
                    'name'=>['not in', $validPackageNames]
                ])->delete();
            }
            DbUtils::commit();
        } catch (\Exception $e) {
            DbUtils::rollBack();
            return $this->ajaxFail($e->getMessage());
        }
        return $this->ajaxSucc(null, '保存成功');
    }
    
    public function onOff() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'packageId'=>'int|required',
            'status'=>'required|int|in:1,2'
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data= $validation->getValidData();
        
        $rows = db('package')->where(['id'=>$data['packageId']])->update([
            'update_time'=>date('Y-m-d H:i:s'),
            'status'=>$data['status']
        ]);
        
        return $rows ? $this->ajaxSucc(null, '修改成功') : $this->ajaxFail('修改失败');
    }
    
    public function del() {
        $validator = new Validator();
        $validation = $validator->validate($this->request(), [
            'packageId'=>'int|required',
        ]);
        if ($validation->fails()) {
            return $this->ajaxFail($validation->errors()->firstOfAll());
        }
        $data= $validation->getValidData();
        
        $rows = db('package')->where(['id'=>$data['packageId']])->delete();
        
        return $rows ? $this->ajaxSucc(null, '删除成功') : $this->ajaxFail('删除失败');
    }
}

