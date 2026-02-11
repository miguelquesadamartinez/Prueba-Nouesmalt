<template>
  <div id="app">
    <header class="header">
      <h1>📚 Biblioteca - Sistema de Gestión</h1>
      <nav class="nav">
        <router-link to="/" class="nav-link">Inicio</router-link>
        <router-link to="/users" class="nav-link">Usuarios</router-link>
        <router-link to="/books" class="nav-link">Libros</router-link>
        <router-link to="/loans" class="nav-link">Préstamos</router-link>
        <router-link to="/reports" class="nav-link">Reportes</router-link>
      </nav>
    </header>

    <main class="main-content">
      <router-view />
    </main>

    <footer class="footer">
      <p>Biblioteca - Sistema de Gestión</p>
      <p class="copyright">
        &copy; 2026 Miguel Quesada Martinez. Todos los derechos reservados.
      </p>
    </footer>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 1.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.header h1 {
  margin: 0 0 1rem 0;
  font-size: 1.8rem;
}

.nav {
  display: flex;
  gap: 1rem;
}

.nav-link {
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background 0.3s;
}

.nav-link:hover,
.nav-link.router-link-active {
  background: rgba(255, 255, 255, 0.2);
}

.main-content {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 1rem;
  min-height: calc(100vh - 200px);
}

.footer {
  background: #2d3748;
  color: white;
  text-align: center;
  padding: 1.5rem;
  margin-top: 2rem;
}

.footer p {
  margin: 0.25rem 0;
}

.copyright {
  font-size: 0.85rem;
  color: #a0aec0;
}
</style>
