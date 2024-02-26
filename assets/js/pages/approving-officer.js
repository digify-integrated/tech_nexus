(function($) {
    'use strict';

    $(function() {
        if($('#approving-officer-table').length){
            approvingOfficerTable('#approving-officer-table');
        }

        if($('#approving-officer-form').length){
            approvingOfficerForm();
        }

        if($('#approving-officer-id').length){
            displayDetails('get approving officer details');
        }

        $(document).on('click','.delete-approving-officer',function() {
            const approving_officer_id = $(this).data('approving-officer-id');
            const transaction = 'delete approving officer';
    
            Swal.fire({
                title: 'Confirm Approving Officer Deletion',
                text: 'Are you sure you want to delete this approving officer?',
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
                        url: 'controller/approving-officer-controller.php',
                        dataType: 'json',
                        data: {
                            approving_officer_id : approving_officer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Approving Officer Success', 'The approving officer has been deleted successfully.', 'success');
                                reloadDatatable('#approving-officer-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Approving Officer Error', 'The approving officer does not exist.', 'danger');
                                    reloadDatatable('#approving-officer-table');
                                }
                                else {
                                    showNotification('Delete Approving Officer Error', response.message, 'danger');
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

        $(document).on('click','#delete-approving-officer',function() {
            let approving_officer_id = [];
            const transaction = 'delete multiple approving officer';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    approving_officer_id.push(element.value);
                }
            });
    
            if(approving_officer_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Approving Officers Deletion',
                    text: 'Are you sure you want to delete these approving officers?',
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
                            url: 'controller/approving-officer-controller.php',
                            dataType: 'json',
                            data: {
                                approving_officer_id: approving_officer_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Approving Officer Success', 'The selected approving officers have been deleted successfully.', 'success');
                                    reloadDatatable('#approving-officer-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Approving Officer Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Approving Officer Error', 'Please select the approving officers you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-approving-officer-details',function() {
            const approving_officer_id = $('#approving-officer-id').text();
            const transaction = 'delete approving officer';
    
            Swal.fire({
                title: 'Confirm Approving Officer Deletion',
                text: 'Are you sure you want to delete this approving officer?',
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
                        url: 'controller/approving-officer-controller.php',
                        dataType: 'json',
                        data: {
                            approving_officer_id : approving_officer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Approving Officer Success', 'The approving officer has been deleted successfully.', 'success');
                                window.location = 'approving-officer.php';
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
                                    showNotification('Delete Approving Officer Error', response.message, 'danger');
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
            discardCreate('approving-officer.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get approving officer details');

            enableForm();
        });
    });
})(jQuery);

function approvingOfficerTable(datatable_name, buttons = false, show_all = false){
    const type = 'approving officer table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_AS' },
        { 'data' : 'APPROVING_OFFICER_TYPE' },
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
            'url' : 'view/_approving_officer_generation.php',
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

function approvingOfficerForm(){
    $('#approving-officer-form').validate({
        rules: {
            contact_id: {
                required: true
            },
            approving_officer_type: {
                required: true
            },
        },
        messages: {
            contact_id: {
                required: 'Please choose the approver'
            },
            approving_officer_type: {
                required: 'Please choose the approving officer type'
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
            const approving_officer_id = $('#approving-officer-id').text();
            const transaction = 'save approving officer';
        
            $.ajax({
                type: 'POST',
                url: 'controller/approving-officer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&approving_officer_id=' + approving_officer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {                        
                        setNotification('Insert Approving Officer Success', 'The approving officer has been inserted successfully.', 'success');
                        window.location = 'approving-officer.php?id=' + response.approvingOfficerID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.approverExist) {
                            showNotification('Approving Officer Exist', 'The approving officer selected already exist.', 'danger');
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
        case 'get approving officer details':
            const approving_officer_id = $('#approving-officer-id').text();
            
            $.ajax({
                url: 'controller/approving-officer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    approving_officer_id : approving_officer_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('approving-officer-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#approving_officer_id').val(approving_officer_id);

                        $('#contact_id_label').text(response.approverName);
                        $('#approving_officer_type_label').text(response.approvingOfficerType);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Approving Officer Details Error', response.message, 'danger');
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