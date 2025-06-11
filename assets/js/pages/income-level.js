(function($) {
    'use strict';

    $(function() {
        if($('#income-level-table').length){
            incomeLevelTable('#income-level-table');
        }

        if($('#income-level-form').length){
            incomeLevelForm();
        }

        if($('#income-level-id').length){
            displayDetails('get income level details');
        }

        $(document).on('click','.delete-income-level',function() {
            const income_level_id = $(this).data('income-level-id');
            const transaction = 'delete income level';
    
            Swal.fire({
                title: 'Confirm Income Level Deletion',
                text: 'Are you sure you want to delete this income level?',
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
                        url: 'controller/income-level-controller.php',
                        dataType: 'json',
                        data: {
                            income_level_id : income_level_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Income Level Success', 'The income level has been deleted successfully.', 'success');
                                reloadDatatable('#income-level-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Income Level Error', 'The income level does not exist.', 'danger');
                                    reloadDatatable('#income-level-table');
                                }
                                else {
                                    showNotification('Delete Income Level Error', response.message, 'danger');
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

        $(document).on('click','#delete-income-level',function() {
            let income_level_id = [];
            const transaction = 'delete multiple income level';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    income_level_id.push(element.value);
                }
            });
    
            if(income_level_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Income Levels Deletion',
                    text: 'Are you sure you want to delete these income levels?',
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
                            url: 'controller/income-level-controller.php',
                            dataType: 'json',
                            data: {
                                income_level_id: income_level_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Income Level Success', 'The selected income levels have been deleted successfully.', 'success');
                                    reloadDatatable('#income-level-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Income Level Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Income Level Error', 'Please select the income levels you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-income-level-details',function() {
            const income_level_id = $('#income-level-id').text();
            const transaction = 'delete income level';
    
            Swal.fire({
                title: 'Confirm Income Level Deletion',
                text: 'Are you sure you want to delete this income level?',
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
                        url: 'controller/income-level-controller.php',
                        dataType: 'json',
                        data: {
                            income_level_id : income_level_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Income Level Success', 'The income level has been deleted successfully.', 'success');
                                window.location = 'income-level.php';
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
                                    showNotification('Delete Income Level Error', response.message, 'danger');
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
            discardCreate('income-level.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get income level details');

            enableForm();
        });

        $(document).on('click','#duplicate-income-level',function() {
            const income_level_id = $('#income-level-id').text();
            const transaction = 'duplicate income level';
    
            Swal.fire({
                title: 'Confirm Income Level Duplication',
                text: 'Are you sure you want to duplicate this income level?',
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
                        url: 'controller/income-level-controller.php',
                        dataType: 'json',
                        data: {
                            income_level_id : income_level_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Income Level Success', 'The income level has been duplicated successfully.', 'success');
                                window.location = 'income-level.php?id=' + response.incomeLevelID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Income Level Error', 'The income level does not exist.', 'danger');
                                    reloadDatatable('#income-level-table');
                                }
                                else {
                                    showNotification('Duplicate Income Level Error', response.message, 'danger');
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

function incomeLevelTable(datatable_name, buttons = false, show_all = false){
    const type = 'income level table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'INCOME_LEVEL_NAME' },
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
            'url' : 'view/_income_level_generation.php',
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

function incomeLevelForm(){
    $('#income-level-form').validate({
        rules: {
            income_level_name: {
                required: true
            },
        },
        messages: {
            income_level_name: {
                required: 'Please enter the income level name'
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
            const income_level_id = $('#income-level-id').text();
            const transaction = 'save income level';
        
            $.ajax({
                type: 'POST',
                url: 'controller/income-level-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&income_level_id=' + income_level_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Income Level Success' : 'Update Income Level Success';
                        const notificationDescription = response.insertRecord ? 'The income level has been inserted successfully.' : 'The income level has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'income-level.php?id=' + response.incomeLevelID;
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
        case 'get income level details':
            const income_level_id = $('#income-level-id').text();
            
            $.ajax({
                url: 'controller/income-level-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    income_level_id : income_level_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('income-level-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#income_level_id').val(income_level_id);
                        $('#income_level_name').val(response.incomeLevelName);

                        $('#income_level_name_label').text(response.incomeLevelName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Income Level Details Error', response.message, 'danger');
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