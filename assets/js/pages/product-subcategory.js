(function($) {
    'use strict';

    $(function() {
        if($('#product-subcategory-table').length){
            productSubcategoryTable('#product-subcategory-table');
        }

        if($('#product-subcategory-form').length){
            productSubcategoryForm();
        }

        if($('#product-subcategory-id').length){
            displayDetails('get product subcategory details');
        }

        $(document).on('click','.delete-product-subcategory',function() {
            const product_subcategory_id = $(this).data('product-subcategory-id');
            const transaction = 'delete product subcategory';
    
            Swal.fire({
                title: 'Confirm Product Subcategory Deletion',
                text: 'Are you sure you want to delete this product subcategory?',
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
                        url: 'controller/product-subcategory-controller.php',
                        dataType: 'json',
                        data: {
                            product_subcategory_id : product_subcategory_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Subcategory Success', 'The product subcategory has been deleted successfully.', 'success');
                                reloadDatatable('#product-subcategory-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Product Subcategory Error', 'The product subcategory does not exist.', 'danger');
                                    reloadDatatable('#product-subcategory-table');
                                }
                                else {
                                    showNotification('Delete Product Subcategory Error', response.message, 'danger');
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

        $(document).on('click','#delete-product-subcategory',function() {
            let product_subcategory_id = [];
            const transaction = 'delete multiple product subcategory';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    product_subcategory_id.push(element.value);
                }
            });
    
            if(product_subcategory_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Product Subcategories Deletion',
                    text: 'Are you sure you want to delete these product subcategories?',
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
                            url: 'controller/product-subcategory-controller.php',
                            dataType: 'json',
                            data: {
                                product_subcategory_id: product_subcategory_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Product Subcategory Success', 'The selected product subcategories have been deleted successfully.', 'success');
                                    reloadDatatable('#product-subcategory-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Product Subcategory Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Product Subcategories Error', 'Please select the product subcategories you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-product-subcategory-details',function() {
            const product_subcategory_id = $('#product-subcategory-id').text();
            const transaction = 'delete product subcategory';
    
            Swal.fire({
                title: 'Confirm Product Subcategory Deletion',
                text: 'Are you sure you want to delete this product subcategory?',
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
                        url: 'controller/product-subcategory-controller.php',
                        dataType: 'json',
                        data: {
                            product_subcategory_id : product_subcategory_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Product Subcategory Success', 'The product subcategory has been deleted successfully.', 'success');
                                window.location = 'product-subcategory.php';
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
                                    showNotification('Delete Product Subcategory Error', response.message, 'danger');
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
            discardCreate('product-subcategory.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get product subcategory details');

            enableForm();
        });

        $(document).on('click','#duplicate-product-subcategory',function() {
            const product_subcategory_id = $('#product-subcategory-id').text();
            const transaction = 'duplicate product subcategory';
    
            Swal.fire({
                title: 'Confirm Product Subcategory Duplication',
                text: 'Are you sure you want to duplicate this product subcategory?',
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
                        url: 'controller/product-subcategory-controller.php',
                        dataType: 'json',
                        data: {
                            product_subcategory_id : product_subcategory_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Product Subcategory Success', 'The product subcategory has been duplicated successfully.', 'success');
                                window.location = 'product-subcategory.php?id=' + response.productSubcategoryID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Product Subcategory Error', 'The product subcategory does not exist.', 'danger');
                                    reloadDatatable('#product-subcategory-table');
                                }
                                else {
                                    showNotification('Duplicate Product Subcategory Error', response.message, 'danger');
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

function productSubcategoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'product subcategory table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PRODUCT_SUBCATEGORY_CODE' },
        { 'data' : 'PRODUCT_SUBCATEGORY' },
        { 'data' : 'PRODUCT_CATEGORY' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '30%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_subcategory_generation.php',
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
        'order': [[ 2, 'asc' ]],
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

function productSubcategoryForm(){
    $('#product-subcategory-form').validate({
        rules: {
            product_subcategory_name: {
                required: true
            },
            product_subcategory_code: {
                required: true
            },
            product_category_id: {
                required: true
            },
        },
        messages: {
            product_subcategory_name: {
                required: 'Please enter the product subcategory name'
            },
            product_subcategory_code: {
                required: 'Please enter the code'
            },
            product_category_id: {
                required: 'Please choose the product category'
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
            const product_subcategory_id = $('#product-subcategory-id').text();
            const transaction = 'save product subcategory';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-subcategory-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&product_subcategory_id=' + product_subcategory_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Product Subcategory Success' : 'Update Product Subcategory Success';
                        const notificationDescription = response.insertRecord ? 'The product subcategory has been inserted successfully.' : 'The product subcategory has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'product-subcategory.php?id=' + response.productSubcategoryID;
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
        case 'get product subcategory details':
            const product_subcategory_id = $('#product-subcategory-id').text();
            
            $.ajax({
                url: 'controller/product-subcategory-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    product_subcategory_id : product_subcategory_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('product-subcategory-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#product_subcategory_id').val(product_subcategory_id);
                        $('#product_subcategory_name').val(response.productSubcategoryName);
                        $('#product_subcategory_code').val(response.productSubcategoryCode);

                        checkOptionExist('#product_category_id', response.productCategoryID, '');

                        $('#product_subcategory_name_label').text(response.productSubcategoryName);
                        $('#product_subcategory_code_label').text(response.productSubcategoryCode);
                        $('#product_category_id_label').text(response.productCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Product Subcategory Details Error', response.message, 'danger');
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