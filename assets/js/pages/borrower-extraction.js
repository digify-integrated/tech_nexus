(function($) {
    'use strict';

    $(function() {
        if($('#borrower-extraction-table').length){
            borrowerExtractionTable('#borrower-extraction-table', true, true);
        }

        $(document).on('click','#apply-filter',function() {
            borrowerExtractionTable('#borrower-extraction-table', true, true);
        });
    });
})(jQuery);

function borrowerExtractionTable(datatable_name, buttons = false, show_all = false){
    const type = 'borrower extraction table';
    var filter_release_date_start_date = $('#filter_release_date_start_date').val();
    var filter_release_date_end_date = $('#filter_release_date_end_date').val();

    const column = [ 
        { 'data' : 'CUSTOMER_ID' },
        { 'data' : 'FIRST_NAME' },
        { 'data' : 'MIDDLE_NAME' },
        { 'data' : 'BIRTHDAY' },
        { 'data' : 'ADDRESS' },
        { 'data' : 'CITY' },
        { 'data' : 'STATE' },
        { 'data' : 'MOBILE' },
        { 'data' : 'EMAIL' },
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
        { 'width': 'auto', 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_borrower_extraction_generation.php',
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