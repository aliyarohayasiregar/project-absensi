const defaultConfig = {
    "pageLength": 25,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
    },
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excel',
            className: 'btn btn-success btn-sm',
            text: '<i class="fas fa-file-excel"></i> Excel'
        },
        {
            extend: 'pdf',
            className: 'btn btn-danger btn-sm',
            text: '<i class="fas fa-file-pdf"></i> PDF'
        },
        {
            extend: 'print',
            className: 'btn btn-info btn-sm',
            text: '<i class="fas fa-print"></i> Print'
        }
    ],
    "ordering": true,
    "responsive": true,
    "autoWidth": false,
    "searching": true
};

// Custom styling untuk buttons
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .dt-buttons {
            margin-bottom: 1rem;
        }
        .dt-button {
            margin-right: 0.5rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3em 0.8em;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }
    `;
    document.head.appendChild(style);
}); 