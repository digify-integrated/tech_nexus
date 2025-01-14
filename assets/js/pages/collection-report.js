(function($) {
    'use strict';

    $(function() {
        if($('#collection-report-table').length){
            collectionReportTable('#collection-report-table', true, true);
        }

        $(document).on('click','#apply-filter',function() {
            collectionReportTable('#collection-report-table', true, true);
        });
    });
})(jQuery);

function collectionReportTable(datatable_name, buttons = false, show_all = false){
    const type = 'collection report table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_payment_date_start_date = $('#filter_payment_date_start_date').val();
    var filter_payment_date_end_date = $('#filter_payment_date_end_date').val();
    var filter_payment_advice = $('.payment-advice-filter:checked').val();
    var collection_report_filter_values_company = [];

    $('.company-checkbox:checked').each(function() {
        collection_report_filter_values_company.push($(this).val());
    });

    var filter_collection_report_company = collection_report_filter_values_company.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'PAYMENT_DATE' },
        { 'data' : 'REFNO' },
        { 'data' : 'PAYMENT_AMOUNT' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'PAYMENT_DETAILS' },
        { 'data' : 'BANK_BRANCH' },
        { 'data' : 'MODE_OF_PAYMENT' },
        { 'data' : 'COLLECTION_STATUS' },
        { 'data' : 'COLLECTED_BY' }
    ];

    const column_definition = [
        { 'width': 'auto', 'type': 'date', 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_collection_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date, 
                'filter_payment_date_start_date' : filter_payment_date_start_date, 
                'filter_payment_date_end_date' : filter_payment_date_end_date, 
                'filter_collection_report_company' : filter_collection_report_company,
                'filter_payment_advice' : filter_payment_advice
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