<?php

require_once('./database/Connection.php');


if (isset($_POST)) {
  $dati = json_decode(file_get_contents("php://input"), true);
  $sql = "SELECT ";
  foreach ($dati["array"] as $key => $valore) {

    $sql .= "Utenti.$valore, ";
  }

  $sql = substr($sql, 0, -2);
  $sql .= " FROM Utenti INNER JOIN Sessioni 
  ON Utenti.ID_Utenti=Sessioni.ID_Utenti 
  WHERE Sessioni.Token='" . $dati['token'] . "' ";
  $result = mysqli_query($conn, $sql);
  if (!$result) {

    die("sei morto");
  }
  $res = mysqli_fetch_assoc($result);
  echo json_encode($res);
}
