$(document).ready(function() {
    $('#datatables_table').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(10)'
        },
        
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'print'
        ]
    } );

    $(document).ready(function() {
        $('.buttons-print').each(function() {
          $(this).removeClass('btn-secondary').addClass('btn-primary bg-blue-600')
        })
    })
} );