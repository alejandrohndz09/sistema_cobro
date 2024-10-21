
const itemsPerPage = 5; // Cambia esto al número deseado de elementos por página
const searchInput = document.getElementById('searchInput');//$("#searchInput"); 
const tableBody = document.getElementById('tableBody');//$("#tableBody"); 
const pagination = document.getElementById('pagination');//$("pagination"); 

let currentPage = 1;
let originalData = Array.from(tableBody.children);
let currentData = [...originalData];

function displayData(data) {
  tableBody.innerHTML = '';
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  for (let i = startIndex; i < endIndex && i < data.length; i++) {
    tableBody.appendChild(data[i]);
  }
  tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-tt="tooltip"]'))
  tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
  // Escuchar el evento de clic en todos los botones con tooltip
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    tooltipTriggerEl.addEventListener('click', function () {
      // Ocultar el tooltip cuando se hace clic en el botón
      var tooltipInstance = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
      tooltipInstance.hide();
    });
  });
}

function updatePagination() {
  const totalPages = Math.ceil(currentData.length / itemsPerPage);
  pagination.innerHTML = '';

  if (totalPages > 1) {
    for (let i = 1; i <= totalPages; i++) {
      const pageLink = document.createElement('a');
      // pageLink.href = '#'+i;
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

// Inicialización
updatePagination();