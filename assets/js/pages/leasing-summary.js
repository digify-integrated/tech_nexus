(function($) {
    'use strict';

    $(function() {
        if($('#leasing-summary-table').length){
            leasingSummaryTable('#leasing-summary-table');
        }

        $(document).on('click','#apply-filter',function() {
            if($('#leasing-summary-table').length){
                leasingSummaryTable('#leasing-summary-table');
            }
        });
    });
})(jQuery);

function leasingSummaryTable(datatable_name, buttons = false, show_all = false){
    const type = 'leasing summary table';
    var leasing_application_status_filter = $('.leasing-application-status-filter:checked').val();
    var settings;

    const column = [ 
        { 'data' : 'TENANT_NAME' },
        { 'data' : 'PROPERTY_NAME' },
        { 'data' : 'FLOOR_AREA' },
        { 'data' : 'TERM' },
        { 'data' : 'INCEPTION_DATE' },
        { 'data' : 'MATURITY_DATE' },
        { 'data' : 'SECURITY_DEPOSIT' },
        { 'data' : 'ESCALATION_RATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'INITIAL_BASIC_RENTAL' },
        { 'data' : 'UNPAID_RENTAL' },
        { 'data' : 'UNPAID_ELECTRICITY' },
        { 'data' : 'UNPAID_WATER' },
        { 'data' : 'UNPAID_OTHER_CHARGES' },
        { 'data' : 'OUTSTANDING_BALANCE' }
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
        { 'width': 'auto', 'aTargets': 14 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_leasing_application_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'leasing_application_status_filter' : leasing_application_status_filter
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
        'order': [[ 0, 'asc' ]],
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
