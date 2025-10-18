<?php
// /public_html/ai-chat/api.php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid JSON payload']);
  exit;
}

$prompt = trim($data['prompt'] ?? '');
if ($prompt === '' || mb_strlen($prompt) > 8000) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid prompt']);
  exit;
}

$endpoint = sprintf(
  'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
  urlencode(GEMINI_MODEL),
  urlencode(GEMINI_API_KEY)
);

$payload = [
  'contents' => [[
    'role' => 'user',
    'parts' => [['text' => $prompt]]
  ]],
  // Safety: keep outputs concise by default; you can tweak this
  'generationConfig' => [
    'temperature' => 0.7,
    'maxOutputTokens' => 512,
  ],
];

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($response === false) {
  http_response_code(500);
  echo json_encode(['error' => 'cURL error: ' . $curlErr]);
  exit;
}

if ($httpCode < 200 || $httpCode >= 300) {
  http_response_code($httpCode);
  echo $response; // forward API error for debugging
  exit;
}

$j = json_decode($response, true);
$reply = '';

if (isset($j['candidates'][0]['content']['parts'])) {
  foreach ($j['candidates'][0]['content']['parts'] as $p) {
    if (isset($p['text'])) $reply .= $p['text'] . "\n";
  }
}
$reply = trim($reply);

echo json_encode([
  'reply' => $reply !== '' ? $reply : '(No response)',
]);
