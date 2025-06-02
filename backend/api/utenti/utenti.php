<?php

require_once "../cors.php";
require_once '../../database/connect.php';

//FUNZIONI-------------------------------------------

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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $arr = [];
    $IsID = false;
    if (isset($_GET["id_user"])) {
        $id = intval($_GET["id_user"]);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res == NULL) {
            http_response_code(404);
            die("utente non trovato");
        }
        $arr[] = $res;
        $IsID = true;
    } else if (isset($_GET["token"])) {
        $token = $_GET["token"];
        $stmt = $pdo->prepare("SELECT id_user FROM users INNER JOIN sessions ON users.id_user = sessions.user_id WHERE sessions.session_uuid = ?");
        $stmt->execute([$token]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res == NULL) {
            http_response_code(404);
            die("utente non trovato");
        }
        $id = $res["id_user"];
        $IsID = true;
    }

    $sql = "SELECT ";
    $params = [];
    if (isset($_GET["fields"])) {
        $fields = $_GET["fields"];
        $requested = explode(',', $fields);
        foreach ($requested as $key => $val) {
            $sql .= "users." . trim($val) . ", ";
        }
        $sql = substr($sql, 0, -2);
    } else {
        $sql .= "*";
    }

    if (isset($_GET["limit"])) {
        $limit = intval($_GET["limit"]);
        $sql .= " FROM users LIMIT $limit";
    } else {
        if ($IsID) {
            $sql .= " FROM users WHERE id_user = ?";
            $params[] = $id;
        } else {
            $sql .= " FROM users";
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    if (!$IsID) {
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $risposta = array(
        "success" => true,
        "message" => NULL,
        "data" => $arr
    );
    http_response_code(200);
}

//METODO PATCH --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $input = json_decode(file_get_contents("php://input"), associative: true);
    $session_uuid = $input['session_uuid'];

    // Verifica sessione con PDO
    if (!verificaSessione($pdo, $session_uuid)) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Sessione non valida o scaduta",
            "data" => null
        ]);
        exit;
    }

    // Ricava l'ID utente associato al session id
    $stmt = $pdo->prepare("SELECT users.id_user FROM users INNER JOIN sessions ON users.id_user = sessions.user_id WHERE sessions.session_uuid = ?");
    $stmt->execute([$session_uuid]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$res) {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "Utente non trovato",
            "data" => null
        ]);
        exit;
    }
    $id_user = $res["id_user"];

    // Ricava i dati da modificare
    if (!$input || !is_array($input)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Modifiche non valide",
            "data" => null
        ]);
        exit;
    }

    // Costruisci query dinamica e parametri
    $input['session_uuid'] = null;
    $params = [];
    $fields = [];
    foreach ($input as $key => $newValue) {
        if ($newValue !== null) {
            $fields[] = "`$key` = ?";
            $params[] = $newValue;
        }
    }
    // Aggiungi id_user alla fine dei parametri
    $params[] = $id_user;

    // Prepara ed esegui la query di UPDATE
    $sql = "UPDATE `users` SET " . implode(", ", $fields) . " WHERE `id_user` = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        $risposta = [
            "success" => true,
            "message" => "Dati aggiornati con successo",
            "data" => null
        ];
    } else {
        http_response_code(500);
        $risposta = [
            "success" => false,
            "message" => "Errore durante l'aggiornamento dei dati",
            "data" => null
        ];
    }
}

//METODO DELETE --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode("?", $uri);
    $uri[1] = urldecode($uri[1]);
    $uri[1] = explode("=", $uri[1]);

    if ($uri[1][0] == "id") {
        $id_user = intval($uri[1][1]);
    } else {
        $session_uuid = $uri[1][1];
        if (!verificaSessione($pdo, $session_uuid)) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Sessione non valida o scaduta",
                "data" => null
            ]);
            exit;
        }
        $stmt = $pdo->prepare("SELECT users.id_user FROM users INNER JOIN sessions ON users.id_user = sessions.user_id WHERE sessions.session_uuid = ?");
        $stmt->execute([$session_uuid]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Utente non trovato",
                "data" => null
            ]);
            exit;
        }
        $id_user = $res["id_user"];
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id_user = ?");
    if ($stmt->execute([$id_user])) {
        http_response_code(200);
        $risposta = [
            "success" => true,
            "message" => "Utente eliminato con successo",
            "data" => null
        ];
    } else {
        http_response_code(500);
        $risposta = [
            "success" => false,
            "message" => "Errore durante l'eliminazione dell'utente",
            "data" => null
        ];
    }
}

echo json_encode($risposta);
