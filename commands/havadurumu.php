<?php

function seo($text){
    $find = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
    $degis = array("G","U","S","I","O","C","g","u","s","i","o","c");
    $text = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$text);
    $text = preg_replace($find,$degis,$text);
    $text = preg_replace("/ +/"," ",$text);
    $text = preg_replace("/ /","-",$text);
    $text = preg_replace("/\s/","",$text);
    $text = strtolower($text);
    $text = preg_replace("/^-/","",$text);
    $text = preg_replace("/-$/","",$text);
    return $text;
}

function getWeather($city, $district = null){
    $apiKey = "8a83948724c466bcbf1bb2a40053bd55";
    $city = seo($city);

    if ($district) {
        $district = seo($district);
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city},{$district}&appid={$apiKey}&units=metric&lang=tr";
    } else {
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=tr";
    }

    $response = file_get_contents($apiUrl);

    if ($response) {
        $data = json_decode($response);
        $weather = $data->weather[0]->description;
        $temperature = $data->main->temp;
        $icon = $data->weather[0]->icon;

        $message = "<b>{$city}";

        if ($district) {
            $message .= " - {$district}";
        }

        $message .= " için Hava Durumu \n</b>";
        $message .= "Hava: {$weather} \n";
        $message .= "Sıcaklık: {$temperature} °C \n";
        $message .= "http://openweathermap.org/img/w/{$icon}.png    ";


        return $message;
    } else {
        return " \n Hava durumu bilgileri alınamadı. Lütfen geçerli bir şehir ve ilçe adı girin. \n";
    }
}

function handleHavadurumuCommand($telegram,  $params, $message)
{

    $city = $params[0];
    $district = isset($params[1]) ? $params[1] : null;

    $weatherMessage = getWeather($city, $district);

    $telegram->sendMessage([
        'chat_id' => $message['chat']['id'],
        'parse_mode' => 'HTML',
        'text' => $weatherMessage
    ]);
}

?>
