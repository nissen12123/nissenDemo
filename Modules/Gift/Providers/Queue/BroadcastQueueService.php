<?php

namespace Modules\Gift\Providers\Queue;

class BroadcastQueueService extends QueueBase
{
    public string $queueName = 'broadcast';

}
