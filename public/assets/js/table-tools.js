(function () {
    function normalize(value) {
        return value.toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function sortValue(cell) {
        if (!cell) {
            return '';
        }

        const field = cell.querySelector('input, select, textarea');
        const raw = cell.dataset.sortValue || (field ? field.value : cell.textContent.trim());
        const numeric = Number(raw.replace(/\s/g, '').replace(',', '.').replace(/[^\d.-]/g, ''));

        return Number.isFinite(numeric) && /\d/.test(raw) ? numeric : normalize(raw);
    }

    function compare(a, b, direction) {
        if (typeof a === 'number' && typeof b === 'number') {
            return (a - b) * direction;
        }

        return a.toString().localeCompare(b.toString(), 'fr') * direction;
    }

    function rowText(row) {
        const fieldValues = Array.from(row.querySelectorAll('input, select, textarea'))
            .map((field) => field.type === 'checkbox' ? (field.checked ? 'actif' : 'inactif') : field.value)
            .join(' ');

        return `${row.textContent} ${fieldValues}`;
    }

    document.querySelectorAll('table[data-table-tools]').forEach((table, index) => {
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.querySelectorAll('tr')).filter((row) => row.cells.length > 1);
        const wrapper = table.closest('.table-responsive') || table.parentElement;
        const filter = document.createElement('input');

        filter.type = 'search';
        filter.className = 'form-control table-filter mb-3';
        filter.placeholder = 'Filtrer le tableau';
        filter.setAttribute('aria-label', 'Filtrer le tableau');
        filter.id = `table-filter-${index}`;
        wrapper.parentElement.insertBefore(filter, wrapper);

        filter.addEventListener('input', () => {
            const query = normalize(filter.value);
            rows.forEach((row) => {
                row.hidden = !normalize(rowText(row)).includes(query);
            });
        });

        table.querySelectorAll('thead th').forEach((header, columnIndex) => {
            if (header.classList.contains('no-sort')) {
                return;
            }

            header.tabIndex = 0;
            header.classList.add('table-sortable');
            header.setAttribute('role', 'button');
            header.setAttribute('aria-sort', 'none');

            const sort = () => {
                const currentDirection = header.dataset.sortDirection === 'asc' ? -1 : 1;
                table.querySelectorAll('thead th').forEach((th) => {
                    th.dataset.sortDirection = '';
                    th.setAttribute('aria-sort', 'none');
                });

                header.dataset.sortDirection = currentDirection === 1 ? 'asc' : 'desc';
                header.setAttribute('aria-sort', currentDirection === 1 ? 'ascending' : 'descending');

                rows.sort((rowA, rowB) => compare(
                    sortValue(rowA.cells[columnIndex]),
                    sortValue(rowB.cells[columnIndex]),
                    currentDirection
                ));

                rows.forEach((row) => tbody.appendChild(row));
            };

            header.addEventListener('click', sort);
            header.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    sort();
                }
            });
        });
    });
})();
