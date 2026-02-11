<template>
  <div class="container">
    <div class="card">
      <h1>Bienvenido al Sistema de Biblioteca</h1>
      <p style="margin-top: 1rem; font-size: 1.1rem">
        Sistema de gestión de biblioteca desarrollado con arquitectura hexagonal
        y DDD.
      </p>

      <div
        style="
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 1rem;
          margin-top: 2rem;
        "
      >
        <div class="feature-card">
          <h3>👥 Gestión de Usuarios</h3>
          <p>Crea y administra usuarios de la biblioteca</p>
          <router-link
            to="/users"
            class="btn"
            style="display: inline-block; margin-top: 1rem"
            >Ir a Usuarios</router-link
          >
        </div>

        <div class="feature-card">
          <h3>📖 Gestión de Libros</h3>
          <p>Administra el catálogo de libros disponibles</p>
          <router-link
            to="/books"
            class="btn"
            style="display: inline-block; margin-top: 1rem"
            >Ir a Libros</router-link
          >
        </div>

        <div class="feature-card">
          <h3>📋 Préstamos</h3>
          <p>Gestiona los préstamos de libros (máx. 3 por usuario)</p>
          <router-link
            to="/loans"
            class="btn"
            style="display: inline-block; margin-top: 1rem"
            >Ir a Préstamos</router-link
          >
        </div>

        <div class="feature-card">
          <h3>📊 Reportes</h3>
          <p>Consulta estadísticas de préstamos por periodo</p>
          <router-link
            to="/reports"
            class="btn"
            style="display: inline-block; margin-top: 1rem"
            >Ver Reportes</router-link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.feature-card {
  background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.feature-card h3 {
  margin-bottom: 0.5rem;
  color: #667eea;
}
</style>
