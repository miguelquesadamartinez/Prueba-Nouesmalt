<template>
  <div class="container">
    <h2>Gestión de Libros</h2>

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
      <h3>Crear Nuevo Libro</h3>
      <form @submit.prevent="createBook">
        <div class="form-group">
          <label>Título:</label>
          <input
            v-model="newBook.titulo"
            type="text"
            required
            maxlength="255"
          />
        </div>
        <div class="form-group">
          <label>Autor:</label>
          <input
            v-model="newBook.autor"
            type="text"
            required
            minlength="2"
            maxlength="100"
          />
        </div>
        <div class="form-group">
          <label>ISBN:</label>
          <input
            v-model="newBook.isbn"
            type="text"
            required
            pattern="[\d\-\sX]{10,17}"
            placeholder="978-0-132-35088-4"
          />
        </div>
        <button type="submit" class="btn">Crear Libro</button>
      </form>
    </div>

    <div class="card">
      <h3>Lista de Libros</h3>
      <div v-if="loading" class="loading">Cargando...</div>
      <table v-else class="table">
        <thead>
          <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>ISBN</th>
            <th>Fecha de Creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books" :key="book.id">
            <td>{{ book.titulo }}</td>
            <td>{{ book.autor }}</td>
            <td>{{ book.isbn }}</td>
            <td>{{ formatDate(book.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from "vue";
import { useMessage } from "@/composables/useMessage";
import { useDateFormat } from "@/composables/useDateFormat";
import { useBookCrud } from "@/composables/useBookCrud";

const { message, messageType } = useMessage();
const { showMessage } = useMessage();
const { formatDate } = useDateFormat();
const { books, newBook, loading, loadBooks, createBook } =
  useBookCrud(showMessage);

onMounted(() => {
  loadBooks();
});
</script>
