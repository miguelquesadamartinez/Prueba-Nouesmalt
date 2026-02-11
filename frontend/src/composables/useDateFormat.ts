export const useDateFormat = () => {
  const formatDate = (date?: string): string => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("es-ES");
  };

  return {
    formatDate,
  };
};
