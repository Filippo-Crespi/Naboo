<?php
require_once('database.php');

if (isset($_POST['register'])) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $name = $_POST['name'];
  $email = $_POST['email'];
  $cognome = $_POST['surname'];

  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  $query = "
            SELECT id
            FROM users
            WHERE username = :username
        ";

  $check = $pdo->prepare($query);
  $check->bindParam(':username', $username, PDO::PARAM_STR);
  $check->execute();

  $user = $check->fetchAll(PDO::FETCH_ASSOC);

  if (count($user) > 0) {
    $msg = 'Username già in uso %s';
  } else {
    $query = "
                INSERT INTO users
                VALUES (0, :username, :password)
            ";

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $username, PDO::PARAM_STR);
    $check->bindParam(':password', $password_hash, PDO::PARAM_STR);
    $check->execute();

    if ($check->rowCount() > 0) {
      $msg = 'Registrazione eseguita con successo';
    } else {
      $msg = 'Problemi con l\'inserimento dei dati %s';
    }
  }
}

printf($msg, '<a href="../register.html">torna indietro</a>');
