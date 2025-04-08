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
  ID_Utente: number;
  Nome: string;
  Cognome: string;
  Email: string;
  Password: string;
  Username: string;
  DataReg: string;
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
  ID_Sessione: number;
  Token: string;
  DataInizio: string;
  DataFine: string;
}

export interface Form {
  Token: string;
  Titolo: string;
  Descrizione: string;
}

export interface FormData {
  Titolo: string;
  Descrizione: string;
}
