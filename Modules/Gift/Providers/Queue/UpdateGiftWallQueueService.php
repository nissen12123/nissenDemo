<?php

namespace Modules\Gift\Providers\Queue;

/**
 * 队列服务 如 rabbitMQ
 */
class UpdateGiftWallQueueService extends QueueBase
{
    /**
     * 队列名称
     * @var string
     */
    public string $queueName = 'updateGiftWall';
}
