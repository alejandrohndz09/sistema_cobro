$(document).ready(function () {
    $('#activoForm').submit(function (e) {
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
                        if (key.startsWith('sucursal.') || key.startsWith('departamento.') || key.startsWith('cantidad.')) {
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

    // Evento click para el botón de agregar fila
    $('#btnAgregarAd').on('click', function (e) {
        e.preventDefault();
        let camposCompletos = true;

        //Verifica que los campos de la(s) fila(s) anterior(es) contengan valor
        $('#tableBodyDepartamentos tr').each(function (index) {
            let $fila = $(this);
            // Obtener los valores de cada campo en la fila
            let sucursal = $fila.find('select[name="sucursal[]"]').val();
            let departamento = $fila.find('select[name="departamento[]"]').val();
            let cantidad = $fila.find('input[name="cantidad[]"]').val();

            // Validar cada campo y mostrar mensaje de error en el span correspondiente si está vacío
            if (!sucursal) {
                $fila.find(`#error-sucursal\\.${index + 1}`).text('Seleccione una sucursal');
                camposCompletos = false;
            } else {
                $fila.find(`#error-sucursal\\.${index + 1}`).text('');
            }

            if (!departamento) {
                $fila.find(`#error-departamento\\.${index + 1}`).text('Seleccione un departamento');
                camposCompletos = false;
            } else {
                $fila.find(`#error-departamento\\.${index + 1}`).text('');
            }

            if (!cantidad) {
                $fila.find(`#error-cantidad\\.${index + 1}`).text('Ingrese una cantidad');
                camposCompletos = false;
            } else {
                $fila.find(`#error-cantidad\\.${index + 1}`).text('');
            }
        });

        if (camposCompletos) {
            let filaOriginal =  $('#tableBodyDepartamentos tr:last');
            let nuevaFila = filaOriginal.clone();
            let filaIndex = $('#tableBodyDepartamentos tr').length;
            // Limpia los valores de los inputs en la fila clonada
            nuevaFila.find('input, select').val('');
            nuevaFila.find('.selectDepartamento').empty().append('<option value="">Seleccione</option>');
            // Actualiza los IDs de los spans de error en la nueva fila con el nuevo índice
            nuevaFila.find(`#error-sucursal\\.${filaIndex}`).attr('id', `error-sucursal.${filaIndex + 1}`).text('');
            nuevaFila.find(`#error-departamento\\.${filaIndex}`).attr('id', `error-departamento.${filaIndex + 1}`).text('');
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
            $(this).find('.sp-sucursal').attr('id', `error-sucursal.${index + 1}`);
            $(this).find('.sp-departamento').attr('id', `error-departamento.${index + 1}`);
            $(this).find('.sp-cantidad').attr('id', `error-cantidad.${index + 1}`);
            index++;
        });

        // Verifica si queda una sola fila en el tbody
        if ($('#tableBodyDepartamentos tr').length === 1) {
            // elimina el botón solo si queda una fila
            const filaActual = $('#tableBodyDepartamentos tr:first');
            filaActual.find('.btnEliminarAd').remove();
        }
    });

    // Evento change para el select de sucursal
    $('#tableBodyDepartamentos').on('change', '.selectSucursal', function () {
        if ($(this).val() != '') {
            llenarDepartamentos($(this).val(), $(this).closest('tr').find('.selectDepartamento'))
        } else {
            $(this).closest('tr').find('.selectDepartamento').val('');
        }
    });

    // Eventos que activan la verificación y habilitación de otros campos
    $('input, textarea').on('keyup', verificarCampos);
    $('select, input[type="file"]').on('change', verificarCampos);

    $('#tableBody').on('click', '.tr-link', function (e) {
        if (!$(e.target).closest('a').length) {
            let id = $(this).data('id');
            window.location.href = `/activos/${id}`;
        }
    });
});

function llenarDepartamentos(dato, selectDepartamento) {
    $.get('/activos/obtener-departamentos/' + dato)
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
                selectDepartamento.append(option);
            });
        })
        .fail(function (xhr, status, error) {
            let index = selectDepartamento.closest('tr').index() + 1;
            console.log(xhr.responseJSON);
            $(`#error-departamento\\.${index}`).text('Error al cargar el listado.');
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
            $('#error-sucursal\\.1').text('Error al cargar el listado.');
        });
}

