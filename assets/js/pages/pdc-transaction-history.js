(function($) {
    'use strict';

    $(function() {
        if($('#transaction-history-table').length){
            allTransactionHistoryTable('#transaction-history-table', true);
        }

        $(document).on('click','#apply-filter',function() {
            allTransactionHistoryTable('#transaction-history-table', true);
        });
    });
})(jQuery);

function allTransactionHistoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'all transaction history table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

    var filter_reference_date_start_date = $('#filter_reference_date_start_date').val();
    var filter_reference_date_end_date = $('#filter_reference_date_end_date').val();

    var transaction_type_filter_values = [];
    var mode_of_payment_filter_values = [];

    $('.transaction-type-filter:checked').each(function() {
        transaction_type_filter_values.push($(this).val());
    });

    $('.mode-of-payment-filter:checked').each(function() {
        mode_of_payment_filter_values.push($(this).val());
    });

    var filter_transaction_type = transaction_type_filter_values.join(', ');
    var filter_mode_of_payment = mode_of_payment_filter_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PAYMENT_DETAILS' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'PAYMENT_AMOUNT' },
        { 'data' : 'MODE_OF_PAYMENT' },
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'REFERENCE_DATE' },
        { 'data' : 'TRANSACTION_BY' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 10},
        { 'width': 'auto', 'aTargets': 11}
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_pdc_management_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date, 
                'filter_reference_date_start_date' : filter_reference_date_start_date, 
                'filter_reference_date_end_date' : filter_reference_date_end_date,
                'filter_transaction_type' : filter_transaction_type,
                'filter_mode_of_payment' : filter_mode_of_payment
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
        'order': [[ 6, 'desc' ]],
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