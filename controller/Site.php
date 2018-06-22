<?php
namespace controller;


use Rakit\Validation\Validator;

class Site extends Controller
{
    public function index() {
        $messageBase64 = '%7B%22qid%22%3A%22Q_6392657252192355110%22%2C%22qname%22%3A%22Bank%22%2C%22date%22%3A1528869662.260551%2C%22status%22%3A1%2C%22body%22%3A%5B%7B%22ename%22%3Anull%2C%22eid%22%3A%22E_6392657252192355109%22%2C%22status%22%3A1%2C%22data%22%3A%7B%22serialNo%22%3A%226392657252192355103%22%2C%22method%22%3A%22FREEZE_REPAY%22%2C%22uuid%22%3A6392657252192355074%2C%22extra%22%3A%7B%22FuncCode%22%3A1%2C%22CustName%22%3A%22%E8%B5%B5%E4%BA%8C%E6%98%8E%22%2C%22CustAcctId%22%3A%226025000000007237%22%2C%22ThirdCustId%22%3A5005%2C%22CcyCode%22%3A%22RMB%22%2C%22TranAmount%22%3A%220.49%22%2C%22TranHandFee%22%3A%220.00%22%7D%2C%22reserve%22%3A%5B%5D%2C%22times%22%3A1%2C%22pushTime%22%3A1528869662%7D%7D%5D%2C%22queueType%22%3A%22rabbitmq%22%2C%22requestUuid%22%3A%22N2Y4YME2%22%7D';
        $queueData = json_decode(base64_decode(rawurldecode($messageBase64)), true)['body'][0]['data'];
        var_dump($queueData);die;
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

