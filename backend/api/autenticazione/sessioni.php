<?php
require_once("../cors.php");
require_once("../../database/Connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Validità sessione
  $input = json_decode(file_get_contents("php://input"), associative: true);
  $session_uuid = $input['session_uuid'] ?? '';
  $stmt = $pdo->prepare("SELECT 1 AS valid FROM sessions WHERE expires_at > NOW() AND session_uuid = :session_uuid");
  $stmt->bindParam(":session_uuid", $session_uuid);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $session = $result['valid'] ?? 0;
  if ($session == '0') {
    http_response_code(403);
    echo json_encode([
      "success" => false,
      "message" => "Sessione scaduta",
      "data" => null
    ]);
    exit;
  }
  http_response_code(200);
  echo json_encode([
    "success" => true,
    "message" => null,
    "data" => null
  ]);
}


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  // Elimina una sessione tramite token
  $input = json_decode(file_get_contents("php://input"), associative: true);
  $token = $input['token'] ?? '';
  if (!$token) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Token mancante",
      "data" => null
    ]);
    exit;
  }
  $stmt = $conn->prepare("DELETE FROM Sessioni WHERE Token = ?");
  $stmt->bind_param("s", $token);
  if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
      "success" => true,
      "message" => "Sessione eliminata",
      "data" => null
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore durante l'eliminazione della sessione",
      "data" => null
    ]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
  // Sospendi una sessione (imposta active=0)
  $input = json_decode(file_get_contents("php://input"), associative: true);
  $token = $input['token'] ?? '';
  if (!$token) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Token mancante",
      "data" => null
    ]);
    exit;
  }
  $stmt = $conn->prepare("UPDATE sessioni SET expires_at = NOW() WHERE token = ?");
  $stmt->bind_param("s", $token);
  if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
      "success" => true,
      "message" => "Sessione sospesa",
      "data" => null
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore durante la sospensione della sessione",
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
