export interface Response {
  success: boolean;
  message: string;
  data: any;
}

export interface Error {
  status: number;
  message: string;
}

export interface User {
  ID_Utente?: number;
  Nome?: string;
  Cognome?: string;
  Email?: string;
  Password?: string;
  Username?: string;
  DataReg?: string;
}

export interface UserLogin {
  Email: string;
  Password: string;
}

export interface UserRegister {
  Nome: string;
  Cognome: string;
  Email: string;
  Password: string;
  Username: string;
}

export interface Session {
  ID_Sessione?: number;
  Token?: string;
  DataInizio?: string;
  DataFine?: string;
}

export interface Form {
  ID_Modulo?: string;
  Token?: string;
  Titolo?: string;
  Descrizione?: string;
}

export interface FormData {
  Titolo: string;
  Descrizione: string;
}

// Types per ogni tabella del database (tutti i campi facoltativi)
export interface DettRisultati {
  IDF_Risultato?: number;
  IDF_Risposta?: number;
  RispostaBreve?: string;
  RispostaLunga?: string;
  RispostaNoLimiti?: string;
}

export interface Domande {
  ID_Domanda?: number;
  IDF_Sezione?: number;
  IDF_Modulo?: number;
  IDF_Tipologia?: number;
  Testo?: string;
  Descrizione?: string;
}

export interface Moduli {
  ID_Modulo?: number;
  IDF_Utente?: number;
  Titolo?: string;
  Descrizione?: string;
  DataCreazione?: string;
}

export interface Risposte {
  ID_Risposta?: number;
  IDF_Domanda?: number;
  Testo?: string;
  Punteggio?: number;
}

export interface Risultati {
  ID_Risultato?: number;
  IDF_Modulo?: number;
  DataCreazione?: string;
}

export interface Sessioni {
  ID_Sessione?: number;
  IDF_Utente?: number;
  Token?: string;
  DataInizio?: string;
  Sospeso?: number;
}

export interface Sezioni {
  ID_Sezione?: number;
  IDF_Modulo?: number;
  Nome?: string;
}

export interface Tipologie {
  ID_Tipologia?: number;
  Nome?: string;
}

export interface Utenti {
  ID_Utente?: number;
  Username?: string;
  Nome?: string;
  Cognome?: string;
  Email?: string;
  Password?: string;
  DataReg?: string;
}

// Types per creazione (insert)
export interface CreateDettRisultati {
  IDF_Risultato?: number;
  IDF_Risposta?: number;
  RispostaBreve?: string;
  RispostaLunga?: string;
  RispostaNoLimiti?: string;
}

export interface CreateDomande {
  IDF_Sezione?: number;
  IDF_Modulo?: number;
  IDF_Tipologia?: number;
  Testo?: string;
  Descrizione?: string;
}

export interface CreateModuli {
  IDF_Utente?: number;
  Titolo?: string;
  Descrizione?: string;
}

export interface CreateRisposte {
  IDF_Domanda?: number;
  Testo?: string;
  Punteggio?: number;
}

export interface CreateRisultati {
  IDF_Modulo?: number;
}

export interface CreateSessioni {
  IDF_Utente?: number;
  Token?: string;
  Sospeso?: number;
}

export interface CreateSezioni {
  IDF_Modulo?: number;
  Nome?: string;
}

export interface CreateTipologie {
  Nome?: string;
}

export interface CreateUtenti {
  Username?: string;
  Nome?: string;
  Cognome?: string;
  Email?: string;
  Password?: string;
}
