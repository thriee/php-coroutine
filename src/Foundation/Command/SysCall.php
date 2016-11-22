<?php


namespace Coroutine\Foundation\Command;

use Coroutine\Foundation\Scheduler;
use Coroutine\Foundation\SystemCall;
use Coroutine\Foundation\Task;
use Generator;


class SysCall
{
    public static function getTaskId()
    {
        return new SystemCall(
            function (Task $task, Scheduler $scheduler) {
                $task->setSendValue($task->getTaskId());
                $scheduler->schedule($task);
            }
        );
    }

    public static function newTask(Generator $coroutine = null)
    {
        return new SystemCall(
            function (Task $task, Scheduler $scheduler) use ($coroutine) {
                $task->setSendValue($scheduler->newTask($coroutine));
                $scheduler->schedule($task);
            }
        );
    }

    public static function killTask($tid)
    {
        return new SystemCall(
            function (Task $task, Scheduler $scheduler) use ($tid) {
                $task->setSendValue($scheduler->killTask($tid));
                $scheduler->schedule($task);
            }
        );
    }

    public static function waitForRead($socket)
    {
        return new SystemCall(
            function (Task $task, Scheduler $scheduler) use ($socket) {
                $scheduler->waitForRead($socket, $task);
            }
        );
    }

    public static function waitForWrite($socket)
    {
        return new SystemCall(
            function (Task $task, Scheduler $scheduler) use ($socket) {
                $scheduler->waitForWrite($socket, $task);
            }
        );
    }
}
