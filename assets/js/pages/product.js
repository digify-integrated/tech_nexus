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

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    if (!is_loading) {
                        page++;
                        loadProductCard(page, is_loading, false);
                    }
                }
            });
        }

        productSearch.val(lastSearchValue);

        if($('#product-id').length){
            displayDetails('get product details');
        }

        if($('#product-form').length){
            productForm();
        }

        if($('#product-details-form').length){
            productDetailsForm();
        }

        if($('#landed-cost-form').length){
            landedCostForm();
        }

        if($('#product-image-form').length){
            productImageForm();
        }

        if($('#import-product-form').length){
            importProductForm();
        }

        if($('#product-import-table').length){
            importProductTable('#product-import-table', false, true);
        }

        if($('#product-category-reference-table').length){
            productCategoryReferenceTable('#product-category-reference-table');
        }

        if($('#product-subcategory-reference-table').length){
            productSubcategoryReferenceTable('#product-subcategory-reference-table');
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

        if($('#product_other_images').length){
            generateProductOtherImages();
        }

        $(document).on('click','#import-product',function() {
            let temp_product_id = [];
            const transaction = 'save imported product';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    temp_product_id.push(element.value);
                }
            });
    
            if(temp_product_id.length > 0){
                Swal.fire({
                    title: 'Confirm Imported Products Saving',
                    text: 'Are you sure you want to save these imported products?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Import',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/product-controller.php',
                            dataType: 'json',
                            data: {
                                temp_product_id: temp_product_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    setNotification('Save Imported Product Success', 'The selected imported products have been saved successfully.', 'success');
                                    window.location = 'product.php';
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Save Imported Product Error', response.message, 'danger');
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
                showNotification('Save Imported Product Error', 'Please select the imported products you wish to save.', 'danger');
            }
        });

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

        $(document).on('click','.delete-product-image',function() {
            const product_image_id = $(this).data('product-image-id');
            const transaction = 'delete product image';
    
            Swal.fire({
                title: 'Confirm Product Image Deletion',
                text: 'Are you sure you want to delete this product image?',
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
                            product_image_id : product_image_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Image Success', 'The product image has been deleted successfully.', 'success');
                                generateProductOtherImages();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Product Error', 'The product does not exist.', 'danger');
                                    generateProductOtherImages();
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
        
        $(document).on('click','#apply-import-filter',function() {
            importProductTable('#product-import-table', false, true);
        });

        $(document).on('change','#product_image',function() {
            if ($(this).val() !== '' && $(this)[0].files.length > 0) {
                const transaction = 'update product image';
                const product_id = $('#product-id').text();
                var formData = new FormData();
                formData.append('product_image', $(this)[0].files[0]);
                formData.append('transaction', transaction);
                formData.append('product_id', product_id);
        
                $.ajax({
                    type: 'POST',
                    url: 'controller/product-controller.php',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('Update Product Thumbnail Success', 'The product thumbnail has been updated successfully.', 'success');
                            displayDetails('get product details');
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

        $(document).on('change','#product_other_image',function() {
            if ($(this).val() !== '' && $(this)[0].files.length > 0) {
                const transaction = 'insert product image';
                const product_id = $('#product-id').text();
                var formData = new FormData();
                formData.append('product_other_image', $(this)[0].files[0]);
                formData.append('transaction', transaction);
                formData.append('product_id', product_id);
        
                $.ajax({
                    type: 'POST',
                    url: 'controller/product-controller.php',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('Inser Product Image Success', 'The product image has been inserted successfully.', 'success');
                            generateProductOtherImages();
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

        $(document).on('click','#discard-create',function() {
            discardCreate('product.php');
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

function generateProductOtherImages(){
    const product_id = $('#product-id').text();
    const type = 'product image cards';

    $.ajax({
        type: 'POST',
        url: 'view/_product_generation.php',
        dataType: 'json',
        data: { type: type, 'product_id' : product_id },
        beforeSend: function(){
            document.getElementById('product_other_images').innerHTML = '<div class="text-center"><div class="spinner-grow text-dark" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        },
        success: function (result) {
            document.getElementById('product_other_images').innerHTML = result[0].OTHER_IMAGE;
        }
    });
}

function productDetailsForm(){
    $('#product-details-form').validate({
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
            const transaction = 'save product details';
        
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

function landedCostForm(){
    $('#landed-cost-form').validate({
        rules: {
            product_price: {
                required: true
            },
            product_cost: {
                required: true
            },
        },
        messages: {
            product_price: {
                required: 'Please enter the product price'
            },
            product_cost: {
                required: 'Please enter the product cost'
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
            const transaction = 'save landed cost';
        
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

function importProductForm(){
    $('#import-product-form').validate({
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
            const transaction = 'save product import';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-load-file');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Product File Load Success', 'The product file has been loaded successfully.', 'success');
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

function importProductTable(datatable_name, buttons = false, show_all = false){
    const type = 'import product table';
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

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PRODUCT_ID' },
        { 'data' : 'PRODUCT_CATEGORY' },
        { 'data' : 'PRODUCT_SUBCATEGORY' },
        { 'data' : 'COMPANY_NAME' },
        { 'data' : 'PRODUCT_STATUS' },
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'ENGINE_NUMBER' },
        { 'data' : 'CHASSIS_NUMBER' },
        { 'data' : 'PLATE_NUMBER' },
        { 'data' : 'DESCRIPTION' },
        { 'data' : 'WAREHOUSE_NAME' },
        { 'data' : 'BODY_TYPE_NAME' },
        { 'data' : 'LENGTH' },
        { 'data' : 'RUNNING_HOURS' },
        { 'data' : 'MILEAGE' },
        { 'data' : 'COLOR' },
        { 'data' : 'PRODUCT_COST' },
        { 'data' : 'PRODUCT_PRICE' },
        { 'data' : 'REMARKS' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
        { 'width': 'auto', 'aTargets': 14 },
        { 'width': 'auto', 'aTargets': 15 },
        { 'width': 'auto', 'aTargets': 16 },
        { 'width': 'auto', 'aTargets': 17 },
        { 'width': 'auto', 'aTargets': 18 },
        { 'width': 'auto', 'aTargets': 19 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'company_filter' : company_filter, 'product_category_filter' : product_category_filter, 'product_subcategory_filter' : product_subcategory_filter, 'warehouse_filter' : warehouse_filter, 'body_type_filter' : body_type_filter, 'color_filter' : color_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 2, 'desc' ]],
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
                        $('#plate_number').val(response.plateNumber);
                        $('#running_hours').val(response.runningHours);
                        $('#mileage').val(response.mileage);
                        $('#length').val(response.length);
                        $('#product_price').val(response.productPrice);
                        $('#product_cost').val(response.productCost);
                        $('#remarks').val(response.remarks);

                        $('#orcr_no').val(response.orcrNo);
                        $('#orcr_date').val(response.orcrDate);
                        $('#orcr_expiry_date').val(response.orcrExpiryDate);
                        $('#received_from').val(response.receivedFrom);
                        $('#received_from_address').val(response.receivedFromAddress);
                        $('#received_from_id_number').val(response.receivedFromIDNumber);
                        $('#unit_description').val(response.unitDescription);
                        
                        $('#rr_no').val(response.rr_no);
                        $('#ref_no').val(response.ref_no);
                        $('#broker').val(response.broker);
                        $('#registered_owner').val(response.registered_owner);
                        $('#mode_of_registration').val(response.mode_of_registration);
                        $('#year_model').val(response.year_model);
                        $('#fx_rate').val(response.fx_rate);
                        $('#unit_cost').val(response.unit_cost);
                        $('#package_deal').val(response.package_deal);
                        $('#taxes_duties').val(response.taxes_duties);
                        $('#freight').val(response.freight);
                        $('#lto_registration').val(response.lto_registration);
                        $('#royalties').val(response.royalties);
                        $('#conversion').val(response.conversion);
                        $('#arrastre').val(response.arrastre);
                        $('#wharrfage').val(response.wharrfage);
                        $('#insurance').val(response.insurance);
                        $('#aircon').val(response.aircon);
                        $('#import_permit').val(response.import_permit);
                        $('#others').val(response.others);
                        $('#sub_total').val(response.sub_total);
                        $('#total_landed_cost').val(response.total_landed_cost);
                        $('#with_cr').val(response.with_cr);
                        $('#with_plate').val(response.with_plate);
                        $('#returned_to_supplier').val(response.returned_to_supplier);
                        $('#quantity').val(response.quantity);
                        $('#rr_date').val(response.rrDate);
                        $('#arrival_date').val(response.arrival_date);
                        $('#checklist_date').val(response.checklist_date);

                        document.getElementById('product_thumbnail').src = response.productImage;

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#product_subcategory_id', response.productSubcategoryID, '');
                        checkOptionExist('#warehouse_id', response.warehouseID, '');
                        checkOptionExist('#body_type_id', response.bodyTypeID, '');
                        checkOptionExist('#color_id', response.colorID, '');
                        checkOptionExist('#length_unit', response.lengthUnit, '');

                        checkOptionExist('#supplier_id', response.supplier_id, '');
                        checkOptionExist('#brand_id', response.brand_id, '');
                        checkOptionExist('#cabin_id', response.cabin_id, '');
                        checkOptionExist('#model_id', response.model_id, '');
                        checkOptionExist('#make_id', response.make_id, '');
                        checkOptionExist('#class_id', response.class_id, '');
                        checkOptionExist('#mode_of_acquisition_id', response.mode_of_acquisition_id, '');

                        checkOptionExist('#received_from_id_type', response.receivedFromIDType, '');
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