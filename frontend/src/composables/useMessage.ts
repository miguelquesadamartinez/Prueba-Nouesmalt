import { ref } from "vue";

export const useMessage = () => {
  const message = ref("");
  const messageType = ref<"success" | "error">("success");

  const showMessage = (msg: string, type: "success" | "error" = "success") => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
      message.value = "";
    }, 3000);
  };

  return {
    message,
    messageType,
    showMessage,
  };
};
