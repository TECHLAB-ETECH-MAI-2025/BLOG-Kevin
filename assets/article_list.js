import $ from 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// Initialisation de Datatables
$('#articles-table').DataTable({
    responsive: true,
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
    },
})