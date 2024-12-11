var detallesCompra = [];
var productoSeleccionado = null;

$(document).ready(function () {
    itemsPerPage = 8;
    updatePagination();
    //----ACCIONES EN VISTA PRINCIPAL ----
    $('#compraForm').submit(function (e) {
        e.preventDefault();
        let url = $(this).attr('action');
        let formData = new FormData(this);

        // Verificar que detallesCompra no esté vacío antes de enviar
        if (detallesCompra.length === 0) {
            alert('Por favor, agregue al menos un detalle antes de proceder.');
            return;
        }

        detallesCompra.forEach((detallesC, index) => {
            formData.append(`detallesCompra[${index}][idProducto]`, detallesC.idProducto);
            formData.append(`detallesCompra[${index}][producto]`, detallesC.producto);
            formData.append(`detallesCompra[${index}][cantidad]`, detallesC.cantidad);
            formData.append(`detallesCompra[${index}][precioUnitario]`, detallesC.precioUnitario);
        });

        formData.append('idProveedor', $('#idProveedorr').val());



        $.ajax({
            url: url,
            method: 'post',
            data: formData,
            contentType: false, // Evitar que jQuery procese el tipo de contenido
            processData: false, // Evitar que jQuery convierta los datos en una cadena de consulta
            cache: false,
            success: function (response) {
                // Procesar la respuesta exitosa
                if (response.success) {
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    });
                    $('#modalForm').modal('hide');
                    //reinicio de detallesCompra
                    detallesCompra = []
                    mostrarDatos();
                    recargarSucursales();
                } else {
                    // Si el servidor responde con error
                    Toast.fire({
                        icon: 'error',
                        title: response.message || 'Ocurrió un error al procesar la compra.'
                    });
                }
            },
            error: function (xhr, status, error) {
                // Verifica si el error es una respuesta de error 500 y muestra el mensaje adecuado
                console.error(xhr.responseText);
                let errorMessage = 'Ocurrió un error al procesar la compra.';
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (response && response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error('No se pudo parsear la respuesta del servidor.');
                }

                // Mostrar el error
                Toast.fire({
                    icon: 'error',
                    title: errorMessage
                });
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


    $(document).on('click', '#btnAgregarDet', function (e) {
        e.preventDefault();

        // Obtener valores del formulario
        let productoInput = $('#producto').val().trim();
        let cantidad = parseInt($('#cantidad').val());
        let precioUnitario = parseFloat($('#precioUnitario').val().replace('$', '').trim());
        let idProducto = $('#idProducto').val();
        let idProveedor = $('#proveedor').val().trim();

        let camposValidos = true;

        // Validar Producto
        if (!idProducto || !productoInput) {
            $('#error-producto').text('Seleccione un producto válido.');
            camposValidos = false;
        } else {
            $('#error-producto').text('');
        }

        // Validar Proveedor
        if (!idProveedor) {
            $('#error-proveedor').text('Seleccione un proveedor.');
            camposValidos = false;
        } else {
            $('#error-proveedor').text('');
        }

        // Validar Precio Unitario
        if (isNaN(precioUnitario) || precioUnitario <= 0) {
            $('#error-precioUnitario').text('Ingrese un precio unitario válido.');
            camposValidos = false;
        } else {
            $('#error-precioUnitario').text('');
        }


        // Validar Cantidad
        if (!cantidad || cantidad <= 0) {
            $('#error-cantidad').text('Ingrese una cantidad válida.');
            camposValidos = false;
        } else {
            $('#error-cantidad').text('');
        }

        // Si todas las validaciones son correctas
        if (camposValidos) {
            // Crear objeto con el detalle del producto
            let detalle = {
                idProducto: idProducto,
                producto: productoInput,
                cantidad: cantidad,
                precioUnitario: precioUnitario,
                subTotal: cantidad * precioUnitario
            };

            // Verificar si el producto ya está en el array de detallesCompra
            let productoExistente = detallesCompra.find(d => d.idProducto === idProducto);

            if (productoExistente) {
                // Si el producto ya existe, actualizar la cantidad y el subtotal
                productoExistente.cantidad += cantidad;
                productoExistente.subTotal = productoExistente.cantidad * productoExistente.precioUnitario;
            } else {
                // Si no existe, agregarlo al array
                detallesCompra.push(detalle);
            }

            console.log(detallesCompra)   // Agregar para verificar que el array contiene los productos correctos

            // Agregar nueva fila a la tabla
            let newRowId = $('#tableBodyDetalle tr').length + 1;
            let newRow = `
            <tr id="row-${newRowId}">
                <td class="col-5">
                    <p class="text-sm mb-0" id="producto-${newRowId}">${detalle.producto}</p>
                </td>
                <td class="ps-4 col-1">
                    <p class="text-sm mb-0" id="cantidad-${newRowId}">${detalle.cantidad}</p>
                </td>
                <td class="ps-4 col-2">
                    <p class="text-sm mb-0" id="precioUnitario-${newRowId}">$${detalle.precioUnitario.toFixed(2)}</p>
                </td>
                <td class="ps-4 col-2">
                    <p class="text-sm text-dark mb-0" id="subtotal-${newRowId}">$${detalle.subTotal.toFixed(2)}</p>
                </td>
                <td>
                    <a role="button" class="btnEliminarDet me-2" data-row-id="${newRowId}">
                        <i class="fas fa-minus text-danger"></i>
                    </a>
                </td>
            </tr>
            `;

            $('#tableBodyDetalle').append(newRow);

            actualizarTotales();

            // Limpiar los campos del formulario
            $('#producto').val('');
            $('#proveedor').val('');
            $('#idProducto').val('');
            $('#idProveedor').val('');
            $('#cantidad').val('');
            $('#precioUnitario').val('');
            $('#stockTotal').text('');
            $('.detallesCompra-prod').hide();
        }
    });



    // Eliminar un detalle
    $(document).on('click', '.btnEliminarDet', function () {
        let rowId = $(this).data('row-id');
        $(`#row-${rowId}`).remove();

        // Eliminar el detalle del arreglo global
        detallesCompra = detallesCompra.filter(detalle => `row-${detallesCompra.indexOf(detalle) + 1}` !== `row-${rowId}`);

        // Actualizar totales después de eliminar
        actualizarTotales();
    });


    $(document).click(function (event) {
        if ($('.dropdown-results').is(':visible') &&
            !$(event.target).closest('#proveedor, #producto, .dropdown-results').length) {
            $('.dropdown-results').empty().hide();
            $('#error-producto').text('');
            $('#error-cantidad').text('');
            $('#error-proveedor').text('');
        }
    });

    // Al hacer clic en un resultado del dropdown, se establece el valor el input correspondiente
    $(document).on('click', '.dropdown-results li', function () {
        let inputId = $(this).closest('.dropdown-results').data('input');
        $(inputId).val($(this).text());
        $(this).closest('.dropdown-results').empty().hide();

        // Asignar datos al producto
        if (inputId == "#producto") {
            $(".detallesCompra-prod").show();
            $("#stockTotal").text($(this).data('producto').stockTotal);
            $('#error-producto').text('');
            $('#error-cantidad').text('');
            productoSeleccionado = {
                idProducto: $(this).data('producto').idProducto,
                nombre: $(this).data('producto').nombre,
                stockTotal: $(this).data('producto').stockTotal
            };
            $('#idProducto').val(productoSeleccionado.idProducto); // Asegúrate de que este campo se actualice
        }
    });


    $('#btnAgregar').click(function () {
        agregar();
    });

    $(document).on('click', '.btnEliminar', function () {
        eliminarCompra($(this).data('id'));
    });

    $(document).on('click', '.btnDeshabilitar', function () {
        bajaCompra($(this).data('id'));
    });

    $(document).on('click', '.btnHabilitar', function () {
        altaCompra($(this).data('id'));
    });


    //Eventos del dropdown
    $('#proveedor').keyup(function (e) {
        llenarProveedores($(this).val().trim());
    });

    $('#proveedor').focus(function () {
        llenarProveedores($(this).val().trim());
    });

    $('#producto').keyup(function (e) {
        llenarProductos($(this).val().trim());
    });

    $('#producto').focus(function () {
        llenarProductos($(this).val().trim());
    });

});


// Actualizar los totales de la compra
function actualizarTotales() {
    let total = 0;


    // Sumar subtotales
    detallesCompra.forEach(detalle => {
        total += detalle.subTotal;
    });

    // Mostrar totales
    $('#total').text(`$${total.toFixed(2)}`);
    // $('#iva').text(`$${iva.toFixed(2)}`);
    $('#totalCompra').text(`$${total.toFixed(2)}`);
}

function llenarProveedores(query) {
    $('#proveedor').val('');  // Limpia el valor actual
    $('#dropdown-proveedor').empty().show().append(
        '<li><div class="spinner"></div> Cargando...</li>'
    );
    $('#error-proveedor').text('');
    $('#error-idProveedor').text('');

    let url = query ? `/g-comercial/compras/obtenerProveedores/${query}` : '/g-comercial/compras/obtenerProveedores/';

    $.get(url)
        .done(function (response) {
            $('#dropdown-proveedor').empty().show();

            if (response.length === 0) {
                $('#error-proveedor').text('No se encontraron resultados');
            } else {
                response.forEach(function (item) {
                    // Crear un ítem en el dropdown para cada proveedor
                    $('#dropdown-proveedor').append(`
                        <li class="dropdown-item" data-idproveedor='${JSON.stringify(item)}'>
                            <span>${item.idProveedor} - ${item.nombre}</span>
                        </li>
                    `);
                });

                // Agregar evento de selección en el dropdown
                $('.dropdown-item').on('click', function () {
                    // Obtener el objeto del proveedor seleccionado
                    var proveedorSeleccionado = JSON.parse($(this).attr('data-idproveedor'));

                    // Establecer el valor de #proveedor como el idProveedor
                    // $('#proveedor').val(proveedorSeleccionado.nombre); // Si deseas mostrar el nombre en el input
                    $('#idProveedorr').val(proveedorSeleccionado.idProveedor); // Asignar el id al campo oculto
                });
            }
        })
        .fail(function () {
            $('#error-proveedor').text('Error al buscar');
        });
}



$('#proveedor').on('input', function () {
    let query = $(this).val().trim();
    if (query.length > 2) {
        llenarProveedores(query);
    } else {
        $('#dropdown-proveedor').empty();
        $('#error-proveedor').text('');
    }
});



function llenarProductos(query) {

    $('#btnAgregarDet').prop('disabled', false);
    if (!query || query.length >= 1) {
        // Mostrar el mensaje de carga mientras se realiza la petición
        $('#dropdown-producto').empty().show().append(
            '<li><div class="spinner"></div> Cargando...</li>'
        );
        $('#error-producto').text('');
        // Si query está vacío, hacer la solicitud sin el término de búsqueda
        let url = query ? `/g-comercial/compras/obtenerProductos/${query}` : '/g-comercial/compras/obtenerProductos/';

        $.get(url)
            .done(function (response) {
                // Limpia los resultados previos
                $('#dropdown-producto').empty().show();

                if (response.length === 0) {
                    $('#error-producto').text('No se encontraron resultados');
                    $(".detallesCompra-prod").hide();
                    $("#stockTotal").text('');
                } else {
                    // Agrega los nuevos resultados al dropdown
                    response.forEach(function (item) {
                        // Verificar si ya existe el producto en el arreglo detallesCompra
                        let cantidadReservada = 0;

                        detallesCompra.forEach(function (detalle) {
                            if (detalle.idProducto == item.idProducto) {
                                cantidadReservada += detalle.cantidad;
                            }
                        });

                        // Calcular el stock disponible considerando las cantidades reservadas
                        let stockDisponible = item.stockTotal - cantidadReservada;
                        item.stockTotal = stockDisponible;
                        $('#dropdown-producto').append(`
                                 <li data-producto='${JSON.stringify(item)}'><span>${item.idProducto} - ${item.nombre}</span></li>`
                        );
                    });
                }
            })
            .fail(function () {
                $('#error-producto').text('Error al buscar');
                $(".detallesCompra-prod").hide();
                $("#stockTotal").text('');
            });
    } else {
        $('#dropdown-producto').empty().hide();  // Ocultar el dropdown si el texto es corto
        $(".detallesCompra-prod").hide();
        $("#stockTotal").text('');
    }
}


function agregar() {
    // Limpieza de spams
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });
    detallesCompra = [];
    productoSeleccionado = null;
    // Preparación de formulario
    $('.dropdown-results').empty().hide();
    $('#titulo').text("Nueva Compra");
    $.get('/g-comercial/compras/obtenerCodigo-compras')
        .done(function (response) { $('#subtitulo').text(`Código: ${response}`) });
    $('#proveedor').val('');
    $('#idProveedor').val('');
    $('#fecha').text("Fecha de compra: " + new Date().toISOString().split('T')[0]);
    $('#btnAgregarDet').prop('disabled', true); // Habilitar campos
    $('#producto').val('');
    $('#precioUnitario').val('');
    $('#cantidad').val('');
    $('#tableBodyDetalle').empty();
    $('#total').text('$0.00');
    $('#totalCompra').text('$0.00');
    // Otros ajustes
    $('#method').val('POST'); // Cambiar a POST
    $('#compraForm').attr('action', '');
    $('#modalForm').modal('show');
}

function eliminarCompra(idCompra) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', 'compras/' + idCompra);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
    // location.reload(); 
}


