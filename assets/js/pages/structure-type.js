(function($) {
    'use strict';

    $(function() {
        if($('#structure-type-table').length){
            structureTypeTable('#structure-type-table');
        }

        if($('#structure-type-form').length){
            structureTypeForm();
        }

        if($('#structure-type-id').length){
            displayDetails('get structure type details');
        }

        $(document).on('click','.delete-structure-type',function() {
            const structure_type_id = $(this).data('structure-type-id');
            const transaction = 'delete structure type';
    
            Swal.fire({
                title: 'Confirm Structure Type Deletion',
                text: 'Are you sure you want to delete this structure type?',
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
                        url: 'controller/structure-type-controller.php',
                        dataType: 'json',
                        data: {
                            structure_type_id : structure_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Structure Type Success', 'The structure type has been deleted successfully.', 'success');
                                reloadDatatable('#structure-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Structure Type Error', 'The structure type does not exist.', 'danger');
                                    reloadDatatable('#structure-type-table');
                                }
                                else {
                                    showNotification('Delete Structure Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-structure-type',function() {
            let structure_type_id = [];
            const transaction = 'delete multiple structure type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    structure_type_id.push(element.value);
                }
            });
    
            if(structure_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Structure Types Deletion',
                    text: 'Are you sure you want to delete these structure types?',
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
                            url: 'controller/structure-type-controller.php',
                            dataType: 'json',
                            data: {
                                structure_type_id: structure_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Structure Type Success', 'The selected structure types have been deleted successfully.', 'success');
                                    reloadDatatable('#structure-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Structure Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Structure Type Error', 'Please select the structure types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-structure-type-details',function() {
            const structure_type_id = $('#structure-type-id').text();
            const transaction = 'delete structure type';
    
            Swal.fire({
                title: 'Confirm Structure Type Deletion',
                text: 'Are you sure you want to delete this structure type?',
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
                        url: 'controller/structure-type-controller.php',
                        dataType: 'json',
                        data: {
                            structure_type_id : structure_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Structure Type Success', 'The structure type has been deleted successfully.', 'success');
                                window.location = 'structure-type.php';
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
                                    showNotification('Delete Structure Type Error', response.message, 'danger');
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
            discardCreate('structure-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get structure type details');

            enableForm();
        });

        $(document).on('click','#duplicate-structure-type',function() {
            const structure_type_id = $('#structure-type-id').text();
            const transaction = 'duplicate structure type';
    
            Swal.fire({
                title: 'Confirm Structure Type Duplication',
                text: 'Are you sure you want to duplicate this structure type?',
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
                        url: 'controller/structure-type-controller.php',
                        dataType: 'json',
                        data: {
                            structure_type_id : structure_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Structure Type Success', 'The structure type has been duplicated successfully.', 'success');
                                window.location = 'structure-type.php?id=' + response.structureTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Structure Type Error', 'The structure type does not exist.', 'danger');
                                    reloadDatatable('#structure-type-table');
                                }
                                else {
                                    showNotification('Duplicate Structure Type Error', response.message, 'danger');
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

function structureTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'structure type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'STRUCTURE_TYPE_NAME' },
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
            'url' : 'view/_structure_type_generation.php',
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

function structureTypeForm(){
    $('#structure-type-form').validate({
        rules: {
            structure_type_name: {
                required: true
            },
        },
        messages: {
            structure_type_name: {
                required: 'Please enter the structure type name'
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
            const structure_type_id = $('#structure-type-id').text();
            const transaction = 'save structure type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/structure-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&structure_type_id=' + structure_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Structure Type Success' : 'Update Structure Type Success';
                        const notificationDescription = response.insertRecord ? 'The structure type has been inserted successfully.' : 'The structure type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'structure-type.php?id=' + response.structureTypeID;
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
        case 'get structure type details':
            const structure_type_id = $('#structure-type-id').text();
            
            $.ajax({
                url: 'controller/structure-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    structure_type_id : structure_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('structure-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#structure_type_id').val(structure_type_id);
                        $('#structure_type_name').val(response.structureTypeName);

                        $('#structure_type_name_label').text(response.structureTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Structure Type Details Error', response.message, 'danger');
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