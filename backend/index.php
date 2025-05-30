<?php
$host = 'db'; // 👈 deve essere esattamente questo
$db   = 'myapp';
$user = 'filippo';
$pass = 'filippo123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
  echo "✅ Connessione al database riuscita!";
} catch (\PDOException $e) {
  echo "❌ Errore di connessione: " . $e->getMessage();
}
