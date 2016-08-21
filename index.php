<?php


include "vendor/autoload.php";

use Coroutine\Foundation\Scheduler;
use Coroutine\Foundation\Task;
use Coroutine\Foundation\SystemCall;

function task1()
{
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield;
    }
}

function task2()
{
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield;
    }
}

function getTaskId()
{
    return new SystemCall(function (Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}

function task($max)
{
    $tid = yield getTaskId();
    for ($i = 1; $i <= $max; ++$i) {
        echo "This is task $tid iteration $i.\n";
        yield;
    }

}

$sch = new Scheduler();
$sch->newTask(task(10));
$sch->newTask(task(5));

$sch->run();