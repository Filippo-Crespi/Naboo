<?php

require("../cors.php");

//funzione per verificare che la sessione sia ativa e che il token sia valido 
function verificaToken($token, $conn)
{
  $sql = "SELECT IDF_Utente FROM Sessioni WHERE Token='$token' AND NOT Sospeso"; //query per verificare se il token è sospeso 
  $result = mysqli_query($conn, $sql);
  if (!$result) {          //se la funzione non funziona per qualche ragione restituisco un errore
    http_response_code(500);
    die("'Errore nella query verifica token'");
  }
  return $result;
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  require_once('../../database/Connection.php');
  /*if(isset($_GET["Token"])){
        if(!mysqli_fetch_array(verificaToken($_GET["Token"], $conn))){
            $msg = "Token non valido";
            http_response_code(401);

            $risposta = array(
            "success" => false,
            "message" => $msg,
            "data"=>NULL
        );
        }
        else{*/
        //query per selezionare tutte le tipologie di domande esistenti
  $sql = "SELECT * FROM Tipologie";
  if (!$result = mysqli_query($conn, $sql)) {
    http_response_code(500);
    die("'Errore nella query verifica token'");
  }

  $tipologie = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $tipologie[] = $row;
  }

  $msg = "Query eseguita con successo";
  http_response_code(200);

  $risposta = array(
    "success" => true,
    "message" => $msg,
    "data" => $tipologie
  );

  echo json_encode($risposta); //invio della risposta
}

//}

//echo json_encode($risposta);
