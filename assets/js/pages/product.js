(function($) {
    'use strict';

    $(function() {
        var page = 1;
        var is_loading = false;
        var productCard = $('#product-card');
        var loadContent = $('#load-content');
        var productSearch = $('#product_search');
        var lastSearchValue = '';

        var debounceTimeout;

        function debounce(func, delay) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(func, delay);
        }

        function loadProductCard(current_page, is_loading, clearExisting) {
            if (is_loading) return;

            var product_search = productSearch.val();
            var filter_product_cost_min = $('#filter_product_cost_min').val();
            var filter_product_cost_max = $('#filter_product_cost_max').val();
            var filter_product_price_min = $('#filter_product_price_min').val();
            var filter_product_price_max = $('#filter_product_price_max').val();
            var company_filter_values = [];
            var product_category_filter_values = [];
            var product_subcategory_filter_values = [];
            var warehouse_filter_values = [];
            var body_type_filter_values = [];
            var color_filter_values = [];

            $('.company-filter:checked').each(function() {
                company_filter_values.push($(this).val());
            });

            $('.product-category-filter:checked').each(function() {
                product_category_filter_values.push($(this).val());
            });

            $('.product-subcategory-filter:checked').each(function() {
                product_subcategory_filter_values.push($(this).val());
            });

            $('.warehouse-filter:checked').each(function() {
                warehouse_filter_values.push($(this).val());
            });

            $('.body-type-filter:checked').each(function() {
                body_type_filter_values.push($(this).val());
            });

            $('.color-filter:checked').each(function() {
                color_filter_values.push($(this).val());
            });
        
            var company_filter = company_filter_values.join(', ');
            var product_category_filter = product_category_filter_values.join(', ');
            var product_subcategory_filter = product_subcategory_filter_values.join(', ');
            var warehouse_filter = warehouse_filter_values.join(', ');
            var body_type_filter = body_type_filter_values.join(', ');
            var color_filter = color_filter_values.join(', ');
        
            lastSearchValue = product_search;

            is_loading = true;
            const type = 'product card';

            if (clearExisting) {
                productCard.empty();
            }

            loadContent.removeClass('d-none');

            $.ajax({
                url: 'view/_product_generation.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    current_page: current_page,
                    product_search: product_search,
                    company_filter: company_filter,
                    product_category_filter: product_category_filter,
                    product_subcategory_filter: product_subcategory_filter,
                    warehouse_filter: warehouse_filter,
                    body_type_filter: body_type_filter,
                    filter_product_cost_min: filter_product_cost_min,
                    filter_product_cost_max: filter_product_cost_max,
                    filter_product_price_min: filter_product_price_min,
                    filter_product_price_max: filter_product_price_max,
                    color_filter: color_filter,
                    type: type
                },
                success: function(response) {
                    is_loading = false;

                    loadContent.addClass('d-none');

                    if (response.length === 0) {
                        if (current_page === 1) {
                            productCard.html('<div class="col-lg-12 text-center">No product found.</div>');
                        }
                        return;
                    }

                    response.forEach(function(item) {
                        productCard.append(item.productCard);
                    });

                    productCard.find('.no-search-result').remove();
                },
                error: function(xhr, status, error) {
                    is_loading = false;

                    loadContent.addClass('d-none');

                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
        }

        function resetAndLoadProductCard() {
            page = 1;
            loadProductCard(page, false, true);
        }

        function debounceAndReset() {
            debounce(function() {
                resetAndLoadProductCard();
            }, 300);
        }

        if (productCard.length) {
            loadProductCard(page, is_loading, true);
            productSearch.on('keyup', function() {
                debounceAndReset();
            });

            $(document).on('click','#apply-filter',function() {
                debounceAndReset();
            });
        }

        productSearch.val(lastSearchValue);

        if($('#product-id').length){
            displayDetails('get product details');
        }

        if($('#product-form').length){
            productForm();
        }

        if($('#product-image-form').length){
            productImageForm();
        }

        $(document).on('click','.delete-product',function() {
            const product_id = $(this).data('product-id');
            const transaction = 'delete product';
    
            Swal.fire({
                title: 'Confirm Product Deletion',
                text: 'Are you sure you want to delete this product?',
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
                        url: 'controller/product-controller.php',
                        dataType: 'json',
                        data: {
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Success', 'The product has been deleted successfully.', 'success');
                                debounceAndReset();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Product Error', 'The product does not exist.', 'danger');
                                    debounceAndReset();
                                }
                                else {
                                    showNotification('Delete Product Error', response.message, 'danger');
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

        $(document).on('click','#delete-product-details',function() {
            const product_id = $('#product-id').text();
            const transaction = 'delete product';
    
            Swal.fire({
                title: 'Confirm Product Deletion',
                text: 'Are you sure you want to delete this product?',
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
                        url: 'controller/product-controller.php',
                        dataType: 'json',
                        data: {
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Product Success', 'The product has been deleted successfully.', 'success');
                                window.location = 'product.php';
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
                                    showNotification('Delete Product Error', response.message, 'danger');
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

function productForm(){
    $('#product-form').validate({
        rules: {
            company_id: {
                required: true
            },
            product_subcategory_id: {
                required: true
            },
            stock_number: {
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
            company_id: {
                required: 'Please choose the company'
            },
            product_subcategory_id: {
                required: 'Please choose the product category'
            },
            stock_number: {
                required: 'Please enter the stock number'
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
            const product_id = $('#product-id').text();
            const transaction = 'save product';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&product_id=' + product_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Product Success' : 'Update Product Success';
                        const notificationDescription = response.insertRecord ? 'The product has been inserted successfully.' : 'The product has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'product.php?id=' + response.productID;
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

function productImageForm(){
    $('#product-image-form').validate({
        rules: {
            product_image: {
                required: true
            }
        },
        messages: {
            product_image: {
                required: 'Please choose the image'
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
            const product_id = $('#product-id').text();
            const transaction = 'update product image';
            var formData = new FormData(form);
            formData.append('product_id', product_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-image-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Product Image Success';
                        const notificationDescription = 'The product image has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.notExist) {
                            setNotification('Transaction Error', 'The product does not exists.', 'danger');
                            window.location = '404.php';
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
                    enableFormSubmitButton('submit-product-image-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get product details':
            const product_id = $('#product-id').text();
            
            $.ajax({
                url: 'controller/product-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    product_id : product_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('product-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#product_id').val(product_id);
                        $('#description').val(response.description);
                        $('#stock_number').val(response.stockNumber);
                        $('#engine_number').val(response.engineNumber);
                        $('#chassis_number').val(response.chassisNumber);
                        $('#running_hours').val(response.runningHours);
                        $('#mileage').val(response.mileage);
                        $('#length').val(response.length);
                        $('#product_price').val(response.productPrice);
                        $('#product_cost').val(response.productCost);
                        $('#remarks').val(response.remarks);

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#product_subcategory_id', response.productSubcategoryID, '');
                        checkOptionExist('#warehouse_id', response.warehouseID, '');
                        checkOptionExist('#body_type_id', response.bodyTypeID, '');
                        checkOptionExist('#color_id', response.colorID, '');
                        checkOptionExist('#length_unit', response.lengthUnit, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Product Details Error', response.message, 'danger');
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