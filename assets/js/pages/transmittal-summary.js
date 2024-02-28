(function($) {
    'use strict';

    $(function() {
        if($('#transmittal-table').length){
            transmittalTable('#transmittal-table');
        }

        $(document).on('click','#apply-filter',function() {
            transmittalTable('#transmittal-table');
        });
    });
})(jQuery);

function transmittalTable(datatable_name, buttons = false, show_all = false){
    const type = 'transmittal summary table';
    var filter_transmittal_date_start_date = $('#filter_transmittal_date_start_date').val();
    var filter_transmittal_date_end_date = $('#filter_transmittal_date_end_date').val();
    var transmittal_status_filter_values = [];
    var settings;

    $('.transmittal-status-filter:checked').each(function() {
        transmittal_status_filter_values.push("'" + $(this).val() + "'");
    });

    var transmittal_status_filter = transmittal_status_filter_values.join(', ');

    const column = [ 
        { 'data' : 'TRANSMITTAL_DESCRIPTION' },
        { 'data' : 'TRANSMITTED_FROM' },
        { 'data' : 'TRANSMITTED_TO' },
        { 'data' : 'TRANSMITTAL_DATE' },
        { 'data' : 'TIME_ELAPSED' },
        { 'data' : 'STATUS' }
    ];

    const column_definition = [
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_transmittal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_transmittal_date_start_date' : filter_transmittal_date_start_date, 'filter_transmittal_date_end_date' : filter_transmittal_date_end_date, 'transmittal_status_filter' : transmittal_status_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
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