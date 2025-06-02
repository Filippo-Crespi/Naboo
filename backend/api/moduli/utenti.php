<?php

require_once "../cors.php";
require_once '../../database/connect.php';

//funzione per verificare che la sessione sia ativa e che il token sia valido 
function verificaSessione($pdo, $session_uuid): bool
{
  $stmt = $pdo->prepare("SELECT expires_at FROM sessions WHERE session_uuid = ?");
  $stmt->execute([$session_uuid]);
  $res = $stmt->fetchColumn();
  // Se il token non esiste
  if ($res == NULL) {
    return false;
  }
  // Se il token è scaduto
  if ($res < date("Y-m-d H:i:s")) {
    return false;
  }
  // Se il token è valido
  return true;
}
//funzione per verificare che un modulo esista
function verificaModulo($pdo, $id_form)
{
  $stmt = $pdo->prepare("SELECT id_form FROM forms WHERE id_form = ?");
  $stmt->execute([$id_form]);
  $res = $stmt->fetchColumn();
  if ($res == NULL) {
    http_response_code(404);
    die("'Modulo non trovato'");
  }
  return $res;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $input = json_decode(file_get_contents("php://input"), true);

  if (!isset($input["title"]) || !isset($input["description"])) {
    // CREAZIONE NUOVO MODULO CON TUTTI I DATI

    $session_uuid = $input["session_uuid"];
    $code = $input['code'];
    $form_data = json_encode($input['data']);

    if (!$result = verificaSessione($pdo, $session_uuid)) {
      //se il token non è valido 

      $msg = "Sessione scaduta, effettua il login";
      http_response_code(404);

      $risposta = [
        "success" => false,
        "message" => $msg,
        "data" => NULL
      ];
      exit;
    } else {
      //query per trovare il modulo richiesto 
      $sql = "SELECT id_form FROM forms WHERE code=:code";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(":code", $code);
      $result = $stmt->execute();
      $id_form = $stmt->fetchColumn();

      if ($id_form === false) { //se non esiste il modulo 
        $msg = "Modulo non trovato";
        http_response_code(404);
        $risposta = [
          "success" => false,
          "message" => $msg,
          "data" => NULL
        ];
        echo json_encode($risposta);
        exit;
      }

      $stmt = $pdo->prepare('UPDATE forms SET data = :data WHERE code = :code');
      $stmt->bindParam(':data', $form_data);
      $stmt->bindParam(':code', $code);
      if ($stmt->execute()) {
        http_response_code(201);
        $risposta = array(
          "success" => true,
          "message" => "Modulo aggiornato con successo",
          "data" => NULL
        );
      }
    }
  } else {

    // CREAZIONE NUOVO MODULO TIOLO E DESCRZIONE 
    $session_uuid = $input["session_uuid"];
    $title = $input["title"];
    $is_anonymous = $input["anonymous"];
    $description = $input["description"];
    if (!verificaSessione($pdo, $session_uuid)) {
      echo json_encode([
        "success" => false,
        "message" => "Sessione scaduta, effettua il login",
        "data" => null
      ]);
      exit;
    }
    // Creo il nuovo modulo
    $user_id = tokenToId($session_uuid);
    $stmt = $pdo->prepare("SELECT UUID()");
    $stmt->execute();
    $code = $stmt->fetchColumn();
    $sql = "INSERT INTO forms (user_id, title, description, code, anonymous) VALUES (:user_id, :title, :description, :code, :is_anonymous)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":code", $code);
    $stmt->bindParam(":is_anonymous", $is_anonymous, PDO::PARAM_BOOL);
    $stmt->execute();
    //estrapolo l'id del nuovo modulo
    $id = $pdo->lastInsertId();
    $data = [
      "code" => $code,
      "id_form" => $id,
    ];
    http_response_code(200);
    $risposta = [
      "success" => true,
      "message" => "Modulo creato con successo con codice: $code",
      "data" => $data
    ];
  }

  echo json_encode($risposta); //invio della risposta
  exit;
} else if ($_SERVER['REQUEST_METHOD'] === "GET") {
  if (isset($_GET["code"])) {
    $code = $_GET["code"];
    $fields = isset($_GET["fields"]) ? explode(",", $_GET["fields"]) : ["id_form", "title", "description", "code", "created_at", "updated_at"];
    // Costruisci la query dinamica solo con i campi consentiti
    $allowedFields = ["id_form", "title", "description", "code", "created_at", "updated_at", "user_id", "anonymous", "needs_authentication", "is_active", "data"];
    $selectedFields = array_intersect($fields, $allowedFields);
    if (empty($selectedFields)) $selectedFields = ["id_form", "title", "description", "code"];
    $sql = "SELECT " . implode(", ", $selectedFields) . " FROM forms WHERE code = :code LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":code", $code);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
      http_response_code(404);
      echo json_encode([
        "success" => false,
        "message" => "Modulo non trovato",
        "data" => null
      ]);
      exit;
    }
    http_response_code(200);
    echo json_encode([
      "success" => true,
      "message" => "Successo",
      "data" => $result
    ]);
    exit;
  } else if (isset($_GET["session_uuid"])) {
    $session_uuid = $_GET["session_uuid"];
    if (!verificaSessione($pdo, $session_uuid)) {
      $msg = "Sessione scaduta, effettua il login";
      http_response_code(401);
      echo json_encode([
        "success" => false,
        "message" => $msg,
        "data" => null
      ]);
      exit;
    }
    $id_user = tokenToId($session_uuid);
    $sql = "SELECT f.description, f.title, f.id_form, f.code, f.created_at, f.updated_at, f.anonymous
            FROM users u INNER JOIN forms f ON u.id_user = f.user_id
            WHERE u.id_user = :id_user ORDER BY f.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id_user", $id_user);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode([
      "success" => true,
      "message" => null,
      "data" => $data
    ]);
    exit;
  }
} else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {


  $dati = $_SERVER['QUERY_STRING']; //ricevo i dati nell'url, questa funzione permette di avere i parametri 

  $dati = explode("&", $dati);
  $dati1 = explode("=", $dati[0]);
  $modulo = explode("=", $dati[1]);

  if ($dati1[0] == "Token") {

    $id = mysqli_fetch_assoc(verificaToken($dati1[1], $conn))["IDF_Utente"];
  } else if ($dati1[0] == "ID_Utente") {

    $id = $dati1[1];
  }

  if ($id == NULL) { //se il token non è valido 

    $msg = "Sessione scaduta, effettua il login";
    http_response_code(404);

    $risposta = array(
      "success" => false,
      "message" => $msg,
      "data" => NULL
    );
  } else {
    //query per trovare il modulo richiesto 
    $sql = "SELECT ID_Modulo FROM Moduli WHERE IDF_Utente=$id AND ID_Modulo=" . $modulo[1];
    $result = mysqli_query($conn, $sql);

    if (!$result) {
      http_response_code(500);
      die("'Errore nella query'");
    }

    $data = mysqli_fetch_assoc($result);
    if ($data > 0) {
      //query per cancellare il modulo 
      $sql = "DELETE FROM Moduli WHERE ID_Modulo=" . $modulo[1];
      $result = mysqli_query($conn, $sql);

      if (!$result) {
        http_response_code(500);
        die("'Errore nella query'");
      }

      http_response_code(200);
      $risposta = array(
        "success" => true,
        "message" => "Modulo eliminato con successo",
        "data" => NULL
      );
    }
  }

  echo json_encode($risposta); ///funzione per convedrtire da array associativo a json e inviarla 
  $conn->close(); //chiudo la connessione 


} else if ($_SERVER['REQUEST_METHOD'] === "PACTH") {


  $dati = $_SERVER['QUERY_STRING']; //ricevo i dati nell'url, questa funzione permette di avere i parametri 

  $dati = explode("&", $dati);
  $token = explode("=", $dati[0]);
  $modifica = explode("=", $dati[1]);

  if ($token[0] == "Token") {

    $id = mysqli_fetch_assoc(verificaToken($token[1], $conn))["IDF_Utente"];
  }
  if ($id == NULL) { // se il token non è valido 

    $msg = "Sessione scaduta, effettua il login";
    http_response_code(404);

    $risposta = array(
      "success" => false,
      "message" => $msg,
      "data" => NULL
    );
  }

  if ($modifica[1] == "0") {
    //rimozione
  } else if ($modifica[1] == "1") {
    //modifica

    $dati = json_decode(file_get_contents("php://input"), true);

    if (isset($dati["ID_Sezione"])) {
      //se si modifica una sezione 
      $sql = "UPDATE Sezioni SET" . $dati["nome"] . "  = " . $dati["valore"] . ";";
    } else if (isset($dati["ID_Domanda"])) {
      //se si modifica una domanda
      $sql = "UPDATE Domande SET" . $dati["nome"] . "  = " . $dati["valore"] . ";";
    } else if (isset($dati["ID_Risposta"])) {
      //se si modifica una risposta
      $sql = "UPDATE Risposte SET " . $dati["nome"] . "  = " . $dati["valore"] . ";";
    } else if (isset($dati["ID_Modulo"])) {
      //se si modifica un modulo
      $sql = "UPDATE Moduli SET" . $dati["nome"] . "  = " . $dati["valore"] . ";";
    }

    if (!$result  = mysqli_query($conn, $sql)) {
      http_response_code(500);
      die("Modifica | Errore query");
    }
  } else if ($modifica[1] == "2") {
    //aggiunta
  }





  echo json_encode($risposta); //invio della risposta 
  $conn->close(); //chiudo la connessione 

}
