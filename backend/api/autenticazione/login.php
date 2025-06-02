<?php
require_once "../cors.php";
require_once "../../database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents("php://input"), associative: true);
  $email = $input['email'] ?? '';
  $password = $input['password'] ?? '';

  if (!$email || !$password) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Email e password obbligatorie",
      "data" => null
    ]);
    exit;
  }

  // Controllo se l'utente è registrato
  $stmt = $pdo->prepare("SELECT password FROM users WHERE email = :email");
  $stmt->bindParam(":email", $email);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  // Utente non registrato
  if (!$result) {
    http_response_code(401);
    echo json_encode([
      "success" => false,
      "message" => "Utente non registrato",
      "data" => null
    ]);
    exit;
  }
  // Verifica corrispondenza password
  $hashedPassword = $result['password'];
  // Password errata
  if (!password_verify($password, $hashedPassword)) {
    http_response_code(403);
    echo json_encode([
      "success" => false,
      "message" => "Email o password errate",
      "data" => null
    ]);
    exit;
  }
  // Dati dell'utente da restituire
  $stmt = $pdo->prepare("SELECT id_user, first_name, last_name, username, email FROM users WHERE email = :email");
  $stmt->bindParam(":email", $email);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // Generazione uuid di sessione
  $stmt = $pdo->query("SELECT UUID() AS uuid");
  $uuid = $stmt->fetchColumn();
  // Creazione della sessione
  $stmt = $pdo->prepare("INSERT INTO sessions (session_uuid, user_id, expires_at) VALUES (:session_uuid, :user_id, DATE_ADD(NOW(), INTERVAL 24 HOUR))");
  $stmt->bindParam(":session_uuid", $uuid);
  $stmt->bindParam(":user_id", $user['id_user']);
  $stmt->execute();
  // Risposta al client con uuid sessione e dati
  echo json_encode([
    "success" => true,
    "message" => "Login effettuato",
    "data" => [
      "user" => $user,
      "session_uuid" => $uuid
    ]
  ]);
  exit;
}

http_response_code(405);
echo json_encode([
  "success" => false,
  "message" => "Metodo non consentito",
  "data" => null
]);
