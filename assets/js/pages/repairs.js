(function($) {
    'use strict';

    $(function() {
        if($('#all-sales-proposal-table').length){
            allSalesProposalTable('#all-sales-proposal-table');
        }

         if($('#backjob-monitoring-table').length){
            backJobMonitoringTable('#backjob-monitoring-table');
        }
    });
})(jQuery);

function allSalesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'all sales proposal table2';

    var sale_proposal_status_filter = [];
    var product_type_filter = [];
    var company_filter = [];
    var user_filter = [];

    $('.sales-proposal-status-filter:checked').each(function() {
        sale_proposal_status_filter.push($(this).val());
    });

    $('.product-type-filter:checked').each(function() {
        product_type_filter.push($(this).val());
    });

    $('.company-filter:checked').each(function() {
        company_filter.push($(this).val());
    });

    $('.user-filter:checked').each(function() {
        user_filter.push($(this).val());
    });

    var filter_sale_proposal_status = sale_proposal_status_filter.join(', ');
    var filter_product_type = product_type_filter.join(', ');
    var filter_company = company_filter.join(', ');
    var filter_user = user_filter.join(', ');

    
    var filter_created_date_start_date = $('#filter_created_date_start_date').val();
    var filter_created_date_end_date = $('#filter_created_date_end_date').val();
    var filter_released_date_start_date = $('#filter_released_date_start_date').val();
    var filter_released_date_end_date = $('#filter_released_date_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '10%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5},
        { 'width': '15%', 'type': 'date', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_sale_proposal_status' : filter_sale_proposal_status,
                'filter_product_type' : filter_product_type,
                'filter_company' : filter_company,
                'filter_user' : filter_user,
                'filter_created_date_start_date' : filter_created_date_start_date,
                'filter_created_date_end_date' : filter_created_date_end_date,
                'filter_released_date_start_date' : filter_released_date_start_date,
                'filter_released_date_end_date' : filter_released_date_end_date,
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
        'searching': true, // Enable searching
        'search': {
            'search': '',
            'placeholder': 'Search...', // Placeholder text for the search input
            'position': 'top', // Position the search bar to the left
        },
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function backJobMonitoringTable(datatable_name, buttons = false, show_all = false){
    const type = 'backjob monitoring table2';
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'SALES_PROPOSAL' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_back_job_monitoring_generation.php',
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