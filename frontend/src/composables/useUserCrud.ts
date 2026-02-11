import { ref } from "vue";
import api from "@/services/api";
import type { User, ApiResponse } from "@/types";

export const useUserCrud = (
  onMessageCallback: (msg: string, type: "success" | "error") => void,
) => {
  const users = ref<User[]>([]);
  const newUser = ref<User>({ nombre: "", apellidos: "", dni: "" });
  const loading = ref(false);

  const loadUsers = async (): Promise<void> => {
    loading.value = true;
    try {
      const response = await api.get<ApiResponse<User[]>>("/api/users");
      users.value = response.data.data || [];
    } catch (error) {
      onMessageCallback("Error al cargar usuarios", "error");
    } finally {
      loading.value = false;
    }
  };

  const createUser = async (): Promise<void> => {
    try {
      await api.post<ApiResponse<User>>("/api/users", newUser.value);
      onMessageCallback("Usuario creado exitosamente", "success");
      newUser.value = { nombre: "", apellidos: "", dni: "" };
      await loadUsers();
    } catch (error: any) {
      onMessageCallback(
        error.response?.data?.message || "Error al crear usuario",
        "error",
      );
    }
  };

  return {
    users,
    newUser,
    loading,
    loadUsers,
    createUser,
  };
};
