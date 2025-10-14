(function($) {
    'use strict';

    $(function() {

        if($('#parts-incoming-table').length){
            partIncomingTable('#parts-incoming-table', true);
        }

        $(document).on('click','#submit-parts-incoming-filter',function() {
            if($('#parts-incoming-table').length){
                partIncomingTable('#parts-incoming-table', true);
            }

            $('#parts-incoming-filter-offcanvas').offcanvas('hide');
        });
    });
})(jQuery);

function partIncomingTable(datatable_name, buttons = false, show_all = false){
    const product_id = $('#product-id').text();
    var view_cost = $('#view-cost').val();

    var parts_incoming_start_date = $('#parts_incoming_start_date').val();
    var parts_incoming_end_date = $('#parts_incoming_end_date').val();

    const type = 'part item table 4';
    var settings;

    if(view_cost > 0){
        var column = [ 
            { 'data' : 'REFERENCE_NUMBER' },
            { 'data' : 'RR_DATE' },
            { 'data' : 'SUPPLIER' },
            { 'data' : 'REF_NO' },
            { 'data' : 'PURCHASE_DATE' },
            { 'data' : 'COMPANY' },
            { 'data' : 'PRODUCT' },
            { 'data' : 'PART' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'UOM' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'UOM' },
            { 'data' : 'COST' },
            { 'data' : 'TOTAL_COST' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'type' : 'date', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'type' : 'date', 'aTargets': 4 },
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
        ];
    }
    else{
        var column = [ 
            { 'data' : 'REFERENCE_NUMBER' },
            { 'data' : 'RR_DATE' },
            { 'data' : 'SUPPLIER' },
            { 'data' : 'REF_NO' },
            { 'data' : 'PURCHASE_DATE' },
            { 'data' : 'COMPANY' },
            { 'data' : 'PRODUCT' },
            { 'data' : 'PART' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'type' : 'date', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'type' : 'date', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
            { 'width': 'auto', 'aTargets': 7 },
            { 'width': 'auto', 'aTargets': 8 },
            { 'width': 'auto', 'aTargets': 9 },
            { 'width': 'auto', 'aTargets': 10 },
            { 'width': 'auto', 'aTargets': 11 },
        ];
    }

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_id' : product_id,
                'parts_incoming_start_date' : parts_incoming_start_date,
                'parts_incoming_end_date' : parts_incoming_end_date,
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