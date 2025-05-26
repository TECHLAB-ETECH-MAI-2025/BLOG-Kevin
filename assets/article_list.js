import $ from 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

$('#article-table').DataTable({
    responsive: true,
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
    },
});
