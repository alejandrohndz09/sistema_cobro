$(document).ready(function () {
    $('#ProductoForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = $('#method').val();

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function (response) {
                // Procesar la respuesta exitosa
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalForm').modal('hide');
                // Actualizar la tabla, etc.
                mostrarDatos();
            },
            error: function (xhr) {
                // Validaciones de datos fallida
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, error) {
                        // Insertar el mensaje de error en el span correspondiente
                        $('#error-' + key).text(error[0]);
                    });
                } else {
                    console.log(xhr.responseJSON);
                    // Manejo de errores generales
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                    });

                }
            }
        });
    });

    $('#confirmarForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let method = $('#methodC').val();

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function (response) {
                // Procesar la respuesta exitosa
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
                console.log(xhr.responseJSON);
                // Manejo de errores generales
                Toast.fire({
                    icon: 'error',
                    title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                });

            }
        });
    });

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
    //Limpieza de spams
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });

    //Preparación de formulario
    $('#titulo').text("Nuevo Registro");
    $('#nombre').val('');
    $('#depreciacion').val('');

    //otros
    $('#method').val('POST'); // Cambiar a POST
    $('#departamentoForm').attr('action', '');
    $('#modalForm').modal('show');
}

function editar(idDepartamento) {
    $.get('/empresa/departamentos/' + idDepartamento + '/edit', function (obj) {
        // Limpieza de spans de error
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });

        // Preparación del formulario
        $('#titulo').text("Editar Registro");
        $('#nombre').val(obj.nombre);

        // Setear el valor de idSucursal en el combo box basado en el id de la sucursal
        $('#idSucursal').val(obj.idSucursal);

        // Otros ajustes
        $('#method').val('PUT'); // Cambiar a PUT
        $('#departamentoForm').attr('action', '/empresa/departamentos/' + idDepartamento);
        $('#modalForm').modal('show');
    });
}


function eliminar(idDepartamento) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/empresa/departamentos/' + idDepartamento);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idDepartamento) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/empresa/departamentos/baja/' + idDepartamento);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idDepartamento) {
    $.ajax({
        url: '/empresa/departamentos/alta/' + idDepartamento,
        method: 'get',
        success: function (response) {
            // Procesar la respuesta exitosa
            Toast.fire({
                icon: response.type,
                title: response.message
            });

            if (response.type == 'success') {
                mostrarDatos();
            }
        },
        error: function (xhr) {
            console.log(xhr.responseJSON);
            // Manejo de errores generales
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });

        }
    });
}
function mostrarDatos() {
    $.ajax({
        url: '/obtener-departamentos',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo

            originalData = data.map(c => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (c.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="${c.idDepartamento}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-3">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idDepartamento}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${c.idDepartamento}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idDepartamento}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
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
                        <p class="text-xs font-weight-bold mb-0">${c.idDepartamento}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.nombre}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.idSucursal}%</p>
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


            // Inicializar los datos actuales
            currentData = [...originalData];
            // Actualizar la paginación
            updatePagination();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar categorías:', error);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar las categorías.'
            });
        }
    });
}