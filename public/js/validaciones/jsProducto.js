$(document).ready(function () {
    $('#ProductoForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let formData = new FormData(this);
        // for (const [key, value] of formData.entries()) {
        //     console.log(`${key}:`, value);
        // }
        $.ajax({
            url: url,
            method: 'post',
            data: formData,
            contentType: false, // Evitar que jQuery procese el tipo de contenido
            processData: false, // Evitar que jQuery convierta los datos en una cadena de consulta
            cache: false,
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

    //Abrir nueva vista
    $('#tableBody').on('click', '.tr-link', function (e) {
        if (!$(e.target).closest('a').length) {
            let id = $(this).data('id');
            window.location.href = `/productos/${id}`;
        }
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
    $('#descripcion').val('');
    $('#stockMinimo').val('');

    //otros
    $('#method').val('POST'); // Cambiar a POST
    $('#ProductoForm').attr('action', '');
    $('#modalForm').modal('show');
}

function editar(idProducto) {
    $.get('/productos/' + idProducto + '/edit', function (obj) {
        // Limpieza de spans de error
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(function (span) {
            span.innerHTML = '';
        });

        // Preparación del formulario
        $('#titulo').text("Editar Registro");
        $('#imagenTemp').val(obj.imagen);
        $('#imagen').val('');
        $('#image-preview').css('background-image', 'url(../assets/img/productos/' + obj.imagen + ')'); // Establece la imagen de fondo en el label
        $('#image-preview').css('background-size', 'cover'); // Ajusta el tamaño de la imagen de fondo
        $('#image-preview').css('background-position', 'center'); // Centra la imagen de fondo
        $('#iconContainer').hide(); // Oculta el icono 
        $('#textImage').hide();//y el texto
        $('#nombre').val(obj.nombre);
        $('#descripcion').val(obj.descripcion);
        $('#stockMinimo').val(obj.stockMinimo);

        // Otros ajustes
        $('#method').val('PUT'); // Cambiar a PUT
        $('#ProductoForm').attr('action', '/productos/' + idProducto);
        $('#modalForm').modal('show');
    });
}


function eliminar(idProducto) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/productos/' + idProducto);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idProducto) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/productos/baja/' + idProducto);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idProducto) {
    $.ajax({
        url: '/productos/alta/' + idProducto,
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
        url: '/obtener-productos',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo

            originalData = data.map(c => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (c.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" 
                           data-id="${c.idProducto}" data-bs-tt="tooltip" 
                           data-bs-original-title="Editar" class="btnEditar me-3">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" 
                           data-id="${c.idProducto}" data-bs-tt="tooltip" 
                           data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${c.idProducto}" data-bs-tt="tooltip" 
                           data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" 
                           data-id="${c.idProducto}" data-bs-tt="tooltip" 
                           data-bs-original-title="Eliminar" class="btnEliminar me-3">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }

                // Determinar la imagen o el ícono predeterminado
                const imagen = c.imagen
                    ? `<img src="../assets/img/productos/${c.imagen}" class="avatar avatar-sm me-3">`
                    : `<div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                           <i class="fas fa-cube opacity-10 text-sm"></i>
                       </div>`;

                // Crear la fila de la tabla
                const tr = document.createElement('tr');
                tr.innerHTML = `
                        <td>
                            ${imagen}
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${c.idProducto}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${c.nombre}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${c.stockMinimo}</p>
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
            console.error('Error al cargar los productos:', error);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar los productos.'
            });
        }
    });
}

$('#imagen').on('change', function (event) {
    // Verifica si se ha seleccionado un archivo
    if ($(this)[0].files.length > 0) {
        // Obtiene el archivo seleccionado
        var selectedFile = $(this)[0].files[0];

        // Oculta el icono y el texto
        $('#iconContainer').hide();
        $('#textImage').hide();

        // Crea una URL del objeto Blob para la vista previa de la imagen
        var imageURL = URL.createObjectURL(selectedFile);

        // Establece la URL como fondo del label
        $('#image-preview').css({
            'background-image': 'url(' + imageURL + ')',
            'background-size': 'cover',
            'background-position': 'center'
        });

    } else {
        $('#image-preview').css('background-image', 'none');// Restaura el fondo del label
        $('#iconContainer').show();// Muestra el icono y el texto nuevamente
        $('#textImage').show();
    }
});

// Evento para capturar el clic en la columna
$('.producto-row').on('click', function (event) {

    const producto = $(this).data('producto'); // Aquí se recupera el objeto $registro
    let id, tipo;

    // Verificar si el ID contiene 'DC' (detalle compra) o 'DV' (detalle venta)
    if (producto.idDetalleCompra.startsWith('DC')) {
        id = producto.idDetalleCompra;
        tipo = 'entrada'; // Es una compra
    } else if (producto.idDetalleCompra.startsWith('DV')) {
        id = producto.idDetalleCompra;
        tipo = 'salida'; // Es una venta
    }

    if (id) {
        $.ajax({
            url: `/producto-detalle/${tipo}/${id}`, // Ruta dinámica con template literals
            type: 'GEt',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
            },
            dataType: 'json',
            success: function (data) {
                if (data.tipo === 'entrada') {
                    // Formato de la fecha
                    const formattedDate = moment(data.fecha).format('DD/MM/YYYY h:mm:ss A');

                    // Rellena el modal con datos de la compra
                    $('#TitleKardex').text('Detalles de la Compra');
                    $('#BodyKardex').html(`
                        <div class="d-flex justify-content-center">
                            <div
                                class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                                <i style="font-size: 27px" class="fas fa-arrow-alt-circle-down opacity-10"></i>
                             </div>
                        </div>
                        <p class="mb-0 mt-3" ><strong>No. de Compra:</strong> ${data.idCompra}</p>
                        <p class="mb-0" ><strong>Fecha:</strong> ${formattedDate}</p>
                        <p class="mb-0" ><strong>Cantidad:</strong> ${data.cantidad}</p>
                        <p class="mb-0" ><strong>Valor unitario:</strong> $ ${producto.ValorUnitario}</p>
                        <p class="mb-0" ><strong>Valor total:</strong> $ ${producto.ValorTotal}</p>
                        <p class="mb-0" ><strong>Realizado por:</strong> ${data.idEmpleado} - ${data.nombreEmpleado}</p>
                    `);
                } else if (data.tipo === 'salida') {
                    // Formato de la fecha
                    const formattedDate = moment(data.fecha).format('DD/MM/YYYY h:mm:ss A');

                    // Rellena el modal con datos de la venta
                    $('#TitleKardex').text('Detalles de la Venta');
                    $('#BodyKardex').html(`
                        <div class="d-flex justify-content-center">
                             <div
                                class="icon icon-shape icon-lg bg-gradient-danger shadow text-center border-radius-lg">
                                 <i style="font-size: 27px" class="fas fa-arrow-alt-circle-up opacity-10 text-xxl"></i>
                             </div>
                        </div>
                        <p class="mb-0 mt-3" ><strong>Número de Venta:</strong> ${data.idVenta}</p>
                        <p class="mb-0" ><strong>Fecha:</strong> ${formattedDate}</p>
                        <p class="mb-0" ><strong>Valor unitario:</strong> $ ${producto.ValorUnitario}</p>
                        <p class="mb-0" ><strong>Valor total:</strong> $ ${producto.ValorTotal}</p>
                        <p class="mb-0" ><strong>Tipo:</strong> ${data.tipoVenta === 0 ? 'Contado' : `Crédito (Plazo: ${data.meses} meses)`}</p>
                        <p class="mb-0" ><strong>Realizado por:</strong> ${data.idEmpleado} - ${data.nombreEmpleado}</p>
                    `);
                }

                // Muestra el modal
                $('#DetalleKardex').modal('show');
            },

            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al obtener los detalles.');
            }
        });
    }
});

