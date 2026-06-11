$(document).ready(function () {
    $('#tabela').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },
        pageLength: 5,
        order: [[0, 'asc']],
    });
});