<?php
require_once("../cors.php");
require_once("../../database/Connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Elenco sessioni (opzionale: filtro per utente o stato)
  $sql = "SELECT s.*, u.Email FROM Sessioni s JOIN Utenti u ON s.IDF_Utente = u.ID_Utente ORDER BY s.DataInizio DESC";
  $result = mysqli_query($conn, $sql);
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  echo json_encode([
    "success" => true,
    "message" => null,
    "data" => $data
  ]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  // Elimina una sessione tramite token
  $token = $_GET['token'] ?? '';
  if (!$token) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Token mancante",
      "data" => null
    ]);
    exit;
  }
  $sql = "DELETE FROM Sessioni WHERE Token = '" . mysqli_real_escape_string($conn, $token) . "'";
  if (mysqli_query($conn, $sql)) {
    echo json_encode([
      "success" => true,
      "message" => "Sessione eliminata",
      "data" => null
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore durante l'eliminazione",
      "data" => null
    ]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
  // Sospendi una sessione (imposta Sospeso=1)
  $input = json_decode(file_get_contents("php://input"), true);
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
  $sql = "UPDATE Sessioni SET Sospeso=1 WHERE Token = '" . mysqli_real_escape_string($conn, $token) . "'";
  if (mysqli_query($conn, $sql)) {
    echo json_encode([
      "success" => true,
      "message" => "Sessione sospesa",
      "data" => null
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore durante la sospensione",
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
