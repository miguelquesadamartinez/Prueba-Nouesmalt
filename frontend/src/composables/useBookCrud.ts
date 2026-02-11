import { ref } from "vue";
import api from "@/services/api";
import type { Book, ApiResponse } from "@/types";

export const useBookCrud = (
  onMessageCallback: (msg: string, type: "success" | "error") => void,
) => {
  const books = ref<Book[]>([]);
  const newBook = ref<Book>({ titulo: "", autor: "", isbn: "" });
  const loading = ref(false);

  const loadBooks = async (): Promise<void> => {
    loading.value = true;
    try {
      const response = await api.get<ApiResponse<Book[]>>("/api/books");
      books.value = response.data.data || [];
    } catch (error) {
      onMessageCallback("Error al cargar libros", "error");
    } finally {
      loading.value = false;
    }
  };

  const createBook = async (): Promise<void> => {
    try {
      await api.post<ApiResponse<Book>>("/api/books", newBook.value);
      onMessageCallback("Libro creado exitosamente", "success");
      newBook.value = { titulo: "", autor: "", isbn: "" };
      await loadBooks();
    } catch (error: any) {
      onMessageCallback(
        error.response?.data?.message || "Error al crear libro",
        "error",
      );
    }
  };

  return {
    books,
    newBook,
    loading,
    loadBooks,
    createBook,
  };
};
