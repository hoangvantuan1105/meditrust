<?php

class OpenAIService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = GEMINI_KEY;
    }

    public function chat($message)
    {
        $url = "https://api.openai.com/v1/chat/completions";

        $data = [
            "model" => "gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => "Bạn là trợ lý AI cho hệ thống nha khoa MediTrust, chuyên tư vấn vật tư."],
                ["role" => "user", "content" => $message]
            ],
            "temperature" => 0.4
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? "AI lỗi 😢";
    }
}
