document.addEventListener('DOMContentLoaded', (event) => {
    // Reloj
    function updateClock() {
        const clock = document.getElementById('clock'); // Obtiene el elemento del reloj
        const now = new Date(); // Obtiene la fecha y hora actual
        const hours = String(now.getHours()).padStart(2, '0'); // Obtiene las horas, con ceros a la izquierda si es necesario
        const minutes = String(now.getMinutes()).padStart(2, '0'); // Obtiene los minutos, con ceros a la izquierda si es necesario
        const seconds = String(now.getSeconds()).padStart(2, '0'); // Obtiene los segundos, con ceros a la izquierda si es necesario
        clock.textContent = $;{hours}$;{minutes}$;{seconds}; // Actualiza el contenido del reloj con la hora actual
    }

    setInterval(updateClock, 1000); // Actualiza el reloj cada segundo
    updateClock(); // Llamada inicial para mostrar la hora actual

    // Cambiar Tema
    const toggleButton = document.getElementById('toggleTheme'); // Obtiene el botón de cambio de tema
    toggleButton.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode'); // Alterna la clase 'dark-mode' en el cuerpo del documento
    });
});