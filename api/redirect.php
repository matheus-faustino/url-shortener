<?php
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$shortCode = basename($_SERVER['REQUEST_URI']);

$query = "SELECT original_url FROM urls WHERE short_code = ?";
$statement = $db->prepare($query);
$statement->bindParam(1, $shortCode);
$statement->execute();

if ($statement->rowCount() == 0) {
    header("HTTP/1.0 404 Not Found");
    echo "No URL found for the provided shortened URL.";

    exit;
}


$row = $statement->fetch(PDO::FETCH_ASSOC);
$originalUrl = $row['original_url'];

$updateQuery = "UPDATE urls SET clicks = clicks + 1 WHERE short_code = ?";
$updateStatement = $db->prepare($updateQuery);
$updateStatement->bindParam(1, $shortCode);
$updateStatement->execute();

header("Location: " . $originalUrl);

exit;
