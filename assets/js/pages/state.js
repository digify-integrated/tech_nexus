(function($) {
    'use strict';

    $(function() {
        if($('#state-table').length){
            stateTable('#state-table');
        }

        if($('#state-form').length){
            stateForm();
        }

        if($('#state-id').length){
            displayDetails('get state details');
        }

        $(document).on('click','.delete-state',function() {
            const state_id = $(this).data('state-id');
            const transaction = 'delete state';
    
            Swal.fire({
                title: 'Confirm State Deletion',
                text: 'Are you sure you want to delete this state?',
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
                        url: 'controller/state-controller.php',
                        dataType: 'json',
                        data: {
                            state_id : state_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete State Success', 'The state has been deleted successfully.', 'success');
                                reloadDatatable('#state-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete State Error', 'The state does not exist.', 'danger');
                                    reloadDatatable('#state-table');
                                }
                                else {
                                    showNotification('Delete State Error', response.message, 'danger');
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

        $(document).on('click','#delete-state',function() {
            let state_id = [];
            const transaction = 'delete multiple state';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    state_id.push(element.value);
                }
            });
    
            if(state_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple States Deletion',
                    text: 'Are you sure you want to delete these countries?',
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
                            url: 'controller/state-controller.php',
                            dataType: 'json',
                            data: {
                                state_id: state_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete State Success', 'The selected countries have been deleted successfully.', 'success');
                                        reloadDatatable('#state-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete State Error', response.message, 'danger');
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
                showNotification('Deletion Multiple State Error', 'Please select the countries you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-state-details',function() {
            const state_id = $('#state-id').text();
            const transaction = 'delete state';
    
            Swal.fire({
                title: 'Confirm State Deletion',
                text: 'Are you sure you want to delete this state?',
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
                        url: 'controller/state-controller.php',
                        dataType: 'json',
                        data: {
                            state_id : state_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted State Success', 'The state has been deleted successfully.', 'success');
                                window.location = 'state.php';
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
                                    showNotification('Delete State Error', response.message, 'danger');
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
            discardCreate('state.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get state details');

            enableForm();
        });

        $(document).on('click','#duplicate-state',function() {
            const state_id = $('#state-id').text();
            const transaction = 'duplicate state';
    
            Swal.fire({
                title: 'Confirm State Duplication',
                text: 'Are you sure you want to duplicate this state?',
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
                        url: 'controller/state-controller.php',
                        dataType: 'json',
                        data: {
                            state_id : state_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate State Success', 'The state has been duplicated successfully.', 'success');
                                window.location = 'state.php?id=' + response.stateID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate State Error', 'The state does not exist.', 'danger');
                                    reloadDatatable('#state-table');
                                }
                                else {
                                    showNotification('Duplicate State Error', response.message, 'danger');
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

        $(document).on('click','#filter-datatable',function() {
            stateTable('#state-table');
        });
    });
})(jQuery);

function stateTable(datatable_name, buttons = false, show_all = false){
    const type = 'state table';
    var filter_country = $('#filter_country').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'STATE_NAME' },
        { 'data' : 'COUNTRY_ID' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '42%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_state_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_country' : filter_country},
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

function stateForm(){
    $('#state-form').validate({
        rules: {
            state_name: {
                required: true
            },
            state_code: {
                required: true
            },
            value: {
                required: true
            }
        },
        messages: {
            state_name: {
                required: 'Please enter the state name'
            },
            state_code: {
                required: 'Please enter the state description'
            },
            value: {
                required: 'Please enter the value'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
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
            const state_id = $('#state-id').text();
            const transaction = 'save state';
        
            $.ajax({
                type: 'POST',
                url: 'controller/state-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&state_id=' + state_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert State Success' : 'Update State Success';
                        const notificationDescription = response.insertRecord ? 'The state has been inserted successfully.' : 'The state has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'state.php?id=' + response.stateID;
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
        case 'get state details':
            const state_id = $('#state-id').text();
            
            $.ajax({
                url: 'controller/state-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    state_id : state_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('state-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#state_id').val(state_id);
                        $('#state_name').val(response.stateName);
                        $('#state_code').val(response.stateCode);

                        checkOptionExist('#country_id', response.countryID, '');

                        $('#state_name_label').text(response.stateName);
                        $('#state_code_label').text(response.stateCode);
                        $('#country_id_label').text(response.countryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get State Details Error', response.message, 'danger');
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