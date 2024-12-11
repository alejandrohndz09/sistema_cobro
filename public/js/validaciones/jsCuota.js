$(document).ready(function () {
    // Evento para enviar el formulario del modal y guardar la fecha de pago
   $('#CuotaForm').submit(function (e) {
    e.preventDefault();

    const idCuota = $(this).data('id');
    const fechaPago = $('#fechaPago').val();

    $.ajax({
        url: `/gestión-comercial/cuotas/${idCuota}/actualizar-fecha`,
        method: 'POST',
        data: {
            fechaPago: fechaPago,
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            Toast.fire({
                icon: 'success',
                title: response.message,
            });
            $('#modalForm').modal('hide');
            mostrarDatos();

            // Verificar si todas las cuotas están canceladas
            const idVenta = $('#idVenta').val();
            verificarEstadoVenta(idVenta);

             // Espera 2 segundos antes de recargar la página
            setTimeout(function () {
                location.reload();
            }, 1000); // 2000 milisegundos = 2 segundos
        },
        error: function (xhr) {
            console.error('Error al actualizar la fecha:', xhr.responseJSON);
        },
    });
});



    // Evento para generar cuotas automáticamente
    $('#btnGenerarCuotas').click(function () {
        const idVenta = $('#idVenta').val(); // Recupera el ID de la venta

        $.ajax({
            url: `/gestión-comercial/cuotas/generar-automaticas/${idVenta}`, // Ruta para generar cuotas
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // Agrega CSRF token
            success: function (response) {
                // Notifica éxito y actualiza la tabla
                Toast.fire({
                    icon: 'success',
                    title: response.message,
                });
                mostrarDatos(); // Actualiza la tabla
            },
            error: function (xhr) {
                // Manejo de errores
                console.error('Error al generar cuotas:', xhr.responseJSON);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al generar cuotas.',
                });
            },
        });
    });
});

// Función para actualizar la tabla con las cuotas
// Función para actualizar la tabla con las cuotas
function mostrarDatos() {
    const idVenta = $('#idVenta').val(); // Obtén el ID de la venta

    $.ajax({
        url: `/obtener-cuotas/${idVenta}`, // Ruta para obtener cuotas
        method: 'GET',
        success: function (data) {
            const tableBody = $('#tableBody');
            tableBody.empty(); // Limpia la tabla antes de llenarla

            if (data.length === 0) {
                tableBody.append('<tr><td colspan="7" class="text-center">No hay cuotas registradas.</td></tr>');
                return;
            }

            data.forEach(cuota => {
                const formatFecha = fecha => {
                    if (!fecha) return '-';
                    const date = new Date(fecha);
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                const fechaPago = formatFecha(cuota.fechaPago);
                const fechaLimite = formatFecha(cuota.fechaLimite);

                let estadoTexto = '';
                let estadoClase = '';
                if (cuota.estado === 1) {
                    estadoTexto = 'Cancelado';
                    estadoClase = 'success';
                } else if (cuota.estado === 2) {
                    estadoTexto = 'En mora';
                    estadoClase = 'danger';
                } else if (cuota.estado === 3) {
                    estadoTexto = 'Cancelado con Mora';
                    estadoClase = 'warning';
                } else {
                    estadoTexto = 'Pendiente';
                    estadoClase = 'secondary';
                }

                tableBody.append(`
                    <tr>
                        <td>${cuota.idCuota}</td>
                        <td>${fechaPago}</td>
                        <td>${fechaLimite}</td>
                        <td>$${parseFloat(cuota.monto).toFixed(2)}</td>
                        <td>$${parseFloat(cuota.mora).toFixed(2)}</td>
                        <td><span class="badge bg-${estadoClase}">${estadoTexto}</span></td>
                        <td>
                            <button
                                class="btn btn-secondary btn-sm btn-ingresar-fecha"
                                data-id="${cuota.idCuota}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForm"
                                ${cuota.estado === 1 || estadoTexto === 'Cancelado' ? 'disabled' : ''}
                                >
                                Pagar Cuota
                            </button>
                        </td>
                    </tr>
                `);
            });

            // Reasignar eventos a los botones
            asignarEventos();
        },
        error: function (xhr) {
            console.error('Error al cargar las cuotas:', xhr);
        },
    });
}

// Función para reasignar eventos
function asignarEventos() {
    // Elimina eventos duplicados
    $(document).off('click', '.btn-ingresar-fecha');

    // Reasignar el evento al botón "Pagar Cuota"
    $(document).on('click', '.btn-ingresar-fecha', function () {
        const idCuota = $(this).data('id'); // Obtén el ID de la cuota
        $('#CuotaForm').data('id', idCuota); // Guarda el ID en el formulario
        $('#fechaPago').val(''); // Limpia el campo de fecha
    });
}


$(document).on('click', '.btn-ingresar-fecha', function () {
    const idCuota = $(this).data('id'); // Obtén el ID de la cuota
    $('#CuotaForm').data('id', idCuota); // Guarda el ID en el formulario
    $('#fechaPago').val(''); // Limpia el campo de fecha
});

function actualizarEstadosYMostrarCuotas() {
    const idVenta = $('#idVenta').val(); // Obtén el ID de la venta

    // Llama al backend para actualizar estados
    $.ajax({
        url: '/gestión-comercial/cuotas/actualizar-estados', // Ruta en el backend
        method: 'GET',
        success: function () {
            // Después de actualizar, carga las cuotas
            mostrarDatos();
        },
        error: function (xhr) {
            console.error('Error al actualizar estados de cuotas:', xhr);
        },
    });
}

$(document).on('click', '.btn-ver-detalles', function () {
    const idVenta = $(this).data('id'); // Obtiene el ID de la venta
    window.location.href = `/gestión-comercial/cuotas/${idVenta}`; // Redirige a la vista de detalles
});

$(document).off('click', '.btn-ingresar-fecha').on('click', '.btn-ingresar-fecha', function () {
    const idCuota = $(this).data('id'); // Obtén el ID de la cuota
    $('#CuotaForm').data('id', idCuota); // Guarda el ID en el formulario
    $('#fechaPago').val(''); // Limpia el campo de fecha
});

function verificarEstadoVenta(idVenta) {
    $.ajax({
        url: `/gestión-comercial/ventas/${idVenta}/actualizar-estado`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            Toast.fire({
                icon: 'success',
                title: response.message,
            });
        },
        error: function (xhr) {
            console.error('Error al verificar el estado de la venta:', xhr.responseJSON);
        },
    });
}




