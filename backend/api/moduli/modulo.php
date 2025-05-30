<?php

require("../cors.php");

// Funzione ricorsiva per convertire tutte le stringhe in UTF-8
function utf8ize($mixed)
{
  if (is_array($mixed)) {
    foreach ($mixed as $key => $value) {
      $mixed[$key] = utf8ize($value);
    }
  } elseif (is_string($mixed)) {
    return mb_convert_encoding($mixed, 'UTF-8', 'auto');
  }
  return $mixed;
}
//funzione per verificare che un modulo esista 
function verificaModulo($idModulo, $conn)
{
  $sql = "SELECT ID_Modulo FROM Moduli WHERE ID_Modulo='$idModulo' ";
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    http_response_code(500);
    die("'Errore nella query verifica token'");
  }
  return $result;
}
//funzione per verificare che la sessione sia attiva e che il token sia valido
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

  if (isset($_GET["Codice"])) { // CREAZIONE NUOVO MODULO CON TUTTI I DATI 

    $modulo = $_GET["Codice"];
    $data = array(
      "ID_Modulo" => NULL,
      "Titolo" => NULL,
      "Descrizione" => NULL,
      "Sezioni" => array(
        array(
          "ID_Sezione" => NULL,
          "Nome" => NULL,
          "Domande" => array(
            array(
              "ID_Domande" => NULL,
              "Testo" => NULL,
              "Descrzione" => NULL,
              "Tipologia" => NULL,
              "Risposte" => array(
                array(
                  "ID_Risposta" => NULL,
                  "Testo" => NULL,
                  "Punteggio" => NULL,
                )
              )
            )
          )

        )

      )
    );
    //query per trovare il modulo richiesto 
    $sql = "SELECT ID_Modulo, Titolo, Descrizione FROM Moduli WHERE Codice='$modulo'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      die("'Errore nella query'");
    }
    if (mysqli_num_rows($result) < 1) {
      http_response_code(404);
      $risposta = array(
        "success" => false,
        "message" => "Modulo non trovato",
        "data" => null
      );
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($risposta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      $conn->close();
      exit;
    }
    $modulo = mysqli_fetch_assoc($result);
    $data = $modulo;
    //query per trovare le sezione del modulo 
    $sql = "SELECT ID_Sezione, Nome FROM Sezioni  WHERE IDF_Modulo='" . $data["ID_Modulo"] . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {

      die("'Errore nella query'");
    }
    if (mysqli_num_rows($result) < 1) {

      http_response_code(404);

      $risposta = array(
        "success" => false,
        "message" => "Modulo non trovato",
        "data" => $data
      );

      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($risposta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ///funzione per convertire da array associativo a json e inviarla
      $conn->close(); //chiudo la connessione
      exit;
    }

    $sezioni = mysqli_fetch_all($result, MYSQLI_ASSOC);
//foreach per vedere tutte le sezioni del modulo
    foreach ($sezioni as $key => $sezione) {


      $data["Sezioni"][$key] = $sezione;
        //query per trovare le domande del modulo suddivise per sezioni
      $sql = "SELECT ID_Domanda, IDF_Tipologia, Testo, Descrizione FROM Domande WHERE IDF_Sezione='" . $sezione["ID_Sezione"] . "'";
      $result = mysqli_query($conn, $sql);

      if (!$result) {
        die("'Errore nella query'");
      }
      // if (mysqli_num_rows($result) < 1) {
      //   http_response_code(405);
      //   $risposta = array(
      //     "success" => false,
      //     "message" => "Domande non trovate",
      //     "data" => null
      //   );

      //   echo json_encode($risposta); ///funzione per convertire da array associativo a json e inviarla
      //   $conn->close(); //chiudo la connessione
      //   exit;
      // }

      $domande = mysqli_fetch_all($result, MYSQLI_ASSOC);

      foreach ($domande as $indice => $domanda) {

        $data["Sezioni"][$key]["Domande"][$indice] = $domanda;
        //query per trovare tutte le risposta alle domande (in caso di scelta multipla)

        $sql = "SELECT ID_Risposta, Testo, Punteggio FROM Risposte WHERE IDF_Domanda = '" . $domanda["ID_Domanda"] . "'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {

          die("'Errore nella query'");
        }
        // if (mysqli_num_rows($result) === 0) {

        //   http_response_code(404);

        //   $risposta = array(
        //     "success" => false,
        //     "message" => NULL,
        //     "data" => $data
        //   );

        //   echo json_encode($risposta); ///funzione per convertire da array associativo a json e inviarla
        //   $conn->close(); //chiudo la connessione 
        //   exit;
        // }

        $risposte = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($risposte as $numero => $risposta) {

          $data["Sezioni"][$key]["Domande"][$indice]["Risposte"][$numero] = $risposta;
        }
      }
    }

    http_response_code(200);

    $risposta = array(
      "success" => true,
      "message" => null,
      "data" => $data
    );

    // Esegui la pulizia in UTF-8
    $risposta_utf8 = utf8ize($risposta);

    // Fai l'encoding e verifica eventuali errori
    $json = json_encode($risposta_utf8, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    if ($json === false) {
      // Stampa l'errore, utile per debug
      echo "Errore JSON: " . json_last_error_msg();
    } else {
      // Stampa il JSON valido
      echo $json;
    }
  }
}
