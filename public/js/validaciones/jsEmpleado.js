$(document).ready(function () {

    $('#empleadoForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let formData = new FormData(form[0]);

        // Imprimir los datos en la consola
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        $.ajax({
            url: url,
            method: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalForm').modal('hide');
                mostrarDatos();
            },
            error: function (xhr) {
                console.log(xhr.responseText); // Verifica los detalles del error en la consola
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
    $('#dui').val('');
    $('#nombres').val('');
    $('#apellidos').val('');
    $('#cargo').val('');
    llenarDepartamentos(null);

    //otros
    $('#method').val('POST'); // Cambiar a POST
    $('#empleadoForm').attr('action', '');
    $('#modalForm').modal('show');
}


function editar(idEmpleado) {
    $.get('/opciones/empleados/' + idEmpleado + '/edit')
        .done(function (obj) {
            // Limpieza de spams
            const errorSpans = document.querySelectorAll('span.text-danger');
            errorSpans.forEach(function (span) {
                span.innerHTML = '';
            });
            // Preparación de formulario
            $('#titulo').text("Editar Registro");
            $('#dui').val(obj.dui);
            $('#nombres').val(obj.nombres);
            $('#apellidos').val(obj.apellidos);
            $('#cargo').val(obj.cargo);
            llenarDepartamentos(obj.idDepartamento);

            // Otros
            $('#method').val('PUT'); // Cambiar a PUT
            $('#empleadoForm').attr('action', '/opciones/empleados/' + idEmpleado); 
            $('#modalForm').modal('show');
        }).fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });
        });
}



function llenarDepartamentos(dato) {
    $.get('/obtener-departamentos')
        .done(function (response) {
            $('#idDepartamento').empty();
            $('#idDepartamento').append($('<option>', {
                value: '',
                text: 'Seleccione'
            }));
            response.map(value => {
                var option = $('<option>', {
                    value: value.idDepartamento,
                    text: value.nombre
                });
                if (dato && dato == value.idDepartamento) {
                    option.attr('selected', 'selected');
                }
                $('#idDepartamento').append(option);
            });
        })
        .fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            $('#error-departamento').text('Error al cargar el listado.');
        });
}


function eliminar(idEmpleado) {
    $('#confirmarForm').attr('action', '/opciones/empleados/' + idEmpleado);
    $('#methodC').val('DELETE'); 
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?');
}


function baja(idEmpleado) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/opciones/empleados/baja/' + idEmpleado);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idEmpleado) {
    $.ajax({
        url: '/opciones/empleados/alta/' + idEmpleado,
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
        url: '/obtener-empleados',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo

            originalData = data.map(em => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (em.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="${em.idEmpleado}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-3">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${em.idEmpleado}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${em.idEmpleado}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${em.idEmpleado}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }

                // Crear las filas de la tabla con la estructura solicitada
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="width: 9%">
                        <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-tag opacity-10 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${em.idEmpleado}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${em.dui}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${em.nombres} ${em.apellidos}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${em.cargo}</p>
                    </td>
                    
                    <td class="px-1">
                    ${em.departamento && em.departamento.nombre ? `<p class="text-xs font-weight-bold mb-0">${em.departamento.nombre}</p>` : `<p class="text-xs font-weight-bold mb-0">Sin departamento</p>`}

                    </td>
                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${em.estado == 1 ? 'success' : 'secondary'}">
                            ${em.estado == 1 ? 'activo' : 'inactivo'}
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
            console.error('Error al cargar empleados:', error);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar los empleados.'
            });
        }
    });
}
