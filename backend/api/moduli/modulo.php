<?php

require("../cors.php");
require_once("../../database/connect.php");

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  // Verifica che sia stato passato il codice del modulo
  if (!isset($_GET["code"])) {
    http_response_code(400);
    echo json_encode([
      "success" => false,
      "message" => "Parametro 'code' mancante",
      "data" => null
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
  }

  $code = $_GET["code"];

  try {
    // Prepara la query per recuperare il modulo tramite il codice
    $stmt = $pdo->prepare("SELECT id_form, code, data, anonymous, needs_authentication, is_active, created_at, updated_at FROM forms WHERE code = :code LIMIT 1");
    $stmt->bindParam(":code", $code, PDO::PARAM_STR);
    $stmt->execute();
    $form = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$form) {
      http_response_code(404);
      echo json_encode([
        "success" => false,
        "message" => "Modulo non trovato",
        "data" => null
      ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      exit;
    }

    // Decodifica il campo JSON della struttura modulo
    $formData = json_decode($form["data"], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      http_response_code(500);
      echo json_encode([
        "success" => false,
        "message" => "Errore nella decodifica del campo JSON del modulo",
        "data" => null
      ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      exit;
    }

    // Restituisci la struttura modulo e alcuni metadati utili
    $response = [
      "success" => true,
      "message" => null,
      "data" => [
        "id_form" => $form["id_form"],
        "code" => $form["code"],
        "anonymous" => (bool)$form["anonymous"],
        "needs_authentication" => (bool)$form["needs_authentication"],
        "is_active" => (bool)$form["is_active"],
        "created_at" => $form["created_at"],
        "updated_at" => $form["updated_at"],
        "structure" => $formData
      ]
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
      "success" => false,
      "message" => "Errore database: " . $e->getMessage(),
      "data" => null
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
  }
}
