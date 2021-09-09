<?php

namespace zafarjonovich\YiiTelegramQueue\queue;

use yii\queue\JobInterface;
use zafarjonovich\Telegram\base\MethodOption;
use zafarjonovich\Telegram\BotApi;
use zafarjonovich\Telegram\update\objects\Response;
use zafarjonovich\YiiTelegramQueue\interfaces\AfterExecuteEventInterface;
use zafarjonovich\YiiTelegramQueue\interfaces\BeforeExecuteEventInterface;

class MessageQueue implements JobInterface
{
    /**
     * Telegram bot method token
     *
     * @var string $botToken
     */
    private $botToken;

    /**
     * Telegram bot method name
     *
     * @var string $botMethod
     */
    private $botMethod;

    /**
     * @var MethodOption $botMethodOptions
     */
    public $botMethodOptions;

    private $afterExecuteEvents = [];

    private $beforeExecuteEvents = [];

    public function __construct(string $botToken,string $botMethod,array $botMethodOptions = [])
    {
        $this->botToken = $botToken;
        $this->botMethod = $botMethod;
        $this->botMethodOptions = new MethodOption($botMethodOptions);
    }

    public function addBeforeExecuteEvent($event)
    {
        $this->beforeExecuteEvents[] = $event;
        return $this;
    }

    public function addAfterExecuteEvent($afterExecuteEvent)
    {
        $this->afterExecuteEvents[] = $afterExecuteEvent;
        return $this;
    }

    private function runBeforeExecuteEvents()
    {
        foreach ($this->beforeExecuteEvents as $event) {
            $object = \Yii::createObject($event);

            if(!($object instanceof BeforeExecuteEventInterface))
                throw new \Exception('Invalid configuration');

            $object->run($this);
        }
    }

    private function runAfterExecuteEvents(BotApi $botApi,Response $response)
    {
        foreach ($this->afterExecuteEvents as $afterExecuteEvent) {
            $object = \Yii::createObject($afterExecuteEvent);

            if(!($object instanceof AfterExecuteEventInterface))
                throw new \Exception('Invalid configuration');

            $object->run($botApi,$response);
        }
    }

    public function execute($queue)
    {
        $botApi = new BotApi($this->botToken);

        $this->runBeforeExecuteEvents();

        $response = $botApi->query($this->botMethod,$this->botMethodOptions->toArray());

        $this->runAfterExecuteEvents($botApi,new Response($response));
    }
}