$(document).ready(function () {
    $('#bienForm').submit(function (e) {
        e.preventDefault();
        let url = $(this).attr('action');
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
                    //Limpieza de spams
                    const errorSpans = document.querySelectorAll('span.text-danger');
                    errorSpans.forEach(function (span) {
                        span.innerHTML = '';
                    });
                    var errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, error) {
                        // Verifica si el campo es un arreglo (usando la notación de corchetes)
                        if (key.startsWith('sucursales.') || key.startsWith('departamentos.') || key.startsWith('cantidad.')) {
                            // Asegúrate de que se imprima en todos los índices
                            const fieldName = key.split('.')[0]; // Obtiene el nombre del campo sin el índice
                            const index = parseInt(key.split('.')[1]) + 1;
                            const spanSelector = `#error-${fieldName}\\.${index}`; // Usar el índice para el span correspondiente
                            $(spanSelector).text(error[0]); // Muestra el error en el span correspondiente

                        } else {
                            // Para campos que no son arreglos
                            const spanSelector = `#error-${key}`;
                            $(spanSelector).text(error[0]);
                        }
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
                if (xhr.status === 422) {
                    //Limpieza de spams
                    const errorSpans = document.querySelectorAll('span.text-danger');
                    errorSpans.forEach(function (span) {
                        span.innerHTML = '';
                    });
                    var errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, error) {
                        // Para campos que no son arreglos
                        const spanSelector = `#error-${key}`;
                        $(spanSelector).text(error[0]);
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

    // Evento click para el botón de agregar fila
    $('.btnAgregarAd').on('click', function (e) {
        e.preventDefault();
        let camposCompletos = true;

        //Verifica que los campos de la(s) fila(s) anterior(es) contengan valor
        $('#tableBodyDepartamentos tr').each(function (index) {
            let $fila = $(this);
            // Obtener los valores de cada campo en la fila
            let sucursal = $fila.find('select[name="sucursales[]"]').val();
            let departamento = $fila.find('select[name="departamentos[]"]').val();
            let cantidad = $fila.find('input[name="cantidad[]"]').val();

            // Validar cada campo y mostrar mensaje de error en el span correspondiente si está vacío
            if (!sucursal) {
                $fila.find(`#error-sucursales\\.${index + 1}`).text('Seleccione una sucursal');
                camposCompletos = false;
            } else {
                $fila.find(`#error-sucursales\\.${index + 1}`).text('');
            }

            if (!departamento) {
                $fila.find(`#error-departamentos\\.${index + 1}`).text('Seleccione un departamento');
                camposCompletos = false;
            } else {
                $fila.find(`#error-departamentos\\.${index + 1}`).text('');
            }

            if (!cantidad) {
                $fila.find(`#error-cantidad\\.${index + 1}`).text('Ingrese una cantidad');
                camposCompletos = false;
            } else {
                $fila.find(`#error-cantidad\\.${index + 1}`).text('');
            }
        });

        if (camposCompletos) {
            let filaOriginal = $('#tableBodyDepartamentos tr:last');
            let nuevaFila = filaOriginal.clone();
            let filaIndex = $('#tableBodyDepartamentos tr').length;
            // Limpia los valores de los inputs en la fila clonada
            nuevaFila.find('input, select').val('');
            nuevaFila.find('.selectDepartamento').empty().append('<option value="">Seleccione</option>');
            // Actualiza los IDs de los spans de error en la nueva fila con el nuevo índice
            nuevaFila.find(`#error-sucursales\\.${filaIndex}`).attr('id', `error-sucursales.${filaIndex + 1}`).text('');
            nuevaFila.find(`#error-departamentos\\.${filaIndex}`).attr('id', `error-departamentos.${filaIndex + 1}`).text('');
            nuevaFila.find(`#error-cantidad\\.${filaIndex}`).attr('id', `error-cantidad.${filaIndex + 1}`).text('');

            // Agrega el botón de eliminar
            if ($('#tableBodyDepartamentos tr').length === 1) {
                filaOriginal.find('td:last').append('<a role="button" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminarAd me-2"><i class="fas fa-minus text-danger"></i></a>');
                nuevaFila.find('td:last').append('<a role="button" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminarAd me-2"><i class="fas fa-minus text-danger"></i></a>');
            }
            // Añade la nueva fila al final de la tabla
            $('#tableBodyDepartamentos').append(nuevaFila);
            instanciarTooltips();
        }
    });

    // Evento click para el botón de eliminar fila
    $('#tableBodyDepartamentos').on('click', '.btnEliminarAd', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove(); // Elimina la fila
        // Reorganiza los IDs después de eliminar
        $('#tableBodyDepartamentos tr').each(function (index) {
            $(this).find('.sp-sucursal').attr('id', `error-sucursales.${index + 1}`);
            $(this).find('.sp-departamento').attr('id', `error-departamentos.${index + 1}`);
            $(this).find('.sp-cantidad').attr('id', `error-cantidad.${index + 1}`);
            index++;
        });

        // Verifica si queda una sola fila en el tbody
        if ($('#tableBodyDepartamentos tr').length === 1) {
            // Elimina el botón solo si queda una fila
            const filaActual = $('#tableBodyDepartamentos tr:first');
            filaActual.find('.btnEliminarAd').remove();
        }
    });

    // Evento change para el select de sucursal
    $('#tableBodyDepartamentos').on('change', '.selectSucursal', function () {
        if ($(this).val() != '') {
            llenarDepartamentos(null, $(this).val(), $(this).closest('tr').find('.selectDepartamento'))
        } else {
            $(this).closest('tr').find('.selectDepartamento').val('');
        }
    });

    $('#sucursal').on('change', function () {
        if ($(this).val() != '') {
            llenarDepartamentos(null, $(this).val(), $('#departamento'))
        } else {
            $('#departamento').val('');
        }
    });
});

function llenarDepartamentos(dato, idSucursal, selectDepartamento) {
    $.get('/activos/obtener-departamentos/' + idSucursal)
        .done(function (response) {
            selectDepartamento.empty();
            selectDepartamento.append($('<option>', {
                value: '',
                text: 'Seleccione'
            }));
            response.map(value => {
                var option = $('<option>', {
                    value: value.idDepartamento,
                    text: value.nombre
                });

                // Verifica si la opción coincide con la que estaba seleccionada anteriormente
                if (dato && dato == value.idDepartamento) {
                    option.attr('selected', 'selected');
                }
                selectDepartamento.append(option);
            });
        })
        .fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            /*madar el error al spam correspontiente, ya sea en un select individual (proc. de edición individual)
            o alguno que esté en la tabla (proc. inserción por grupos)*/
            if (selectDepartamento.attr('id') !== undefined) {
                $(`#error-departamento`).text('Error al cargar el listado.');
            } else {
                let index = selectDepartamento.closest('tr').index() + 1;
                $(`#error-departamentos\\.${index}`).text('Error al cargar el listado.');
            }

        });
}

