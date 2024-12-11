$(document).ready(function () {
    $('#proveedorForm').submit(function (e) {
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

            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const key in errors) {
                        $(`#error-${key}`).text(errors[key][0]); // Mostrar mensajes de error
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
                    });
                }
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

// Función para agregar un nuevo proveedor
function agregar() {
    // Limpiar errores anteriores
    limpiarFormulario();
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });

    $('#titulo').text("Nuevo Proveedor");
    $('#nombre').val('');
    $('#direccion').val('');
    $('#telefono').val('');
    $('#correo').val('');
    $('#estado').val(1);

    $('#method').val('POST');
    $('#proveedorForm').attr('action', '/opciones/proveedores');
    $('#modalForm').modal('show');
}

// Función para editar un proveedor
function editar(IdProveedor) {
    $.get(`/opciones/proveedores/${IdProveedor}/edit`, function (obj) {
        if (obj) {
            limpiarFormulario(); // Limpia los errores y el formulario

            $('#titulo').text("Editar Proveedor");
            $('#nombre').val(obj.nombre);
            $('#direccion').val(obj.direccion);
            $('#telefono').val(obj.telefono);
            $('#correo').val(obj.correo);
            $('#estado').val(obj.estado);

            $('#method').val('PUT'); // Define el método como PUT
            $('#proveedorForm').attr('action', `/opciones/proveedores/${IdProveedor}`);
            $('#modalForm').modal('show'); // Abre el modal
        } else {
            Toast.fire({
                icon: 'error',
                title: 'No se pudieron cargar los datos del proveedor.'
            });
        }
    }).fail(function () {
        Toast.fire({
            icon: 'error',
            title: 'Error al obtener los datos del proveedor.'
        });
    });
}


function limpiarFormulario() {
    // Limpia mensajes de error
    $('small.text-danger').text('');
    $('span.text-danger').text('');

    // Resetea los valores del formulario
    $('#proveedorForm')[0].reset();
}

// Función para eliminar un proveedor
function eliminar(IdProveedor) {
    $('#confirmarForm').attr('action', '/opciones/proveedores/' + IdProveedor);
    $('#methodC').val('DELETE');
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?');
}

// Función para deshabilitar un proveedor
function baja(IdProveedor) {
    $('#confirmarForm').attr('action', '/opciones/proveedores/baja/' + IdProveedor);
    $('#methodC').val('GET');
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?');
}

// Función para habilitar un proveedor
function alta(IdProveedor) {
    $.ajax({
        url: '/opciones/proveedores/alta/' + IdProveedor,
        method: 'get',
        success: function (response) {
            Toast.fire({
                icon: response.type,
                title: response.message
            });

            if (response.type == 'success') {
                mostrarDatos();  // Actualizar la lista de proveedores
            }
        },
        error: function (xhr) {
            console.log(xhr.responseJSON);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });
        }
    });
}

// Función para mostrar los datos de los proveedores en la tabla
function mostrarDatos() {
    $.ajax({
        url: '/obtener-proveedores',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty();  // Limpiar la tabla antes de agregar los nuevos datos
            data.forEach((p) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${p.IdProveedor}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${p.nombre}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${p.direccion}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${p.telefono}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${p.correo}</p>
                    </td>
                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${p.estado == 1 ? 'success' : 'secondary'}">
                            ${p.estado == 1 ? 'ACTIVO' : 'INACTIVO'}
                        </span>
                    </td>
                    <td>
                        ${p.estado == 1
                            ? `<a role="button" data-id="${p.IdProveedor}" class="btnEditar me-3">
                                    <i class="fas fa-pen text-secondary"></i>
                                </a>
                                <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${p.IdProveedor}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                                <i class="fas fa-minus-circle text-secondary"></i>
                                 </a>`
                            : `<a role="button" data-id="${p.IdProveedor}" data-bs-tt="tooltip" data-bs-original-title="alta" class="btnHabilitar me-3">
                                    <i class="fas fa-arrow-up text-secondary"></i>
                               </a>
                               <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${p.IdProveedor}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar me-3">
                                    <i class="fas fa-trash text-secondary"></i>
                                </a>`}
                    </td>
                `;
                $('#tableBody').append(tr);
            });

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
        document.getElementById('proveedorForm').action = `/opciones/proveedores/${idusuario}`;
        document.getElementById('method').value = 'PUT';
        document.getElementById('titulo').innerText = 'Editar Usuario';
        
        // Cargar datos en el formulario para edición (AJAX request para obtener datos del usuario)
        fetch(`/opciones/proveedores/${idusuario}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('nombre').value = data.usuario;
                document.getElementById('direccion').value = data.direccion;
                document.getElementById('telefono').value = data.telefono;
                document.getElementById('correo').value = data.correo;
            });
    });
});