function llenarCategorias(dato) {
    $.get('/activos/obtener-categorias')
        .done(function (response) {
            $('#categoria').empty();
            $('#categoria').append($('<option>', {
                value: '',
                text: 'Seleccione'
            }));
            response.map(value => {
                var option = $('<option>', {
                    value: value.idCategoria,
                    text: value.nombre
                });


                // Verifica si la opción coincide con la que estaba seleccionada anteriormente
                if (dato && dato == value.idCategoria) {

                    option.attr('selected', 'selected');
                }
                $('#categoria').append(option);
            });
        })
        .fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            $('#error-categoria').text('Error al cargar el listado.');
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
    $('#imagenTemp').val('');
    $('#imagen').val('');
    $('#image-preview').css('background-image', 'none');// Restaura el fondo del label
    $('#iconContainer').show();// Muestra el icono y el texto nuevamente
    $('#textImage').show();
    $('#nombre').val('');
    llenarCategorias(null);
    $('#descripcion').val('');

    $('#precioAdquisicion').val('');
    $('#fechaAdquisicion').val(new Date().toISOString().split('T')[0]);
    instanscearTablaAdquisicion();
    $('#panelAdquisicion').show();
    $('#precioAdquisicion, #fechaAdquisicion, .selectSucursal, .selectDepartamento, .inputCantidad, #btnAgregarAd').prop('disabled', true);

    //otros
    $('#method').val('POST'); // Cambiar a POST
    $('#activoForm').attr('action', '');
    $('#modalForm').modal('show');
}

function editar(idActivo) {
    $.get('/activos/' + idActivo + '/edit')
        .done(function (obj) {
            //Limpieza de spams
            const errorSpans = document.querySelectorAll('span.text-danger');
            errorSpans.forEach(function (span) {
                span.innerHTML = '';
            });
            //Preparación de formulario
            $('#titulo').text("Editar Registro");
            $('#imagenTemp').val(obj.imagen);
            $('#imagen').val('');
            $('#image-preview').css('background-image', 'url(../assets/img/activos/' + obj.imagen + ')'); // Establece la imagen de fondo en el label
            $('#image-preview').css('background-size', 'cover'); // Ajusta el tamaño de la imagen de fondo
            $('#image-preview').css('background-position', 'center'); // Centra la imagen de fondo
            $('#iconContainer').hide(); // Oculta el icono 
            $('#textImage').hide();//y el texto
            $('#nombre').val(obj.nombre);
            llenarCategorias(obj.idCategoria);
            $('#descripcion').val(obj.descripcion);


            $('#precioAdquisicion').val('');
            $('#fechaAdquisicion').val(new Date().toISOString().split('T')[0]);
            instanscearTablaAdquisicion();
            $('#panelAdquisicion').hide();

            //otros
            $('#method').val('PUT'); // Cambiar a PUT
            $('#activoForm').attr('action', '/activos/' + idActivo);
            $('#modalForm').modal('show');
        }).fail(function (xhr, status, error) {
            console.log(xhr.responseJSON);
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error. Por favor, inténtelo de nuevo.'
            });
        });
}

function eliminar(idActivo) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/activos/' + idActivo);
    $('#methodC').val('Delete')
    $('#dialogo').text('Está a punto de eliminar permanentemente el registro. ¿Desea continuar?')
}

function baja(idActivo) {
    //Preparacion visual y direccion de la accion en el formulario
    $('#confirmarForm').attr('action', '/activos/baja/' + idActivo);
    $('#methodC').val('get')
    $('#dialogo').text('Está a punto de deshabilitar el registro. ¿Desea continuar?')
}

