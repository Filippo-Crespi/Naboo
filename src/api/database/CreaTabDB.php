<?php

require('ConnConfig.php');

$con = mysqli_connect($dbhost, $dbuser, $dbpassword);
if (mysqli_connect_errno()) {
    die("Errore". mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if(mysqli_query($con, $sql)){
    echo "Database creato con successo";
}
else {
    die("Errore database non creato");
}

$con = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if (mysqli_connect_errno()) {
    die("Errore". mysqli_connect_error());
}

$sql = 'CREATE TABLE IF NOT EXISTS Utenti (
    ID_Utente INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    DataReg TIMESTAMP DEFAULT CURRENT_TIMESTAMP #ON UPDATE CURRENT_TIMESTAMP

)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Utenti creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Moduli (
    ID_Modulo INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Utente INT(6) UNSIGNED FOREIGN KEY REFERENCES Utenti(ID_Utente),
    Titolo VARCHAR(30) NOT NULL,
    Descrizione TEXT NOT NULL,
    DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP #ON UPDATE CURRENT_TIMESTAMP

)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Moduli creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Sezioni (
    ID_Sezione INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Modulo INT(6) UNSIGNED FOREIGN KEY REFERENCES Moduli(ID_Modulo),
    Nome VARCHAR(30) NOT NULL,
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Sezioni creata con successo';
}


$sql = 'CREATE TABLE IF NOT EXISTS Tipologie(
    ID_Tipologia INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(30) NOT NULL,
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Tipologie creata con successo';
}


$sql = 'CREATE TABLE IF NOT EXISTS Domande (
    ID_Domanda INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Sezione INT(6) UNSIGNED FOREIGN KEY REFERENCES Sezioni(ID_Sezione),
    IDF_Modulo INT(6) UNSIGNED FOREIGN KEY REFERENCES Moduli(ID_Modulo),
    IDF_Tipologia INT(6) UNSIGNED FOREIGN KEY REFERENCES Tipologie(ID_Tipologia),
    Testo TEXT NOT NULL,
    Descrizione TEXT,
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Domande creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Risposte (
    ID_Risposta INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Domanda INT(6) UNSIGNED FOREIGN KEY REFERENCES Domande(ID_Domanda),
    Testo TEXT NOT NULL,
    Punteggio INT DEFAULT 0,
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Risposte creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Risultati(
    ID_Risultato INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Modulo INT(6) UNSIGNED FOREIGN KEY REFERENCES Moduli(ID_Modulo),
    DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP #ON UPDATE CURRENT_TIMESTAMP
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Risultati creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS DettRisultati(
    IDF_Risultato INT(6) UNSIGNED FOREIGN KEY REFERENCES Risultati(ID_Risultato),
    IDF_Risposta INT(6) UNSIGNED FOREIGN KEY REFERENCES Risposte(ID_Risposta),
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella Dettagli Risultati creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Sessione(
    ID_Sessione INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Utente INT(6) UNSIGNED FOREIGN KEY REFERENCES Utenti(ID_Utente),
    Token VARCHAR(50) NOT NULL,
    DataInizio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella creata con successo';
}
$sql = 'CREATE TABLE IF NOT EXISTS Risultati(
    ID_Risultato INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Modulo INT(6) UNSIGNED FOREIGN KEY REFERENCES Moduli(ID_Modulo),
    DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP #ON UPDATE CURRENT_TIMESTAMP
)';

if(mysqli_query($conn, $sql)) {
    echo 'Tabella creata con successo';
}
$con->close();