<?php
require_once("../cors.php");
require_once("../../database/Connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents("php://input"), true);
  $email = $input['Email'] ?? '';
  $password = $input['Password'] ?? '';

  if (!$email || !$password) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Email e password obbligatorie",
      "data" => null
    ]);
    exit;
  }

  $sql = "SELECT * FROM Utenti WHERE Email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
  $result = mysqli_query($conn, $sql);
  if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(401);
    echo json_encode([
      "success" => false,
      "message" => "Credenziali non valide",
      "data" => null
    ]);
    exit;
  }
  $user = mysqli_fetch_assoc($result);
  if (!password_verify($password, $user['Password'])) {
    http_response_code(401);
    echo json_encode([
      "success" => false,
      "message" => "Credenziali non valide",
      "data" => null
    ]);
    exit;
  }
  // Genera token sessione
  $token = bin2hex(random_bytes(32));
  $sql = "INSERT INTO Sessioni (IDF_Utente, Token, DataInizio, Sospeso) VALUES ('{$user['ID_Utente']}', '$token', NOW(), 0)";
  mysqli_query($conn, $sql);
  // Sospendi tutte le altre sessioni di quell'utente
  $sql = "UPDATE Sessioni SET Sospeso=1 WHERE IDF_Utente='{$user['ID_Utente']}' AND Token != '$token'";
  mysqli_query($conn, $sql);
  echo json_encode([
    "success" => true,
    "message" => "Login effettuato",
    "data" => ["token" => $token]
  ]);
  exit;
}

http_response_code(405);
echo json_encode([
  "success" => false,
  "message" => "Metodo non consentito",
  "data" => null
]);
