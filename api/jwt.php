<?php
// Create token header as a JSON string
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

// Create token payload as a JSON string
$usersData = [
    'user_id' => 123,
    'fname' => 'dallan',
    'lname' => 'franco',
    'email' => 'francodaillanleigh@gmail.com',
    'tel' => '123456789'
];

$secret = 'abcdefghijklmnopqrst';

$payload = json_encode($usersData);

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Encode Secret to Base64Url String
$base64UrlSecret = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($secret));

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $base64UrlSecret, true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt_token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

echo $jwt_token;

?>