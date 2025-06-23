<?php
header("Content-Type: application/json");

$message = $_POST['message'] ?? '';
$response = [];

if (!$message) {
    $response['reply'] = "No message received.";
    echo json_encode($response);
    exit;
}

$normalized = strtolower($message);

// === Pattern Matching ===
if (preg_match('/\b(hello|hi|hey|yo|hii|hiii)\b/i', $message)) {
    $response['reply'] = "Hey there! How can I assist you today?";
} elseif (preg_match('/\bhow are you\b/i', $message)) {
    $response['reply'] = "I'm just code, but feeling helpful! How about you?";
} elseif (preg_match('/\b(joke|funny)\b/i', $message)) {
    $response['reply'] = "Why don’t robots ever get tired? Because they recharge! ⚡";
} elseif (preg_match('/\b(time|date|day)\b/i', $message)) {
    $response['reply'] = "Current server time is: " . date('l, Y-m-d H:i:s');
} elseif (preg_match('/\b(who|what) are you\b/i', $message)) {
    $response['reply'] = "I'm your friendly AI assistant 🤖 built in PHP. Here to chat and help!";
} elseif (preg_match('/\b(thank(s)?|thank you)\b/i', $message)) {
    $response['reply'] = "You're welcome! 😊";
} elseif (preg_match('/\bbye\b/i', $message)) {
    $response['reply'] = "Goodbye! 👋 Have a great day!";
} else {
    // Default response
    $response['reply'] = "I heard you say: \"$message\" 🤔. Can you elaborate?";
}

echo json_encode($response);
?>
