<?php

//header della risposta
require("../cors.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once('../../database/Connection.php');
  // Conversione dei dati in JSON in un array associativo
  $dati = json_decode(file_get_contents("php://input"), true);

  $idMod = $dati["ID_Modulo"];
  //query per inserire un risultato 
  $sql = "INSERT INTO Risultati (IDF_Modulo) VALUES ('$idMod');";
  $result = mysqli_query($conn, $sql);
  //ottengo il nuovo id dek risultato appena crato 
  $idRis = mysqli_insert_id($conn);
  foreach ($dati["Risposte"] as $ris) {
    $idRisposta = $ris["ID_Risposta"];
    $tipoDomanda = $ris["ID_Tipologia"];
    //inserisco la risposta data dall'utente in base alla tipologia della domanda 
    switch ($tipoDomanda) {
        //risposta breve
      case '6':
        $testo = $ris["Testo"];
        $sql = "INSERT INTO DettRisultati(IDF_Risultato, IDF_Risposta, RispostaBreve) VALUES('$idRis', '$idRisposta', '$testo');";
        break;
        //risposta luga
      case '7':
        $testo = $ris["Testo"];
        $sql = "INSERT INTO DettRisultati(IDF_Risultato, IDF_Risposta, RispostaLunga) VALUES('$idRis', '$idRisposta', '$testo');";
        break;
        //risposta senza limiti
      case '8':
        $testo = $ris["Testo"];
        $sql = "INSERT INTO DettRisultati(IDF_Risultato, IDF_Risposta, RispostaNoLimiti) VALUES('$idRis', '$idRisposta', '$testo');";
        break;
        //scelta multipla
      default:
        $sql = "INSERT INTO DettRisultati (IDF_Risultato, IDF_Risposta) VALUES ('$idRis', '$idRisposta');";
        break;
    }
    if (!mysqli_query($conn, $sql)) {
      die("Compila | Errore query");
    }
  }

  http_response_code(201);
  $msg = "Caricamento risultati riuscito con successo";
  $risposta = array(
    "success" => true,
    "message" => $msg,
    "data" => NULL
  );
//invio della risposta
  echo json_encode($risposta);
}
