(function($) {
    'use strict';

    $(function() {
        if($('#cmap-report-type-table').length){
            cmapReportTypeTable('#cmap-report-type-table');
        }

        if($('#cmap-report-type-form').length){
            cmapReportTypeForm();
        }

        if($('#cmap-report-type-id').length){
            displayDetails('get cmap report type details');
        }

        $(document).on('click','.delete-cmap-report-type',function() {
            const cmap_report_type_id = $(this).data('cmap-report-type-id');
            const transaction = 'delete cmap report type';
    
            Swal.fire({
                title: 'Confirm CMAP Report Type Deletion',
                text: 'Are you sure you want to delete this cmap report type?',
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
                        url: 'controller/cmap-report-type-controller.php',
                        dataType: 'json',
                        data: {
                            cmap_report_type_id : cmap_report_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete CMAP Report Type Success', 'The cmap report type has been deleted successfully.', 'success');
                                reloadDatatable('#cmap-report-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete CMAP Report Type Error', 'The cmap report type does not exist.', 'danger');
                                    reloadDatatable('#cmap-report-type-table');
                                }
                                else {
                                    showNotification('Delete CMAP Report Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-cmap-report-type',function() {
            let cmap_report_type_id = [];
            const transaction = 'delete multiple cmap report type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    cmap_report_type_id.push(element.value);
                }
            });
    
            if(cmap_report_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple CMAP Report Types Deletion',
                    text: 'Are you sure you want to delete these cmap report types?',
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
                            url: 'controller/cmap-report-type-controller.php',
                            dataType: 'json',
                            data: {
                                cmap_report_type_id: cmap_report_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete CMAP Report Type Success', 'The selected cmap report types have been deleted successfully.', 'success');
                                    reloadDatatable('#cmap-report-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete CMAP Report Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple CMAP Report Type Error', 'Please select the cmap report types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-cmap-report-type-details',function() {
            const cmap_report_type_id = $('#cmap-report-type-id').text();
            const transaction = 'delete cmap report type';
    
            Swal.fire({
                title: 'Confirm CMAP Report Type Deletion',
                text: 'Are you sure you want to delete this cmap report type?',
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
                        url: 'controller/cmap-report-type-controller.php',
                        dataType: 'json',
                        data: {
                            cmap_report_type_id : cmap_report_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted CMAP Report Type Success', 'The cmap report type has been deleted successfully.', 'success');
                                window.location = 'cmap-report-type.php';
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
                                    showNotification('Delete CMAP Report Type Error', response.message, 'danger');
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

        $(document).on('click','#discard-create',function() {
            discardCreate('cmap-report-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get cmap report type details');

            enableForm();
        });

        $(document).on('click','#duplicate-cmap-report-type',function() {
            const cmap_report_type_id = $('#cmap-report-type-id').text();
            const transaction = 'duplicate cmap report type';
    
            Swal.fire({
                title: 'Confirm CMAP Report Type Duplication',
                text: 'Are you sure you want to duplicate this cmap report type?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Duplicate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/cmap-report-type-controller.php',
                        dataType: 'json',
                        data: {
                            cmap_report_type_id : cmap_report_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate CMAP Report Type Success', 'The cmap report type has been duplicated successfully.', 'success');
                                window.location = 'cmap-report-type.php?id=' + response.cmapReportTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate CMAP Report Type Error', 'The cmap report type does not exist.', 'danger');
                                    reloadDatatable('#cmap-report-type-table');
                                }
                                else {
                                    showNotification('Duplicate CMAP Report Type Error', response.message, 'danger');
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

function cmapReportTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'cmap report type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CMAP_REPORT_TYPE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '84%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_cmap_report_type_generation.php',
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

function cmapReportTypeForm(){
    $('#cmap-report-type-form').validate({
        rules: {
            cmap_report_type_name: {
                required: true
            },
        },
        messages: {
            cmap_report_type_name: {
                required: 'Please enter the cmap report type name'
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
            const cmap_report_type_id = $('#cmap-report-type-id').text();
            const transaction = 'save cmap report type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/cmap-report-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&cmap_report_type_id=' + cmap_report_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert CMAP Report Type Success' : 'Update CMAP Report Type Success';
                        const notificationDescription = response.insertRecord ? 'The cmap report type has been inserted successfully.' : 'The cmap report type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'cmap-report-type.php?id=' + response.cmapReportTypeID;
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get cmap report type details':
            const cmap_report_type_id = $('#cmap-report-type-id').text();
            
            $.ajax({
                url: 'controller/cmap-report-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    cmap_report_type_id : cmap_report_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('cmap-report-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#cmap_report_type_id').val(cmap_report_type_id);
                        $('#cmap_report_type_name').val(response.cmapReportTypeName);

                        $('#cmap_report_type_name_label').text(response.cmapReportTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CMAP Report Type Details Error', response.message, 'danger');
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
    }
}