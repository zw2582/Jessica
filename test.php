<?php

//$message[] = '{"qid":"Q_6409967636926432159","qname":"Bank","date":1528279532.12773,"status":1,"body":[{"ename":null,"eid":"E_6409967636926432158","status":1,"data":{"serialNo":"6409967636926432152","method":"DEBT_REPAY","uuid":"6409967636926432153","extra":{"FuncCode":"1","FuncFlag":"3","OutCustName":"娃娃","OutThirdCustId":6405325015998268129,"OutCustAcctId":"6025000000017137","InCustAcctId":"6025000000016836","InCustName":"和平","InThirdCustId":6405325015977296784,"CcyCode":"RMB","OutTranHandFee":0,"InTranHandFee":0,"RemainAmount":0,"ItemId":6405325016006656627,"ThirdHtId":6405325016006656740,"TranAmount":0.41,"RepayAmount":"0.00","RepayHandFee":"0.41","AssuCustId":0,"AssuAmount":0,"OAThirdLogNo":"6405325016019239503"},"reserve":[],"times":1,"pushTime":1528279532}}],"queueType":"rabbitmq","requestUuid":"MTRJNMU1"}';
/* $message[] = 'sdfsdf';

$message[] = '{"qid":"Q_6409967636926432161","qname":"Bank","date":1528279532.136288,"status":1,"body":[{"ename":null,"eid":"E_6409967636926432160","status":1,"data":{"serialNo":"6409967636926432154","method":"DEBT_REPAY","uuid":"6409967636926432155","extra":{"FuncCode":"1","FuncFlag":"3","OutCustName":"娃娃","OutThirdCustId":6405325015998268129,"OutCustAcctId":"6025000000017137","InCustAcctId":"6025000000016816","InCustName":"可塔","InThirdCustId":6405325015977296429,"CcyCode":"RMB","OutTranHandFee":0,"InTranHandFee":0,"RemainAmount":0,"ItemId":6405325016006656627,"ThirdHtId":6405325016006656753,"TranAmount":0.82,"RepayAmount":"0.00","RepayHandFee":"0.82","AssuCustId":0,"AssuAmount":0,"OAThirdLogNo":"6405325016019239503"},"reserve":[],"times":1,"pushTime":1528279532}}],"queueType":"rabbitmq","requestUuid":"MTRJNMU1"}';

$message[] = '{"qid":"Q_6409967636926432163","qname":"Bank","date":1528279532.175649,"status":1,"body":[{"ename":null,"eid":"E_6409967636926432162","status":1,"data":{"serialNo":"6409967636926432156","method":"DEBT_REPAY","uuid":"6409967636926432157","extra":{"FuncCode":"1","FuncFlag":"3","OutCustName":"娃娃","OutThirdCustId":6405325015998268129,"OutCustAcctId":"6025000000017137","InCustAcctId":"6025000000016816","InCustName":"可塔","InThirdCustId":6405325015977296429,"CcyCode":"RMB","OutTranHandFee":0,"InTranHandFee":0,"RemainAmount":0,"ItemId":6405325016006656627,"ThirdHtId":6405325016006656771,"TranAmount":2.87,"RepayAmount":"0.00","RepayHandFee":"2.87","AssuCustId":0,"AssuAmount":0,"OAThirdLogNo":"6405325016019239503"},"reserve":[],"times":1,"pushTime":1528279532}}],"queueType":"rabbitmq","requestUuid":"MTRJNMU1"}';

foreach ($message as $va) {
    $messageArr = json_decode($va, true);
    if (isset($messageArr['body'][0]['ename']) && $messageArr['body'][0]['ename'] == 'Stop') {
        return null;
    }
    $messageBase64 = base64_encode($va);
    
    echo $messageBase64,PHP_EOL;
} */

echo 'cac1';
$data = exec('php ./wai.php', $result);
echo 'ca2';

echo 'data:'.$data,PHP_EOL;
echo var_dump($result);