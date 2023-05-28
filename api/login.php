<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization');

include('dbConn.php');
$objDb = new dbConn();
$conn = $objDb->connect();

// echo json_encode(['username' => $username, 'password' => $password]);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);


    try {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        $data = [];
        $checkPwd = false;

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            $checkPwd = password_verify($password, $data[0]['password']);
        }


        if ($checkPwd && count($data) == 1) {

            // Create token header as a JSON string
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

            // Create token payload as a JSON string
            $usersData = [
                'user_id' => $data[0]['id'],
                'username' => $data[0]['username'],
                'password' => $data[0]['password'],
            ];
            $payload = json_encode($usersData);
            $secret = 'abcdefghijklmnopqrst';

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

            http_response_code(200);
            echo json_encode($jwt_token);
        } else {
            http_response_code(400);
            echo json_encode(['result' => "Incorrect username or password"]);
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }

}