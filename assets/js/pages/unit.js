(function($) {
    'use strict';

    $(function() {
        if($('#unit-table').length){
            unitTable('#unit-table');
        }

        if($('#unit-form').length){
            unitForm();
        }

        if($('#unit-id').length){
            displayDetails('get unit details');
        }

        $(document).on('click','.delete-unit',function() {
            const unit_id = $(this).data('unit-id');
            const transaction = 'delete unit';
    
            Swal.fire({
                title: 'Confirm Unit Deletion',
                text: 'Are you sure you want to delete this unit?',
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
                        url: 'controller/unit-controller.php',
                        dataType: 'json',
                        data: {
                            unit_id : unit_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Unit Success', 'The unit has been deleted successfully.', 'success');
                                reloadDatatable('#unit-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Unit Error', 'The unit does not exist.', 'danger');
                                    reloadDatatable('#unit-table');
                                }
                                else {
                                    showNotification('Delete Unit Error', response.message, 'danger');
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

        $(document).on('click','#delete-unit',function() {
            let unit_id = [];
            const transaction = 'delete multiple unit';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    unit_id.push(element.value);
                }
            });
    
            if(unit_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Units Deletion',
                    text: 'Are you sure you want to delete these units?',
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
                            url: 'controller/unit-controller.php',
                            dataType: 'json',
                            data: {
                                unit_id: unit_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Unit Success', 'The selected units have been deleted successfully.', 'success');
                                    reloadDatatable('#unit-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Unit Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Unit Error', 'Please select the units you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-unit-details',function() {
            const unit_id = $('#unit-id').text();
            const transaction = 'delete unit';
    
            Swal.fire({
                title: 'Confirm Unit Deletion',
                text: 'Are you sure you want to delete this unit?',
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
                        url: 'controller/unit-controller.php',
                        dataType: 'json',
                        data: {
                            unit_id : unit_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Unit Success', 'The unit has been deleted successfully.', 'success');
                                window.location = 'unit.php';
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
                                    showNotification('Delete Unit Error', response.message, 'danger');
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
            discardCreate('unit.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get unit details');

            enableForm();
        });

        $(document).on('click','#duplicate-unit',function() {
            const unit_id = $('#unit-id').text();
            const transaction = 'duplicate unit';
    
            Swal.fire({
                title: 'Confirm Unit Duplication',
                text: 'Are you sure you want to duplicate this unit?',
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
                        url: 'controller/unit-controller.php',
                        dataType: 'json',
                        data: {
                            unit_id : unit_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Unit Success', 'The unit has been duplicated successfully.', 'success');
                                window.location = 'unit.php?id=' + response.unitID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Unit Error', 'The unit does not exist.', 'danger');
                                    reloadDatatable('#unit-table');
                                }
                                else {
                                    showNotification('Duplicate Unit Error', response.message, 'danger');
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

function unitTable(datatable_name, buttons = false, show_all = false){
    const type = 'unit table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'UNIT_NAME' },
        { 'data' : 'SHORT_NAME' },
        { 'data' : 'UNIT_CATEGORY' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '24%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_unit_generation.php',
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

function unitForm(){
    $('#unit-form').validate({
        rules: {
            unit_name: {
                required: true
            },
            short_name: {
                required: true
            },
            unit_category_id: {
                required: true
            },
        },
        messages: {
            unit_name: {
                required: 'Please enter the unit name'
            },
            short_name: {
                required: 'Please enter the short name'
            },
            unit_category_id: {
                required: 'Please choose the unit category'
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
            const unit_id = $('#unit-id').text();
            const transaction = 'save unit';
        
            $.ajax({
                type: 'POST',
                url: 'controller/unit-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&unit_id=' + unit_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Unit Success' : 'Update Unit Success';
                        const notificationDescription = response.insertRecord ? 'The unit has been inserted successfully.' : 'The unit has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'unit.php?id=' + response.unitID;
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
        case 'get unit details':
            const unit_id = $('#unit-id').text();
            
            $.ajax({
                url: 'controller/unit-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    unit_id : unit_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('unit-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#unit_id').val(unit_id);
                        $('#unit_name').val(response.unitName);
                        $('#short_name').val(response.shortName);

                        checkOptionExist('#unit_category_id', response.unitCategoryID, '');

                        $('#unit_name_label').text(response.unitName);
                        $('#short_name_label').text(response.shortName);
                        $('#unit_category_id_label').text(response.unitCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Unit Details Error', response.message, 'danger');
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