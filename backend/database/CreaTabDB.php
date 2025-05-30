<?php

require('Connection.php');

$conn = mysqli_connect($dbhost, $dbuser, $dbpassword);
if (mysqli_connect_errno()) {
    die("Errore" . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if (mysqli_query($conn, $sql)) {

    echo "Database creato con successo";
} else {
    die("Errore database non creato");
}

$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if (mysqli_connect_errno()) {
    die("Errore" . mysqli_connect_error());
}

$sql = 'CREATE TABLE IF NOT EXISTS Utenti (
    ID_Utente INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(30) NOT NULL,
    Nome VARCHAR(30) NOT NULL,
    Cognome VARCHAR(30) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    DataReg TIMESTAMP DEFAULT CURRENT_TIMESTAMP #ON UPDATE CURRENT_TIMESTAMP

)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Utenti creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Moduli (
    ID_Modulo INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Utente INT(6) UNSIGNED,
    Titolo VARCHAR(30) NOT NULL,
    Descrizione TEXT NOT NULL,
    DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDF_Utente) REFERENCES Utenti(ID_Utente) ON DELETE CASCADE
);';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Moduli creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Sezioni (
    ID_Sezione INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Modulo INT(6) UNSIGNED ,
    Nome VARCHAR(30) NOT NULL,
    FOREIGN KEY (IDF_Modulo) REFERENCES Moduli(ID_Modulo) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Sezioni creata con successo';
}


$sql = 'CREATE TABLE IF NOT EXISTS Tipologie(
    ID_Tipologia INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(30) NOT NULL
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Tipologie creata con successo';
}


$sql = 'CREATE TABLE IF NOT EXISTS Domande (
    ID_Domanda INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Sezione INT(6) UNSIGNED,
    IDF_Modulo INT(6) UNSIGNED,
    IDF_Tipologia INT(6) UNSIGNED,
    Testo TEXT NOT NULL,
    Descrizione TEXT,
    FOREIGN KEY (IDF_Sezione) REFERENCES Sezioni(ID_Sezione) ON DELETE CASCADE, 
    FOREIGN KEY ( IDF_Modulo) REFERENCES Moduli(ID_Modulo) ON DELETE CASCADE,
    FOREIGN KEY (IDF_Tipologia) REFERENCES Tipologie(ID_Tipologia) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Domande creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Risposte (
    ID_Risposta INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Domanda INT(6) UNSIGNED ,
    Testo TEXT NOT NULL,
    Punteggio INT DEFAULT 0,
    FOREIGN KEY (IDF_Domanda) REFERENCES Domande(ID_Domanda) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Risposte creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Risultati(
    ID_Risultato INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Modulo INT(6) UNSIGNED,
    DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP, #ON UPDATE CURRENT_TIMESTAMP
    FOREIGN KEY (IDF_Modulo) REFERENCES Moduli(ID_Modulo) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Risultati creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS DettRisultati(
    IDF_Risultato INT(6) UNSIGNED,
    IDF_Risposta INT(6) UNSIGNED,
    FOREIGN KEY (IDF_Risultato) REFERENCES Risultati(ID_Risultato) ON DELETE CASCADE,
    FOREIGN KEY (IDF_Risposta) REFERENCES Risposte(ID_Risposta) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella Dettagli Risultati creata con successo';
}

$sql = 'CREATE TABLE IF NOT EXISTS Sessioni(
    ID_Sessione INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Utente INT(6) UNSIGNED,
    Token VARCHAR(50) NOT NULL,
    DataInizio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Sospeso INT(1) DEFAULT 0 NOT NULL,
    FOREIGN KEY (IDF_Utente) REFERENCES Utenti(ID_Utente) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella creata con successo';
}
$sql = 'CREATE TABLE IF NOT EXISTS Risultati(
    ID_Risultato INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    IDF_Modulo INT(6) UNSIGNED,
    DataCreazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP, #ON UPDATE CURRENT_TIMESTAMP
    FOREIGN KEY (IDF_Modulo) REFERENCES Moduli(ID_Modulo) ON DELETE CASCADE
)';

if (mysqli_query($conn, $sql)) {
    echo 'Tabella creata con successo';
}
$conn->close();
