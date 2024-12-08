$(document).ready(function () {
    $('#usuarioForm').submit(function (e) {
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
    const id = $(this).data('id');
    editar(id); // Llama a la función editar
});

$(document).on('click', '.btnDeshabilitar', function () {
    const id = $(this).data('id');
    baja(id); // Llama a la función para deshabilitar
});

$(document).on('click', '.btnHabilitar', function () {
    const id = $(this).data('id');
    alta(id); // Llama a la función para habilitar
});

$(document).on('click', '.btnEliminar', function () {
    const id = $(this).data('id');
    eliminar(id); // Llama a la función para eliminar
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
    $('#usuario').val('');
    $('#idEmpleado').val('');

    //otros
    $('#method').val('POST'); // Cambiar a POST
    $('#usuarioForm').attr('action', '');
    $('#modalForm').modal('show');
}

function editar(idUsuario) {
    $.get('/opciones/usuarios/' + idUsuario + '/edit', function (obj) {
        //Limpieza de spams
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });
        //Preparación de formulario
        $('#titulo').text("Editar Registro");
        $('#usuario').val(obj.usuario);
        $('#idEmpleado').val(obj.idEmpleado);

        //otros
        $('#method').val('PUT'); // Cambiar a PUT
        $('#usuarioForm').attr('action', '/opciones/usuarios/' + idUsuario);
        $('#modalForm').modal('show');
    });
}

function eliminar(idUsuario) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/opciones/usuarios/' + idUsuario);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idUsuario) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/opciones/usuarios/baja/' + idUsuario);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idUsuario) {
    $.ajax({
        url: '/opciones/usuarios/alta/'+idUsuario,
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
        url: '/obtener-usuarios',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty();

            data.forEach((c) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.idusuario}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.usuario}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.clave}</p>
                    </td>
                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${c.estado == 1 ? 'success' : 'secondary'}">
                            ${c.estado == 1 ? 'ACTIVO' : 'INACTIVO'}
                        </span>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.nombres || 'Sin asignar'}</p>
                    </td>
                    <td>
                        ${c.estado == 1
                            ? `<a role="button" data-id="${c.idusuario}" class="btnEditar me-3">
                                    <i class="fas fa-pen text-secondary"></i>
                               </a>
                               <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idusuario}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                                <i class="fas fa-minus-circle text-secondary"></i>
                                 </a>`
                            : `<a role="button" data-id="${c.idusuario}" class="btnHabilitar me-3">
                                    <i class="fas fa-arrow-up text-secondary"></i>
                               </a>
                               <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idusuario}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
                                    <i class="fas fa-trash text-secondary"></i>
                                </a>`}
                    </td>
                `;
                $('#tableBody').append(tr);
            });

            // Reactivar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        error: function (xhr) {
            console.error('Error al cargar datos:', xhr);
        }
    });
}

document.querySelectorAll('.btnEditar').forEach(button => {
    button.addEventListener('click', function() {
        const idusuario = this.getAttribute('data-id');
        
        // Configuración para editar un usuario existente
        document.getElementById('usuarioForm').action = `/opciones/usuarios/${idusuario}`;
        document.getElementById('method').value = 'PUT';
        document.getElementById('titulo').innerText = 'Editar Usuario';
        
        // Cargar datos en el formulario para edición (AJAX request para obtener datos del usuario)
        fetch(`/opciones/usuarios/${idusuario}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('usuario').value = data.usuario;
                document.getElementById('idEmpleado').value = data.idEmpleado; // Selecciona el ID del empleado
            });
    });
});
