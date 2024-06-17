(function($) {
    'use strict';

    $(function() {
        if($('#parts-inquiry-table').length){
            partsInquiryTable('#parts-inquiry-table');
        }

        if($('#parts-inquiry-form').length){
            partsInquiryForm();
        }

        if($('#import-parts-inquiry-form').length){
            importPartsInquiryForm();
        }

        if($('#parts-inquiry-id').length){
            displayDetails('get parts inquiry details');
        }

        $(document).on('click','.delete-parts-inquiry',function() {
            const parts_inquiry_id = $(this).data('parts-inquiry-id');
            const transaction = 'delete parts inquiry';
    
            Swal.fire({
                title: 'Confirm Parts Inquiry Deletion',
                text: 'Are you sure you want to delete this parts inquiry?',
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
                        url: 'controller/parts-inquiry-controller.php',
                        dataType: 'json',
                        data: {
                            parts_inquiry_id : parts_inquiry_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Parts Inquiry Success', 'The parts inquiry has been deleted successfully.', 'success');
                                reloadDatatable('#parts-inquiry-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Parts Inquiry Error', 'The parts inquiry does not exist.', 'danger');
                                    reloadDatatable('#parts-inquiry-table');
                                }
                                else {
                                    showNotification('Delete Parts Inquiry Error', response.message, 'danger');
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

        $(document).on('click','#delete-parts-inquiry',function() {
            let parts_inquiry_id = [];
            const transaction = 'delete multiple parts inquiry';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    parts_inquiry_id.push(element.value);
                }
            });
    
            if(parts_inquiry_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Parts Inquiries Deletion',
                    text: 'Are you sure you want to delete these parts inquiries?',
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
                            url: 'controller/parts-inquiry-controller.php',
                            dataType: 'json',
                            data: {
                                parts_inquiry_id: parts_inquiry_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Parts Inquiry Success', 'The selected parts inquiries have been deleted successfully.', 'success');
                                    reloadDatatable('#parts-inquiry-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Parts Inquiry Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Parts Inquiry Error', 'Please select the parts inquiries you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-parts-inquiry-details',function() {
            const parts_inquiry_id = $('#parts-inquiry-id').text();
            const transaction = 'delete parts inquiry';
    
            Swal.fire({
                title: 'Confirm Parts Inquiry Deletion',
                text: 'Are you sure you want to delete this parts inquiry?',
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
                        url: 'controller/parts-inquiry-controller.php',
                        dataType: 'json',
                        data: {
                            parts_inquiry_id : parts_inquiry_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Parts Inquiry Success', 'The parts inquiry has been deleted successfully.', 'success');
                                window.location = 'parts-inquiry.php';
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
                                    showNotification('Delete Parts Inquiry Error', response.message, 'danger');
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
            discardCreate('parts-inquiry.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get parts inquiry details');

            enableForm();
        });
    });
})(jQuery);

function partsInquiryTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts inquiry table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PARTS_NUMBER' },
        { 'data' : 'STOCK' },
        { 'data' : 'PRICE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '54%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_inquiry_generation.php',
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

function partsInquiryForm(){
    $('#parts-inquiry-form').validate({
        rules: {
            parts_inquiry_id: {
                required: true
            },
            stock: {
                required: true
            },
            parts_description: {
                required: true
            },
            price: {
                required: true
            },
        },
        messages: {
            parts_inquiry_id: {
                required: 'Please enter the part number'
            },
            stock: {
                required: 'Please enter the stock'
            },
            parts_description: {
                required: 'Please enter the description'
            },
            price: {
                required: 'Please enter the price'
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
            const parts_inquiry_id = $('#parts-inquiry-id').text();
            const transaction = 'save parts inquiry';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-inquiry-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_inquiry_id=' + parts_inquiry_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Parts Inquiry Success' : 'Update Parts Inquiry Success';
                        const notificationDescription = response.insertRecord ? 'The parts inquiry has been inserted successfully.' : 'The parts inquiry has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'parts-inquiry.php?id=' + response.partsInquiryID;
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

function importPartsInquiryForm(){
    $('#import-parts-inquiry-form').validate({
        rules: {
            import_file: {
                required: true
            }
        },
        messages: {
            import_file: {
                required: 'Please choose the import file'
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
            const transaction = 'save parts inquiry import';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-inquiry-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-load-file');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Parts Inquiry Load Success', 'The parts inquiry has been loaded successfully.', 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-load-file', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get parts inquiry details':
            const parts_inquiry_id = $('#parts-inquiry-id').text();
            
            $.ajax({
                url: 'controller/parts-inquiry-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_inquiry_id : parts_inquiry_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('parts-inquiry-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#parts_number').val(response.partsNumber);
                        $('#parts_description').val(response.partsDescription);
                        $('#stock').val(response.stock);
                        $('#price').val(response.price);

                        $('#parts_number_label').text(response.partsNumber);
                        $('#parts_description_label').text(response.partsDescription);
                        $('#stock_label').text(response.stock);
                        $('#price_label').text(response.price);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Parts Inquiry Details Error', response.message, 'danger');
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