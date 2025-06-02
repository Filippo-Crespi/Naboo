<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-type: application/json');

// CORS Handler
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(204);
  exit();
}
