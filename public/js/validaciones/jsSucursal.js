$(document).ready(function () {
    // Evento para enviar el formulario `#sucursalForm`
    $('#sucursalForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = $('#method').val();

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function (response) {
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalFormS').modal('hide');
                mostrarDatos();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, error) {
                        $('#error-' + key).text(error[0]);
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                    });
                }
            }
        });
    });

    // Evento para enviar el formulario `#confirmarForm`
    $('#confirmarForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = $('#methodS').val();

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function (response) {
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalConfirm').modal('hide');
                if (response.type == 'success') {
                    mostrarDatos();
                }
            },
            error: function (xhr) {
                Toast.fire({
                    icon: 'error',
                    title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                });
            }
        });
    });


    // Otros eventos para agregar, editar y eliminar sucursales
    $('#btnAgregar').click(function () {
        agregar();
    });

    $(document).on('click', '.btnEditar', function () {
        editar($(this).data('id'));
    });

    $(document).on('click', '.btnEliminar', function () {
        eliminar($(this).data('id'));
    });

    $(document).on('click', '.btnDeshabilitar', function () {
        baja($(this).data('id'));
    });

    $(document).on('click', '.btnHabilitar', function () {
        alta($(this).data('id'));
    });
});

function agregar() {
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });

    $('#titulo').text("Nuevo Registro");
    $('#telefono').val('');
    $('#direccion').val('');
    $('#ubicacion').val('');

    $('#method').val('POST');
    $('#sucursalForm').attr('action', '/opciones/sucursal');
    $('#modalFormS').modal('show');
}

function editar(idSucursal) {
    $.get('/opciones/sucursal/' + idSucursal + '/edit', function (obj) {
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });
        $('#titulo').text("Editar Registro");
        $('#telefono').val(obj.telefono);
        $('#direccion').val(obj.direccion);
        $('#ubicacion').val(obj.ubicacion);

        $('#method').val('PUT');
        $('#sucursalForm').attr('action', '/opciones/sucursal/' + idSucursal);
        $('#modalFormS').modal('show');
    });
}

function eliminar(idSucursal) {
    $('#confirmarForm').attr('action', '/opciones/sucursal/' + idSucursal);
    $('#methodS').val('Delete');
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?');
}

function baja(idSucursal) {
    $('#confirmarForm').attr('action', '/opciones/sucursal/baja/' + idSucursal);
    $('#methodS').val('get');
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?');
}

function alta(idSucursal) {
    $.ajax({
        url: '/opciones/sucursal/alta/' + idSucursal,
        method: 'get',
        success: function (response) {
            Toast.fire({
                icon: response.type,
                title: response.message
            });
            if (response.type == 'success') {
                mostrarDatos();
            }
        },
        error: function (xhr) {
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });
        }
    });
}

function mostrarDatos() {
    $.ajax({
        url: '/obtener-sucursales',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log('Datos recibidos:', data); // Verifica si los datos son los esperados
            $('#tableBody').empty();

            originalData = data.map(c => {
                let acciones;
                if (c.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalFormS" data-id="${c.idSucursal}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-3">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idSucursal}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${c.idSucursal}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idSucursal}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="width: 9%">
                        <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-tag opacity-10 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.idSucursal}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.telefono}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.direccion}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.ubicacion}</p>
                    </td>
                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${c.estado == 1 ? 'success' : 'secondary'}">
                            ${c.estado == 1 ? 'activo' : 'inactivo'}
                        </span>
                    </td>
                    <td>
                        ${acciones}
                    </td>
                `;
                return tr;
            });

            currentData = [...originalData];
            updatePagination();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar registros:', xhr.responseJSON); // Verifica el error detallado
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar las sucursales'
            });
        }
    });
}


function validarInput(input) {
    let telefonoValue = input.value;

    // Eliminar caracteres no válidos
    telefonoValue = telefonoValue.replace(/[^+\d\s-]/g, '');

    // Eliminar guiones existentes
    telefonoValue = telefonoValue.replace(/-/g, '');

    // Limitar la longitud máxima a 14 caracteres
    if (telefonoValue.length > 8) {
        telefonoValue = telefonoValue.slice(0, 8);
    }

    // Agregar un guion después del cuarto dígito
    if (telefonoValue.length >= 8) {
        telefonoValue = telefonoValue.slice(0, 4) + '-' + telefonoValue.slice(4);
    }

    // Asignar el valor al campo de entrada
    input.value = telefonoValue;

}
