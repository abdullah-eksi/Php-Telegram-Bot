<?php

function handleAeXpCommand($telegram, $input, $message)
{
    $telegram->sendMessage([
        'chat_id' => $message['chat']['id'],
        'text' => 'AeXp komutu işlendi.'
    ]);
}

?>
