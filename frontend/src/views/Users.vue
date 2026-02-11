<template>
  <div class="container">
    <h2>Gestión de Usuarios</h2>

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
      <h3>Crear Nuevo Usuario</h3>
      <form @submit.prevent="createUser">
        <div class="form-group">
          <label>Nombre:</label>
          <input
            v-model="newUser.nombre"
            type="text"
            required
            minlength="2"
            maxlength="100"
          />
        </div>
        <div class="form-group">
          <label>Apellidos:</label>
          <input
            v-model="newUser.apellidos"
            type="text"
            required
            minlength="2"
            maxlength="150"
          />
        </div>
        <div class="form-group">
          <label>DNI:</label>
          <input
            v-model="newUser.dni"
            type="text"
            required
            minlength="5"
            maxlength="20"
          />
        </div>
        <button type="submit" class="btn">Crear Usuario</button>
      </form>
    </div>

    <div class="card">
      <h3>Lista de Usuarios</h3>
      <div v-if="loading" class="loading">Cargando...</div>
      <table v-else class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>DNI</th>
            <th>Fecha de Creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.nombre }}</td>
            <td>{{ user.apellidos }}</td>
            <td>{{ user.dni }}</td>
            <td>{{ formatDate(user.created_at) }}</td>
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
import { useUserCrud } from "@/composables/useUserCrud";

const { message, messageType } = useMessage();
const { showMessage } = useMessage();
const { formatDate } = useDateFormat();
const { users, newUser, loading, loadUsers, createUser } =
  useUserCrud(showMessage);

onMounted(() => {
  loadUsers();
});
</script>
