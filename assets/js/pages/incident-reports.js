(function($) {
    'use strict';

    $(function() {
        if($('#incident-reports-table').length){
            incidentReportTable('#incident-reports-table');
        }
    });
})(jQuery);

function incidentReportTable(datatable_name, buttons = false, show_all = false){
    const type = 'incident reports table';
    var settings;

    const column = [ 
        { 'data' : 'EMPLOYEE' },
        { 'data' : 'DOCUMENT' },
        { 'data' : 'UPLOAD_DATE' }
    ];

    const column_definition = [
        { 'width': '50%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '30%', 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_incident_reports_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 2, 'desc' ]],
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