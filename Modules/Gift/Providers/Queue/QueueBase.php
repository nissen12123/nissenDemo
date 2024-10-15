<?php

namespace Modules\Gift\Providers\Queue;

class QueueBase
{
    public string $queueName = '';

    public static function push(array $params)
    {
        //推进队列
    }

    public static function pop(): array
    {
        //拉出队列
        return [];
    }
}