function bajaCompra(idCompra) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/g-comercial/compras/bajaCompra/' + idCompra);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function altaCompra(idCompra) {
    $.ajax({
        url: '/g-comercial/compras/altaCompra/' + idCompra,
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


document.querySelectorAll('.btnVer').forEach(button => {
    button.addEventListener('click', function () {
        // Obtener el ID de la compra
        const idCompra = this.getAttribute('data-id');

        // Hacer la solicitud AJAX al servidor para obtener los detalles de la compra
        fetch(`compras/${idCompra}`)
            .then(response => response.json())
            .then(data => {
                // Verifica si los datos contienen productos y el total
                if (!data || !data.productos || !data.total) {
                    console.error("No se encontró el total en la respuesta.");
                    return;
                }

                // Llenar la tabla con los detalles de la compra
                const tbody = document.querySelector('#detalles-compra tbody');
                tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

                data.productos.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-1">
                            <p class="text-xs font-bold mb-0">${item.nombreProducto}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-bold mb-0">${item.precio}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-bold mb-0">${item.cantidad}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-bold mb-0">${item.monto}</p>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Colocar el total en el modal
                const totalC = data.total;  // Asegúrate de que 'total' esté presente

                if (totalC !== undefined) {
                    const totalCompraElement = document.querySelector('#totalC');
                    totalCompraElement.innerHTML = `$${totalC.toFixed(2)}`;
                } else {
                    console.error("El total es undefined o no está presente.");
                }

                // Limpiar el atributo data-id después de la solicitud
                this.removeAttribute('data-id'); // Elimina el id
            })
            .catch(error => {
                console.error('Error al obtener los detalles de la compra:', error);
            });
    });
});


function mostrarDatos() {
    $.ajax({
        url: '/compras/obtener', // Ruta donde se obtienen los datos
        method: 'GET',
        success: function (response) {
            if (response.success) {
                let compras = response.data;
                let html = '';

                compras.forEach((c) => {
                    html += `
                    <tr class="tr-link" data-id="${c.idCompra}">
                        <td>
                            <div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                <i class="fas fa-arrow-alt-circle-up opacity-10 text-sm"></i>
                            </div>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-bold mb-0">${c.idCompra}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${formatFecha(c.fecha)}</p>
                            <p class="text-xxs  mb-0">(${formatHora(c.fecha)})</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-weight-bold mb-0">${formatCurrency(c.montoTotal)}</p>
                        </td>
                        <td class="px-1">
                            <p class="text-xs font-bold mb-0">${c.nombreProveedor}</p>
                        </td>
                        <td class="px-1 text-sm">
                            <span class="badge badge-xs opacity-7 bg-${c.estado == 1 ? 'success' : 'secondary'}">
                                ${c.estado == 1 ? 'completada' : 'inactiva'}
                            </span>
                        </td>
                        <th>
                            <a role="button" data-bs-toggle="modal" data-bs-target="#modalShow"
                                data-id="${c.idCompra}" data-bs-tt="tooltip" data-bs-original-title="Ver detalles"
                                class="btnVer me-3">
                                <i class="fa-solid fa-eye text-secondary"></i>
                            </a>
                            ${c.estado == 1
                            ? `
                                   <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm"
                                    data-id="${c.idCompra}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                    class="btnDeshabilitar me-3">
                                    <i class="fas fa-minus-circle text-secondary"></i>
                                   </a>`
                            : `<a role="button" data-id="${c.idCompra}" data-bs-tt="tooltip" 
                                    data-bs-original-title="Habilitar" class="btnHabilitar me-3">
                                    <i class="fas fa-arrow-up text-secondary"></i>
                                   </a>
                                   <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm"
                                    data-id="${c.idCompra}" data-bs-tt="tooltip" data-bs-original-title="Eliminar"
                                    class="btnEliminar me-3">
                                    <i class="fas fa-trash text-secondary"></i>
                                   </a>`
                        }
                        </th>
                    </tr>`;
                });

                // Actualizar el contenido del tbody
                $('#tableBody').html(html);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al obtener los datos:', xhr.responseText);
        }
    });
}

function recargarSucursales() {
    $.ajax({
        url: '/sucursales-obtener', // Usamos la ruta nombrada
        method: 'GET',
        success: function (response) {
            if (response.success) { // Aseguramos que la respuesta tenga "success"
                let listaSucursales = '';
                response.data.forEach(sucursal => {
                    listaSucursales += `
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">${sucursal.ubicacion}</h6>
                                    <span class="text-xs">Valor distribuido:</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-sm font-weight-bold">
                                $${parseFloat(sucursal.monto_total_compras).toFixed(2)}
                            </div>
                        </li>
                    `;
                });
                $('.list-group').html(listaSucursales); // Reemplazamos la lista de sucursales
            } else {
                console.error('No se pudo obtener la lista de sucursales.');
            }
        },
        error: function (error) {
            console.error('Error al recargar las sucursales:', error);
            alert('Ocurrió un error al recargar la lista de sucursales.');
        }
    });
}



//     $.ajax({
//         url: '/compras/sucursales-obtener',
//         method: 'GET',
//         success: function(response) {
//             let listaSucursales = '';
//             response.forEach(sucursal => {
//                 listaSucursales += `
//                     <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
//                         <div class="d-flex align-items-center">
//                             <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
//                                 <i class="fas fa-map-marker-alt"></i>
//                             </button>
//                             <div class="d-flex flex-column">
//                                 <h6 class="mb-1 text-dark font-weight-bold text-sm">${sucursal.ubicacion}</h6>
//                                 <span class="text-xs">Valor distribuido:</span>
//                             </div>
//                         </div>
//                         <div class="d-flex align-items-center text-sm font-weight-bold">
//                             $${parseFloat(sucursal.monto_total_compras).toFixed(2)}
//                         </div>
//                     </li>
//                 `;
//             });
//             $('.list-group').html(listaSucursales); // Reemplazamos la lista de sucursales
//         },
//         error: function(error) {
//             console.error('Error al recargar las sucursales:', error);
//         }
//     });
// }



// Función para formatear fecha
function formatFecha(fecha) {
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' });
}

// Función para formatear hora
function formatHora(fecha) {
    const date = new Date(fecha);
    return date.toLocaleTimeString('es-ES');
}

// Función para formatear dinero
function formatCurrency(valor) {
    return `$${Number(valor).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
}





