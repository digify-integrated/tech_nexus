(function($) {
    'use strict';

    $(function() {
        if($('#contractor-table').length){
            contractorTable('#contractor-table');
        }

        if($('#contractor-form').length){
            contractorForm();
        }

        if($('#contractor-id').length){
            displayDetails('get contractor details');
        }

        $(document).on('click','.delete-contractor',function() {
            const contractor_id = $(this).data('contractor-id');
            const transaction = 'delete contractor';
    
            Swal.fire({
                title: 'Confirm Contractor Deletion',
                text: 'Are you sure you want to delete this contractor?',
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
                        url: 'controller/contractor-controller.php',
                        dataType: 'json',
                        data: {
                            contractor_id : contractor_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Contractor Success', 'The contractor has been deleted successfully.', 'success');
                                reloadDatatable('#contractor-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Contractor Error', 'The contractor does not exist.', 'danger');
                                    reloadDatatable('#contractor-table');
                                }
                                else {
                                    showNotification('Delete Contractor Error', response.message, 'danger');
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

        $(document).on('click','#delete-contractor',function() {
            let contractor_id = [];
            const transaction = 'delete multiple contractor';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    contractor_id.push(element.value);
                }
            });
    
            if(contractor_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Contractors Deletion',
                    text: 'Are you sure you want to delete these contractors?',
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
                            url: 'controller/contractor-controller.php',
                            dataType: 'json',
                            data: {
                                contractor_id: contractor_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Contractor Success', 'The selected contractors have been deleted successfully.', 'success');
                                    reloadDatatable('#contractor-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Contractor Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Contractor Error', 'Please select the contractors you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-contractor-details',function() {
            const contractor_id = $('#contractor-id').text();
            const transaction = 'delete contractor';
    
            Swal.fire({
                title: 'Confirm Contractor Deletion',
                text: 'Are you sure you want to delete this contractor?',
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
                        url: 'controller/contractor-controller.php',
                        dataType: 'json',
                        data: {
                            contractor_id : contractor_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Contractor Success', 'The contractor has been deleted successfully.', 'success');
                                window.location = 'contractor.php';
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
                                    showNotification('Delete Contractor Error', response.message, 'danger');
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
            discardCreate('contractor.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get contractor details');

            enableForm();
        });

        $(document).on('click','#duplicate-contractor',function() {
            const contractor_id = $('#contractor-id').text();
            const transaction = 'duplicate contractor';
    
            Swal.fire({
                title: 'Confirm Contractor Duplication',
                text: 'Are you sure you want to duplicate this contractor?',
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
                        url: 'controller/contractor-controller.php',
                        dataType: 'json',
                        data: {
                            contractor_id : contractor_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Contractor Success', 'The contractor has been duplicated successfully.', 'success');
                                window.location = 'contractor.php?id=' + response.contractorID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Contractor Error', 'The contractor does not exist.', 'danger');
                                    reloadDatatable('#contractor-table');
                                }
                                else {
                                    showNotification('Duplicate Contractor Error', response.message, 'danger');
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

function contractorTable(datatable_name, buttons = false, show_all = false){
    const type = 'contractor table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CONTRACTOR_NAME' },
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
            'url' : 'view/_contractor_generation.php',
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

function contractorForm(){
    $('#contractor-form').validate({
        rules: {
            contractor_name: {
                required: true
            },
        },
        messages: {
            contractor_name: {
                required: 'Please enter the contractor name'
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
            const contractor_id = $('#contractor-id').text();
            const transaction = 'save contractor';
        
            $.ajax({
                type: 'POST',
                url: 'controller/contractor-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&contractor_id=' + contractor_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Contractor Success' : 'Update Contractor Success';
                        const notificationDescription = response.insertRecord ? 'The contractor has been inserted successfully.' : 'The contractor has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'contractor.php?id=' + response.contractorID;
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
        case 'get contractor details':
            const contractor_id = $('#contractor-id').text();
            
            $.ajax({
                url: 'controller/contractor-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contractor_id : contractor_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('contractor-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contractor_id').val(contractor_id);
                        $('#contractor_name').val(response.contractorName);

                        $('#contractor_name_label').text(response.contractorName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Contractor Details Error', response.message, 'danger');
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