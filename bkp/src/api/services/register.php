<?php
require_once('../database/Connection.php');

if (isset($_POST)) {
  $dati = json_decode(file_get_contents("php://input"), true);

  $username = $dati['username'];
  $password = $dati['password'];
  $name = $dati['name'];
  $email = $dati['email'];
  $cognome = $dati['surname'];

  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  $sql = "SELECT ID_Utente FROM Utenti WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    $msg = "Utente già registrato";
    $risposta = array(
      "success" => false,
      "msg" => $msg,
    );
  } else {
    $sql = "INSERT INTO UTENTI(username, password, nome, email, cognome) VALUES ( '$username', '$password_hash', '$name', '$email', '$cognome');";
    $result = mysqli_query($conn, $sql);
    $msg = " ";
    $risposta = array(
      "success" => true,
      "msg" => $msg,
    );
  }
  echo json_encode($risposta);
  $conn->close();
}
