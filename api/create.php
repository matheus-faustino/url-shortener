<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Controll-Allow-Methods: POST");
header("Access-Controll-Max-Age: 3600");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$url = $data['url'];

if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(["message" => "No valid URL provided to be shortened."]);

    exit();
}

$shortCode = generateUniqueCode($db);

$query = "INSERT INTO urls (original_url, short_code) VALUES (?, ?)";
$statement = $db->prepare($query);
$statement->bindParam(1, $url);
$statement->bindParam(2, $shortCode);

$result = $statement->execute();

if (!$result) {
    http_response_code(503);
    echo json_encode(["message" => "We couldn't short this URL. Try again."]);
}

$baseUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$shortUrl = $baseUrl . "/redirect.php/" . $shortCode;

http_response_code(201);

echo json_encode([
    "message" => 'URL shortened successfully!',
    "shortUrl" => $shortUrl,
    "shortCode" => $shortCode,
]);

function generateUniqueCode(PDO $db)
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charLength = 8;

    do {
        $shortCode = '';

        for ($i = 0; $i < $charLength; $i++) {
            $shortCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        $statement = $db->prepare("SELECT id FROM urls WHERE short_code = ?");
        $statement->bindParam(1, $shortCode);
        $statement->execute();
    } while ($statement->rowCount() > 0);

    return $shortCode;
}