function llenarSucursales(dato) {
    $.get('/activos/obtener-sucursales')
        .done(function (response) {
            $('.selectSucursal').empty();
            $('.selectSucursal').append($('<option>', {
                value: '',
                text: 'Seleccione'
            }));
            response.map(value => {
                var option = $('<option>', {
                    value: value.idSucursal,
                    text: value.ubicacion
                });

                // Verifica si la opción coincide con la que estaba seleccionada anteriormente
                if (dato && dato == value.idSucursal) {
                    option.attr('selected', 'selected');
                }
                $('.selectSucursal').append(option);
            });
        })
        .fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            /*madar el error al spam correspontiente, ya sea en un select individual (proc. de edición individual)
           o alguno que esté en la tabla (proc. inserción por grupos)*/
            if ($('.selectSucursal').attr('id') !== undefined) {
                $(`#error-sucursal`).text('Error al cargar el listado.');
            } else {
                $(`#error-sucursales\\.1`).text('Error al cargar el listado.');
            }
        });
}

function agregar() {
    //Limpieza de spams
    const errorSpans = document.querySelectorAll('span.text-danger');
    errorSpans.forEach(function (span) {
        span.innerHTML = '';
    });

    //Preparación de formulario
    $('#titulo').text("Nuevo Registro");
    $('#precioAdquisicion').val('');
    $('#fechaAdquisicion').val(new Date().toISOString().split('T')[0]);
    instanscearTablaAdquisicion();
    $('#sucursal-container, #departamento-container').hide();
    $('#tableDepartamentos, #help-container').show();
    //otros
    $('#method').val('POST'); // Cambiar a POST
    // Obtener el último segmento de la URL actual
    const urlActual = window.location.href;
    const partesUrl = urlActual.split('/');
    const idActivo = partesUrl[partesUrl.length - 1]; // Último segmento
    $('#bienForm').attr('action', `/activos/${idActivo}/bienes`);
    $('#modalForm').modal('show');
}

function editar(idBien) {
    const urlActual = window.location.href;
    const partesUrl = urlActual.split('/');
    const idActivo = partesUrl[partesUrl.length - 1]; // Último segmento

    $.get(`/activos/${idActivo}/bienes/${idBien}/edit`)
        .done(function (obj) {
            //Limpieza de spams
            const errorSpans = document.querySelectorAll('span.text-danger');
            errorSpans.forEach(function (span) {
                span.innerHTML = '';
            });

            console.log(obj);
            //Preparación de formulario
            $('#titulo').text("Editar Registro");
            instanscearTablaAdquisicion();
            $('#tableDepartamentos, #help-container').hide();
            $('#precioAdquisicion').val(obj.precio);
            $('#fechaAdquisicion').val(obj.fechaAdquisicion.split('T')[0]);
            $('#sucursal-container, #departamento-container').show();
            llenarSucursales(obj.departamento.idSucursal);
            llenarDepartamentos(obj.departamento.idDepartamento, obj.departamento.idSucursal, $('#departamento'));

            //otros
            $('#method').val('PUT'); // Cambiar a PUT
            $('#bienForm').attr('action', `/activos/${idActivo}/bienes/${idBien}`);
            $('#modalForm').modal('show');
        }).fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });
        });
}

