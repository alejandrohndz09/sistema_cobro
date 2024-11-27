let itemsPerPage = 5; // Cambia esto al número deseado de elementos por página
const searchInput = document.getElementById('searchInput');
const tableBody = document.getElementById('tableBody');
const pagination = document.getElementById('pagination');

let currentPage = 1;
let originalData = Array.from(tableBody.children);
let currentData = [...originalData];
let sortColumnIndex = null; // Para saber cuál columna se está ordenando
let sortAscending = false; // Orden descendente por defecto

function displayData(data) {
  tableBody.innerHTML = '';
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  for (let i = startIndex; i < endIndex && i < data.length; i++) {
    tableBody.appendChild(data[i]);
  }
  instanciarTooltips();
}

function instanciarTooltips() {
  tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-tt="tooltip"]'));
  tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    tooltipTriggerEl.addEventListener('click', function () {
      var tooltipInstance = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
      tooltipInstance.hide();
    });
  });
}

function updatePagination() {
  const totalPages = Math.ceil(currentData.length / itemsPerPage);
  pagination.innerHTML = '';

   // Hacer que los th tengan cursor pointer al actualizar la paginación
   const headers = document.querySelectorAll('th');
   headers.forEach(header => {
     header.style.cursor = 'pointer';  // Asignar el cursor pointer
   });
  
  if (totalPages > 1) {
    for (let i = 1; i <= totalPages; i++) {
      const pageLink = document.createElement('a');
      pageLink.classList.add('btn', 'bg-gradient-dark', 'btn-rounded', 'me-2');
      pageLink.textContent = i;
      pageLink.addEventListener('click', () => {
        currentPage = i;
        displayData(currentData);
        updatePagination();
      });
      pagination.appendChild(pageLink);
    }
  }

  if (currentData.length === 0) {
    tableBody.innerHTML =
      '<tr><td colspan="3">No se encontraron resultados.</td></tr>';
  } else {
    displayData(currentData);
  }
}

searchInput.addEventListener('input', (event) => {
  const query = event.target.value.trim().toLowerCase();
  currentData = originalData.filter((row) => {
    const rowData = Array.from(row.getElementsByTagName('td'));
    return rowData.some((cell) =>
      cell.textContent.toLowerCase().includes(query)
    );
  });
  currentPage = 1;
  updatePagination();
});

// Función para ordenar las filas
function sortTable(columnIndex) {
  // Si la columna es la misma que la anterior, se invierte el orden
  const sortOrder = (sortColumnIndex === columnIndex && !sortAscending) ? 1 : -1;

  // Ordena las filas
  originalData.sort((rowA, rowB) => {
    const cellA = rowA.cells[columnIndex].textContent.trim();
    const cellB = rowB.cells[columnIndex].textContent.trim();

    // Comparación para ordenar (numérico o alfabético)
    const comparison = isNaN(cellA) || isNaN(cellB)
      ? cellA.localeCompare(cellB)
      : parseFloat(cellA) - parseFloat(cellB);

    return sortOrder * comparison;
  });

  // Actualiza el estado de orden y la visualización
  sortAscending = sortOrder === 1;
  sortColumnIndex = columnIndex;

  // Cambiar iconos en los encabezados
  updateSortIcons(columnIndex);

  // Re-actualiza los datos y la paginación
  currentData = [...originalData];
  updatePagination();
}

// Función para actualizar los iconos en los encabezados
function updateSortIcons(columnIndex) {
  const headers = document.querySelectorAll('th');

  headers.forEach((header, index) => {
    const icon = header.querySelector('.sort-icon');
    if (icon) {
      icon.remove(); // Eliminar iconos anteriores
    }
  });

  const selectedHeader = headers[columnIndex];
  const icon = document.createElement('span');
  icon.classList.add('sort-icon', 'ms-2');

  // Crear el icono según el orden (ascendente/descendente)
  if (sortAscending) {
    icon.innerHTML = '&#9650;'; // Flecha hacia arriba
  } else {
    icon.innerHTML = '&#9660;'; // Flecha hacia abajo
  }

  selectedHeader.appendChild(icon);
}

// Agregar eventos a los encabezados de columna para ordenar
const headers = document.querySelectorAll('th');
headers.forEach((header, index) => {
  header.addEventListener('click', () => sortTable(index));
});

// Inicialización
updatePagination();
