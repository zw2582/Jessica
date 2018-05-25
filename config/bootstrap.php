<?php

use utils\db\QueryBuilder;

function db($table) {
    $builder = new QueryBuilder();
    $builder->table($table);
    return $builder;
}