<?php


namespace zafarjonovich\YiiTelegramQueue;


use yii\queue\JobInterface;
use zafarjonovich\YiiTelegramQueue\queue\MessageQueue;

class Queue implements JobInterface
{
    /**
     * @var MessageQueue[] $queues
     */
    private $queues = [];

    private $usleepTime = 0;

    /**
     * @param MessageQueue $messageQueue
     * @return MessageQueue
     */
    public function push(MessageQueue $messageQueue):MessageQueue
    {
        $this->queues[] = $messageQueue;

        $ref = &$this->queues[count($this->queues) - 1];

        return $ref;
    }

    public function setSleepTime(int $time)
    {
        $this->usleepTime = $time;
    }

    public function execute($baseQueue)
    {
        foreach ($this->queues as $queue) {
            $queue->execute($baseQueue);
            usleep($this->usleepTime);
        }
    }
}