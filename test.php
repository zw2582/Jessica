<?php

$debtInfo['d_loan_days'] = 180;
$debtInfo['d_invest_due_time'] = '2018-06-02';

$needay = $debtInfo['d_loan_days'] + 5;
$endTime = strtotime("+ {$needay} days", strtotime($debtInfo['d_invest_due_time']));

var_dump(date('Y-m-d', $endTime));