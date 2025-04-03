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

export interface Session {
  ID_Sessione: number;
  Token: string;
  DataInizio: string;
  DataFine: string;
}
