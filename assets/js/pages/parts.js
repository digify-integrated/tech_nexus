(function($) {
    'use strict';

    $(function() {
        var partsSearch = $('#parts_search');
        var lastSearchValue = '';

        var debounceTimeout;

        partsSearch.on('keyup', function() {
            partsTable('#parts-table');
        });

        partsSearch.val(lastSearchValue);

        if($('#part-id').length){
            displayDetails('get parts details');
        }

        if($('#parts-table').length){
            partsTable('#parts-table', true, false);
        }

        if($('#parts-expense-table').length){
            partsExpenseTable('#parts-expense-table');
        }

        if($('#parts-document-table').length){
            partsDocumentTable('#parts-document-table');
        }

        if($('#parts-form').length){
            partsForm();
        }

        if($('#parts-details-form').length){
            partsDetailsForm();
        }

        if($('#landed-cost-form').length){
            landedCostForm();
        }

        if($('#parts-expense-form').length){
            partsExpenseForm();
        }

        if($('#parts-document-form').length){
            partsDocumentForm();
        }

        if($('#parts-image-form').length){
            partsImageForm();
        }

        if($('#import-parts-form').length){
            importPartForm();
        }

        if($('#parts-import-table').length){
            importPartTable('#parts-import-table', false, true);
        }

        if($('#parts-category-reference-table').length){
            partsCategoryReferenceTable('#parts-category-reference-table');
        }

        if($('#parts-subcategory-reference-table').length){
            partsSubcategoryReferenceTable('#parts-subcategory-reference-table');
        }

        if($('#company-reference-table').length){
            companyReferenceTable('#company-reference-table');
        }

        if($('#warehouse-reference-table').length){
            warehouseReferenceTable('#warehouse-reference-table');
        }

        if($('#body-type-reference-table').length){
            bodyTypeReferenceTable('#body-type-reference-table');
        }

        if($('#unit-reference-table').length){
            unitReferenceTable('#unit-reference-table');
        }

        if($('#color-reference-table').length){
            colorReferenceTable('#color-reference-table');
        }

        if($('#parts_other_images').length){
            generatePartOtherImages();
        }

        $(document).on('click','.delete-parts',function() {
            const parts_id = $(this).data('parts-id');
            const transaction = 'delete parts';
    
            Swal.fire({
                title: 'Confirm Part Deletion',
                text: 'Are you sure you want to delete this parts?',
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
                        url: 'controller/parts-controller.php',
                        dataType: 'json',
                        data: {
                            parts_id : parts_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Success', 'The parts has been deleted successfully.', 'success');
                                partsTable('#parts-table', true, false);
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Part Error', 'The parts does not exist.', 'danger');
                                }
                                else {
                                    showNotification('Delete Part Error', response.message, 'danger');
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

        $(document).on('click','#delete-parts-details',function() {
            const parts_id = $('#part-id').text();
            const company_id = $('#page-company').val();
            const transaction = 'delete parts';
    
            Swal.fire({
                title: 'Confirm Part Deletion',
                text: 'Are you sure you want to delete this parts?',
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
                        url: 'controller/parts-controller.php',
                        dataType: 'json',
                        data: {
                            parts_id : parts_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Part Success', 'The parts has been deleted successfully.', 'success');
                                
                                if(company_id == '2'){
                                    window.location = 'netruck-parts.php';
                                }
                                else{
                                    window.location = 'parts.php';
                                }
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
                                    showNotification('Delete Part Error', response.message, 'danger');
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

        $(document).on('click','.delete-parts-image',function() {
            const parts_image_id = $(this).data('parts-image-id');
            const transaction = 'delete parts image';
    
            Swal.fire({
                title: 'Confirm Part Image Deletion',
                text: 'Are you sure you want to delete this parts image?',
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
                        url: 'controller/parts-controller.php',
                        dataType: 'json',
                        data: {
                            parts_image_id : parts_image_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Image Success', 'The parts image has been deleted successfully.', 'success');
                                generatePartOtherImages();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Part Error', 'The parts does not exist.', 'danger');
                                    generatePartOtherImages();
                                }
                                else {
                                    showNotification('Delete Part Error', response.message, 'danger');
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
        
        $(document).on('click','#apply-import-filter',function() {
            importPartTable('#parts-import-table', false, true);
        });

        $(document).on('change','#parts_image',function() {
            if ($(this).val() !== '' && $(this)[0].files.length > 0) {
                const transaction = 'update parts image';
                const parts_id = $('#part-id').text();
                var formData = new FormData();
                formData.append('parts_image', $(this)[0].files[0]);
                formData.append('transaction', transaction);
                formData.append('parts_id', parts_id);
        
                $.ajax({
                    type: 'POST',
                    url: 'controller/parts-controller.php',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('Update Part Thumbnail Success', 'The parts thumbnail has been updated successfully.', 'success');
                            displayDetails('get parts details');
                        }
                        else {
                            if (response.isInactive || response.userNotExist || response.userInactive || response.userLocked || response.sessionExpired) {
                                setNotification(response.title, response.message, response.messageType);
                                window.location = 'logout.php?logout';
                            }
                            else if (response.notExist) {
                                setNotification(response.title, response.message, response.messageType);
                                window.location = page_link;
                            }
                            else {
                                showNotification(response.title, response.message, response.messageType);
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

        $(document).on('change','#parts_other_image',function() {
            if ($(this).val() !== '' && $(this)[0].files.length > 0) {
                const transaction = 'insert parts image';
                const parts_id = $('#part-id').text();
                var formData = new FormData();
                formData.append('parts_other_image', $(this)[0].files[0]);
                formData.append('transaction', transaction);
                formData.append('parts_id', parts_id);
        
                $.ajax({
                    type: 'POST',
                    url: 'controller/parts-controller.php',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('Inser Part Image Success', 'The parts image has been inserted successfully.', 'success');
                            generatePartOtherImages();
                        }
                        else {
                            if (response.isInactive || response.userNotExist || response.userInactive || response.userLocked || response.sessionExpired) {
                                setNotification(response.title, response.message, response.messageType);
                                window.location = 'logout.php?logout';
                            }
                            else if (response.notExist) {
                                setNotification(response.title, response.message, response.messageType);
                                window.location = page_link;
                            }
                            else {
                                showNotification(response.title, response.message, response.messageType);
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

        $(document).on('click','#tag-parts-for-sale',function() {
            const parts_id = $('#part-id').text();
            const transaction = 'tag for sale';
    
            Swal.fire({
                title: 'Confirm Tagging of Part For Sale',
                text: 'Are you sure you want to tag this parts for sale?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Sale',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/parts-controller.php',
                        dataType: 'json',
                        data: {
                            parts_id : parts_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Part For Sale Success', 'The product has been tagged for sale successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.zeroPrice) {
                                    showNotification('Part Price Zero', 'The parts poduct price is set to 0. Check the field and try again.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Part For Sale Error', response.message, 'danger');
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

        $(document).on('click','.delete-parts-expense',function() {
            const parts_expense_id = $(this).data('parts-expense-id');
            const transaction = 'delete parts expense';
    
            Swal.fire({
                title: 'Confirm Expense Deletion',
                text: 'Are you sure you want to delete this expense?',
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
                        url: 'controller/parts-controller.php',
                        dataType: 'json',
                        data: {
                            parts_expense_id : parts_expense_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Expense Success', 'The expense has been deleted successfully.', 'success');
                                reloadDatatable('#parts-expense-table');
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
                                    showNotification('Delete Expense Error', response.message, 'danger');
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

        $(document).on('click','.delete-parts-document',function() {
            const parts_document_id = $(this).data('parts-document-id');
            const transaction = 'delete parts document';
    
            Swal.fire({
                title: 'Confirm Part Document Deletion',
                text: 'Are you sure you want to delete this parts document?',
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
                        url: 'controller/parts-controller.php',
                        dataType: 'json',
                        data: {
                            parts_document_id : parts_document_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Document Success', 'The parts document has been deleted successfully.', 'success');
                                reloadDatatable('#parts-document-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Part Error', 'The parts does not exist.', 'danger');
                                    generatePartOtherImages();
                                }
                                else {
                                    showNotification('Delete Part Error', response.message, 'danger');
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

        $(document).on('click', '#table-view-tab', function() {
            $('#datatable-length').removeClass('d-none');
        });
        
        $(document).on('click', '#card-view-tab', function() {
            $('#datatable-length').addClass('d-none');
        });

        $('#datatable-length').on('change', function() {
            var table = $('#parts-table').DataTable();
            var length = $(this).val(); 
            table.page.len(length).draw();
        });

        $(document).on('click','#generate-qr-code',function() {
            displayDetails('get parts qr code details');
        });

        $(document).on('click','#discard-create',function() {
            
            const company_id = $('#page-company').val();

             if(company_id == '2'){
                discardCreate('netruck-parts.php');
            }
            else{
                discardCreate('parts.php');
            }
        });

        $(document).on('click','#apply-filter',function() {
            if($('#parts-expense-table').length){
                partsExpenseTable('#parts-expense-table');
            }

            if($('#parts-table').length){
                partsTable('#parts-table', true, false);
            }
        });

        $(document).on('click','#submit-parts-transaction-filter',function() {
            if($('#parts-transaction-table').length){
                partTransactionTable('#parts-transaction-table');
            }

            $('#parts-transaction-filter-offcanvas').offcanvas('hide');
        });

        $(document).on('click','#submit-parts-incoming-filter',function() {
            if($('#parts-incoming-table').length){
                partIncomingTable('#parts-incoming-table');
            }

            $('#parts-incoming-filter-offcanvas').offcanvas('hide');
        });

        $(document).on('click','#submit-parts-transaction-filter',function() {
            if($('#parts-transaction-table').length){
                partTransactionTable('#parts-transaction-table');
            }

            $('#parts-transaction-filter-offcanvas').offcanvas('hide');
        });

        if($('#parts-incoming-table').length){
            partIncomingTable('#parts-incoming-table');
        }

        if($('#parts-transaction-table').length){
            partTransactionTable('#parts-transaction-table');
        }
    });
})(jQuery);

function partsForm(){
    $('#parts-form').validate({
        rules: {
            brand_id: {
                required: true
            },
            bar_code: {
                required: true
            },
            part_category_id: {
                required: true
            },
            part_class_id: {
                required: true
            },
            part_subclass_id: {
                required: true
            },
            quantity: {
                required: true
            },
            stock_alert: {
                required: true
            },
            unit_sale: {
                required: true
            },
            unit_purchase: {
                required: true
            },
            description: {
                required: true
            },
            warehouse_id: {
                required: true
            },
        },
        messages: {
            brand_id: {
                required: 'Please choose the brand'
            },
            bar_code: {
                required: 'Please enter the bar code'
            },
            part_category_id: {
                required: 'Please choose the category'
            },
            part_class_id: {
                required: 'Please choose the class'
            },
            part_subclass_id: {
                required: 'Please choose the subclass'
            },
            quantity: {
                required: 'Please enter the quantity'
            },
            stock_alert: {
                required: 'Please enter the stock alert'
            },
            unit_sale: {
                required: 'Please choose the unit sale'
            },
            unit_purchase: {
                required: 'Please choose the unit purchase'
            },
            description: {
                required: 'Please enter the description'
            },
            warehouse_id: {
                required: 'Please choose the warehouse'
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
            const parts_id = $('#part-id').text();
            const company_id = $('#page-company').val();
            const transaction = 'save new parts';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_id=' + parts_id + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Part Success' : 'Update Part Success';
                        const notificationDescription = response.insertRecord ? 'The parts has been inserted successfully.' : 'The parts has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        if(company_id == '2'){
                            window.location = 'netruck-parts.php?id=' + response.partsID;
                        }
                        else{
                            window.location = 'parts.php?id=' + response.partsID;
                        }
                        
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

function partsTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts table';
    var parts_search = $('#parts_search').val();
    var parts_status_filter = $('.parts-status-filter:checked').val();
    var filter_created_date_start_date = $('#filter_created_date_start_date').val();
    var filter_created_date_end_date = $('#filter_created_date_end_date').val();
    var filter_for_sale_date_start_date = $('#filter_for_sale_date_start_date').val();
    var filter_for_sale_date_end_date = $('#filter_for_sale_date_end_date').val();
    var company_filter_values = [];
    var brand_filter_values = [];
    var parts_category_filter_values = [];
    var parts_class_filter_values = [];
    var parts_subclass_filter_values = [];
    var warehouse_filter_values = [];
    const company_id = $('#page-company').val();

    $('.brand-filter:checked').each(function() {
        brand_filter_values.push($(this).val());
    });

    $('.parts-category-filter:checked').each(function() {
        parts_category_filter_values.push($(this).val());
    });

    $('.parts-class-filter:checked').each(function() {
        parts_class_filter_values.push($(this).val());
    });

    $('.parts-subclass-filter:checked').each(function() {
        parts_subclass_filter_values.push($(this).val());
    });

    $('.company-filter:checked').each(function() {
        company_filter_values.push($(this).val());
    });

    $('.warehouse-filter:checked').each(function() {
        warehouse_filter_values.push($(this).val());
    });

    var company_filter = company_filter_values.join(', ');
    var brand_filter = brand_filter_values.join(', ');
    var parts_category_filter = parts_category_filter_values.join(', ');
    var parts_class_filter = parts_class_filter_values.join(', ');
    var parts_subclass_filter = parts_subclass_filter_values.join(', ');
    var warehouse_filter = warehouse_filter_values.join(', ');
    var settings;

    let column = [];
    let column_definition = [];

    if($('#cost_column').length){
        column = [ 
            { 'data' : 'CHECK_BOX' },
            { 'data' : 'IMAGE' },
            { 'data' : 'PART' },
            { 'data' : 'CATEGORY' },
            { 'data' : 'CLASS' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'PRODUCT_COST' },
            { 'data' : 'PRODUCT_PRICE' },
            { 'data' : 'PRODUCT_STATUS' },
            { 'data' : 'ACTION' }
        ];

        column_definition = [
            { 'width': '1%','bSortable': false, 'aTargets': 0 },
            { 'width': '1%','bSortable': false, 'aTargets': 1 },
            { 'width': '40%', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
            { 'width': 'auto', 'aTargets': 7 },
            { 'width': 'auto', 'aTargets': 8 },
            { 'width': '10%','bSortable': false, 'aTargets': 9 }
        ];
    }
    else{
        column = [ 
            { 'data' : 'CHECK_BOX' },
            { 'data' : 'IMAGE' },
            { 'data' : 'PART' },
            { 'data' : 'CATEGORY' },
            { 'data' : 'CLASS' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'PRODUCT_PRICE' },
            { 'data' : 'PRODUCT_STATUS' },
            { 'data' : 'ACTION' }
        ];

        column_definition = [
            { 'width': '1%','bSortable': false, 'aTargets': 0 },
            { 'width': '1%','bSortable': false, 'aTargets': 1 },
            { 'width': '40%', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
            { 'width': 'auto', 'aTargets': 7 },
            { 'width': '10%','bSortable': false, 'aTargets': 8 }
        ];
    }    

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'parts_search' : parts_search,
                'company_filter' : company_filter,
                'brand_filter' : brand_filter,
                'parts_category_filter' : parts_category_filter,
                'parts_class_filter' : parts_class_filter,
                'parts_subclass_filter' : parts_subclass_filter,
                'warehouse_filter' : warehouse_filter,
                'filter_created_date_start_date' : filter_created_date_start_date,
                'filter_created_date_end_date' : filter_created_date_end_date,
                'filter_for_sale_date_start_date' : filter_for_sale_date_start_date,
                'filter_for_sale_date_end_date' : filter_for_sale_date_end_date,
                'parts_status_filter' : parts_status_filter,
                'company_id' : company_id,
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'dom': 'Brtip',
        'order': [[ 2, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'searching': false,
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

function partsExpenseTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts expense table';
    const parts_id = $('#part-id').text();
    const reference_type_filter = $('#reference_type_filter').val();
    const expense_type_filter = $('#expense_type_filter').val();
    var settings;

    const column = [ 
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'REFERENCE_TYPE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'EXPENSE_TYPE' },
        { 'data' : 'EXPENSE_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '20%', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '5%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'parts_id' : parts_id,
                'reference_type_filter' : reference_type_filter,
                'expense_type_filter' : expense_type_filter
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'dom': 'Brtip',
        'order': [[ 0, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        },
       'footerCallback': function(row, data, start, end, display) {
            var api = this.api();

            // Calculate the total for EXPENSE_AMOUNT across all pages
            var total = api
                .column(5, { page: 'all' })  // Use { page: 'all' } for total of all pages
                .data()
                .reduce(function (a, b) {
                    // Remove commas and parse each amount as a float
                    return a + parseFloat(b.replace(/,/g, '') || 0);
                }, 0);

            // Format total with thousand separators
            var formattedTotal = total.toLocaleString('en-US', { minimumFractionDigits: 2 });

            // Update footer with formatted total
            $(api.column(5).footer()).html('<b>Total: ' + formattedTotal + '</b>');
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function partsDocumentTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts document table';
    const parts_id = $('#part-id').text();
    var settings;

    const column = [ 
        { 'data' : 'DOCUMENT_TYPE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': '5%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'parts_id' : parts_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'dom': 'Brtip',
        'order': [[ 0, 'desc' ]],
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

function generatePartOtherImages(){
    const parts_id = $('#part-id').text();
    const type = 'parts image cards';

    $.ajax({
        type: 'POST',
        url: 'view/_parts_generation.php',
        dataType: 'json',
        data: { type: type, 'parts_id' : parts_id },
        beforeSend: function(){
            document.getElementById('parts_other_images').innerHTML = '<div class="text-center"><div class="spinner-grow text-dark" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        },
        success: function (result) {
            document.getElementById('parts_other_images').innerHTML = result[0].OTHER_IMAGE;
        }
    });
}

function partsDetailsForm(){
    $('#parts-details-form').validate({
        rules: {
            brand_id: {
                required: true
            },
            bar_code: {
                required: true
            },
            part_number: {
                required: true
            },
            part_category_id: {
                required: true
            },
            part_class_id: {
                required: true
            },
            part_subclass_id: {
                required: true
            },
            company_id: {
                required: true
            },
            quantity: {
                required: true
            },
            stock_alert: {
                required: true
            },
            unit_sale: {
                required: true
            },
            unit_purchase: {
                required: true
            },
            description: {
                required: true
            },
            warehouse_id: {
                required: true
            },
        },
        messages: {
            brand_id: {
                required: 'Please choose the brand'
            },
            part_number: {
                required: 'Please enter the part number'
            },
            bar_code: {
                required: 'Please enter the bar code'
            },
            part_category_id: {
                required: 'Please choose the category'
            },
            part_class_id: {
                required: 'Please choose the class'
            },
            part_subclass_id: {
                required: 'Please choose the subclass'
            },
            company_id: {
                required: 'Please choose the company'
            },
            quantity: {
                required: 'Please enter the quantity'
            },
            stock_alert: {
                required: 'Please enter the stock alert'
            },
            unit_sale: {
                required: 'Please choose the unit sale'
            },
            unit_purchase: {
                required: 'Please choose the unit purchase'
            },
            description: {
                required: 'Please enter the description'
            },
            warehouse_id: {
                required: 'Please choose the warehouse'
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
            const parts_id = $('#part-id').text();
            const transaction = 'save parts details';
            const company_id = $('#page-company').val();
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_id=' + parts_id + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-parts-details-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Part Success' : 'Update Part Success';
                        const notificationDescription = response.insertRecord ? 'The parts has been inserted successfully.' : 'The parts has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-parts-details-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function partsExpenseForm(){
    $('#parts-expense-form').validate({
        rules: {
            reference_type: {
                required: true
            },
            reference_number: {
                required: true
            },
            expense_amount: {
                required: true
            },
            expense_type: {
                required: true
            },
            particulars: {
                required: true
            },
        },
        messages: {
            reference_type: {
                required: 'Please choose the reference type'
            },
            reference_number: {
                required: 'Please enter the reference number'
            },
            expense_amount: {
                required: 'Please enter the amount'
            },
            expense_type: {
                required: 'Please choose the expense type'
            },
            particulars: {
                required: 'Please enter the particulars'
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
            const parts_id = $('#part-id').text();
            const transaction = 'save parts expense';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_id=' + parts_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-parts-expense');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Expense Success' : 'Update Landed Cost Success';
                        const notificationDescription = response.insertRecord ? 'The expense has been inserted successfully.' : 'The landed cost has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-parts-expense', 'Submit');
                    reloadDatatable('#parts-expense-table');
                    $('#parts-expense-offcanvas').offcanvas('hide');
                    resetModalForm('parts-expense-form');
                }
            });
        
            return false;
        }
    });
}

function partsDocumentForm(){
    $('#parts-document-form').validate({
        rules: {
            document_type: {
                required: true
            },
            parts_document: {
                required: true
            },
        },
        messages: {
            document_type: {
                required: 'Please choose the document type'
            },
            parts_document: {
                required: 'Please choose the document'
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
            const parts_id = $('#part-id').text();
            const transaction = 'add parts document';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('parts_id', parts_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-parts-document');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Document Success';
                        const notificationDescription = 'The document has been inserted successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-parts-document', 'Submit');
                    reloadDatatable('#parts-document-table');
                    $('#parts-document-offcanvas').offcanvas('hide');
                    resetModalForm('parts-document-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get parts details':
            const parts_id = $('#part-id').text();
            
            $.ajax({
                url: 'controller/parts-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_id : parts_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('parts-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#parts_id').val(parts_id);
                        $('#bar_code').val(response.bar_code);
                        $('#part_number').val(response.part_number);
                        $('#quantity').val(response.quantity);
                        $('#stock_alert').val(response.stock_alert);
                        $('#description').val(response.description);
                        $('#remarks').val(response.remarks);
                        $('#part_price').val(response.part_price);

                        document.getElementById('parts_thumbnail').src = response.part_image;

                        checkOptionExist('#brand_id', response.brand_id, '');
                        checkOptionExist('#part_category_id', response.part_category_id, '');
                        checkOptionExist('#part_class_id', response.part_class_id, '');
                        checkOptionExist('#part_subclass_id', response.part_subclass_id, '');
                        checkOptionExist('#unit_sale', response.unit_sale, '');
                        checkOptionExist('#warehouse_id', response.warehouse_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Details Error', response.message, 'danger');
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

function partIncomingTable(datatable_name, buttons = false, show_all = false){
    const parts_id = $('#part-id').text();
    var view_cost = $('#view-cost').val();

    var parts_incoming_start_date = $('#parts_incoming_start_date').val();
    var parts_incoming_end_date = $('#parts_incoming_end_date').val();

    const type = 'part item table 2';
    var settings;

    if(view_cost > 0){
        var column = [ 
            { 'data' : 'REFERENCE_NUMBER' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'COST' },
            { 'data' : 'TOTAL_COST' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 }
        ];
    }
    else{
        var column = [ 
            { 'data' : 'REFERENCE_NUMBER' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 }
        ];
    }

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'parts_id' : parts_id,
                'parts_incoming_start_date' : parts_incoming_start_date,
                'parts_incoming_end_date' : parts_incoming_end_date,
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function partTransactionTable(datatable_name, buttons = false, show_all = false){
    const parts_id = $('#part-id').text();
    const type = 'part item table 2';
    
    var parts_transaction_start_date = $('#parts_transaction_start_date').val();
    var parts_transaction_end_date = $('#parts_transaction_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'PART_TRANSACTION_NO' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'DISCOUNT' },
        { 'data' : 'DISCOUNT_TOTAL' },
        { 'data' : 'SUBTOTAL' },
        { 'data' : 'TOTAL' }
    ];

     var column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'parts_id' : parts_id,
                'parts_transaction_start_date' : parts_transaction_start_date,
                'parts_transaction_end_date' : parts_transaction_end_date,
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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