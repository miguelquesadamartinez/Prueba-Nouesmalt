export interface User {
  id?: number;
  nombre: string;
  apellidos: string;
  dni: string;
  created_at?: string;
  updated_at?: string;
}

export interface Book {
  id?: number;
  titulo: string;
  autor: string;
  isbn: string;
  created_at?: string;
  updated_at?: string;
}

export interface Loan {
  id?: number;
  user_id: number;
  book_id: number;
  loan_date: string;
  return_date?: string;
  created_at?: string;
  updated_at?: string;
}

export interface LoanReport {
  user_id: number;
  user_nombre: string;
  user_apellidos: string;
  user_dni: string;
  total_loans: number;
  first_loan_date: string;
  last_loan_date: string;
}

export interface ApiResponse<T> {
  success: boolean;
  data?: T;
  message?: string;
}
