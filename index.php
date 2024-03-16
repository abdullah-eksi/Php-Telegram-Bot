<?php

require_once 'vendor/autoload.php';

use Telegram\Bot\Api;

$botToken = '6025932489:AAFrYArdUgVGDenxyvQQ-MEfKiXgQY42T8o';
$commandsDir = __DIR__ . '/commands';
$processedMessagesFile = __DIR__ . '/processed_messages.txt';

$telegram = new Api($botToken);



try {

    $processedMessages = file_exists($processedMessagesFile) ? unserialize(file_get_contents($processedMessagesFile)) : [];


    $updates = $telegram->getUpdates();


    foreach ($updates as $update) {
        $message = $update->getMessage();

        if (!$message || isset($processedMessages[$message->getMessageId()])) {
            continue;
        }


        $messageTimestamp = $message->getDate();


        $currentTime = time();


        $timeDifference = $currentTime - $messageTimestamp;

        if ($timeDifference >= 300) {
            continue;
        }


        $text = $message->getText();


        if (str_starts_with($text, '/')) {
            $parts = explode(' ', $text);
            $command = $parts[0];
            $params = array_slice($parts, 1);
            processCommand($telegram, $command, $params, $message);
        } else {

            require_once $commandsDir . '/autorespond.php';
            handleAutorespondCommand($telegram, [], $message);
        }


        $processedMessages[$message->getMessageId()] = true;
    }


    file_put_contents($processedMessagesFile, serialize($processedMessages));
} catch (\Telegram\Bot\Exceptions\TelegramResponseException $e) {
    echo "Telegram Hata: " . $e->getMessage();
} catch (\Exception $e) {
    echo "Hata: " . $e->getMessage();
}


function processCommand($telegram, $command, $params, $message)
{
    global $commandsDir;
    $commandFile = $commandsDir . '/' . substr($command, 1) . '.php';

    if (file_exists($commandFile)) {
        require_once $commandFile;
        $functionName = 'handle' . ucfirst(substr($command, 1)) . 'Command';

        if (function_exists($functionName)) {
            call_user_func($functionName, $telegram, $params, $message);
        } else {

            $telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => 'Geçersiz Metod: ' . $command
            ]);
        }
    } else {

        $telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => 'Geçersiz Komut: ' . $command
        ]);
    }
}
?>
