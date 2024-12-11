// Evento para ir a detalle de un registro y abrir el modal con los datos
$('#tableBody').on('click', '.tr-link', function (e) {
    if (!$(e.target).closest('a').length) {
        // Obtener el ID del producto desde la fila
        let id = $(this).data('id');

        // Realizar una solicitud AJAX para obtener los detalles del producto
        $.ajax({
            url: `/gestión-comercial/obtener-producto/${id}`, // Aquí pones la URL donde se obtienen los datos
            type: 'GET',
            success: function (response) {
                // Si la respuesta es exitosa, mostrar los datos en el modal
                $('#productoNombre').text(response.nombre);
                $('#productoDescripcion').text(response.descripcion);
                $('#productoCantidad').text(response.cantidad);
                $('#productoSubtotal').text(response.subtotal);

                // Mostrar la imagen en el modal
                $('#productoImagen').attr('src', response.imagen);

                // Mostrar el modal
                $('#productoModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseJSON);
                alert("Hubo un error al cargar los detalles del producto.");
            }
        });
    }
});
