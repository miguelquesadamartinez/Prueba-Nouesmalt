<template>
  <div class="container">
    <h2>Reportes de Préstamos</h2>

    <div class="card">
      <h3>Consultar Préstamos por Periodo</h3>
      <form @submit.prevent="loadReport">
        <div
          style="
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
          "
        >
          <div class="form-group">
            <label>Fecha Inicio:</label>
            <input v-model="startDate" type="date" required />
          </div>
          <div class="form-group">
            <label>Fecha Fin:</label>
            <input v-model="endDate" type="date" required />
          </div>
          <div>
            <button type="submit" class="btn">Generar Reporte</button>
          </div>
        </div>
      </form>
    </div>

    <div v-if="reportLoaded" class="card">
      <h3>Resultados del Reporte</h3>
      <p style="margin-bottom: 1rem">
        <strong>Periodo:</strong> {{ formatDate(startDate) }} -
        {{ formatDate(endDate) }}
      </p>

      <div v-if="loading" class="loading">Cargando...</div>
      <div
        v-else-if="reportData.length === 0"
        style="text-align: center; padding: 2rem; color: #718096"
      >
        No se encontraron préstamos en este periodo
      </div>
      <table v-else class="table">
        <thead>
          <tr>
            <th>Nombre Completo</th>
            <th>DNI</th>
            <th>Total de Préstamos</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in reportData" :key="row.user_id">
            <td>{{ row.user_nombre }} {{ row.user_apellidos }}</td>
            <td>{{ row.user_dni }}</td>
            <td>
              <span
                style="
                  background: #667eea;
                  color: white;
                  padding: 0.25rem 0.75rem;
                  border-radius: 12px;
                  font-weight: bold;
                "
              >
                {{ row.total_loans }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <div
        v-if="reportData.length > 0"
        style="
          margin-top: 1rem;
          padding: 1rem;
          background: #f7fafc;
          border-radius: 4px;
        "
      >
        <strong>Resumen:</strong> {{ reportData.length }} usuario(s) con
        préstamos en este periodo
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import api from "@/services/api";
import type { LoanReport, ApiResponse } from "@/types";

const startDate = ref("");
const endDate = ref("");
const reportData = ref<LoanReport[]>([]);
const loading = ref(false);
const reportLoaded = ref(false);

const loadReport = async () => {
  loading.value = true;
  reportLoaded.value = true;

  try {
    const response = await api.get<ApiResponse<LoanReport[]>>(
      "/api/loans/report",
      {
        params: {
          start_date: startDate.value,
          end_date: endDate.value,
        },
      },
    );
    reportData.value = response.data.data || [];
  } catch (error) {
    alert("Error al generar reporte");
  } finally {
    loading.value = false;
  }
};

const formatDate = (date: string) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("es-ES");
};

// Set default dates (today and tomorrow)
const today = new Date();
const tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000);
startDate.value = today.toISOString().split("T")[0];
endDate.value = tomorrow.toISOString().split("T")[0];
</script>
