<?php
header("Content-Type: application/json");

$userMessage = $_POST['message'] ?? '';

$apiKey = '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'; // Replace with your real API key

$url = "https://openrouter.ai/api/v1/chat/completions";

$data = [
    "model" => "mistralai/mistral-7b-instruct", // Working OpenRouter model
    "messages" => [
        ["role" => "user", "content" => $userMessage]
    ]
];

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json",
    "HTTP-Referer: http://localhost", // Required by OpenRouter
    "X-Title: Chatbot Web App"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["reply" => "cURL Error: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$json = json_decode($response, true);

if (isset($json['choices'][0]['message']['content'])) {
    $reply = $json['choices'][0]['message']['content'];
} else {
    $reply = "OpenRouter Error: " . json_encode($json);
}

echo json_encode(["reply" => $reply]);
