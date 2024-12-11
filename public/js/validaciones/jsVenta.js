var detalles = [];
var productoSeleccionado = null;
var liProductoSel = null;
$(document).ready(function () {
    itemsPerPage = 8;
    updatePagination();
    //----ACCIONES EN VISTA PRINCIPAL ----
    $('#ventaForm').submit(function (e) {
        e.preventDefault();
        let url = $(this).attr('action');
        let formData = new FormData(this);
        // for (const [key, value] of formData.entries()) {
        //     console.log(`${key}:`, value);
        // }

        detalles.forEach((detalle, index) => {
            formData.append(`detalles[${index}][idProducto]`, detalle.idProducto);
            formData.append(`detalles[${index}][producto]`, detalle.producto);
            formData.append(`detalles[${index}][cantidad]`, detalle.cantidad);
            formData.append(`detalles[${index}][precioVenta]`, detalle.precioVenta);
            formData.append(`detalles[${index}][subTotal]`, detalle.subTotal);
        });

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
                //reinicio de detalles
                detalles = []
                // Actualizar la tabla, etc.
                mostrarDatos();
            },
            error: function (xhr) {
                // Validaciones de datos fallida
                if (xhr.status === 422) {
                    //Limpieza de spams
                    const errorSpans = document.querySelectorAll('span.text-danger');
                    errorSpans.forEach(function (span) {
                        span.innerHTML = '';
                    });
                    var errors = xhr.responseJSON.errors;
                    console.log(errors);
                    $.each(errors, function (key, error) {

                        if (key.startsWith('detalles.')) {
                            $('#error-detalles').text(error[0]); // Muestra el error en el span correspondiente

                        } else {
                            const spanSelector = `#error-${key}`;
                            $(spanSelector).text(error[0]);
                        }

                    });
                } else {
                    console.log(xhr.responseJSON);
                    // Manejo de errores generales
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error al procesar la venta.'
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

    //Evento para ir a detalle de un registro
    $('#tableBody').on('click', '.tr-link', function (e) {
        if (!$(e.target).closest('a').length) {
            let id = $(this).data('id');
            window.location.href = `/gestión-comercial/ventas/${id}`;
        }
    });

    //Evento para clic para los tags de filtrado de tabla
    $(document).on('click', '.nav-link', function (e) {
        mostrarDatos();
    });

    //----ACCIONES EN EL MODAL ----
    $('#tipo').change(function (e) {
        e.preventDefault();

        // Mostrar u ocultar detalles específicos del crédito
        if ($(this).val() == 'Crédito') {
            $('.detalles-credito').show();
            let plazo = parseInt($('#plazo').val());
            $('#cuota').show();
            $('#cuota').text(`Será pagado en ${plazo} cuotas con un valor de $0.00`);
        } else {
            $('.detalles-credito').hide();
            $('#cuota').hide();
        }

        // Si un producto está seleccionado, actualiza su precio temporalmente
        if (productoSeleccionado) {
            let precio = $('#tipo').val() == 'Crédito' ? calcularCuotas(liProductoSel.data('producto').precioVenta).totalPagar :
                liProductoSel.data('producto').precioVenta;
            $("#precioVenta").text('$' + precio);
            $('#error-producto').text('');
            $('#error-cantidad').text('');
            productoSeleccionado.precioVenta = precio;

        }

        // Si existen detalles en la tabla, actualiza sus precios
        actualizarDetalles();
    });

    $(document).on('click', '#btnAgregarDet', function (e) {
        e.preventDefault();
        let productoInput = $('#producto').val().trim();
        // Obtener la cantidad desde el input
        let cantidad = parseInt($('#cantidad').val());



        let camposValidos = true;
        // Validar Producto
        if (productoSeleccionado == null || !productoInput) {
            $('#error-producto').text('Seleccione un producto.');
            camposValidos = false;
        } else {
            $('#error-producto').text('');
        }


        // Validar Cantidad
        if (!cantidad || cantidad <= 0) {
            $('#error-cantidad').text('Ingrese una cantidad válida.');
            camposValidos = false;
        } else if (cantidad > parseInt($('#stockTotal').text())) {
            $('#error-cantidad').text('La cantidad supera el stock disponible.');
            camposValidos = false;
        } else {
            $('#error-cantidad').text('');
        }

        if (camposValidos) {

            // Armamos el detalle a colocar
            let detalle = {
                idProducto: productoSeleccionado.idProducto,
                producto: productoSeleccionado.idProducto + ' - ' + productoSeleccionado.nombre,
                cantidad: cantidad,
                precioVenta: parseFloat(productoSeleccionado.precioVenta).toFixed(2),
                precioVentaOriginal: parseFloat(liProductoSel.data('producto').precioVenta).toFixed(2),
                subTotal: cantidad * parseFloat(productoSeleccionado.precioVenta)
            };

            // Introducimos el detalle al arreglo global `detalles`
            detalles.push(detalle);
            mostrarDetallesEnTabla();
            // Reiniciar la variable producto
            productoSeleccionado = null;
            liProductoSel = null;

            // Limpiar los campos después de agregar el detalle
            // $('#plazo, #tipo').prop('disabled', true);
            $('#producto').val('');
            $('#cantidad').val('');
            $('#dropdown-producto').empty();
            $('#precioVenta').text('');
            $('#stockTotal').text('');
            $('.detalles-prod').hide();
        }
    });

    $('#tableBodyDetalle').on('click', '.btnEliminarDet', function (e) {
        e.preventDefault();
        // Obtener el índice de la fila
        let rowIndex = $(this).closest('tr').index();
        $(this).closest('tr').remove();// Eliminar la fila de la tabla
        detalles.splice(rowIndex, 1);// Eliminar el detalle correspondiente en el arreglo `detalles` según el índice

        actualizarTotales();
    });

    $(document).on('keyup focus', '#cliente, #producto', function () {
        // Obtén el valor del campo actual y su id
        let valor = $(this).val().trim();
        let id = $(this).attr('id');

        // Llama a la función correspondiente según el id
        if (id === 'cliente') {
            llenarClientes(valor);
        } else if (id === 'producto') {
            llenarProductos(valor);
        }
    });

    $(document).on('keyup', '#plazo', function (e) {
        if (productoSeleccionado) {
            // Accede al precio original desde liProductoSel
            let precioOriginal = liProductoSel.data('producto').precioVenta;

            // Calcula los datos del crédito
            let datosCredito = calcularCuotas(precioOriginal);

            // Actualiza la interfaz con los nuevos valores
            $("#precioVenta").text('$' + datosCredito.totalPagar);
            $('#error-producto').text('');
            $('#error-cantidad').text('');

            // Actualiza el precio en productoSeleccionado
            productoSeleccionado.precioVenta = datosCredito.totalPagar;

        }
        let plazo = $(this).val()==''?'':parseInt($(this).val());
        $('#cuota').show();
        $('#cuota').text(`Será pagado en ${plazo} cuotas con un valor de $0.00`);
        actualizarDetalles(true)

    });
    // Al hacer clic en un resultado del dropdown, se establece el valor el input correspondiente
    $(document).on('click', '.dropdown-results li', function () {
        let inputId = $(this).closest('.dropdown-results').data('input');
        $(inputId).val($(this).text());
        $(this).closest('.dropdown-results').empty().hide();

        //otros cambios visibles
        if (inputId == "#producto") {//Si es el drop de producto
            $(".detalles-prod").show();
            liProductoSel = $(this);
            if ($('#tipo').val() == 'Crédito') {
                let datosCredito = calcularCuotas(parseFloat($(this).data('producto').precioVenta));
                $("#precioVenta").text('$' + datosCredito.totalPagar);
                $("#stockTotal").text($(this).data('producto').stockTotal);
                $('#error-producto').text('');
                $('#error-cantidad').text('');
                productoSeleccionado = {
                    idProducto: $(this).data('producto').idProducto,
                    nombre: $(this).data('producto').nombre,
                    precioVenta: datosCredito.totalPagar,
                    stockTotal: $(this).data('producto').stockTotal
                }

            } else {
                $("#precioVenta").text('$' + $(this).data('producto').precioVenta);
                $("#stockTotal").text($(this).data('producto').stockTotal);
                $('#error-producto').text('');
                $('#error-cantidad').text('');
                productoSeleccionado = {
                    idProducto: $(this).data('producto').idProducto,
                    nombre: $(this).data('producto').nombre,
                    precioVenta: $(this).data('producto').precioVenta,
                    stockTotal: $(this).data('producto').stockTotal
                }
            }

        } else {//Si es el drop de cliente
            $('#idCliente').val($(this).data('idcliente'));
            $('#producto, #cantidad, #btnAgregarDet').prop('disabled', false);
            $('#error-cliente').text('');
        }

    });
    // Cerrar el dropdown si se hace clic fuera de él
    $(document).click(function (event) {
        if ($('.dropdown-results').is(':visible') &&
            !$(event.target).closest('#cliente, #producto, .dropdown-results').length) {
            $('.dropdown-results').empty().hide();
            $('#error-producto').text('');
            $('#error-cantidad').text('');
            $('#error-cliente').text('');
            //si ocurre esto y no hay un cliente seleccionado, desactivar todo
            if (!$('#idCliente').val().trim()) {
                $('#producto, #cantidad, #btnAgregarDet').prop('disabled', true);
                $('#cliente').val('');
            } else if (!$('#producto').val()) {
                $('#producto').val('');
                $(".detalles-prod").hide();

            }
        }
    });
});

function actualizarDetalles(isPlazoEvent) {
    if (detalles.length > 0) {
        detalles.forEach(detalle => {
            // Recalcular precioVenta usando precioVentaOriginal
            detalle.precioVenta = $('#tipo').val() == 'Crédito'
                ? calcularCuotas(detalle.precioVentaOriginal).totalPagar
                : detalle.precioVentaOriginal;
            // Recalcular subtotal
            detalle.subTotal = detalle.cantidad * detalle.precioVenta;
        });

        // Redibujar los detalles actualizados en la tabla
        mostrarDetallesEnTabla();
    }
}

function calcularCuotas(capital) {
    console.log(capital);
    let plazo = $('#plazo').val() == '' ? 0 : parseInt($('#plazo').val());
    if (plazo == 0) {
        return {
            cuotaMensual: 0.00,
            totalPagar: 0.00
        }
    }
    // Calcular la tasa mensual dividiendo la anual entre 12
    const tasaMensual = 0.10 / 12;

    // Fórmula del pago fijo: cuota = (P * r) / (1 - (1 + r)^-n)
    const cuotaMensual = (capital * tasaMensual) / (1 - Math.pow(1 + tasaMensual, -plazo));

    // Total a pagar es la cuota mensual multiplicada por el plazo
    const totalPagar = cuotaMensual * plazo;

    // Retornar los valores
    return {
        cuotaMensual: cuotaMensual.toFixed(2),
        totalPagar: totalPagar.toFixed(2),
    };
}

function actualizarTotales() {
    let total = 0;
    let iva = 0;
    let totalVenta = 0;

    // Recorrer el arreglo 'detalles' para calcular el total
    detalles.forEach(function (detalle) {
        total += detalle.subTotal;
    });

    iva = total * 0.13; // 13% de IVA
    totalVenta = total + iva;

    // Mostrar los totales actualizados
    $('#total').text('$' + total.toFixed(2));
    $('#iva').text('$' + iva.toFixed(2));
    $('#totalVenta').text('$' + totalVenta.toFixed(2));
    if ($('#tipo').val() == 'Crédito') {
        let plazo = parseInt($('#plazo').val());
        $('#cuota').text(`Será pagado en ${plazo} cuotas con un valor de $${(totalVenta / plazo).toFixed(2)}`);
    }
}

function mostrarDetallesEnTabla() {
    // Limpiar el cuerpo de la tabla antes de redibujar
    $('#tableBodyDetalle').empty();

    // Recorrer el arreglo de detalles y generar filas
    detalles.forEach((detalle, index) => {
        let rowId = index + 1; // Index para generar un id único por fila
        let filaHTML = `
            <tr id="row-${rowId}">
                <td class="col-5">
                    <p class="text-sm mb-0" id="producto-${rowId}">${detalle.producto}</p>
                </td>
                <td class="ps-4 col-1">
                    <p class="text-sm mb-0" id="cantidad-${rowId}">${detalle.cantidad}</p>
                </td>
                <td class="ps-4 col-2">
                    <p class="text-sm mb-0" id="precioUnitario-${rowId}">$${detalle.precioVenta}</p>
                </td>
                <td class="ps-4 col-2">
                    <p class="text-sm text-dark mb-0" id="subtotal-${rowId}">$${detalle.subTotal.toFixed(2)}</p>
                </td>
                <td>
                    <a role="button" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminarDet me-2">
                        <i class="fas fa-minus text-danger"></i>
                    </a>
                </td>
            </tr>
        `;

        // Añadir la fila al cuerpo de la tabla
        $('#tableBodyDetalle').append(filaHTML);
    });

    // Actualizar los totales después de mostrar
    actualizarTotales();
}

function llenarClientes(query) {
    $('#idCliente').val('');
    $('#error-cliente').text('');
    $('#error-idCliente').text('');
    $('#producto, #cantidad, #btnAgregarDet').prop('disabled', true);

    if (!query || query.length >= 1) {
        // Mostrar el mensaje de carga mientras se realiza la petición
        $('#dropdown-cliente').empty().show().append(
            '<li><div class="spinner"></div> Cargando...</li>'
        );
        $('#error-cliente').text('');
        // Si query está vacío, hacer la solicitud sin el término de búsqueda
        let url = query ? `/gestión-comercial/ventas/obtener-clientes/${query}` : '/gestión-comercial/ventas/obtener-clientes/';

        $.get(url)
            .done(function (response) {
                // Limpia los resultados previos
                $('#dropdown-cliente').empty().show();
                if (response.length === 0) {
                    $('#error-cliente').text('No se encontraron resultados');
                } else {
                    // Agrega los nuevos resultados al dropdown
                    response.forEach(function (item) {
                        var iconClass = item.tipo == 1 ? 'fas fa-building' : 'fas fa-person';
                        $('#dropdown-cliente').append(`<li data-idcliente="${item.idCliente}"><span><i class="${iconClass}"></i>&nbsp;${item.idCliente} - ${item.cliente}</span></li>`);
                    });
                }
            })
            .fail(function () {
                $('#error-cliente').text('Error al buscar');
            });
    } else {
        $('#dropdown-cliente').empty().hide();  // Ocultar el dropdown si el texto es corto
    }
}

function llenarProductos(query) {
    $('#error-producto').text('');
    if (!query || query.length >= 1) {
        // Mostrar el mensaje de carga mientras se realiza la petición
        $('#dropdown-producto').empty().show().append(
            '<li><div class="spinner"></div> Cargando...</li>'
        );
        $('#error-producto').text('');
        // Si query está vacío, hacer la solicitud sin el término de búsqueda
        let url = query ? `/gestión-comercial/ventas/obtener-productos/${query}` : '/gestión-comercial/ventas/obtener-productos/';

        $.get(url)
            .done(function (response) {
                // Limpia los resultados previos
                $('#dropdown-producto').empty().show();

                if (response.length === 0) {
                    $('#error-producto').text('No se encontraron resultados');
                    $(".detalles-prod").hide();
                    $("#precioVenta").text('');
                    $("#stockTotal").text('');
                } else {
                    // Agrega los nuevos resultados al dropdown
                    response.forEach(function (item) {
                        // Verificar si ya existe el producto en el arreglo detalles
                        let cantidadReservada = 0;

                        detalles.forEach(function (detalle) {

                            if (detalle.idProducto == item.idProducto) {
                                cantidadReservada += detalle.cantidad;
                            }
                        });
                        // Calcular el stock disponible considerando las cantidades reservadas
                        let stockDisponible = item.stockTotal - cantidadReservada;
                        // Si el stock es suficiente, lo mostramos
                        if (stockDisponible > 0) {
                            item.stockTotal = stockDisponible; //modificamos conforme al stock calculado
                            $('#dropdown-producto').append(`
                                <li data-producto='${JSON.stringify(item)}'><span>${item.idProducto} - ${item.nombre}</span></li>`
                            );
                        }

                        //Para poner imagen
                        /* <li data-precio="${item.precioVenta}" data-stock="${item.stockTotal} class="mb-2">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/productos/${item.imagen}" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">${item.idProducto} - ${item.nombre}</span> from Laur
                                        </h6>
                                    </div>
                                </div>
                            </li>*/
                    });
                }
            })
            .fail(function () {
                $('#error-producto').text('Error al buscar');
                $(".detalles-prod").hide();
                $("#precioVenta").text('');
                $("#stockTotal").text('');
            });
    } else {
        $('#dropdown-producto').empty().hide();  // Ocultar el dropdown si el texto es corto
        $(".detalles-prod").hide();
        $("#precioVenta").text('');
        $("#stockTotal").text('');
    }
}

function agregar() {
    //Limpieza de spams
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });
    detalles = [];
    productoSeleccionado = null;
    liProductoSel = $(this);

    //Preparación de formulario
    $('.dropdown-results').empty().hide();
    $('#titulo').text("Nueva Venta");
    $.get('/gestión-comercial/ventas/obtener-codigo').done(function (response) { $('#subtitulo').text(`Código: ${response}`) });
    $('#cliente').val('');
    $('#idCliente').val('');
    $('#fecha').text("Fecha de venta: " + new Date().toISOString().split('T')[0]);
    $('#tipo').val('Contado').trigger('change');
    $('#producto, #cantidad, #btnAgregarDet').prop('disabled', true);
    $('#producto').val('');
    $('#precioVenta').val('');
    $('#cantidad').val('');
    $('#tableBodyDetalle').empty();
    $('#plazo').val('3');
    $('.detalles-credito').hide();
    $('#total').text('$0.00');
    $('#iva').text('$0.00');
    $('#totalVenta').text('$0.00');
    $('#cuota').hide();

    //otros
    $('#method').val('POST'); // Cambiar a POST
    $('#ventaForm').attr('action', '');
    $('#modalForm').modal('show');
}

function eliminar(idVenta) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/gestión-comercial/ventas/' + idVenta);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idVenta) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/gestión-comercial/ventas/baja/' + idVenta);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idVenta) {
    $.ajax({
        url: '/gestión-comercial/ventas/alta/' + idVenta,
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
    //obtención de filtros
    let fltVentas = $('.nav-wrapper ul.nav').eq(0).find('.nav-link.active').data('sort');
    let fltCliente = $('.nav-wrapper ul.nav').eq(1).find('.nav-link.active').data('sort');
    // console.log(fltVentas);
    // console.log(fltCliente);
    $.ajax({
        url: `/obtener-ventas/${fltVentas}/${fltCliente}`,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo
            originalData = data.map(a => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (a.estado == 1) {
                    acciones = `
                       
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idVenta}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${a.idVenta}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-2">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idVenta}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }

                // Crear fila (tr)
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', a.idVenta);
                tr.classList.add('tr-link'); // Clase de la fila
                let hora12 = new Date(a.fecha).toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true // Asegura el formato de 12 horas
                });

                // Insertar contenido HTML en la fila
                tr.innerHTML = `
                <tr class="tr-link" data-id="${a.idVenta}">
                    <td>
                        <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-arrow-alt-circle-up opacity-10 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-bold mb-0">${a.idVenta}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${new Date(a.fecha).toLocaleDateString('es-ES')}</p>
                        <p class="text-xxs mb-0">(${hora12.replace(/(AM|PM)/, '$1'.toUpperCase())})</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0" style="text-align: left !important;">
                            $${parseFloat(a.total).toFixed(2)}
                        </p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">
                            <i class="fas fa-${a.cliente_natural ? 'person' : 'building'} text-xxs"></i>&nbsp;
                            ${a.cliente_natural ? `${a.cliente_natural.nombres} ${a.cliente_natural.apellidos}` : a.cliente_juridico.nombre_empresa}
                        </p>
                        <p class="text-xxs mb-0">
                            &nbsp;&nbsp;&nbsp;&nbsp;${a.cliente_natural ? 'Cliente Natural' : 'Cliente Jurídico'}
                        </p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">
                            ${a.tipo == 1 ? 'Crédito' : 'Contado'}
                        </p>
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
                title: xhr.responseJSON.error
            });
        }
    });
}

function setDepreciationType(type) {
    // Establecer el tipo de depreciación en el campo oculto
    document.getElementById('tipo').value = type;
}
// Función para enviar el formulario
function submitForm() {
    // Obtener los datos del formulario
    const formData = {
        empresa: document.getElementById('empresa').value,
        sucursal: document.getElementById('sucursal').value,
        departamento: document.getElementById('departamento').value,
        venta: document.getElementById('venta').value,
        tipo: document.getElementById('tipo').value,
    };

    // Realizar la solicitud AJAX con los datos del formulario
    $.ajax({
        url: "/ventas/pdf", // URL de la ruta que maneja la generación del PDF
        type: "GET", // Método HTTP que deseas utilizar
        data: formData, // Los datos que se van a enviar
        success: function (response) {
            // Verificar si hay algún error en la respuesta
            if (response.type === 'info') {
                // Procesar la respuesta fallida
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
            } else {
                // Crear un objeto Blob con el contenido en base64
                const byteCharacters = atob(response.pdf);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], { type: 'application/pdf' });

                // Crear un enlace temporal para abrir el PDF en una nueva pestaña
                const pdfURL = URL.createObjectURL(blob);
                window.open(pdfURL, '_blank');
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseJSON); // Mostrar error en la consola para depuración
            alert('Ocurrió un error al generar el PDF. Por favor, intente nuevamente.');
        }
    });
}
