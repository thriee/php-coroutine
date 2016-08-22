<?php


include "vendor/autoload.php";

use Coroutine\Foundation\Scheduler;
use Coroutine\Foundation\Task;
use Coroutine\Foundation\SystemCall;

function getTaskId()
{
    return new SystemCall(
        function (Task $task, Scheduler $scheduler) {
            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        }
    );
}

function newTask(Generator $coroutine)
{
    return new SystemCall(
        function (Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

function killTask($tid)
{
    return new SystemCall(
        function (Task $task, Scheduler $scheduler) use ($tid) {
            $task->setSendValue($scheduler->killTask($tid));
            $scheduler->schedule($task);
        }
    );
}

function childTask()
{
    $tid = yield getTaskId();
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}

function task()
{
    $tid = yield getTaskId();
    $childTid = yield childTask();

    for ($i = 0; $i < 6; $i++) {
        echo "Parent task $tid iteration $i.\n";
        yield;

        if ($i == 3) {
            yield killTask($childTid);
        }
    }

}

$sch = new Scheduler();
$sch->newTask(task());

$sch->run();