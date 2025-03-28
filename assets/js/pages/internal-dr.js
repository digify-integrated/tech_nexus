(function($) {
    'use strict';

    $(function() {
        if($('#internal-dr-table').length){
            internalDRTable('#internal-dr-table');
        }

        if($('#job-order-progress-table').length){
            jobOrderProgress('#job-order-progress-table');
        }

        if($('#additional-job-order-progress-table').length){
            additionalJobOrderProgress('#additional-job-order-progress-table');
        }

        if($('#internal-dr-form').length){
            internalDRForm();
        }

        if($('#job-order-form').length){
            jobOrderForm();
        }

        if($('#internal-dr-job-order-progress-form').length){
            internalDRJobOrderProgressForm();
        }

        if($('#internal-dr-additional-job-order-progress-form').length){
            internalDRAdditionalJobOrderProgressForm();
        }

        if($('#internal-dr-id').length){
            displayDetails('get internal DR details');
        }

        if($('#internal-dr-tag-as-released-form').length){
            internalDRReleaseForm();
        }

        if($('#internal-dr-tag-as-cancelled-form').length){
            internalDRCancelForm();
        }

        if($('#internal-dr-unit-image-form').length){
            internalDRUnitImageForm();
        }

        if($('#internal-dr-outgoing-checklist-form').length){
            internalDROutgoingChecklistForm();
        }

        if($('#internal-dr-quality-control-form-form').length){
            internalDRQualityControlFormForm();
        }

        if($('#internal_dr_status').val() == 'Released'){
            disableFormAndSelect2('internal-dr-form');
        }

        $(document).on('click','#internal-dr-tab-1',function() {
            $('#gatepass-print-button').addClass('d-none');
        });

        $(document).on('click','#internal-dr-tab-2',function() {
            $('#gatepass-print-button').addClass('d-none');
        });

        $(document).on('click','#internal-dr-tab-3',function() {
            $('#gatepass-print-button').removeClass('d-none');
        });

        $(document).on('click','.delete-internal-dr',function() {
            const internal_dr_id = $(this).data('internal-dr-id');
            const transaction = 'delete internal DR';
    
            Swal.fire({
                title: 'Confirm Internal DR Deletion',
                text: 'Are you sure you want to delete this Internal DR?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_id : internal_dr_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Internal DR Success', 'The Internal DR has been deleted successfully.', 'success');
                                reloadDatatable('#internal-dr-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Internal DR Error', 'The Internal DR does not exist.', 'danger');
                                    reloadDatatable('#internal-dr-table');
                                }
                                else {
                                    showNotification('Delete Internal DR Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#delete-internal-dr',function() {
            let internal_dr_id = [];
            const transaction = 'delete multiple internal DR';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    internal_dr_id.push(element.value);
                }
            });
    
            if(internal_dr_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Internal DRs Deletion',
                    text: 'Are you sure you want to delete these Internal DRs?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-danger mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/internal-dr-controller.php',
                            dataType: 'json',
                            data: {
                                internal_dr_id: internal_dr_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Internal DR Success', 'The selected Internal DRs have been deleted successfully.', 'success');
                                        reloadDatatable('#internal-dr-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Internal DR Error', response.message, 'danger');
                                    }
                                }
                            },
                            error: function(xhr, status, error) {
                                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                                if (xhr.responseText) {
                                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                                }
                                showErrorDialog(fullErrorMessage);
                            },
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Deletion Multiple Internal DR Error', 'Please select the Internal DRs you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-internal-dr-details',function() {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'delete internal DR';
    
            Swal.fire({
                title: 'Confirm Internal DR Deletion',
                text: 'Are you sure you want to delete this Internal DR?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_id : internal_dr_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Internal DR Success', 'The Internal DR has been deleted successfully.', 'success');
                                window.location = 'internal-dr.php';
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Internal DR Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#tag-as-on-process',function() {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'tag as on process';
    
            Swal.fire({
                title: 'Confirm Tagging Internal DR On Process',
                text: 'Are you sure you want to tag this Internal DR on process?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'On Process',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_id : internal_dr_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tagging Internal DR On Process Success', 'The Internal DR has been tagged successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.noJobOrder) {
                                    showNotification('UsTagging Internal DR On Process Error', 'There is no job order loaded.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tagging Internal DR On Process Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });
        
        $(document).on('click','#tag-as-ready-for-release',function() {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'tag as ready for release';
    
            Swal.fire({
                title: 'Confirm Tagging Internal DR Ready For Release',
                text: 'Are you sure you want to tag this Internal DR ready for release?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Ready For Release',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_id : internal_dr_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tagging Internal DR Ready For Release Success', 'The Internal DR has been tagged successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.jobOrderUnfinished) {
                                    setNotification('Tagging Internal DR Ready For Release Error', 'There are still on going job order.', 'danger');
                                }
                                else if (response.imageNotUploaded) {
                                    setNotification('Tagging Internal DR Ready For Release Error', 'There are outgoing checklist or the quality control form has not been uploaded yet.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tagging Internal DR Ready For Release Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });
        
        $(document).on('click','#tag-as-for-dr',function() {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'tag as for dr';
    
            Swal.fire({
                title: 'Confirm Tagging Internal DR For DR',
                text: 'Are you sure you want to tag this Internal DR for DR?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For DR',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_id : internal_dr_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tagging Internal DR For DR Success', 'The Internal DR has been tagged successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tagging Internal DR For DR Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','.update-internal-dr-job-order',function() {
            const internal_dr_job_order_id = $(this).data('internal-dr-job-order-id');
    
            sessionStorage.setItem('internal_dr_job_order_id', internal_dr_job_order_id);
            
            displayDetails('get internal dr job order details');
        });

        $(document).on('click','.update-internal-dr-additional-job-order',function() {
            const internal_dr_job_order_id = $(this).data('internal-dr-job-order-id');
    
            sessionStorage.setItem('internal_dr_job_order_id', internal_dr_job_order_id);
            
            displayDetails('get internal dr additional job order details');
        });

        $(document).on('click','.delete-internal-dr-job-order',function() {
            const internal_dr_job_order_id = $(this).data('internal-dr-job-order-id');
            const transaction = 'delete internal dr job order';
    
            Swal.fire({
                title: 'Confirm Job Order Deletion',
                text: 'Are you sure you want to delete this job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_job_order_id : internal_dr_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Order Success', 'The job order has been deleted successfully.', 'success');
                                reloadDatatable('#job-order-progress-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','.delete-internal-dr-additional-job-order',function() {
            const internal_dr_additional_job_order_id = $(this).data('internal-dr-additional-job-order-id');
            const transaction = 'delete internal dr additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Deletion',
                text: 'Are you sure you want to delete this additional job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/internal-dr-controller.php',
                        dataType: 'json',
                        data: {
                            internal_dr_additional_job_order_id : internal_dr_additional_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Additional Job Order Success', 'The additional job order has been deleted successfully.', 'success');
                                
                                reloadDatatable('#additional-job-order-progress-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Additional Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });
    });
})(jQuery);

function internalDRTable(datatable_name, buttons = false, show_all = false){
    const type = 'internal DR table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'RELEASE_TO' },
        { 'data' : 'DR_TYPE' },
        { 'data' : 'DR_NUMBER' },
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'DR_STATUS' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_internal_dr_generation.php',
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
        'order': [[ 6, 'desc' ]],
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

function internalDRJobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'internal DR table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'RELEASE_TO' },
        { 'data' : 'DR_TYPE' },
        { 'data' : 'DR_NUMBER' },
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'DR_STATUS' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_internal_dr_generation.php',
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
        'order': [[ 6, 'desc' ]],
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

function internalDRAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'internal DR table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'RELEASE_TO' },
        { 'data' : 'DR_TYPE' },
        { 'data' : 'DR_NUMBER' },
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'DR_STATUS' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_internal_dr_generation.php',
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
        'order': [[ 6, 'desc' ]],
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

function jobOrderProgress(datatable_name, buttons = false, show_all = false){
    const internal_dr_id = $('#internal-dr-id').text();
    const type = 'job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_internal_dr_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'internal_dr_id' : internal_dr_id},
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
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function additionalJobOrderProgress(datatable_name, buttons = false, show_all = false){
    const internal_dr_id = $('#internal-dr-id').text();
    const type = 'additional job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER_NUMBER' },
        { 'data' : 'JOB_ORDER_DATE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_internal_dr_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'internal_dr_id' : internal_dr_id},
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
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function internalDRForm(){
    $('#internal-dr-form').validate({
        rules: {
            release_to: {
                required: true
            },
            release_mobile: {
                required: true
            },
            release_address: {
                required: true
            },
            dr_number: {
                required: true
            },
            dr_type: {
                required: true
            },
            product_description: {
                required: true
            },
        },
        messages: {
            release_to: {
                required: 'Please enter the release to'
            },
            release_mobile: {
                required: 'Please enter the release to mobile'
            },
            release_address: {
                required: 'Please enter the release to address'
            },
            dr_number: {
                required: 'Please enter the DR number'
            },
            dr_type: {
                required: 'Please enter the DR type'
            },
            product_description: {
                required: 'Please enter the product description'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'save internal DR';
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&internal_dr_id=' + internal_dr_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Internal DR Success' : 'Update Internal DR Success';
                        const notificationDescription = response.insertRecord ? 'The Internal DR has been inserted successfully.' : 'The Internal DR has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'internal-dr.php?id=' + response.internalDRID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function jobOrderForm(){
    $('#job-order-form').validate({
        rules: {
            sales_proposal_id: {
                required: true
            },
        },
        messages: {
            sales_proposal_id: {
                required: 'Please select the sales proposal'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'load job order';
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&internal_dr_id=' + internal_dr_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Load Job Order Success';
                        const notificationDescription = 'The job order and additional job backjob has been loaded successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        if($('#job-order-progress-table').length){
                            jobOrderProgress('#job-order-progress-table');
                        }
                
                        if($('#additional-job-order-progress-table').length){
                            additionalJobOrderProgress('#additional-job-order-progress-table');
                        }
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function internalDRReleaseForm(){
    $('#internal-dr-tag-as-released-form').validate({
        rules: {
            release_remarks: {
                required: true
            },
        },
        messages: {
            release_remarks: {
                required: 'Please eneter the release remarks'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'tag for release';
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&internal_dr_id=' + internal_dr_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-internal-dr-tag-as-released');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-internal-dr-tag-as-released', 'Submit');
                    displayDetails('get internal DR details');
                }
            });
        
            return false;
        }
    });
}

function internalDRCancelForm(){
    $('#internal-dr-tag-as-cancelled-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please eneter the cancellation remarks'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'tag for cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&internal_dr_id=' + internal_dr_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-internal-dr-tag-as-cancelled');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-internal-dr-tag-as-cancelled', 'Submit');
                    displayDetails('get internal DR details');
                }
            });
        
            return false;
        }
    });
}

function internalDRUnitImageForm(){
    $('#internal-dr-unit-image-form').validate({
        rules: {
            unit_image_image: {
                required: true
            },
        },
        messages: {
            unit_image_image: {
                required: 'Please choose the unit image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'save internal dr unit image';
    
            var formData = new FormData(form);
            formData.append('internal_dr_id', internal_dr_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-internal-dr-unit-image');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Unit Image Upload Success', 'The unit image has been uploaded successfully', 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-internal-dr-unit-image', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function internalDROutgoingChecklistForm(){
    $('#internal-dr-outgoing-checklist-form').validate({
        rules: {
            outgoing_checklist_image: {
                required: true
            },
        },
        messages: {
            outgoing_checklist_image: {
                required: 'Please choose the outgoing checklist image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'save internal dr outgoing checklist';
    
            var formData = new FormData(form);
            formData.append('internal_dr_id', internal_dr_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-internal-dr-outgoing-checklist');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Outgoing Checklist Upload Success', 'The outgoing checklist has been uploaded successfully', 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-internal-dr-outgoing-checklist', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function internalDRQualityControlFormForm(){
    $('#internal-dr-quality-control-form-form').validate({
        rules: {
            quality_control_form: {
                required: true
            },
        },
        messages: {
            quality_control_form: {
                required: 'Please choose the quality control form'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const internal_dr_id = $('#internal-dr-id').text();
            const transaction = 'save internal dr quality control form';
    
            var formData = new FormData(form);
            formData.append('internal_dr_id', internal_dr_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-internal-dr-quality-control-form');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Quality Control Form Upload Success', 'The quality control form has been uploaded successfully', 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-internal-dr-quality-control-form', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function internalDRJobOrderProgressForm(){
    $('#job-order-progress-form').validate({
        rules: {
            job_order_progress: {
                required: true
            },
        },
        messages: {
            job_order_progress: {
                required: 'Please enter the progress'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const transaction = 'save internal dr progress job order';
            const internal_dr_id = $('#internal-dr-id').text();
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&internal_dr_id=' + internal_dr_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-job-order-progress');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Job Order Progress Success';
                        const notificationDescription = 'The job order progress has been updated successfully';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#job-order-progress-table');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-job-order-progress', 'Save');
                    
                    $('#job-order-monitoring-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function internalDRAdditionalJobOrderProgressForm(){
    $('#additional-job-order-progress-form').validate({
        rules: {
            additional_job_order_progress: {
                required: true
            },
        },
        messages: {
            additional_job_order_progress: {
                required: 'Please enter the progress'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const transaction = 'save internal dr progress additional job order';
            const internal_dr_id = $('#internal-dr-id').text();
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&internal_dr_id=' + internal_dr_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-additional-job-order-progress');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Additional Job Order Progress Success';
                        const notificationDescription = 'The additional job order progress has been updated successfully';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#additional-job-order-progress-table');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-additional-job-order-progress', 'Save');
                    $('#additional-job-order-monitoring-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get internal DR details':
            const internal_dr_id = $('#internal-dr-id').text();
            
            $.ajax({
                url: 'controller/internal-dr-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    internal_dr_id : internal_dr_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('internal-dr-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#internal_dr_id').val(internal_dr_id);
                        $('#release_to').val(response.releaseTo);
                        $('#release_mobile').val(response.releaseMobile);
                        $('#release_address').val(response.releaseAddress);
                        $('#dr_number').val(response.drNumber);
                        $('#stock_number').val(response.stockNumber);
                        $('#engine_number').val(response.engineNumber);
                        $('#chassis_number').val(response.chassisNumber);
                        $('#plate_number').val(response.plateNumber);
                        $('#product_description').val(response.productDescription);

                        checkOptionExist('#dr_type', response.drType, '');

                        if($('#unit-image').length){
                            document.getElementById('unit-image').src = response.unitImage;
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Internal DR Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
            case 'get internal dr job order details':
                var internal_dr_job_order_id = sessionStorage.getItem('internal_dr_job_order_id');
                    
                $.ajax({
                    url: 'controller/internal-dr-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        internal_dr_job_order_id : internal_dr_job_order_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {                        
                            $('#internal_dr_job_order_id').val(internal_dr_job_order_id);
                            $('#job_order_progress').val(response.progress);
    
                            checkOptionExist('#job_order_contractor_id', response.contractorID, '');
                            checkOptionExist('#job_order_work_center_id', response.workCenterID, '');
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Internal DR Job Order Details Error', response.message, 'danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                        if (xhr.responseText) {
                            fullErrorMessage += ', Response: ${xhr.responseText}';
                        }
                        showErrorDialog(fullErrorMessage);
                    }
                });
                break;
            case 'get internal dr additional job order details':
                var internal_dr_additional_job_order_id = sessionStorage.getItem('internal_dr_additional_job_order_id');
                
                $.ajax({
                    url: 'controller/internal-dr-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        internal_dr_additional_job_order_id : internal_dr_additional_job_order_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            
                            $('#internal_dr_additional_job_order_id').val(internal_dr_additional_job_order_id);
                            $('#additional_job_order_progress').val(response.progress);
    
                            checkOptionExist('#additional_job_order_contractor_id', response.contractorID, '');
                            checkOptionExist('#additional_job_order_work_center_id', response.workCenterID, '');
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Internal DR Additional Job Order Details Error', response.message, 'danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                        if (xhr.responseText) {
                            fullErrorMessage += ', Response: ${xhr.responseText}';
                        }
                        showErrorDialog(fullErrorMessage);
                    }
                });
                break;
    }
}

function disableFormAndSelect2(formId) {
    // Disable all form elements
    var form = document.getElementById(formId);
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }

    // Disable Select2 dropdowns
    var select2Dropdowns = form.getElementsByClassName('select2');
    for (var j = 0; j < select2Dropdowns.length; j++) {
        var select2Instance = $(select2Dropdowns[j]);
        select2Instance.select2('destroy');
        select2Instance.prop('disabled', true);
    }
}