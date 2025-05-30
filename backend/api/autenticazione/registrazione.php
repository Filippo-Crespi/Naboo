<?php
require_once("../cors.php");
require_once("../../database/Connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents("php://input"), true);
  $nome = $input['Nome'] ?? '';
  $cognome = $input['Cognome'] ?? '';
  $email = $input['Email'] ?? '';
  $password = $input['Password'] ?? '';
  $username = $input['Username'] ?? '';

  if (!$nome || !$cognome || !$email || !$password || !$username) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Compilare tutti i campi",
      "data" => null
    ]);
    exit;
  }
  $sql = "SELECT * FROM Utenti WHERE Email = '" . mysqli_real_escape_string($conn, $email) . "' OR Username = '" . mysqli_real_escape_string($conn, $username) . "' LIMIT 1";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    http_response_code(409);
    echo json_encode([
      "success" => false,
      "message" => "Email o username già registrati",
      "data" => null
    ]);
    exit;
  }
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO Utenti (Nome, Cognome, Email, Password, Username, DataReg) VALUES ('" . mysqli_real_escape_string($conn, $nome) . "', '" . mysqli_real_escape_string($conn, $cognome) . "', '" . mysqli_real_escape_string($conn, $email) . "', '$hash', '" . mysqli_real_escape_string($conn, $username) . "', NOW())";
  if (mysqli_query($conn, $sql)) {
    http_response_code(201);
    echo json_encode([
      "success" => true,
      "message" => "Registrazione avvenuta con successo",
      "data" => null
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore durante la registrazione",
      "data" => null
    ]);
  }
  exit;
}

http_response_code(405);
echo json_encode([
  "success" => false,
  "message" => "Metodo non consentito",
  "data" => null
]);
