<?php

require("../cors.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//funzione per verificare che la sessione sia ativa e che il token sia valido 
function verificaToken($token, $conn)
{
  $sql = "SELECT IDF_Utente FROM Sessioni WHERE Token='$token' AND NOT Sospeso";
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    http_response_code(500);
    die("'Errore nella query verifica token'");
  }
  return $result;
}
//funzione per verificare che un modulo esista
function verificaModulo($idModulo, $conn)
{
  $sql = "SELECT ID_Modulo FROM Moduli WHERE ID_Modulo='$idModulo' ";
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    http_response_code(500);
    die("'Errore nella query verifica modulo'");
  }
  return $result;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  require_once('../../database/Connection.php');
// Conversione dei dati in JSON in un array associativo
  $dati = json_decode(file_get_contents("php://input"), true);

  if (!isset($dati["Titolo"]) && !isset($dati["Descrizione"])) { // CREAZIONE NUOVO MODULO CON TUTTI I DATI 

    $token = $dati["Token"];
    $modulo = $dati["Codice"];
    $result = verificaToken($token, $conn);
    $utente = mysqli_fetch_assoc($result);
    if ($utente == NULL) { //se il token non è valido 

      $msg = "Sessione scaduta, effettua il login";
      http_response_code(404);

      $risposta = array(
        "success" => false,
        "message" => $msg,
        "data" => NULL
      );
    } else {
        //query per trovare il modulo richiesto 
      $sql = "SELECT ID_Modulo FROM Moduli WHERE Codice='$modulo'";
      $result = mysqli_query($conn, $sql);
      if (!$result) {
        die("'Errore nella query'");
      }

      if (mysqli_num_rows($result) < 1) {//se non esiste il modulo 
        $msg = "Modulo non trovato";
        http_response_code(404);
        $risposta = array(
          "success" => false,
          "message" => $msg,
          "data" => NULL
        );
        echo json_encode($risposta);
        $conn->close();
        exit;
      }
      $idModulo = mysqli_fetch_assoc($result)["ID_Modulo"];
        //query per trovare le sezioni del modulo 
      $sql = "SELECT ID_Sezione FROM Sezioni WHERE IDF_Modulo='$idModulo'";
      $result = mysqli_query($conn, $sql);
      if (!$result) {
        die("'Errore nella query'");
      }
      if (mysqli_num_rows($result) > 0) {//se esistono delle sezioni 
        $msg = "Esistono già delle sezioni per questo modulo, impossibile continuare";
        http_response_code(400);
        $risposta = array(
          "success" => false,
          "message" => $msg,
          "data" => NULL
        );
        echo json_encode($risposta);
        $conn->close();
        exit;
      }
      $sezioni = $dati["Sezioni"];
      foreach ($sezioni as $sezione) {

        $testo = $sezione["Nome"];
        //inserisco le seioni nel database
        $sql = "INSERT INTO Sezioni (IDF_Modulo, Nome) VALUES ('" . $idModulo . "','" . $testo . "')";
        $result = mysqli_query($conn, $sql);

        if (!$result) {

          die("'Errore nella query'");
        }
        //estrapolo il nuovo id delle sezioni appena create 
        $id_sezione = mysqli_insert_id($conn);

        foreach ($sezione["Domande"] as $domanda) {
            //query per ricavare la tipologia di domanda 
          $sql = "SELECT ID_Tipologia FROM Tipologie WHERE ID_Tipologia = '" . $domanda["Tipologia"] . "'";
          $result = mysqli_query($conn, $sql);
          if (!$result || mysqli_num_rows($result) < 1) {//se la tipologia non esiste 
            http_response_code(500);
            $msg = "Tipologia non esiste";
            $risposta = array(
              "success" => false,
              "message" => $msg,
              "data" => NULL
            );
            echo json_encode($risposta); ///funzione per convedrtire da array associativo a json e inviarla 
            $conn->close(); //chiudo la connessione
          }
    //query er inserire la doamnda nel database 
          $sql = "INSERT INTO Domande (IDF_Sezione, IDF_Modulo, IDF_Tipologia, Testo, Descrizione) VALUES ('" . $id_sezione . "', '" . $idModulo . "','" . $domanda["Tipologia"] . "', '" . mysqli_real_escape_string($conn, $domanda["Testo"]) . "','" . mysqli_real_escape_string($conn, $domanda["Descrizione"]) . "')";
          $result = mysqli_query($conn, $sql);

          if (!$result) {

            die("'Errore nella query'");
          }
            //ricavo il nuovo id della nuova domanda 
          $id_domanda = mysqli_insert_id($conn);
          foreach ($domanda["Risposte"] as $risposta) {
              //se la domanda è a risposta aperta allora non inserisco alcun testo nelle risposte nel database 
            if ($domanda["Tipologia"] == 1 || $domanda["Tipologia"] == 6 || $domanda["Tipologia"] == 7) {

              $testo_risposta = NULL;
            } else {

              $testo_risposta = $risposta["Testo"];
            }
            //query per inserire le possibili risposte nel database 
            $sql = "INSERT INTO Risposte (IDF_Domanda, Testo, Punteggio) VALUES ('" . $id_domanda . "', '" . $testo_risposta . "','" . $risposta["Punteggio"] . "')";
            $result = mysqli_query($conn, $sql);
            if (!$result) {

              die("'Errore nella query'");
            }
          }
        }
      }
      $msg = "Modulo creato con successo";
      http_response_code(201);

      $risposta = array(
        "success" => true,
        "message" => $msg,
        "data" => NULL
      );
    }
  } else {

    // CREAZIONE NUOVO MODULO TIOLO E DESCRZIONE 

    $token = $dati["Token"];
    $titolo = $dati["Titolo"];
    $descrizione = $dati["Descrizione"];
    $codice = $dati["Codice"];
    $result = verificaToken($token, $conn);
    $utente = mysqli_fetch_assoc($result);

    if ($utente == NULL) {//se il token non è valido 

      $msg = "Sessione scaduta, effettua il login";
      http_response_code(404);

      $risposta = array(
        "success" => false,
        "message" => $msg,
        "data" => NULL
      );
    } else {
        //creo il nuovo modulo
      $sql = "INSERT INTO Moduli (IDF_Utente, Titolo, Descrizione, Codice) VALUES ('" . $utente['IDF_Utente'] . "', '" . $titolo . "', '" . $descrizione . "','" . $codice . "')";
      $result = mysqli_query($conn, $sql);
      //estrapolo l'id del nuovo modulo
      $id = mysqli_insert_id($conn);
      $data = array(

        "Codice" => $codice,
        "ID_Moduli" => $id


      );




      $msg = "Modulo creato";
      http_response_code(200);
      $risposta = array(
        "success" => true,
        "message" => $msg,
        "data" => $data
      );
    }
  }

  echo json_encode($risposta); //invio della risposta
  $conn->close();//chiusura connessione 
} else if ($_SERVER['REQUEST_METHOD'] === "GET") {

  require_once('../../database/Connection.php');
  if (isset($_GET["Codice"])) {

    $codice = $_GET["Codice"];
    $dati = $_GET["data"];
//query per trovare il modulo richiesto 
    $sql = "SELECT ID_Modulo FROM Moduli WHERE Codice='$codice' ";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      http_response_code(500);
      die("'Errore nella query verifica modulo'");
    }

    if (mysqli_fetch_assoc($result) == NULL) {//se il modulo non esiste 

      $msg = "Modulo non esiste";
      http_response_code(404);

      $risposta = array(
        "success" => false,
        "message" => $msg,
        "data" => NULL
      );
    } else {
        //divido la stringa ricevuta per ottenere i dati da inviare
      $dati = explode(" ", $dati);
      //li ricavo dal database 
      $sql = "SELECT ";
      foreach ($dati as $key => $valore) {
        $sql .= "Moduli.$valore, ";
      }
      $sql = substr($sql, 0, -2);
      $sql .= " FROM Moduli WHERE Codice='$codice'";

      $result = mysqli_query($conn, $sql);
      if (!$result) {
        http_response_code(500);
        die("'Errore nella query'");
      }
      $result = mysqli_fetch_assoc($result);

      $msg = "Successo";
      http_response_code(200);
      $risposta = array(
        "success" => true,
        "message" => $msg,
        "data" => $result
      );
    }

    echo json_encode($risposta);//invio la risposta 
    $conn->close();
  } else if (isset($_GET["Token"])) {


    $token = $_GET["Token"];
    $result = verificaToken($token, $conn);
    $utente = mysqli_fetch_assoc($result);
    if ($utente == NULL) {//se il token non è valido 

      $msg = "Sessione scaduta, effettua il login";
      http_response_code(404);

      $risposta = array(
        "success" => false,
        "message" => $msg,
        "data" => NULL
      );
    } else {
      $id = mysqli_fetch_assoc(verificaToken($token, $conn))['IDF_Utente'];
      //qury per ottenere i dati prinicièali di tutti i moduli di un utente specifico 

      $sql = "SELECT Moduli.Descrizione, Moduli.Titolo, Moduli.ID_Modulo, Moduli.Codice
                    FROM Utenti INNER JOIN Moduli ON Utenti.ID_Utente=Moduli.IDF_Utente
                    WHERE Utenti.ID_Utente='$id' ORDER BY Moduli.DataCreazione DESC";

      $result = mysqli_query($conn, $sql);

      if (!$result) {
        http_response_code(500);
        die("'Errore nella query'");
      }

      $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
      http_response_code(200);
      $risposta = array(
        "success" => true,
        "message" => NULL,
        "data" => $data
      );
    }
    echo json_encode($risposta); ///funzione per convertire da array associativo a json e inviarla
  }
} else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

  require_once('../../database/Connection.php');

  $dati = $_SERVER['QUERY_STRING']; //ricevo i dati nell'url, questa funzione permette di avere i parametri 

  $dati = explode("&", $dati);
  $dati1 = explode("=", $dati[0]);
  $modulo = explode("=", $dati[1]);

  if ($dati1[0] == "Token") {

    $id = mysqli_fetch_assoc(verificaToken($dati1[1], $conn))["IDF_Utente"];
  } else if ($dati1[0] == "ID_Utente") {

    $id = $dati1[1];
  }

  if ($id == NULL) {//se il token non è valido 

    $msg = "Sessione scaduta, effettua il login";
    http_response_code(404);

    $risposta = array(
      "success" => false,
      "message" => $msg,
      "data" => NULL
    );
  } else {
      //query per trovare il modulo richiesto 
    $sql = "SELECT ID_Modulo FROM Moduli WHERE IDF_Utente=$id AND ID_Modulo=" . $modulo[1];
    $result = mysqli_query($conn, $sql);

    if (!$result) {
      http_response_code(500);
      die("'Errore nella query'");
    }

    $data = mysqli_fetch_assoc($result);
    if ($data > 0) {
        //query per cancellare il modulo 
      $sql = "DELETE FROM Moduli WHERE ID_Modulo=" . $modulo[1];
      $result = mysqli_query($conn, $sql);

      if (!$result) {
        http_response_code(500);
        die("'Errore nella query'");
      }

      http_response_code(200);
      $risposta = array(
        "success" => true,
        "message" => "Modulo eliminato con successo",
        "data" => NULL
      );
    }
  }

  echo json_encode($risposta); ///funzione per convedrtire da array associativo a json e inviarla 
  $conn->close(); //chiudo la connessione 


} else if ($_SERVER['REQUEST_METHOD'] === "PACTH") {

  require_once('../../database/Connection.php');

  $dati = $_SERVER['QUERY_STRING']; //ricevo i dati nell'url, questa funzione permette di avere i parametri 

  $dati = explode("&", $dati);
  $token = explode("=", $dati[0]);
  $modifica = explode("=", $dati[1]);

  if ($token[0] == "Token") {

    $id = mysqli_fetch_assoc(verificaToken($token[1], $conn))["IDF_Utente"];
  }
  if ($id == NULL) {// se il token non è valido 

    $msg = "Sessione scaduta, effettua il login";
    http_response_code(404);

    $risposta = array(
      "success" => false,
      "message" => $msg,
      "data" => NULL
    );
  }

  if ($modifica[1] == "0") {
    //rimozione
  } else if ($modifica[1] == "1") {
    //modifica

    $dati = json_decode(file_get_contents("php://input"), true);

    if (isset($dati["ID_Sezione"])) {
      //se si modifica una sezione 
      $sql = "UPDATE Sezioni SET" . $dati["nome"] . "  = " . $dati["valore"] . ";";
    } else if (isset($dati["ID_Domanda"])) {
      //se si modifica una domanda
      $sql = "UPDATE Domande SET" . $dati["nome"] . "  = " . $dati["valore"] . ";";
    } else if (isset($dati["ID_Risposta"])) {
      //se si modifica una risposta
      $sql = "UPDATE Risposte SET " . $dati["nome"] . "  = " . $dati["valore"] . ";";
    } else if (isset($dati["ID_Modulo"])) {
      //se si modifica un modulo
      $sql = "UPDATE Moduli SET" . $dati["nome"] . "  = " . $dati["valore"] . ";";
    }

    if (!$result  = mysqli_query($conn, $sql)) {
      http_response_code(500);
      die("Modifica | Errore query");
    }
  } else if ($modifica[1] == "2") {
    //aggiunta
  }





  echo json_encode($risposta);//invio della risposta 
  $conn->close(); //chiudo la connessione 

}
