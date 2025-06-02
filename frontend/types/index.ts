export interface Response {
  success: boolean;
  message: string;
  data: Array<any> | any;
}

export interface User {
  id_user: string | number;
  first_name: string;
  last_name: string;
  username: string;
  password?: string;
  email: string;
  role?: "admin" | "user";
  created_at?: string;
  updated_at?: string;
}

interface Question {
  id_question?: number;
  type: string | number;
  label: string;
  description?: string;
  options?: Array<Answers>;
}

interface Answers {
  id_answer?: number;
  label: string;
  score: string | number;
}

export interface Section {
  id_section?: number;
  title: string;
  description?: string;
  questions: Array<Question>;
}

export interface Form {
  id_form?: number;
  user_id?: number;
  title?: string;
  description?: string;
  code?: string;
  data?: {
    sections: Array<Section>;
  };
  anonymous?: boolean;
  needs_authentication?: boolean;
  is_active?: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface FormOwnership {
  user_id: number;
  form_id: number;
  created_at: string;
  updated_at: string;
}

export interface FormSubmission {
  id_submission: number;
  form_id: number;
  user_id: number;
  data: JSON;
  authentication: string | null;
  created_at: string;
}

export interface Session {
  session_uuid: string;
  user_id: number;
  created_at: string;
  expires_at: string;
  notes: string | null;
}

export interface SharedQuestion {
  id_question: number;
  user_id: number;
  data: JSON;
  created_at: string;
  updated_at: string;
}
