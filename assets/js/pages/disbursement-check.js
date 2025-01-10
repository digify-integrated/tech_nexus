(function($) {
    'use strict';

    $(function() {
        if($('#disbursement-check-table').length){
            disbursementTable('#disbursement-check-table');
        }

        $(document).on('click','#apply-filter',function() {
            disbursementTable('#disbursement-check-table');
        });
    });
})(jQuery);

function disbursementTable(datatable_name, buttons = false, show_all = false){
    const type = 'disbursement check monitoring table';
    var filter_check_date_start_date = $('#filter_check_date_start_date').val();
    var filter_check_date_end_date = $('#filter_check_date_end_date').val();

    var settings;

    const column = [
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'COMPANY_NAME' },
        { 'data' : 'TRANSACTION_NUMBER' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'CHECK_AMOUNT' },
        { 'data' : 'CHECK_STATUS' }
    ];

    const column_definition = [
        { 'width': 'auto', 'type': 'date', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_check_date_start_date' : filter_check_date_start_date, 
                'filter_check_date_end_date' : filter_check_date_end_date
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}