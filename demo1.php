<?php

function gen() {
    $ret = (yield 'yield1');
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

$gen = gen();

var_dump($gen->current());  //yield1

var_dump($gen->send('ret1')); //ret1 yield2

var_dump($gen->send('ret2'));  // ret2 null
