<?php
require_once('connection.php');


if (isset($_POST)) {
  $dati = json_decode(file_get_contents("php://input"), true);

  $password = $dati['password'];
  $email = $dati['email'];


  $sql = "
            SELECT id, password
            FROM users
            WHERE email = '$email'
        ";

  $result = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) == 0) {

    $msg = "Utente non registrato";


    $risposta = array(

      "success" => false,
      "msg" => $msg,


    );
  } else {
    if (password_verify($password, $res['password'])) {


      $mioToken = random_bytes(16);

      $mioToken = bin2hex($mioToken);
      //inserire token nel database
      $msg = "";
      $risposta = array(

        "success" => true,
        "msg" => $msg,
        "token" => $mioToken,


      );
    } else {


      $msg = "Password errata";
      $risposta = array(

        "success" => false,
        "msg" => $msg,


      );
    }
  }
  echo json_encode($risposta);
  $conn->close();
}
