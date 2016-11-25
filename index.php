<?php

include "vendor/autoload.php";

use Bee\Framework\Foundation\Coroutine\Command\SysCall;
use Bee\Framework\Foundation\Coroutine\Scheduler;

function childTask()
{
    $tid = yield SysCall::getTaskId();
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}


function task()
{
    $tid = yield SysCall::getTaskId();
    $childTid = yield SysCall::newTask(childTask());

    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;
        if ($i == 3) {
            yield SysCall::killTask($childTid);
        }
    }
}

$scheduler = new Scheduler;
$tid = $scheduler->newTask(task());
$scheduler->run();
