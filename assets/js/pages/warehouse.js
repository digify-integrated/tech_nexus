(function($) {
    'use strict';

    $(function() {
        if($('#warehouse-table').length){
            warehouseTable('#warehouse-table');
        }

        if($('#warehouse-form').length){
            warehouseForm();
        }

        if($('#warehouse-id').length){
            displayDetails('get warehouse details');
        }

        $(document).on('click','.delete-warehouse',function() {
            const warehouse_id = $(this).data('warehouse-id');
            const transaction = 'delete warehouse';
    
            Swal.fire({
                title: 'Confirm Warehouse Deletion',
                text: 'Are you sure you want to delete this warehouse?',
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
                        url: 'controller/warehouse-controller.php',
                        dataType: 'json',
                        data: {
                            warehouse_id : warehouse_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Warehouse Success', 'The warehouse has been deleted successfully.', 'success');
                                reloadDatatable('#warehouse-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Warehouse Error', 'The warehouse does not exist.', 'danger');
                                    reloadDatatable('#warehouse-table');
                                }
                                else {
                                    showNotification('Delete Warehouse Error', response.message, 'danger');
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

        $(document).on('click','#delete-warehouse',function() {
            let warehouse_id = [];
            const transaction = 'delete multiple warehouse';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    warehouse_id.push(element.value);
                }
            });
    
            if(warehouse_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Warehouses Deletion',
                    text: 'Are you sure you want to delete these warehouses?',
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
                            url: 'controller/warehouse-controller.php',
                            dataType: 'json',
                            data: {
                                warehouse_id: warehouse_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Warehouse Success', 'The selected warehouses have been deleted successfully.', 'success');
                                    reloadDatatable('#warehouse-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Warehouse Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Warehouse Error', 'Please select the warehouses you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-warehouse-details',function() {
            const warehouse_id = $('#warehouse-id').text();
            const transaction = 'delete warehouse';
    
            Swal.fire({
                title: 'Confirm Warehouse Deletion',
                text: 'Are you sure you want to delete this warehouse?',
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
                        url: 'controller/warehouse-controller.php',
                        dataType: 'json',
                        data: {
                            warehouse_id : warehouse_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Warehouse Success', 'The warehouse has been deleted successfully.', 'success');
                                window.location = 'warehouse.php';
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
                                    showNotification('Delete Warehouse Error', response.message, 'danger');
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
            discardCreate('warehouse.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get warehouse details');

            enableForm();
        });

        $(document).on('click','#duplicate-warehouse',function() {
            const warehouse_id = $('#warehouse-id').text();
            const transaction = 'duplicate warehouse';
    
            Swal.fire({
                title: 'Confirm Warehouse Duplication',
                text: 'Are you sure you want to duplicate this warehouse?',
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
                        url: 'controller/warehouse-controller.php',
                        dataType: 'json',
                        data: {
                            warehouse_id : warehouse_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Warehouse Success', 'The warehouse has been duplicated successfully.', 'success');
                                window.location = 'warehouse.php?id=' + response.warehouseID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Warehouse Error', 'The warehouse does not exist.', 'danger');
                                    reloadDatatable('#warehouse-table');
                                }
                                else {
                                    showNotification('Duplicate Warehouse Error', response.message, 'danger');
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

function warehouseTable(datatable_name, buttons = false, show_all = false){
    const type = 'warehouse table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WAREHOUSE_NAME' },
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
            'url' : 'view/_warehouse_generation.php',
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

function warehouseForm(){
    $('#warehouse-form').validate({
        rules: {
            warehouse_name: {
                required: true
            },
        },
        messages: {
            warehouse_name: {
                required: 'Please enter the warehouse name'
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
            const warehouse_id = $('#warehouse-id').text();
            const transaction = 'save warehouse';
        
            $.ajax({
                type: 'POST',
                url: 'controller/warehouse-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&warehouse_id=' + warehouse_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Warehouse Success' : 'Update Warehouse Success';
                        const notificationDescription = response.insertRecord ? 'The warehouse has been inserted successfully.' : 'The warehouse has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'warehouse.php?id=' + response.warehouseID;
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
        case 'get warehouse details':
            const warehouse_id = $('#warehouse-id').text();
            
            $.ajax({
                url: 'controller/warehouse-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    warehouse_id : warehouse_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('warehouse-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#warehouse_id').val(warehouse_id);
                        $('#warehouse_name').val(response.warehouseName);

                        $('#warehouse_name_label').text(response.warehouseName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Warehouse Details Error', response.message, 'danger');
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