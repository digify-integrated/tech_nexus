(function($) {
    'use strict';

    $(function() {
        if($('#released-report-table').length){
            releasedReportTable('#released-report-table');
        }

        

        $(document).on('click','#apply-filter',function() {
            releasedReportTable('#released-report-table');
        });
    });
})(jQuery);

function releasedReportTable(datatable_name) {

    const type = 'released report table';
    
    const filter_release_start_date = $('#filter_release_start_date').val();
    const filter_release_end_date = $('#filter_release_end_date').val();

    var company_filter_values = [];
    var product_type_filter_values = [];

    $('.company-filter:checked').each(function() {
        company_filter_values.push($(this).val());
    });

    $('.product-type-filter:checked').each(function() {
        product_type_filter_values.push($(this).val());
    });

    const column = [
        { data: 'CUSTOMER' },
        { data: 'DR_NO' },
        { data: 'DR_DATE' },
        { data: 'STOCK_NO' },
        { data: 'ENGINE' },
        { data: 'CHASSIS' },
        { data: 'PLATE_NO' },
        { data: 'DESCRIPTION' },
        { data: 'SRP' },
        { data: 'DISCOUNT' },
        { data: 'ADD_ON' },
        { data: 'NET' },
    ];

    const column_definition = [
        { 'type': 'date', 'aTargets': 2 },
    ];

    destroyDatatable(datatable_name);

    const table = $(datatable_name).DataTable({
        ajax: {
            url: 'view/_sales_proposal_generation.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: type,
                company_filter: company_filter_values,         // Passed as array
                product_type_filter: product_type_filter_values, // Passed as array
                filter_release_start_date: filter_release_start_date,
                filter_release_end_date: filter_release_end_date,
            },
            dataSrc: '',
            error: function (xhr, status, error) {
                let fullErrorMessage = `XHR status: ${status}, Error: ${error}`;

                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }

                showErrorDialog(fullErrorMessage);
            }
        },

        order: [[2, 'desc']],
        columns: column,
        columnDefs: column_definition,

        dom: 'flrtip',

        language: {
            emptyTable: 'No data found',
            searchPlaceholder: 'Search...',
            search: '',
            loadingRecords: 'Just a moment while we fetch your data...'
        },

        buttons: [
            { extend: 'csvHtml5' },
        ]
    });

    $('#export-csv').off('click').on('click', function () {
        table.button(0).trigger();
    });
}