<?php
header("Content-Type: application/json");

// Your API key from https://makersuite.google.com/app/apikey
$GEMINI_API_KEY = "AIzaSyDfj6e8aNDZ0GX-vQCgfjByX2hJ0u_pywQ"; // Replace with your actual key

$message = $_POST['message'] ?? '';
if (!$message) {
    echo json_encode(["reply" => "No message received."]);
    exit;
}

// Gemini REST expects 'contents' format
$payload = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => "You're my smart, friendly buddy. Talk casually and make it short replies, like a human friend who knows a lot. Be helpful, but not too formal."]
            ]
        ],
        [
            "role" => "user",
            "parts" => [
                ["text" => $message]
            ]
        ]
    ]
];

$model = "gemini-2.5-flash";

// 🔥 Use latest Gemini model:
// $model = "gemini-2.5-pro"; // or "gemini-2.5-flash" for faster/cheaper
$url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$GEMINI_API_KEY}";

// Send POST request to Gemini API
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode($payload),
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Decode the JSON
$responseData = json_decode($response, true);

if ($httpCode >= 400 || !$responseData) {
    $errorMsg = $responseData['error']['message'] ?? 'Unknown Gemini error';
    echo json_encode([
        "reply" => "Gemini API error: $errorMsg",
        "http_code" => $httpCode
    ]);
    exit;
}

// Extract Gemini's reply
$reply = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '(no reply)';
echo json_encode(["reply" => $reply]);