function alta(idActivo) {
    $.ajax({
        url: '/activos/alta/' + idActivo,
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
        url: '/obtener-activos',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty(); // Limpiar el tbody antes de llenarlo
            originalData = data.map(a => {
                // Lógica para los botones dependiendo del estado
                let acciones;
                if (a.estado == 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="${a.idActivo}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditar me-2">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idActivo}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar">
                            <i class="fas fa-minus-circle text-secondary"></i>
                        </a>
                    `;
                } else {
                    acciones = `
                        <a role="button" data-id="${a.idActivo}" data-bs-tt="tooltip" data-bs-original-title="Habilitar" class="btnHabilitar me-2">
                            <i class="fas fa-arrow-up text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${a.idActivo}" data-bs-tt="tooltip" data-bs-original-title="Eliminar" class="btnEliminar">
                            <i class="fas fa-trash text-secondary"></i>
                        </a>
                    `;
                }

                // Generar imagen o ícono dependiendo de si hay imagen disponible
                let imagen = a.imagen
                    ? `<img src="../assets/img/activos/${a.imagen}" class="avatar avatar-sm me-3">`
                    : `<div class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-cube opacity-10 text-sm"></i>
                        </div>`;

                // Crear fila (tr)
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', a.idActivo); 
                tr.classList.add('tr-link'); // Clase de la fila

                // Insertar contenido HTML en la fila
                tr.innerHTML = `
                    <td>
                        ${imagen}
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${a.idActivo}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${a.nombre}</p>
                        <p class="text-xxs mb-0">(${a.categoria.nombre})</p>
                    </td>
                    <td class="px-5">
                        <p class="text-xs font-weight-bold mb-0">${a.bienes.filter(b => b.estado === 1).length}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">
                            $${a.bienes.reduce((total, b) => total + parseFloat(b.precio), 0).toFixed(2)}
                        </p>
                    </td>
                    <td class="px-1 text-xs">
                        <span class="badge badge-xs opacity-7 bg-${a.estado == 1 ? 'success' : 'secondary'}">
                            ${a.estado == 1 ? 'activo' : 'inactivo'}
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
    nuevaFila.find('.sp-sucursal').attr('id', `error-sucursal\.1`).text('');
    nuevaFila.find('.sp-departamento').attr('id', `error-departamento\.1`).text('');
    nuevaFila.find('.sp-cantidad').attr('id', `error-cantidad\.1`).text('');
    $('#tableBodyDepartamentos').append(nuevaFila);
    llenarSucursales(null);
    $('#precioAdquisicion, #fechaAdquisicion, .selectSucursal, .selectDepartamento, .inputCantidad, #btnAgregarAd').prop('disabled', false);
    instanciarTooltips();
}

function verificarCampos() {
    const nombre = $('#nombre').val().trim();
    const categoria = $('#categoria').val();
    const descripcion = $('#descripcion').val().trim();
    const imagen = $('#imagen').val();

    // Verifica si todos los campos tienen valores
    if (nombre && categoria && descripcion && imagen) {
        // Habilita los campos requeridos
        $('#precioAdquisicion, #fechaAdquisicion, .selectSucursal, .selectDepartamento, .inputCantidad, #btnAgregarAd').prop('disabled', false);
    } else {
        // Deshabilita los campos si falta algún valor
        $('#precioAdquisicion, #fechaAdquisicion, .selectSucursal, .selectDepartamento, .inputCantidad, #btnAgregarAd').prop('disabled', true);
    }
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
        activo: document.getElementById('activo').value,
        tipo: document.getElementById('tipo').value,
    };

    // Realizar la solicitud AJAX con los datos del formulario
    $.ajax({
        url: "/activos/pdf", // URL de la ruta que maneja la generación del PDF
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
            console.error(error); // Mostrar error en la consola para depuración
            alert('Ocurrió un error al generar el PDF. Por favor, intente nuevamente.');
        }
    });
}
