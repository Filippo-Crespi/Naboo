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
  nome: string;
  cognome: string;
  email: string;
  password: string;
  username: string;
  DataReg: string;
}

export interface Session {
  ID_Sessione: number;
  token: string;
  DataInizio: string;
  DataFine: string;
}
