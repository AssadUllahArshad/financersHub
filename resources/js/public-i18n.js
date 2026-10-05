const spanish = {
    "Switch to light mode": "Cambiar al modo claro",
    "Switch to dark mode": "Cambiar al modo oscuro",
    "Open menu": "Abrir menú",
    "Close menu": "Cerrar menú",
    "Link copied": "Enlace copiado",
    "Copy unavailable": "No se pudo copiar",
    "Estimated balance": "Saldo estimado",
    "Total contributed": "Total aportado",
    "Estimated interest": "Interés estimado",
    "Inputs changed. Select Calculate savings to refresh your results.":
        "Los datos han cambiado. Selecciona Calcular ahorro para actualizar los resultados.",
};
export const locale =
    document.documentElement.lang === "es" ? "es-US" : "en-US";
export const t = (text) => (locale === "es-US" ? spanish[text] || text : text);
