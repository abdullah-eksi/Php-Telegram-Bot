<?php


function handleStartCommand($telegram,$input, $message)
{
    $telegram->sendMessage([
        'chat_id' => $message['chat']['id'],
        'text' => 'Start Komutu Çalıştı'
    ]);
}

?>
