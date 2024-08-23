(function($) {
    'use strict';

    $(function() {
        if($('#mode-of-acquisition-table').length){
            modeOfAcquisitionTable('#mode-of-acquisition-table');
        }

        if($('#mode-of-acquisition-form').length){
            modeOfAcquisitionForm();
        }

        if($('#mode-of-acquisition-id').length){
            displayDetails('get mode of acquisition details');
        }

        $(document).on('click','.delete-mode-of-acquisition',function() {
            const mode_of_acquisition_id = $(this).data('mode-of-acquisition-id');
            const transaction = 'delete mode of acquisition';
    
            Swal.fire({
                title: 'Confirm Mode Of Acquisition Deletion',
                text: 'Are you sure you want to delete this mode of acquisition?',
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
                        url: 'controller/mode-of-acquisition-controller.php',
                        dataType: 'json',
                        data: {
                            mode_of_acquisition_id : mode_of_acquisition_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Mode Of Acquisition Success', 'The mode of acquisition has been deleted successfully.', 'success');
                                reloadDatatable('#mode-of-acquisition-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Mode Of Acquisition Error', 'The mode of acquisition does not exist.', 'danger');
                                    reloadDatatable('#mode-of-acquisition-table');
                                }
                                else {
                                    showNotification('Delete Mode Of Acquisition Error', response.message, 'danger');
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

        $(document).on('click','#delete-mode-of-acquisition',function() {
            let mode_of_acquisition_id = [];
            const transaction = 'delete multiple mode of acquisition';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    mode_of_acquisition_id.push(element.value);
                }
            });
    
            if(mode_of_acquisition_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Mode Of Acquisitions Deletion',
                    text: 'Are you sure you want to delete these mode of acquisitions?',
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
                            url: 'controller/mode-of-acquisition-controller.php',
                            dataType: 'json',
                            data: {
                                mode_of_acquisition_id: mode_of_acquisition_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Mode Of Acquisition Success', 'The selected mode of acquisitions have been deleted successfully.', 'success');
                                    reloadDatatable('#mode-of-acquisition-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Mode Of Acquisition Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Mode Of Acquisition Error', 'Please select the mode of acquisitions you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-mode-of-acquisition-details',function() {
            const mode_of_acquisition_id = $('#mode-of-acquisition-id').text();
            const transaction = 'delete mode of acquisition';
    
            Swal.fire({
                title: 'Confirm Mode Of Acquisition Deletion',
                text: 'Are you sure you want to delete this mode of acquisition?',
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
                        url: 'controller/mode-of-acquisition-controller.php',
                        dataType: 'json',
                        data: {
                            mode_of_acquisition_id : mode_of_acquisition_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Mode Of Acquisition Success', 'The mode of acquisition has been deleted successfully.', 'success');
                                window.location = 'mode-of-acquisition.php';
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
                                    showNotification('Delete Mode Of Acquisition Error', response.message, 'danger');
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
            discardCreate('mode-of-acquisition.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get mode of acquisition details');

            enableForm();
        });

        $(document).on('click','#duplicate-mode-of-acquisition',function() {
            const mode_of_acquisition_id = $('#mode-of-acquisition-id').text();
            const transaction = 'duplicate mode of acquisition';
    
            Swal.fire({
                title: 'Confirm Mode Of Acquisition Duplication',
                text: 'Are you sure you want to duplicate this mode of acquisition?',
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
                        url: 'controller/mode-of-acquisition-controller.php',
                        dataType: 'json',
                        data: {
                            mode_of_acquisition_id : mode_of_acquisition_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Mode Of Acquisition Success', 'The mode of acquisition has been duplicated successfully.', 'success');
                                window.location = 'mode-of-acquisition.php?id=' + response.modeOfAcquisitionID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Mode Of Acquisition Error', 'The mode of acquisition does not exist.', 'danger');
                                    reloadDatatable('#mode-of-acquisition-table');
                                }
                                else {
                                    showNotification('Duplicate Mode Of Acquisition Error', response.message, 'danger');
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

function modeOfAcquisitionTable(datatable_name, buttons = false, show_all = false){
    const type = 'mode of acquisition table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BANK_ACCOUNT_TYPE_NAME' },
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
            'url' : 'view/_mode_of_acquisition_generation.php',
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

function modeOfAcquisitionForm(){
    $('#mode-of-acquisition-form').validate({
        rules: {
            mode_of_acquisition_name: {
                required: true
            },
        },
        messages: {
            mode_of_acquisition_name: {
                required: 'Please enter the mode of acquisition name'
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
            const mode_of_acquisition_id = $('#mode-of-acquisition-id').text();
            const transaction = 'save mode of acquisition';
        
            $.ajax({
                type: 'POST',
                url: 'controller/mode-of-acquisition-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&mode_of_acquisition_id=' + mode_of_acquisition_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Mode Of Acquisition Success' : 'Update Mode Of Acquisition Success';
                        const notificationDescription = response.insertRecord ? 'The mode of acquisition has been inserted successfully.' : 'The mode of acquisition has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'mode-of-acquisition.php?id=' + response.modeOfAcquisitionID;
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
        case 'get mode of acquisition details':
            const mode_of_acquisition_id = $('#mode-of-acquisition-id').text();
            
            $.ajax({
                url: 'controller/mode-of-acquisition-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    mode_of_acquisition_id : mode_of_acquisition_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('mode-of-acquisition-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#mode_of_acquisition_id').val(mode_of_acquisition_id);
                        $('#mode_of_acquisition_name').val(response.modeOfAcquisitionName);

                        $('#mode_of_acquisition_name_label').text(response.modeOfAcquisitionName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Mode Of Acquisition Details Error', response.message, 'danger');
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