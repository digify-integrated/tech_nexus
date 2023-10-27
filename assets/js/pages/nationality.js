(function($) {
    'use strict';

    $(function() {
        if($('#nationality-table').length){
            nationalityTable('#nationality-table');
        }

        if($('#nationality-form').length){
            nationalityForm();
        }

        if($('#nationality-id').length){
            displayDetails('get nationality details');
        }

        $(document).on('click','.delete-nationality',function() {
            const nationality_id = $(this).data('nationality-id');
            const transaction = 'delete nationality';
    
            Swal.fire({
                title: 'Confirm Nationality Deletion',
                text: 'Are you sure you want to delete this nationality?',
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
                        url: 'controller/nationality-controller.php',
                        dataType: 'json',
                        data: {
                            nationality_id : nationality_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Nationality Success', 'The nationality has been deleted successfully.', 'success');
                                reloadDatatable('#nationality-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Nationality Error', 'The nationality does not exist.', 'danger');
                                    reloadDatatable('#nationality-table');
                                }
                                else {
                                    showNotification('Delete Nationality Error', response.message, 'danger');
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

        $(document).on('click','#delete-nationality',function() {
            let nationality_id = [];
            const transaction = 'delete multiple nationality';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    nationality_id.push(element.value);
                }
            });
    
            if(nationality_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Nationalities Deletion',
                    text: 'Are you sure you want to delete these nationalities?',
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
                            url: 'controller/nationality-controller.php',
                            dataType: 'json',
                            data: {
                                nationality_id: nationality_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Nationality Success', 'The selected nationalities have been deleted successfully.', 'success');
                                        reloadDatatable('#nationality-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Nationality Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Nationality Error', 'Please select the nationalities you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-nationality-details',function() {
            const nationality_id = $('#nationality-id').text();
            const transaction = 'delete nationality';
    
            Swal.fire({
                title: 'Confirm Nationality Deletion',
                text: 'Are you sure you want to delete this nationality?',
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
                        url: 'controller/nationality-controller.php',
                        dataType: 'json',
                        data: {
                            nationality_id : nationality_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Nationality Success', 'The nationality has been deleted successfully.', 'success');
                                window.location = 'nationality.php';
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
                                    showNotification('Delete Nationality Error', response.message, 'danger');
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
            discardCreate('nationality.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get nationality details');

            enableForm();
        });

        $(document).on('click','#duplicate-nationality',function() {
            const nationality_id = $('#nationality-id').text();
            const transaction = 'duplicate nationality';
    
            Swal.fire({
                title: 'Confirm Nationality Duplication',
                text: 'Are you sure you want to duplicate this nationality?',
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
                        url: 'controller/nationality-controller.php',
                        dataType: 'json',
                        data: {
                            nationality_id : nationality_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Nationality Success', 'The nationality has been duplicated successfully.', 'success');
                                window.location = 'nationality.php?id=' + response.nationalityID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Nationality Error', 'The nationality does not exist.', 'danger');
                                    reloadDatatable('#nationality-table');
                                }
                                else {
                                    showNotification('Duplicate Nationality Error', response.message, 'danger');
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

function nationalityTable(datatable_name, buttons = false, show_all = false){
    const type = 'nationality table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'NATIONALITY_NAME' },
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
            'url' : 'view/_nationality_generation.php',
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

function nationalityForm(){
    $('#nationality-form').validate({
        rules: {
            nationality_name: {
                required: true
            },
        },
        messages: {
            nationality_name: {
                required: 'Please enter the nationality name'
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
            const nationality_id = $('#nationality-id').text();
            const transaction = 'save nationality';
        
            $.ajax({
                type: 'POST',
                url: 'controller/nationality-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&nationality_id=' + nationality_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Nationality Success' : 'Update Nationality Success';
                        const notificationDescription = response.insertRecord ? 'The nationality has been inserted successfully.' : 'The nationality has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'nationality.php?id=' + response.nationalityID;
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
        case 'get nationality details':
            const nationality_id = $('#nationality-id').text();
            
            $.ajax({
                url: 'controller/nationality-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    nationality_id : nationality_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('nationality-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#nationality_id').val(nationality_id);
                        $('#nationality_name').val(response.nationalityName);

                        $('#nationality_name_label').text(response.nationalityName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Nationality Details Error', response.message, 'danger');
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