$(document).ready(function () {
    // Manejador de envío de formularios
    $('#clienteFormNatural, #clienteFormJuridico').submit(function (e) {
        e.preventDefault(); // Prevenir el envío normal del formulario

        var form = $(this); // Formulario actual
        var formData = new FormData(form[0]); // Crear un objeto FormData con los datos del formulario
        let url = form.attr('action');

        $.ajax({
            url: url, // URL del método store
            method: 'POST',  // Método HTTP
            data: formData,  // Los datos del formulario
            contentType: false,  // No se necesita establecer contentType porque estamos usando FormData
            processData: false,  // No procesar los datos, ya que es FormData
            cache: false,
            success: function (response) {
                // Procesar la respuesta exitosa
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });

                // Determinar el formulario enviado
                if (form.attr('id') === 'clienteFormNatural') {
                    // Acciones específicas para cliente natural
                    $('#modalFormNatural').modal('hide');
                } else if (form.attr('id') === 'clienteFormJuridico') {
                    // Acciones específicas para cliente jurídico
                    $('#modalFormJuridico').modal('hide');
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
                        console.log(errors);
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

    $('#estadoResultados').on('change', function () {
        if ($(this)[0].files.length > 0) {
            var selectedFile = $(this)[0].files[0];
            var fileType = selectedFile.type;
            var fileName = selectedFile.name; // Obtener el nombre del archivo

            // Verificar si es un PDF
            if (fileType === 'application/pdf') {
                $('#iconContainerResultados').html('<i class="fas fa-file-pdf" style="color: red; font-size: 32px;"></i>');
            } else {
                // Volver al ícono por defecto si no es un PDF
                $('#iconContainerResultados').html('<i class="fas fa-file" style="color: #c4c4c4; font-size: 32px;"></i>');
            }
            $('#textImageResultados').text(fileName).show(); // Mostrar el nombre del archivo
        } else {
            // Si no hay archivo seleccionado, restablecer al estado inicial
            $('#iconContainerResultados').html('<i class="fas fa-file" style="color: #c4c4c4; font-size: 32px;"></i>');
            $('#textImageResultados').text('Subir estado de resultados').show();
        }
    });

    $('#balanceGeneral').on('change', function () {
        if ($(this)[0].files.length > 0) {
            var selectedFile = $(this)[0].files[0];
            var fileType = selectedFile.type;
            var fileName = selectedFile.name; // Obtener el nombre del archivo

            // Verificar si es un PDF
            if (fileType === 'application/pdf') {
                $('#iconContainerBalance').html('<i class="fas fa-file-pdf" style="color: red; font-size: 32px;"></i>');
            } else {
                // Volver al ícono por defecto si no es un PDF
                $('#iconContainerBalance').html('<i class="fas fa-file" style="color: #c4c4c4; font-size: 32px;"></i>');
            }
            $('#textImageBalance').text(fileName).show(); // Mostrar el nombre del archivo
        } else {
            // Si no hay archivo seleccionado, restablecer al estado inicial
            $('#iconContainerBalance').html('<i class="fas fa-file" style="color: #c4c4c4; font-size: 32px;"></i>');
            $('#textImageBalance').text('Subir balance general').show();
        }
    });

    $('#btnNatural').click(function () {
        $('#tipoNatural').val(0); // Establece el tipo como Natural
        agregar();
    });

    $('#btnJuridico').click(function () {
        $('#tipoJuridico').val(1); // Establece el tipo como Jurídico
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

    //Evento para clic para los tags de filtrado de tabla
    $(document).on('click', '.nav-link', function (e) {
        mostrarDatos();
    });

});

function alta(idCliente) {
    $.ajax({
        url: '/clientes/alta/' + idCliente,
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

function baja(idCliente) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/clientes/baja/' + idCliente);
    $('#methodC').val('get')
    $('#dialogoC').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function eliminar(idCliente) {
    // Preparación visual y dirección de la acción en el formulario
    $('#confirmarForm').attr('action', '/clientes/' + idCliente); // Actualiza la acción del formulario
    $('#methodC').val('DELETE'); // Configura el método como DELETE
    $('#dialogoC').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?'); // Cambia el texto del diálogo

    $('#modalConfirm').modal('show'); // Asegúrate de que el modal se abre
}

function agregar() {
    //Limpieza de spams
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });

    // Obtener el valor del input hidden 'tipo'
    const tipo = $('#tipoNatural').val();
    const tipo2 = $('#tipoJuridico').val();


    if (tipo === '0' && tipo != null) {
        // Configuración para cliente natural

        // Preparación de formulario
        $('#titulo').text("Nuevo Registro");
        $('#method').val('POST'); // Cambiar a POST
        $('#clienteFormNatural').attr('action', ''); // Asignar la acción adecuada
        $('#dui').val('');
        $('#nombres').val('');
        $('#apellidos').val('');
        $('#telefono').val('');
        $('#ingresos').val('');
        $('#egresos').val('');
        $('#lugarTrabajo').val('');
        $('#direccion').val('');
        $('#modalFormNatural').modal('show');
    } else if (tipo2 === '1' && tipo2 != null) {
        // Configuración para cliente jurídico

        // Preparación de formulario
        $('#titulo2').text("Nuevo Registro");
        $('#method2').val('POST'); // Cambiar a POST
        $('#clienteFormJuridico').attr('action', ''); // Asignar la acción adecuada
        $('#nit').val('');
        $('#nombre').val('');
        $('#telefono').val('');
        $('#direccion').val('');
        $('#ventasNetas').val('');
        $('#activoCorriente').val('');
        $('#inventario').val('');
        $('#costoVentas').val('');
        $('#pasivoCorriente').val('');
        $('#cuentasCobrar').val('');
        $('#cuentasPagar').val('');
        $('#modalFormJuridico').modal('show');
        $('#iconContainerResultados').html('<i class="fas fa-file" style="color: #c4c4c4; font-size: 32px;"></i>');
        $('#textImageResultados').text('Subir estado de resultados').show();
        $('#iconContainerBalance').html('<i class="fas fa-file" style="color: #c4c4c4; font-size: 32px;"></i>');
        $('#textImageBalance').text('Subir balance general').show();
    }
}

function editar(idcliente) {
    $.get('/clientes/' + idcliente + '/edit', function (obj) {
        // Limpieza de spans de error
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });

        if (idcliente.includes("CN")) {
            // Preparación de formulario
            $('#titulo').text("Editar Registro");
            $('#method').val('PUT');
            $('#clienteFormNatural').attr('action', '/clientes/' + idcliente); // Asignar la acción adecuada
            $('#dui').val(obj.dui);
            $('#nombres').val(obj.nombres);
            $('#apellidos').val(obj.apellidos);
            $('#telefono').val(obj.telefono);
            $('#ingresos').val(obj.ingresos);
            $('#egresos').val(obj.egresos);
            $('#lugarTrabajo').val(obj.lugarTrabajo);
            $('#direccion').val(obj.direccion);

            $('#modalFormNatural').modal('show');

        } else if (idcliente.includes("CJ")) {
            // Configuración para cliente jurídico

            // Preparación de formulario
            $('#titulo2').text("Editar Registro");
            $('#method2').val('PUT'); // Cambiar a POST
            $('#clienteFormJuridico').attr('action', '/clientes/' + idcliente); // Asignar la acción adecuada
            $('#nit').val(obj.nit);
            $('#nombreEmpresa').val(obj.nombre_empresa);
            $('#telefonoJuridico').val(obj.telefono);
            $('#direccionJuridico').val(obj.direccion);
            $('#ventasNetas').val(obj.ventas_netas);
            $('#activoCorriente').val(obj.activo_corriente);
            $('#inventario').val(obj.inventario);
            $('#costoVentas').val(obj.costos_ventas);
            $('#pasivoCorriente').val(obj.pasivos_corriente);
            $('#cuentasCobrar').val(obj.cuentas_cobrar);
            $('#cuentasPagar').val(obj.cuentas_pagar);

            // Para estado de resultados
            $('#estadoResultados').val('');
            $('#iconContainerResultados').show(); // Mostrar el icono
            $('#iconContainerResultados').html('<i class="fas fa-file-pdf" style="color: red; font-size: 32px;"></i>'); // Cambiar icono a PDF
            $('#textImageResultados').text(obj.estado_resultado); // Mostrar el nombre del archivo
            $('#textImageResultados').css('color', '#333'); // Estilizar el texto si es necesario

            // Para balance general
            $('#balanceGeneral').val('');
            $('#iconContainerBalance').show(); // Mostrar el icono
            $('#iconContainerBalance').html('<i class="fas fa-file-pdf" style="color: red; font-size: 32px;"></i>'); // Cambiar icono a PDF
            $('#textImageBalance').text(obj.balance_general); // Mostrar el nombre del archivo
            $('#textImageBalance').css('color', '#333'); // Estilizar el texto si es necesario


            $('#modalFormJuridico').modal('show');
        }
    });
}

function mostrarDatos() {
    // Obtención de filtros
    let fltCliente = $('.nav-wrapper ul.nav').eq(0).find('.nav-link.active').data('sort');
    let fltCartera = $('.nav-wrapper ul.nav').eq(1).find('.nav-link.active').data('sort');

    $.ajax({
        url: `/obtener-listaclientes/${fltCliente}/${fltCartera}`, // Ajusta esta URL según tu endpoint de clientes
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo
            originalData = data.map(a => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (a.estado == 1) {
                    acciones = `
                    <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="${a.idCliente}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-2">
                        <i class="fas fa-pen text-secondary"></i>
                    </a>
                    <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idCliente}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar">
                        <i class="fas fa-minus-circle text-secondary"></i>
                    </a>
                `;
                } else {
                    acciones = `
                    <a role="button" data-id="${a.idCliente}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-2">
                        <i class="fas fa-arrow-up text-secondary"></i>
                    </a>
                    <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idCliente}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar">
                        <i class="fas fa-trash text-secondary"></i>
                    </a>
                `;
                }

                // Crear fila (tr)
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', a.id);
                tr.classList.add('tr-link'); // Clase de la fila<<

                // Insertar contenido HTML en la fila
                tr.innerHTML = `
                 <tr class="tr-link" data-id="${a.idCliente}">
                    <td>
                        <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-arrow-alt-circle-up opacity-10 text-sm"></i>
                        </div>
                    </td>
                     <td class="px-1">
                        <p class="text-xs font-bold mb-0"> ${a.idCliente}</p>
                     </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">
                            <i class="fas fa-${a.tipoCliente === 'natural' ? 'person' : 'building'} text-xxs"></i>&nbsp;
                             ${a.nombre}
                        </p>
                        <p class="text-xxs mb-0">
                            &nbsp;&nbsp;&nbsp;&nbsp;${a.tipoCliente === 'Natural' ? 'Cliente Natural' : 'Cliente Jurídico'}
                        </p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${a.direccion}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${a.telefono}</p>
                    </td>
                    <td class="px-1 text-xs">
                        <span class="badge badge-xs opacity-7 bg-${a.estado == 1 ? 'success' : 'secondary'}">
                        ${a.estado == 1 ? 'activa' : 'inactiva'}
                        </span>
                    </td>
                    <td>
                          ${acciones}
                    </td>
                </tr>
        `;

                return tr;
            });


            // Inicializar los datos actuales
            currentData = [...originalData];
            // Actualizar la paginación
            updatePagination();
        },
        error: function (xhr, status, error) {
            Toast.fire({
                icon: 'error',
                title: xhr.responseJSON
            });
            console.log(xhr.responseJSON);
        }
    });
}

function validarDui(input) {
    let duiValue = input.value;

    // Eliminar caracteres no válidos
    duiValue = duiValue.replace(/[^\d-]/g, '');

    // Eliminar guiones adicionales al final del valor
    duiValue = duiValue.replace(/-+$/, '');

    // Limitar la longitud máxima a 10 caracteres (8 dígitos + 1 guion + 1 verificador)
    if (duiValue.length > 10) {
        duiValue = duiValue.slice(0, 10);
    }

    // Insertar el guion después del octavo dígito
    if (duiValue.length == 8) {
        duiValue = duiValue.slice(0, 8) + '-' + duiValue.slice(8);
    }

    // Asignar el valor al campo de entrada
    input.value = duiValue;
}

function validarInput(input) {
    let telefonoValue = input.value;

    // Eliminar caracteres no válidos (mantener solo dígitos, espacio, y guiones)
    telefonoValue = telefonoValue.replace(/[^+\d\s-]/g, '');

    // Limitar la longitud máxima a 9 caracteres
    if (telefonoValue.length > 9) {
        telefonoValue = telefonoValue.slice(0, 9);
    }

    // Eliminar guiones existentes
    telefonoValue = telefonoValue.replace(/-/g, '');

    // Agregar un guion después del 5to dígito (antes del 6to)
    if (telefonoValue.length >= 4) {
        telefonoValue = telefonoValue.slice(0, 4) + '-' + telefonoValue.slice(4);
    }

    // Asignar el valor al campo de entrada
    input.value = telefonoValue;
}

function validarInputNit(input) {
    let nit = input.value;

    // Eliminar caracteres no válidos (mantener solo dígitos)
    nit = nit.replace(/[^0-9]/g, '');

    // Limitar la longitud máxima a 17 caracteres sin guiones
    nit = nit.slice(0, 14);

    // Formatear con guiones según el formato 0000-000000-000-0
    if (nit.length > 4) {
        nit = nit.slice(0, 4) + '-' + nit.slice(4);
    }
    if (nit.length > 11) {
        nit = nit.slice(0, 11) + '-' + nit.slice(11);
    }
    if (nit.length > 15) {
        nit = nit.slice(0, 15) + '-' + nit.slice(15);
    }

    // Asignar el valor formateado al campo de entrada
    input.value = nit;
}


