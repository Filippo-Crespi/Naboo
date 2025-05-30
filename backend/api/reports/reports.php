<?php
require("../cors.php");

if (isset($_GET["codice"])) {
  require_once('../../database/Connection.php');
  $codiceModulo = mysqli_real_escape_string($conn, $_GET["codice"]);

  // Recupera l'ID_Modulo dal campo Codice
  $sqlIdModulo = "SELECT ID_Modulo FROM Moduli WHERE Codice = '$codiceModulo' LIMIT 1";
  $resIdModulo = mysqli_query($conn, $sqlIdModulo);
  if (!$resIdModulo) {
    http_response_code(500);
    die(json_encode(["error" => "Report | Errore nella query per ID_Modulo"]));
  }
  $rowIdModulo = mysqli_fetch_assoc($resIdModulo);
  if (!$rowIdModulo) {
    http_response_code(404);
    die(json_encode(["error" => "Report | Modulo non trovato"]));
  }
  $idModulo = $rowIdModulo["ID_Modulo"];

  // Query per domande e numero risposte
  $sqlDomande = "SELECT D.ID_Domanda, D.Testo, D.Descrizione, COUNT(DR.IDF_Risultato) as num
          FROM Domande D
          LEFT JOIN Risposte R ON R.IDF_Domanda = D.ID_Domanda
          LEFT JOIN DettRisultati DR ON DR.IDF_Risposta = R.ID_Risposta
          WHERE D.IDF_Modulo = '$idModulo'
          GROUP BY D.ID_Domanda, D.Testo
          ORDER BY D.ID_Domanda";

  $datiDomande = mysqli_query($conn, $sqlDomande);
  if (!$datiDomande) {
    http_response_code(500);
    die(json_encode(["error" => "Report | Errore nella query domande"]));
  }

  // Query per tutte le risposte dettagliate
  $sqlRisposte = "SELECT RS.ID_Risultato, D.ID_Domanda, R.Punteggio, R.ID_Risposta, R.Testo as TestoRisposta, DR.IDF_Risposta, DR.RispostaBreve, DR.RispostaLunga, DR.RispostaNoLimiti
          FROM Domande D
          LEFT JOIN Risposte R ON R.IDF_Domanda = D.ID_Domanda
          LEFT JOIN DettRisultati DR ON DR.IDF_Risposta = R.ID_Risposta
          LEFT JOIN Risultati RS ON RS.ID_Risultato = DR.IDF_Risultato
          WHERE D.IDF_Modulo = '$idModulo'
          ORDER BY D.ID_Domanda";

  $datiRisposte = mysqli_query($conn, $sqlRisposte);
  if (!$datiRisposte) {
    http_response_code(500);
    die(json_encode(["error" => "Report | Errore nella query risposte"]));
  }

  // Organizza le risposte per domanda
  $rispostePerDomanda = [];
  while ($ris = mysqli_fetch_assoc($datiRisposte)) {
    $idDomanda = $ris["ID_Domanda"];
    $risposta = [
      "IDF_Risposta" => $ris["IDF_Risposta"],
      "ID_Risposta" => $ris["ID_Risposta"],
      "ID_Risultato" => $ris["ID_Risultato"],
      "Testo" => $ris["TestoRisposta"],
      "RispostaBreve" => $ris["RispostaBreve"],
      "RispostaLunga" => $ris["RispostaLunga"],
      "RispostaNoLimiti" => $ris["RispostaNoLimiti"],
      "Punteggio" => $ris["Punteggio"] ?? null,
    ];
    $rispostePerDomanda[$idDomanda][] = $risposta;
  }

  // Costruisci il risultato finale
  $result = ["Domande" => []];
  while ($domanda = mysqli_fetch_assoc($datiDomande)) {
    $id = $domanda["ID_Domanda"];
    $result["Domande"][] = [
      "ID_Domanda" => $id,
      "Testo" => $domanda["Testo"],
      "Descrizione" => $domanda["Descrizione"],
      "Numero_Risposte" => intval($domanda["num"]),
      "Risposte" => $rispostePerDomanda[$id] ?? []
    ];
  }
  $result["ID_Modulo"] = $idModulo;
  $result["Codice"] = $codiceModulo;
  echo json_encode($result);
  exit;
}