function eliminar(idBien) {
    const urlActual = window.location.href;
    const partesUrl = urlActual.split('/');
    const idActivo = partesUrl[partesUrl.length - 1]; // Último segmento

    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', `/activos/${idActivo}/bienes/${idBien}`);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
    $('#motivo').hide();
}

function baja(idBien) {
    $('#confirmarForm').attr('action', `/activos/bienes/baja/${idBien}`);
    $('#methodC').val('post')
    $('#dialogo').text('Seleccione el motivo de baja del registro:');
    $('#motivo').show();
    $('#motivo').val('');
}

function alta(idBien) {
    $.ajax({
        url: `/activos/bienes/alta/${idBien}`,
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
    const urlActual = window.location.href;
    const partesUrl = urlActual.split('/');
    const idActivo = partesUrl[partesUrl.length - 1];
    $.ajax({
        url: `/activos/${idActivo}/obtener-bienes`,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo
            originalData = data.map(a => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (a.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="${a.idBien}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-2">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idBien}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${a.idBien}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-2">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idBien}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }

                // Obtener el valor actual y calcular el porcentaje
                let v = a.valorActual >= 0 ? a.valorActual : 0; // Asegura que sea no negativo
                let val = a.precio > 0 ? (v / a.precio) * 100 : 0; // Evita la división por cero
                let señal = val >= 70 ? 'success' : (val > 40 ? 'info' : (val > 15 ? 'warning' : 'danger'));

                // Crear fila (tr)
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', a.idBien);
                tr.classList.add('tr-link'); // Clase de la fila

                // Insertar contenido HTML en la fila
                tr.innerHTML = tr.innerHTML = `
                <td>
                   <div
                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                        <i class="fas fa-cube opacity-10 text-sm"></i>
                    </div>
                </td>
                <td class="px-1">
                    <p class="text-xs font-weight-bold mb-0">
                        ${a.departamento.idSucursal}-${a.idDepartamento}-${a.idActivo}-${a.idBien}
                    </p>
                </td>
                <td class="px-1">
                    <p class="text-xs font-weight-bold mb-0">${new Date(a.fechaAdquisicion).toLocaleDateString('es-ES')}</p>
                    <p class="text-xxs mb-0"></p>
                </td>
                <td class="px-1">
                    <p class="text-xs font-weight-bold mb-0">${'$' + Number(a.precio).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
                </td>
                <td class="px-1">
                    <p class="text-xs font-weight-bold mb-0">${'$' + Number(a.valorActual).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
                </td>
                <td>
                    <div class="d-flex-column align-items-center justify-content-center">
                        <span class="me-2 text-xs font-weight-bold">${(100 - val).toFixed(2) + '%'}</span>
                        <div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-${señal}"
                                    role="progressbar" aria-valuenow="${100 - val}"
                                    aria-valuemin="0" aria-valuemax="100"
                                    style="width:${100 - val}%;"></div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-1 text-xs">
                    <span class="badge badge-xs opacity-7 bg-${a.estado == 1 ? 'success' : (a.estado == 2 ? 'dark' : (a.estado == 3 ? 'info' : 'danger'))}">
                        ${a.estado == 1 ? 'Activo' : (a.estado == 2 ? 'Vendido' : (a.estado == 3 ? 'Donado' : 'Desecho'))}
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
            console.error('Error al cargar registros:', error);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar los registros.'
            });
        }
    });
}

function instanscearTablaAdquisicion() {
    let nuevaFila = $('#tableBodyDepartamentos tr').last().clone();
    $('#tableBodyDepartamentos').empty();
    nuevaFila.find('input, select').val('');
    nuevaFila.find('.selectDepartamento').empty().append('<option value="">Seleccione</option>');
    nuevaFila.find('.btnEliminarAd').remove();
    // Actualiza los IDs de los spans de error en la nueva fila con el nuevo índice
    nuevaFila.find('.sp-sucursal').attr('id', `error-sucursales\.1`).text('');
    nuevaFila.find('.sp-departamento').attr('id', `error-departamentos\.1`).text('');
    nuevaFila.find('.sp-cantidad').attr('id', `error-cantidad\.1`).text('');
    $('#tableBodyDepartamentos').append(nuevaFila);
    llenarSucursales(null);
    $('#precioAdquisicion, #fechaAdquisicion, .selectSucursal, .selectDepartamento, .inputCantidad, .btnAgregarAd').prop('disabled', false);
    instanciarTooltips();
}

// Función para generar el PDF
function generatePDF(tipo, element) {
    // Obtener el idActivo desde el atributo data-id
    var idActivo = element.getAttribute('data-id');


    $.ajax({
        url: '/activos/pdfActivo/' + idActivo,  // Concatenar idActivo a la URL
        type: 'GET',
        data: { tipo: tipo },  // Pasar tipo de depreciación como parámetro
        success: function (response) {
            if (response.type === 'info') {
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
            } else {
                const byteCharacters = atob(response.pdf);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], { type: 'application/pdf' });
                const pdfURL = URL.createObjectURL(blob);
                window.open(pdfURL, '_blank');
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseJSON);
            alert('Ocurrió un error al generar el PDF. Por favor, intente nuevamente.');
        }
    });
}

