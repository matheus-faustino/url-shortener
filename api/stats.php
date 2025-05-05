<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$shortCode = basename($_SERVER['REQUEST_URI']);

$query = "SELECT original_url, short_code, created_at, clicks FROM urls WHERE short_code = ?";
$statement = $db->prepare($query);
$statement->bindParam(1, $shortCode);
$statement->execute();

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo json_encode(["message" => "No URL found for the provided shortened URL."]);
}

$row = $statement->fetch(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    "original_url" => $row['original_url'],
    "short_code" => $row['short_code'],
    "created_at" => $row['created_at'],
    "clicks" => $row['clicks']
]);
