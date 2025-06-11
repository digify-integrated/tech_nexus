(function($) {
    'use strict';

    $(function() {
        if($('#business-premises-table').length){
            businessPremisesTable('#business-premises-table');
        }

        if($('#business-premises-form').length){
            businessPremisesForm();
        }

        if($('#business-premises-id').length){
            displayDetails('get business premises details');
        }

        $(document).on('click','.delete-business-premises',function() {
            const business_premises_id = $(this).data('business-premises-id');
            const transaction = 'delete business premises';
    
            Swal.fire({
                title: 'Confirm Business Premises Deletion',
                text: 'Are you sure you want to delete this business premises?',
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
                        url: 'controller/business-premises-controller.php',
                        dataType: 'json',
                        data: {
                            business_premises_id : business_premises_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Business Premises Success', 'The business premises has been deleted successfully.', 'success');
                                reloadDatatable('#business-premises-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Business Premises Error', 'The business premises does not exist.', 'danger');
                                    reloadDatatable('#business-premises-table');
                                }
                                else {
                                    showNotification('Delete Business Premises Error', response.message, 'danger');
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

        $(document).on('click','#delete-business-premises',function() {
            let business_premises_id = [];
            const transaction = 'delete multiple business premises';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    business_premises_id.push(element.value);
                }
            });
    
            if(business_premises_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Business Premisess Deletion',
                    text: 'Are you sure you want to delete these business premisess?',
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
                            url: 'controller/business-premises-controller.php',
                            dataType: 'json',
                            data: {
                                business_premises_id: business_premises_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Business Premises Success', 'The selected business premisess have been deleted successfully.', 'success');
                                    reloadDatatable('#business-premises-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Business Premises Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Business Premises Error', 'Please select the business premisess you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-business-premises-details',function() {
            const business_premises_id = $('#business-premises-id').text();
            const transaction = 'delete business premises';
    
            Swal.fire({
                title: 'Confirm Business Premises Deletion',
                text: 'Are you sure you want to delete this business premises?',
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
                        url: 'controller/business-premises-controller.php',
                        dataType: 'json',
                        data: {
                            business_premises_id : business_premises_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Business Premises Success', 'The business premises has been deleted successfully.', 'success');
                                window.location = 'business-premises.php';
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
                                    showNotification('Delete Business Premises Error', response.message, 'danger');
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
            discardCreate('business-premises.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get business premises details');

            enableForm();
        });

        $(document).on('click','#duplicate-business-premises',function() {
            const business_premises_id = $('#business-premises-id').text();
            const transaction = 'duplicate business premises';
    
            Swal.fire({
                title: 'Confirm Business Premises Duplication',
                text: 'Are you sure you want to duplicate this business premises?',
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
                        url: 'controller/business-premises-controller.php',
                        dataType: 'json',
                        data: {
                            business_premises_id : business_premises_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Business Premises Success', 'The business premises has been duplicated successfully.', 'success');
                                window.location = 'business-premises.php?id=' + response.businessPremisesID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Business Premises Error', 'The business premises does not exist.', 'danger');
                                    reloadDatatable('#business-premises-table');
                                }
                                else {
                                    showNotification('Duplicate Business Premises Error', response.message, 'danger');
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

function businessPremisesTable(datatable_name, buttons = false, show_all = false){
    const type = 'business premises table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BUSINESS_PREMISES_NAME' },
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
            'url' : 'view/_business_premises_generation.php',
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

function businessPremisesForm(){
    $('#business-premises-form').validate({
        rules: {
            business_premises_name: {
                required: true
            },
        },
        messages: {
            business_premises_name: {
                required: 'Please enter the business premises name'
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
            const business_premises_id = $('#business-premises-id').text();
            const transaction = 'save business premises';
        
            $.ajax({
                type: 'POST',
                url: 'controller/business-premises-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&business_premises_id=' + business_premises_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Business Premises Success' : 'Update Business Premises Success';
                        const notificationDescription = response.insertRecord ? 'The business premises has been inserted successfully.' : 'The business premises has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'business-premises.php?id=' + response.businessPremisesID;
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
        case 'get business premises details':
            const business_premises_id = $('#business-premises-id').text();
            
            $.ajax({
                url: 'controller/business-premises-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    business_premises_id : business_premises_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('business-premises-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#business_premises_id').val(business_premises_id);
                        $('#business_premises_name').val(response.businessPremisesName);

                        $('#business_premises_name_label').text(response.businessPremisesName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Business Premises Details Error', response.message, 'danger');
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