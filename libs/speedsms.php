<?php

function sendSpeedSMS($phone, $content)
{
    $apiKey = "iss8XJjJHrEkRFyZMXAmW8DNtGHXnPO1";

    $url = "https://api.speedsms.vn/index.php/sms/send";

    $data = [
        "to" => [$phone],
        "content" => $content,
        "sms_type" => 2,
        "sender" => "MEDItrUST"
    ];

    $payload = json_encode($data);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($apiKey . ":")
        ],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30
    ]);

    $result = curl_exec($ch);

    if ($result === false) {
        return ["status" => false, "error" => curl_error($ch)];
    }

    curl_close($ch);

    return json_decode($result, true);
}
