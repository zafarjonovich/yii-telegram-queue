<?php


namespace zafarjonovich\YiiTelegramQueue\interfaces;


use zafarjonovich\Telegram\BotApi;
use zafarjonovich\Telegram\update\objects\Response;

interface AfterExecutable
{
    public function run(BotApi $botApi,Response $response);
}