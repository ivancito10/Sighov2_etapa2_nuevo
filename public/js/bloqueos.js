  document.addEventListener('contextmenu', function(e) {
    e.preventDefault(); // Bloquear clic derecho
  });

  document.addEventListener('keydown', function(e) {
    // Convertir tecla a minúscula por compatibilidad
    const key = e.key.toLowerCase();

    // Bloquear teclas comunes de inspección
    if (
      e.key === "F12" || 
      (e.ctrlKey && e.shiftKey && (key === "i" || key === "j")) || 
      (e.ctrlKey && key === "u")
    ) {
      e.preventDefault();
      alert('Acceso bloqueado por razones de seguridad.');
    }

    // Bloquear impresión: Ctrl + P
    if (e.ctrlKey && key === 'p') {
      e.preventDefault();
      alert('La función de impresión está deshabilitada en esta página.');
    }
  });