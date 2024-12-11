$(document).ready(function () {
    // Evento para enviar el formulario del modal y guardar la fecha de pago
    $('#CuotaForm').submit(function (e) {
    e.preventDefault();

    const idCuota = $(this).data('id'); // Obtén el ID de la cuota
    const fechaPago = $('#fechaPago').val(); // Obtén la fecha ingresada

    $.ajax({
        url: `/gestión-comercial/cuotas/${idCuota}/actualizar-fecha`, // Ruta para actualizar
        method: 'POST',
        data: {
            fechaPago: fechaPago,
            _token: $('input[name="_token"]').val() // Token CSRF
        },
        success: function (response) {
            Toast.fire({
                icon: 'success',
                title: response.message,
            });
            $('#modalForm').modal('hide'); // Cierra el modal
            mostrarDatos(); // Actualiza la tabla con los cambios
        },
        error: function (xhr) {
            console.error('Error al actualizar la fecha:', xhr.responseJSON);
        }
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
function mostrarDatos() {
    const idVenta = $('#idVenta').val(); // Obtén el ID de la venta

    $.ajax({
        url: `/obtener-cuotas/${idVenta}`, // Ruta para obtener cuotas
        method: 'GET',
        success: function (data) {
    const tableBody = $('#tableBody');
    tableBody.empty(); // Limpia la tabla antes de llenarla

    if (data.length === 0) {
        tableBody.append('<tr><td colspan="6" class="text-center">No hay cuotas registradas.</td></tr>');
        return;
    }

    data.forEach(cuota => {
    // Formatear las fechas a 'YYYY-MM-DD'
    const formatFecha = fecha => {
        if (!fecha) return '-'; // Si no hay fecha, muestra un guion
        const date = new Date(fecha);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const fechaPago = formatFecha(cuota.fechaPago);
    const fechaLimite = formatFecha(cuota.fechaLimite);

    const estadoTexto = cuota.estado === 1 ? 'Cancelado' : cuota.estado === 2 ? 'En mora' : 'Pendiente';
    const estadoClase = cuota.estado === 1 ? 'success' : cuota.estado === 2 ? 'danger' : 'secondary';

    $('#tableBody').append(`
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
                    data-bs-target="#modalForm">
                    Pagar Cuota
                </button>
            </td>
        </tr>
    `);
});

}
,
        error: function (xhr) {
            console.error('Error al cargar las cuotas:', xhr);
        },
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


// function cargarCuotas(idVenta) {
//     $.ajax({
//         url: `/gestión-comercial/cuotas/${idVenta}`, // Llama al método getCuotas
//         method: 'GET',
//         success: function (data) {
//             const tableBody = $('#tableBody');
//             tableBody.empty(); // Limpia la tabla

//             // Si no hay cuotas, muestra un mensaje
//             if (data.length === 0) {
//                 tableBody.append('<tr><td colspan="7" class="text-center">No hay cuotas registradas.</td></tr>');
//                 return;
//             }

//             // Itera sobre las cuotas recibidas y las muestra en la tabla
//             data.forEach(cuota => {
//                 const estadoTexto = cuota.estado === 1 ? 'Pagado' : 'Pendiente';
//                 const estadoClase = cuota.estado === 1 ? 'success' : 'secondary';

//                 tableBody.append(`
//                     <tr>
//                         <td>${cuota.idCuota}</td>
//                         <td>${cuota.fechaPago ? cuota.fechaPago.split(' ')[0] : '-'}</td>
//                         <td>${cuota.fechaLimite.split(' ')[0]}</td>
//                         <td>$${parseFloat(cuota.monto).toFixed(2)}</td>
//                         <td>$${parseFloat(cuota.mora).toFixed(2)}</td>
//                         <td>
//                             <span class="badge bg-${estadoClase}">
//                                 ${estadoTexto}
//                             </span>
//                         </td>
//                         <td>
//                             <button class="btn btn-primary btn-sm" onclick="pagarCuota('${cuota.idCuota}')">Pagar</button>
//                         </td>
//                     </tr>
//                 `);
//             });
//         },
//         error: function (xhr) {
//             console.error('Error al cargar las cuotas:', xhr.responseJSON);
//         }
//     });
// }

$(document).on('click', '.btn-ver-detalles', function () {
    const idVenta = $(this).data('id'); // Obtiene el ID de la venta
    window.location.href = `/gestión-comercial/cuotas/${idVenta}`; // Redirige a la vista de detalles
});




