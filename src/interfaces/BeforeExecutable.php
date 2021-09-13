<?php


namespace zafarjonovich\YiiTelegramQueue\interfaces;


use zafarjonovich\YiiTelegramQueue\queue\MessageQueue;

interface BeforeExecutable
{
    public function run(MessageQueue &$messageQueue);
}