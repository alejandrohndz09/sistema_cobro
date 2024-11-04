function setDepreciationType(type) {
    // Establecer el tipo de depreciación en el campo oculto
    document.getElementById('tipo').value = type;
}

// Función para enviar el formulario
function submitForm() {
    // Obtener los datos del formulario
    const formData = {
        empresa: document.getElementById('empresa').value,
        sucursal: document.getElementById('sucursal').value,
        departamento: document.getElementById('departamento').value,
        activo: document.getElementById('activo').value,
        tipo: document.getElementById('tipo').value,
    };

    // Realizar la solicitud AJAX con los datos del formulario
    $.ajax({
        url: "/activos/pdf", // URL de la ruta que maneja la generación del PDF
        type: "GET", // Método HTTP que deseas utilizar
        data: formData, // Los datos que se van a enviar
        success: function (response) {
            // Verificar si hay algún error en la respuesta
            if (response.type === 'info') {
                // Procesar la respuesta fallida
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
            } else {
                // Crear un objeto Blob con el contenido en base64
                const byteCharacters = atob(response.pdf);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], { type: 'application/pdf' });

                // Crear un enlace temporal para abrir el PDF en una nueva pestaña
                const pdfURL = URL.createObjectURL(blob);
                window.open(pdfURL, '_blank');
            }
        },
        error: function (xhr, status, error) {
            console.error(error); // Mostrar error en la consola para depuración
            alert('Ocurrió un error al generar el PDF. Por favor, intente nuevamente.');
        }
    });
}
