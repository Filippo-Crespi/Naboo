export interface Response {
  success: boolean;
  message: string;
  data: any;
}

export interface Error {
  status: number;
  message: string;
}
