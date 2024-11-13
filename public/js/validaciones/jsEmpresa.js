$(document).ready(function () {
    // Evento para enviar el formulario `#empresaForm`
    $('#empresaForm').submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        // Crear un objeto FormData para enviar archivos y datos
        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST', // Usa POST con FormData para simulación de PUT
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer el tipo de contenido
            success: function (response) {
                Toast.fire({
                    icon: response.type,
                    title: response.message
                });
                $('#modalFormE').modal('hide');

                // Actualiza los datos en la página sin recargar
                let idEmpresa = response.data.idEmpresa;
                $(`#empresa-nit-${idEmpresa}`).text(response.data.nit);
                $(`#empresa-nombre-${idEmpresa}`).text(response.data.nombre);

                // Actualizar la imagen sin recargar la página
                if (response.data.logo) {
                    // Actualiza el contenedor de la imagen con la nueva URL
                    $(`#image-previewI-${idEmpresa}`).css('background-image', `url(/${response.data.logo})`);
                }
            },
            error: function (xhr) {
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

    // Evento para abrir el modal de edición y cargar datos de la empresa seleccionada
    $(document).on('click', '.btnEditarE', function () {
        editarE($(this).data('id'));
    });
});

function editarE(idEmpresa) {
    $.get('/opciones/empresa/' + idEmpresa + '/editEmpresa', function (empresa) {
        // Limpia los errores previos
        const errorSpans = document.querySelectorAll('span.text-danger');
        errorSpans.forEach(span => span.innerHTML = '');

        // Preparación de formulario
        $('#titulo').text("Editar Registro");
        $('#imagenTemp').val(empresa.logo);
        $('#logo').val('');

        // Verifica si existe un logo antes de intentar cargarlo
        if (empresa.logo) {
            $('#image-preview').css('background-image', 'url(/' + empresa.logo + ')');
            $('#image-preview').css('background-size', 'cover');
            $('#image-preview').css('background-position', 'center');
            $('#iconContainer').hide();
            $('#textImage').hide();
        } else {
            $('#image-preview').css('background-image', 'none');
            $('#iconContainer').show();
            $('#textImage').show();
        }

        $('#nit').val(empresa.nit);
        $('#nombre').val(empresa.nombre);
        $('#method').val('PUT');
        $('#empresaForm').attr('action', '/opciones/empresa/' + idEmpresa + '/updateEmpresa');
        $('#modalFormE').modal('show');
    });
}


function mostrarDatosEmpresa() {
    $.ajax({
        url: '/obtener-empresa', // Asegúrate de que esta ruta devuelva los datos de las empresas
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#tableBody').empty();

            originalData = data.map(c => {
                let acciones;
                if (c.estado === 1) {
                    acciones = `
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalFormE" data-id="${c.idEmpresa}" data-bs-tt="tooltip" data-bs-original-title="Editar" class="btnEditarE me-3">
                            <i class="fas fa-pen text-secondary"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id="${c.idEmpresa}" data-bs-tt="tooltip" data-bs-original-title="Deshabilitar" class="btnDeshabilitar me-3">
                            <i class="fas fa-minus-circle text-secondary"></i>
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
                        <p class="text-xs font-weight-bold mb-0">${c.idEmpresa}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.nit}</p>
                    </td>
                    <td class="px-1">
                        <p class="text-xs font-weight-bold mb-0">${c.nombre}</p>
                    </td>
                    <td class="px-1 text-sm">
                        <span class="badge badge-xs opacity-7 bg-${c.estado === 1 ? 'success' : 'secondary'}">
                            ${c.estado === 1 ? 'activo' : 'inactivo'}
                        </span>
                    </td>
                    <td>
                        ${acciones}
                    </td>
                `;
                return tr;
            });

            currentData = [...originalData];
            updatePagination();
        },
        error: function (xhr) {
            Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error al cargar las empresas.'
            });
        }
    });
}

// Mostrar la imagen cargada al seleccionarla
document.getElementById('logo').addEventListener('change', function(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('image-preview').style.backgroundImage = `url(${reader.result})`;
        document.getElementById('iconContainer').style.display = 'none'; // Oculta el ícono de cámara
    };
    reader.readAsDataURL(event.target.files[0]);
});
