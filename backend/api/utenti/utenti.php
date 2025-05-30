<?php

require("../cors.php");
require_once('../../database/Connection.php');

//FUNZIONI-------------------------------------------

function verificaToken($token, $conn){
    $sql = "SELECT Sospeso FROM Sessioni WHERE Token='$token' AND NOT Sospeso";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        die("'Errore nella query verifica token'");
    }
    return $result;
}

//METODO GET --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $IsID = false;
    //se è presente l'id nella richiesta
    if(isset($_GET["ID_Utente"])){
        $id=$_GET["ID_Utente"];
        $sql = "SELECT * FROM Utenti WHERE ID_Utente=$id";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            http_response_code(500);
            die("GET | errore nella query");
        }
        $res = mysqli_fetch_assoc($result);
        if($res==NULL){
            http_response_code(404);
            die("utente non trovato");
        }

        $IsID = true;
    }

    //se è presente il token nella richiesta
    else if(isset($_GET["Token"])){
        $token=$_GET["Token"];
        verificaToken($token, $conn);

        $sql = "SELECT ID_Utente FROM Utenti INNER JOIN Sessioni 
            ON Utenti.ID_Utente=Sessioni.IDF_Utente 
            WHERE Sessioni.Token='$token' ";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            http_response_code(500);
            die("GET | errore nella query");
        }
        $res = mysqli_fetch_assoc($result);
        if($res==NULL){
            http_response_code(404);
            die("utente non trovato");
        }
        $id=$res["ID_Utente"];
        $IsID = true;
    }
    
    $sql = "SELECT ";
    //se specifica i paramametri da visualizzare modifico la query
    if(isset($_GET["stringa"])){
        $dati=$_GET["stringa"];

        $richieste=explode(' ', $dati);
        foreach ($richieste as $key => $valore) {
            $sql .= "Utenti.$valore, ";
        }
        $sql = substr($sql, 0, -2);
    }
    //se non specifica i parametri da visualizzare li aggiungo tutti
    else{
        $sql .= "*";
    }

    //se specifica il limite di righe da visualizzare modifico la query
    if(isset($_GET["limit"])){
        $limit=$_GET["limit"];
        $sql.=" FROM Utenti LIMIT $limit";

    }
    else{
        if($IsID){
            $sql.=" FROM Utenti WHERE ID_Utente=$id";
        }else{
             $sql.=" FROM Utenti";
        }
        
        
    }

    //echo $sql;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        die("GET | errore nella query");
    }
    $i = 0;
    while($res =  mysqli_fetch_assoc($result)){
        $arr[$i] = $res;
        $i++;
    }
    $risposta=array(
        "success" => true,
        "message" => NULL,
        "data"=>$arr
    );
    http_response_code(200);
}

//METODO PATCH --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $uri =  $_SERVER['REQUEST_URI'];
    $uri = explode("?", $uri);

    //prendo l'uri della richiesta così da ricavarne il token
    $uri[1] = urldecode($uri[1]);
    $uri[1] = explode("=", $uri[1]);
    $token = $uri[1][1];

    verificaToken($token, $conn);


    //ricavo l'ID dell'utente associato al token
    $sql = "SELECT Utenti.ID_Utente FROM Utenti INNER JOIN Sessioni
    ON Utenti.ID_Utente=Sessioni.IDF_Utente
    WHERE Sessioni.Token='" . $token . "' ";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        die("'DELETE | errore nella query token'");
    }

    $res = mysqli_fetch_assoc($result);
    $ID_Utente = $res["ID_Utente"]; 

    //ricavo i dati da modificare
    $dati = json_decode(file_get_contents('php://input'), true);

    foreach ($dati as $key => $valore) {
        $sql = "UPDATE Utenti SET $key='$valore' WHERE ID_Utente=" . $ID_Utente . " ";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            http_response_code(500);
            die("'UPDATE | errore nella query'");
        }
    }

    http_response_code(200);
    $risposta=array(
        "success" => true,
        "message" => "dati aggiornati con successo",
        "data"=>NULL
    );

}

//METODO DELETE --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $uri =  $_SERVER['REQUEST_URI'];
    $uri = explode("?", $uri);

    //prendo l'uri della richiesta così da ricavarne il token
    $uri[1] = urldecode($uri[1]);
    $uri[1] = explode("=", $uri[1]);

    if($uri[1][0]=="id"){
        //se il paramentro dell'uri è ID
        $ID_Utente = $uri[1][1];
    }else{
        //se il parametro dell'uri è Token
        $token = $uri[1][1];
        verificaToken($token, $conn);

        //ricavo l'ID dell'utente associato al token
        $sql = "SELECT Utenti.ID_Utente FROM Utenti INNER JOIN Sessioni
        ON Utenti.ID_Utente=Sessioni.IDF_Utente
        WHERE Sessioni.Token='" . $token . "' ";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            http_response_code(500);
            die("'DELETE | errore nella query token'");
        }

        $res = mysqli_fetch_assoc($result);
        $ID_Utente = $res["ID_Utente"]; 

    }


    $sql = "DELETE FROM Utenti WHERE ID_Utente=" . $ID_Utente . " ";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        die("'DELETE | errore nella query eliminazione utente'");
    }

    http_response_code(200);
    $risposta=array(
        "success" => true,
        "message" => "Utente eliminato con successo",
        "data"=>NULL
    );  
}

    echo json_encode($risposta);
