<?php

function gen()
{
    $ret = yield "hello1";
    var_dump($ret);

    $ret = yield "hello2";
    var_dump($ret);

    for ($i = 0; $i <= 5; $i++) {
        echo "this is {$i} \n";
        yield;
    }
}

$gen = gen();


$gen->send(1);
