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
  Codice?: string;
}

export interface FormData {
  Titolo?: string;
  Descrizione?: string;
  Codice?: string;
}

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

// Interfaccia per una risposta utente (compilazione)
export interface RispostaUtente {
  [key: string]: string | number | null | Array<number>;
}

// Interfaccia per una risposta (opzione di domanda)
export interface RispostaOpzione {
  ID_Risposta?: string | number;
  Testo?: string;
  Punteggio?: number;
}

// Interfaccia per una domanda (annidata in sezione)
export interface DomandaModulo {
  ID_Domanda?: string | number;
  Testo: string;
  Descrizione?: string;
  Tipologia?: number;
  IDF_Tipologia?: number;
  Risposte?: RispostaOpzione[];
  RispostaBreve?: string;
  RispostaLunga?: string;
  RispostaNoLimiti?: string;
  PunteggioBreve?: number;
  PunteggioLunga?: number;
  PunteggioNoLimiti?: number;
}

// Interfaccia per una sezione del modulo
export interface SezioneModulo {
  ID_Sezione?: string | number;
  Nome: string;
  Domande: DomandaModulo[];
}

// Interfaccia per il modulo completo (usata in edit/compile/view)
export interface ModuloCompleto {
  ID_Modulo?: string | number;
  Titolo: string;
  Descrizione?: string;
  Sezioni: SezioneModulo[];
}

// Interfaccia per oggetto di aggregazione report (esempio base)
export interface ReportCompilazione {
  ID_Risultato: string | number;
  risposte: Array<{
    domanda: string;
    risposta: string | null;
    punteggio: number;
  }>;
}
