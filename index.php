<?php

$carpeta   = realpath(dirname(__FILE__));
require_once __DIR__ . '/vendor/autoload.php';
require_once ($carpeta . "/config.php");

var_dump("eco");

if ($op == 'set' . TELEGRAM_KEY) {
    $hook_url = BASE_URL.'/telegram/hook' . TELEGRAM_KEY . '/';
    //var_dump($hook_url);
    try {
        $telegram = new Longman\TelegramBot\Telegram(TELEGRAM_API, TELEGRAM_BOT);
        $result   = $telegram -> setWebhook($hook_url);
        if ($result -> isOk()) {
            echo $result -> getDescription();
        }
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        echo $e;
    }
}
if ($op == 'unset' . TELEGRAM_KEY) {
    try {
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram(TELEGRAM_API, TELEGRAM_BOT);
        // Delete webhook
        $result   = $telegram -> deleteWebhook();
        if ($result -> isOk()) {
            echo $result -> getDescription();
        }
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        echo $e -> getMessage();
    }
}



if ($op == 'hook' . TELEGRAM_KEY) {

    $mysql_credentials = [
        'host'     => DB_HOST,
        'user'     => DB_USER,
        'password' => DB_PWD,
        'port'     => DB_PORT,
        'database' => DB_DB,
    ];

    try {

        $telegram = new Longman\TelegramBot\Telegram(TELEGRAM_API, TELEGRAM_BOT);
        $telegram -> addCommandsPath(dirname(__DIR__) . '/telegram/Comandos/');
        
        $telegram -> enableMySql($mysql_credentials);

        Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/" . TELEGRAM_BOT . "_error.log");
        Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/" . TELEGRAM_BOT . "_debug.log");
        Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/" . TELEGRAM_BOT . "_update.log");

        //$result = Request::sendMessage(['chat_id' => '-224101374', 'text' => dirname(__DIR__) . '/telegram/Comandos/']);

        $telegram -> handle();
        
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // Silence is golden!
        // log telegram errors
        echo $e;
    }
}