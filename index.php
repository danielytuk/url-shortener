<?php
$db = new mysqli("host", "user", "pass", "table_name");

if ($db->connect_error) die("Connection failed: " . $db->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  isset($_GET["code"]) ? handleRedirectRequest($db, $_GET["code"]) : (isset($_GET["url"]) ? handleShortenRequest($db, $_GET["url"]) : sendErrorResponse(400, "Invalid request"));
}

exit;

function handleRedirectRequest($db, $code) {
  $code = sanitizeInput($code);
  $result = $db->query("SELECT original FROM links WHERE short = '$code'");
  if ($result->num_rows === 1) redirectToUrl($result->fetch_assoc()["original"]);
  elseif (isset($_GET['code']) && isset($_GET['url'])) handleShortenRequest($db, $_GET["url"]);
  else sendErrorResponse(404, "Invalid short code");
}

function handleShortenRequest($db, $url) {
  $url = sanitizeInput($url);
  if (!filter_var($url, FILTER_VALIDATE_URL)) sendErrorResponse(400, "Invalid URL");

  $existingUrlResult = $db->query("SELECT short FROM links WHERE original = '$url'");
  if ($existingUrlResult->num_rows === 1) sendResponse(["short_url" => "https://{$_SERVER['HTTP_HOST']}?code={$existingUrlResult->fetch_assoc()['short']}"]);

  $code = isset($_GET["code"]) && !empty($_GET["code"]) ? sanitizeInput($_GET["code"]) : generateUniqueCode(7);
  $sql = "INSERT INTO links (original, short) VALUES ('$url', '$code')";
  if ($db->query($sql) === TRUE) sendResponse(["short_url" => "https://{$_SERVER['HTTP_HOST']}?code=$code"]);
  else sendErrorResponse(500, "Failed to save URL");
}

function redirectToUrl($url) {
  header("Location: $url");
  exit();
}

function sendResponse($data) {
  echo json_encode($data);
  exit();
}

function sendErrorResponse($statusCode, $errorMessage) {
  http_response_code($statusCode);
  echo json_encode(["error" => $errorMessage]);
  exit();
}

function sanitizeInput($input) {
  return htmlspecialchars(trim($input));
}

function generateUniqueCode($length) {
  return substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', ceil($length / 62))), 0, $length);
}

