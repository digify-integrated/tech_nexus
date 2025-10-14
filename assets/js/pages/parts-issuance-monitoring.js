(function($) {
    'use strict';

    $(function() {

        if($('#parts-issuance-monitoring-table').length){
            partsIssuanceMonitoringTable('#parts-issuance-monitoring-table');
        }
        
         if($('#parts-incoming-table').length){
            partIncomingTable('#parts-incoming-table');
        }

        if($('#parts-transaction-table').length){
            partTransactionTable('#parts-transaction-table');
        }

        $(document).on('click','#submit-parts-incoming-filter',function() {
            if($('#parts-incoming-table').length){
                partIncomingTable('#parts-incoming-table');
            }

            $('#parts-incoming-filter-offcanvas').offcanvas('hide');
        });

        $(document).on('click','#submit-parts-transaction-filter',function() {
            if($('#parts-transaction-table').length){
                partTransactionTable('#parts-transaction-table');
            }

            $('#parts-transaction-filter-offcanvas').offcanvas('hide');
        });
    });
})(jQuery);

function partIncomingTable(datatable_name, buttons = false, show_all = false){
    const product_id = $('#product-id').text();
    var view_cost = $('#view-cost').val();

    var parts_incoming_start_date = $('#parts_incoming_start_date').val();
    var parts_incoming_end_date = $('#parts_incoming_end_date').val();

    const type = 'part item table 3';
    var settings;

    if(view_cost > 0){
        var column = [ 
            { 'data' : 'REFERENCE_NUMBER' },
            { 'data' : 'PRODUCT' },
            { 'data' : 'PART' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'COST' },
            { 'data' : 'TOTAL_COST' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
            { 'width': 'auto', 'aTargets': 7 },
        ];
    }
    else{
        var column = [ 
            { 'data' : 'REFERENCE_NUMBER' },
            { 'data' : 'PRODUCT' },
            { 'data' : 'PART' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
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

function partTransactionTable(datatable_name, buttons = false, show_all = false){
    const product_id = $('#product-id').text();
    const type = 'part item table 3';
    
    var parts_transaction_start_date = $('#parts_transaction_start_date').val();
    var parts_transaction_end_date = $('#parts_transaction_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'PART_TRANSACTION_NO' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PART' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'DISCOUNT' },
        { 'data' : 'DISCOUNT_TOTAL' },
        { 'data' : 'SUBTOTAL' },
        { 'data' : 'TOTAL' }
    ];

     var column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_id' : product_id,
                'parts_transaction_start_date' : parts_transaction_start_date,
                'parts_transaction_end_date' : parts_transaction_end_date,
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

function partsIssuanceMonitoringTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts issuance monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '85%', 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_issuance_monitoring_generation.php',
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
        'order': [[ 1, 'asc' ]],
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