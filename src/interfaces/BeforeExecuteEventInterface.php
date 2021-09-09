<?php


namespace zafarjonovich\YiiTelegramQueue\interfaces;


use zafarjonovich\YiiTelegramQueue\queue\MessageQueue;

interface BeforeExecuteEventInterface
{
    public function run(MessageQueue &$messageQueue);
}