(function($) {
    'use strict';

    $(function() {
        if($('#product-category-table').length){
            productCategoryTable('#product-category-table');
        }

        if($('#product-category-form').length){
            productCategoryForm();
        }

        if($('#product-category-id').length){
            displayDetails('get product category details');
        }

        $(document).on('click','.delete-product-category',function() {
            const product_category_id = $(this).data('product-category-id');
            const transaction = 'delete product category';
    
            Swal.fire({
                title: 'Confirm Product Category Deletion',
                text: 'Are you sure you want to delete this product category?',
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
                        url: 'controller/product-category-controller.php',
                        dataType: 'json',
                        data: {
                            product_category_id : product_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Category Success', 'The product category has been deleted successfully.', 'success');
                                reloadDatatable('#product-category-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Product Category Error', 'The product category does not exist.', 'danger');
                                    reloadDatatable('#product-category-table');
                                }
                                else {
                                    showNotification('Delete Product Category Error', response.message, 'danger');
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

        $(document).on('click','#delete-product-category',function() {
            let product_category_id = [];
            const transaction = 'delete multiple product category';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    product_category_id.push(element.value);
                }
            });
    
            if(product_category_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Product Categories Deletion',
                    text: 'Are you sure you want to delete these product categories?',
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
                            url: 'controller/product-category-controller.php',
                            dataType: 'json',
                            data: {
                                product_category_id: product_category_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Product Category Success', 'The selected product categories have been deleted successfully.', 'success');
                                    reloadDatatable('#product-category-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Product Category Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Product Category Error', 'Please select the product categories you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-product-category-details',function() {
            const product_category_id = $('#product-category-id').text();
            const transaction = 'delete product category';
    
            Swal.fire({
                title: 'Confirm Product Category Deletion',
                text: 'Are you sure you want to delete this product category?',
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
                        url: 'controller/product-category-controller.php',
                        dataType: 'json',
                        data: {
                            product_category_id : product_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Product Category Success', 'The product category has been deleted successfully.', 'success');
                                window.location = 'product-category.php';
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
                                    showNotification('Delete Product Category Error', response.message, 'danger');
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
            discardCreate('product-category.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get product category details');

            enableForm();
        });

        $(document).on('click','#duplicate-product-category',function() {
            const product_category_id = $('#product-category-id').text();
            const transaction = 'duplicate product category';
    
            Swal.fire({
                title: 'Confirm Product Category Duplication',
                text: 'Are you sure you want to duplicate this product category?',
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
                        url: 'controller/product-category-controller.php',
                        dataType: 'json',
                        data: {
                            product_category_id : product_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Product Category Success', 'The product category has been duplicated successfully.', 'success');
                                window.location = 'product-category.php?id=' + response.productCategoryID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Product Category Error', 'The product category does not exist.', 'danger');
                                    reloadDatatable('#product-category-table');
                                }
                                else {
                                    showNotification('Duplicate Product Category Error', response.message, 'danger');
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

function productCategoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'product category table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PRODUCT_CATEGORY_NAME' },
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
            'url' : 'view/_product_category_generation.php',
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

function productCategoryForm(){
    $('#product-category-form').validate({
        rules: {
            product_category_name: {
                required: true
            },
        },
        messages: {
            product_category_name: {
                required: 'Please enter the product category name'
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
            const product_category_id = $('#product-category-id').text();
            const transaction = 'save product category';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-category-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&product_category_id=' + product_category_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Product Category Success' : 'Update Product Category Success';
                        const notificationDescription = response.insertRecord ? 'The product category has been inserted successfully.' : 'The product category has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'product-category.php?id=' + response.productCategoryID;
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
        case 'get product category details':
            const product_category_id = $('#product-category-id').text();
            
            $.ajax({
                url: 'controller/product-category-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    product_category_id : product_category_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('product-category-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#product_category_id').val(product_category_id);
                        $('#product_category_name').val(response.productCategoryName);

                        $('#product_category_name_label').text(response.productCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Product Category Details Error', response.message, 'danger');
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