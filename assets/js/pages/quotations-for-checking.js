(function($) {
    'use strict';

    $(function() {
        if($('#quotations-for-checking-table').length){
            quotationsForCheckingTable('#quotations-for-checking-table');
        }

        $(document).on('click','#submit-filter',function() {
            if($('#quotations-for-checking-table').length){
                quotationsForCheckingTable('#quotations-for-checking-table');
            }

            $('#filter-offcanvas').offcanvas('hide');
        });

        $(document).on('click', '.for-approval-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'For Approval Quotation',
                text: 'Are you sure you want to tag this quotation for approval?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'For Approval',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'for approval quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged for approval.',
                                    'success'
                                );

                                reloadDatatable('#quotations-for-checking-table');
                            }
                            else{

                                showNotification(
                                    'Error',
                                    response.message,
                                    'danger'
                                );
                            }
                        },

                        error: function(){
                            showNotification(
                                'Error',
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.approve-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'Approve Quotation',
                text: 'Are you sure you want to tag this quotation as approved?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'approve quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged as approved.',
                                    'success'
                                );

                                reloadDatatable('#quotations-for-checking-table');
                            }
                            else{

                                showNotification(
                                    'Error',
                                    response.message,
                                    'danger'
                                );
                            }
                        },

                        error: function(){

                            showNotification(
                                'Error',
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });
    });
})(jQuery);

function quotationsForCheckingTable(datatable_name, buttons = false, show_all = false){
    const type = 'quotations for checking table';

     var quotations_status_filter = [];

    $('.quotations-status-checkbox:checked').each(function() {
        quotations_status_filter.push($(this).val());
    });

    var filter_quotations_status = quotations_status_filter.join(', ');

    var settings;

    const column = [ 
        { 'data' : 'REFERENCE_NO' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PRICE' },
        { 'data' : 'STATUS' },
        { 'data' : 'COMPANY' },
        { 'data' : 'CREATED_BY' },
        { 'data' : 'EXPIRATION_DATE' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '5%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_quotations_status' : filter_quotations_status,
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