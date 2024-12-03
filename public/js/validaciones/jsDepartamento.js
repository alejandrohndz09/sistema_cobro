$(document).ready(function () {
    $('#departamentoForm, #sucursalForm').submit(function (e) {
        e.preventDefault();
        var form = $(this); // Formulario actual
        var formData = new FormData(form[0]); // Crear un objeto FormData con los datos del formulario
        let url = form.attr('action');

        $.ajax({
            url: url, // URL del método store
            method: 'POST',  // Método HTTP
            data: formData,  // Los datos del formulario
            contentType: false,
            processData: false,  // No procesar los datos, ya que es FormData
            cache: false,
            success: function (response) {
                // Procesar la respuesta exitosa
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalForm').modal('hide');
                if ($('#evaluar').val() == 1) {
                    $('#modalFormS').modal('hide');
                    $('#evaluar').val(0);
                }
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

    $(document).on('click', '.btnEditarE', function () {
        editarE($(this).data('id'));
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
    $('#departamentoForm').attr('action', '/opciones/departamentos');
    $('#modalForm').modal('show');
}

function editarE(idSucursal) {
    $.get('/opciones/sucursal/' + idSucursal + '/edit', function (obj) {
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });
        $('#tituloSucursal').text("Editar Registro");
        $('#telefono').val(obj.telefono);
        $('#direccion').val(obj.direccion);
        $('#ubicacion').val(obj.ubicacion);

        $('#evaluar').val(1);
        $('#sucursalForm').attr('action', '/opciones/sucursal/' + idSucursal);
        $('#modalFormS').modal('show');
    });

}


function editar(idDepartamento) {
    $.get('/opciones/departamentos/' + idDepartamento + '/edit', function (obj) {
        // Limpieza de spans de error
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });

        // Preparación del formulario
        $('#titulo').text("Editar Registro");
        $('#nombre').val(obj.nombre);

        // Otros ajustes
        $('#method').val('PUT'); // Cambiar a PUT
        $('#departamentoForm').attr('action', '/opciones/departamentos/' + idDepartamento);
        $('#modalForm').modal('show');
    });
}


function eliminar(idDepartamento) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/opciones/departamentos/' + idDepartamento);
    $('#methodC').val('Delete')
    $('#dialogoDepartamento').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idDepartamento) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/opciones/departamentos/baja/' + idDepartamento);
    $('#methodC').val('get')
    $('#dialogoDepartamento').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idDepartamento) {
    $.ajax({
        url: '/opciones/departamentos/alta/' + idDepartamento,
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
    const valor = $('#idSucursal').val();
    $.ajax({
        url: '/obtener-departamentos/' + valor,
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

function validarInput(input) {
    let telefonoValue = input.value;

    // Eliminar caracteres no válidos (mantener solo dígitos, espacio y guiones)
    telefonoValue = telefonoValue.replace(/[^+\d\s-]/g, '');

    // Eliminar guiones existentes para facilitar el procesamiento
    telefonoValue = telefonoValue.replace(/-/g, '');

    // Limitar la longitud máxima a 8 caracteres (sin incluir el guion)
    if (telefonoValue.length > 8) {
        telefonoValue = telefonoValue.slice(0, 8);
    }

    // Agregar un guion automáticamente después del 4to carácter
    if (telefonoValue.length > 4) {
        telefonoValue = telefonoValue.slice(0, 4) + '-' + telefonoValue.slice(4);
    }

    // Asignar el valor al campo de entrada
    input.value = telefonoValue;
}
