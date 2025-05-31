<?php

require_once 'config.php';


try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Errore di connessione: " . $e->getMessage();
}