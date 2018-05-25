<?php
$arr = ['name','sex','age'];

$d = array_reduce($arr, function($v1, $v2){
    return "$v1, $v2=?";
});

header("content-type:text/json");
print_r($d);