<?php
require_once "../cors.php";
require_once "../../database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents("php://input"), associative: true);
  $first_name = $input['first_name'] ?? '';
  $last_name = $input['last_name'] ?? '';
  $email = $input['email'] ?? '';
  $password = $input['password'] ?? '';
  $username = $input['username'] ?? '';

  // Controllo campi tutti compilati
  if (!$first_name || !$last_name || !$email || !$password || !$username) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Compilare tutti i campi",
      "data" => null
    ]);
    exit;
  }

  // Controllo esistenza email o username
  $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = :email OR username = :username LIMIT 1");
  $stmt->bindParam(":email", $email);
  $stmt->bindParam(":username", $username);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result) {
    http_response_code(409);
    echo json_encode([
      "success" => false,
      "message" => "Email o username già registrati",
      "data" => null
    ]);
    exit;
  }
  // Hash della password inserita
  $hash = password_hash($password, PASSWORD_DEFAULT);
  // Creazione dell'utente
  $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, password, email) VALUES (:first_name, :last_name, :username, :password, :email)");
  $stmt->bindParam(":first_name", $first_name);
  $stmt->bindParam(":last_name", $last_name);
  $stmt->bindParam(":username", $username);
  $stmt->bindParam(":password", $hash);
  $stmt->bindParam(":email", $email);
  if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
      "success" => true,
      "message" => "Registrazione avvenuta con successo",
      "data" => null
    ]);
  } else {
    // Errore generico
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore durante la registrazione, riprovare",
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
