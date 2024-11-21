(function($) {
    'use strict';

    $(function() {
        if($('#loan-extraction-table').length){
            loanExtractionTable('#loan-extraction-table', true, true);
        }

        $(document).on('click','#apply-filter',function() {
            loanExtractionTable('#loan-extraction-table', true, true);
        });
    });
})(jQuery);

function loanExtractionTable(datatable_name, buttons = false, show_all = false){
    const type = 'loan extraction table';
    var filter_release_date_start_date = $('#filter_release_date_start_date').val();
    var filter_release_date_end_date = $('#filter_release_date_end_date').val();

    const column = [ 
        { 'data' : 'CUSTOMER_ID' },
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'APPLICATION_NUMBER' },
        { 'data' : 'LOAN_PRODUCT' },
        { 'data' : 'DISBURSED_BY' },
        { 'data' : 'OUTSTANDING_BALANCE' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'FIRST_DUE_DATE' },
        { 'data' : 'INTEREST_RATE' },
        { 'data' : 'LOAN_INTEREST_PERIOD' },
        { 'data' : 'TERM_LENGTH' },
        { 'data' : 'TERM_TYPE' },
        { 'data' : 'PAYMENT_FREQUENCY' },
        { 'data' : 'NUMBER_OF_REPAYMENTS' },
        { 'data' : 'TOTAL_DELIVERY_PRICE' },
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'RELEASE_REMARKS' },
        { 'data' : 'CREATED_BY' },
        { 'data' : 'PRODUCT_SUBCATEGORY' },
        { 'data' : 'DOWNPAYMENT' },
        { 'data' : 'INSURANCE' },
        { 'data' : 'REGISTRATION_FEE' },
        { 'data' : 'HANDLING_FEE' },
        { 'data' : 'TRANSFER_FEE' },
        { 'data' : 'DOC_STAMP_TAX' },
        { 'data' : 'TRANSACTION_FEE' },
        { 'data' : 'DEPOSIT' },
        { 'data' : 'INSURANCE_SCHEDULE' },
        { 'data' : 'REGISTRATION_FEE_SCHEDULE' },
        { 'data' : 'HANDLING_FEE_SCHEDULE' },
        { 'data' : 'TRANSFER_FEE_SCHEDULE' },
        { 'data' : 'DOC_STAMP_TAX_SCHEDULE' },
        { 'data' : 'TRANSACTION_FEE_SCHEDULE' },
        { 'data' : 'DEPOSIT_SCHEDULE' }
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
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
        { 'width': 'auto', 'aTargets': 14 },
        { 'width': 'auto', 'aTargets': 15 },
        { 'width': '10%', 'aTargets': 16 },
        { 'width': 'auto', 'aTargets': 17 },
        { 'width': 'auto', 'aTargets': 18 },
        { 'width': 'auto', 'aTargets': 19 },
        { 'width': 'auto', 'aTargets': 20 },
        { 'width': 'auto', 'aTargets': 21 },
        { 'width': 'auto', 'aTargets': 22 },
        { 'width': 'auto', 'aTargets': 23 },
        { 'width': 'auto', 'aTargets': 24 },
        { 'width': 'auto', 'aTargets': 25 },
        { 'width': 'auto', 'aTargets': 26 },
        { 'width': 'auto', 'aTargets': 27 },
        { 'width': 'auto', 'aTargets': 28 },
        { 'width': 'auto', 'aTargets': 29 },
        { 'width': 'auto', 'aTargets': 30 },
        { 'width': 'auto', 'aTargets': 31 },
        { 'width': 'auto', 'aTargets': 32 },
        { 'width': 'auto', 'aTargets': 33 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_loan_extraction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_release_date_start_date' : filter_release_date_start_date, 
                'filter_release_date_end_date' : filter_release_date_end_date, 
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