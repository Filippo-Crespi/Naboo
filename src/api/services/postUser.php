<?php

require_once('connection.php');


if (isset($_POST)) {
  $dati = json_decode(file_get_contents("php://input"), true);
  $sql = "SELECT ";
  foreach ($dati["array"] as $key->$valore) {

    $sql .= "Utenti.$valore, ";
  }
  $sql .= " FROM Utenti INNER JOIN Sessioni 
  ON Utenti.ID_Utenti=Sessioni.ID_Utenti 
  WHERE Sessioni.Token='{$dati['token']} ' ";
}
