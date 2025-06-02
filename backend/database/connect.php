<?php

require_once 'config.php';


try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Errore di connessione: " . $e->getMessage();
}

function tokenToId($token)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT user_id FROM sessions WHERE session_uuid = :token LIMIT 1");
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? (int)$result['user_id'] : null;
}
