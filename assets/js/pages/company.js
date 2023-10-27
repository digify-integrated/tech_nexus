(function($) {
    'use strict';

    $(function() {
        if($('#company-table').length){
            companyTable('#company-table');
        }

        if($('#company-form').length){
            companyForm();
        }

        if($('#company-id').length){
            displayDetails('get company details');

            $('#company_logo').change(function() {
                var selectedFile = $(this)[0].files[0];

                if (selectedFile) {
                    const transaction = 'change company logo';
                    const company_id = $('#company-id').text();

                    var formData = new FormData();
                    formData.append('company_id', company_id);
                    formData.append('transaction', transaction);
                    formData.append('company_logo', selectedFile);
            
                    $.ajax({
                        type: 'POST',
                        url: 'controller/company-controller.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showNotification('Company Logo Change Success', 'The company logo has been successfully updated.', 'success');
                                location.reload();
                            }
                            else{
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Company Logo Change Error', response.message, 'danger');
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
                }
            });
        }

        $(document).on('click','.delete-company',function() {
            const company_id = $(this).data('company-id');
            const transaction = 'delete company';
    
            Swal.fire({
                title: 'Confirm Company Deletion',
                text: 'Are you sure you want to delete this company?',
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
                        url: 'controller/company-controller.php',
                        dataType: 'json',
                        data: {
                            company_id : company_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Company Success', 'The company has been deleted successfully.', 'success');
                                reloadDatatable('#company-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Company Error', 'The company does not exist.', 'danger');
                                    reloadDatatable('#company-table');
                                }
                                else {
                                    showNotification('Delete Company Error', response.message, 'danger');
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

        $(document).on('click','#delete-company',function() {
            let company_id = [];
            const transaction = 'delete multiple company';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    company_id.push(element.value);
                }
            });
    
            if(company_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Companies Deletion',
                    text: 'Are you sure you want to delete these companies?',
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
                            url: 'controller/company-controller.php',
                            dataType: 'json',
                            data: {
                                company_id: company_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Company Success', 'The selected companies have been deleted successfully.', 'success');
                                        reloadDatatable('#company-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Company Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Company Error', 'Please select the companies you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-company-details',function() {
            const company_id = $('#company-id').text();
            const transaction = 'delete company';
    
            Swal.fire({
                title: 'Confirm Company Deletion',
                text: 'Are you sure you want to delete this company?',
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
                        url: 'controller/company-controller.php',
                        dataType: 'json',
                        data: {
                            company_id : company_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Company Success', 'The company has been deleted successfully.', 'success');
                                window.location = 'company.php';
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
                                    showNotification('Delete Company Error', response.message, 'danger');
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
            discardCreate('company.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get company details');

            enableForm();
        });

        $(document).on('click','#duplicate-company',function() {
            const company_id = $('#company-id').text();
            const transaction = 'duplicate company';
    
            Swal.fire({
                title: 'Confirm Company Duplication',
                text: 'Are you sure you want to duplicate this company?',
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
                        url: 'controller/company-controller.php',
                        dataType: 'json',
                        data: {
                            company_id : company_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Company Success', 'The company has been duplicated successfully.', 'success');
                                window.location = 'company.php?id=' + response.companyID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Company Error', 'The company does not exist.', 'danger');
                                    reloadDatatable('#company-table');
                                }
                                else {
                                    showNotification('Duplicate Company Error', response.message, 'danger');
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

function companyTable(datatable_name, buttons = false, show_all = false){
    const type = 'company table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'COMPANY_NAME' },
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
            'url' : 'view/_company_generation.php',
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

function companyForm(){
    $('#company-form').validate({
        rules: {
            company_name: {
                required: true
            },
            address: {
                required: true
            },
            city_id: {
                required: true
            }
        },
        messages: {
            company_name: {
                required: 'Please enter the company name'
            },
            address: {
                required: 'Please enter the address'
            },
            city_id: {
                required: 'Please choose the city'
            }
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
            const company_id = $('#company-id').text();
            const transaction = 'save company';
        
            $.ajax({
                type: 'POST',
                url: 'controller/company-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Company Success' : 'Update Company Success';
                        const notificationDescription = response.insertRecord ? 'The company has been inserted successfully.' : 'The company has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'company.php?id=' + response.companyID;
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
        case 'get company details':
            const company_id = $('#company-id').text();
            
            $.ajax({
                url: 'controller/company-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    company_id : company_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('company-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#company_id').val(company_id);
                        $('#company_name').val(response.companyName);
                        $('#address').val(response.address);
                        $('#tax_id').val(response.taxID);
                        $('#phone').val(response.phone);
                        $('#mobile').val(response.mobile);
                        $('#telephone').val(response.telephone);
                        $('#email').val(response.email);
                        $('#website').val(response.website);

                        checkOptionExist('#city_id', response.cityID, '');
                        checkOptionExist('#currency_id', response.currencyID, '');

                        document.getElementById('company_logo_image').src = response.companyLogo;

                        $('#city_id_label').text(response.cityName);
                        $('#tax_id_label').text(response.taxID);
                        $('#currency_id_label').text(response.currencyName);
                        $('#company_name_label').text(response.companyName);
                        $('#address_label').text(response.address);
                        $('#phone_label').text(response.phone);
                        $('#mobile_label').text(response.mobile);
                        $('#telephone_label').text(response.telephone);
                        $('#email_label').text(response.email);
                        $('#website_label').text(response.website);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Company Details Error', response.message, 'danger');
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