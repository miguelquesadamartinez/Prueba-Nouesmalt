<template>
  <div class="container">
    <h2>Gestión de Préstamos</h2>

    <div
      v-if="message"
      :class="[
        'alert',
        messageType === 'success' ? 'alert-success' : 'alert-error',
      ]"
    >
      {{ message }}
    </div>

    <div class="card">
      <h3>Crear Nuevo Préstamo</h3>
      <form @submit.prevent="createLoan">
        <div class="form-group">
          <label>Usuario:</label>
          <select v-model="newLoan.user_id" required>
            <option value="">Seleccionar usuario...</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.nombre }} {{ user.apellidos }} ({{ user.dni }})
            </option>
          </select>
        </div>
        <div class="form-group">
          <label>Libro:</label>
          <select v-model="newLoan.book_id" required>
            <option value="">Seleccionar libro...</option>
            <option v-for="book in books" :key="book.id" :value="book.id">
              {{ book.titulo }} - {{ book.autor }}
            </option>
          </select>
        </div>
        <button type="submit" class="btn">Crear Préstamo</button>
      </form>
    </div>

    <div class="card">
      <h3>Lista de Préstamos</h3>
      <div v-if="loading" class="loading">Cargando...</div>
      <table v-else class="table">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Libro</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="loan in loans" :key="loan.id">
            <td>{{ getUserName(loan.user_id) }}</td>
            <td>{{ getBookTitle(loan.book_id) }}</td>
            <td>{{ formatDate(loan.loan_date) }}</td>
            <td>{{ loan.return_date ? formatDate(loan.return_date) : "-" }}</td>
            <td>
              <span
                :style="{
                  color: loan.return_date ? 'green' : 'orange',
                  fontWeight: 'bold',
                }"
              >
                {{ loan.return_date ? "Devuelto" : "Activo" }}
              </span>
            </td>
            <td>
              <button
                v-if="!loan.return_date"
                @click="returnBook(loan.id!)"
                class="btn btn-secondary"
                style="padding: 0.5rem 1rem; font-size: 0.9rem"
              >
                Devolver
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import api from "@/services/api";
import type { Loan, User, Book, ApiResponse } from "@/types";

const loans = ref<Loan[]>([]);
const users = ref<User[]>([]);
const books = ref<Book[]>([]);
const newLoan = ref({ user_id: 0, book_id: 0 });
const loading = ref(false);
const message = ref("");
const messageType = ref<"success" | "error">("success");

const loadLoans = async () => {
  loading.value = true;
  try {
    const response = await api.get<ApiResponse<Loan[]>>("/api/loans");
    loans.value = response.data.data || [];
  } catch (error) {
    showMessage("Error al cargar préstamos", "error");
  } finally {
    loading.value = false;
  }
};

const loadUsers = async () => {
  try {
    const response = await api.get<ApiResponse<User[]>>("/api/users");
    users.value = response.data.data || [];
  } catch (error) {
    console.error("Error al cargar usuarios");
  }
};

const loadBooks = async () => {
  try {
    const response = await api.get<ApiResponse<Book[]>>("/api/books");
    books.value = response.data.data || [];
  } catch (error) {
    console.error("Error al cargar libros");
  }
};

const createLoan = async () => {
  try {
    await api.post<ApiResponse<Loan>>("/api/loans", newLoan.value);
    showMessage("Préstamo creado exitosamente", "success");
    newLoan.value = { user_id: 0, book_id: 0 };
    await loadLoans();
  } catch (error: any) {
    showMessage(
      error.response?.data?.message || "Error al crear préstamo",
      "error",
    );
  }
};

const returnBook = async (loanId: number) => {
  try {
    await api.post(`/api/loans/${loanId}/return`);
    showMessage("Libro devuelto exitosamente", "success");
    await loadLoans();
  } catch (error: any) {
    showMessage(
      error.response?.data?.message || "Error al devolver libro",
      "error",
    );
  }
};

const showMessage = (msg: string, type: "success" | "error") => {
  message.value = msg;
  messageType.value = type;
  setTimeout(() => {
    message.value = "";
  }, 3000);
};

const formatDate = (date?: string) => {
  if (!date) return "";
  return new Date(date).toLocaleString("es-ES");
};

const getUserName = (userId: string) => {
  const user = users.value.find((u) => u.id === userId);
  return user ? `${user.nombre} ${user.apellidos}` : userId;
};

const getBookTitle = (bookId: string) => {
  const book = books.value.find((b) => b.id === bookId);
  return book ? book.titulo : bookId;
};

onMounted(() => {
  loadLoans();
  loadUsers();
  loadBooks();
});
</script>
