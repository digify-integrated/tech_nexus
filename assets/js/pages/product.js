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
            var product_status_filter = $('.product-status-filter:checked').val();
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
                    product_status_filter: product_status_filter,
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
                productTable('#product-table');
            });

            $(document).on('click','#apply-filter',function() {
                debounceAndReset();
                productTable('#product-table');
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

        if($('#product-table').length){
            productTable('#product-table');
        }

        if($('#product-expense-table').length){
            productExpenseTable('#product-expense-table');
        }

        if($('#product-document-table').length){
            productDocumentTable('#product-document-table');
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

        if($('#product-expense-form').length){
            productExpenseForm();
        }

        if($('#product-document-form').length){
            productDocumentForm();
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
                                productTable('#product-table');
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

        $(document).on('change','#unit_cost',function() {
            calculateConvertedAmount();
        });

        $(document).on('change','#fx_rate',function() {
            calculateConvertedAmount();
        });

        $(document).on('change','#package_deal',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#taxes_duties',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#freight',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#lto_registration',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#royalties',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#conversion',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#arrastre',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#wharrfage',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#insurance',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#aircon',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#import_permit',function() {
            calculateTotalLandedCost();
        });

        $(document).on('change','#others',function() {
            calculateTotalLandedCost();
        });

        $(document).on('click','#tag-product-for-sale',function() {
            const product_id = $('#product-id').text();
            const transaction = 'tag for sale';
    
            Swal.fire({
                title: 'Confirm Tagging of Product For Sale',
                text: 'Are you sure you want to tag this product for sale?',
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
                        url: 'controller/product-controller.php',
                        dataType: 'json',
                        data: {
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Product For Sale Success', 'The producct has been tagged for sale successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.preOrder) {
                                    showNotification('Product Pre-order', 'The product is tagged as pre-order.', 'danger');
                                }
                                else if (response.zeroCost) {
                                    showNotification('Product Pre-order', 'The product poduct price or cost is set to 0. Check the fields and try again.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Product For Sale Error', response.message, 'danger');
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

        $(document).on('click','#tag-product-as-sold',function() {
            const product_id = $('#product-id').text();
            const transaction = 'tag as sold';
    
            Swal.fire({
                title: 'Confirm Tagging of Product As Sold',
                text: 'Are you sure you want to tag this product as sold?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Sold',
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
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Product As Sold Success', 'The producct has been tagged as sold successfully.', 'success');
                                window.location.reload();
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
                                    showNotification('Tag Product As Sold Error', response.message, 'danger');
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

        $(document).on('click','#tag-product-as-returned',function() {
            const product_id = $('#product-id').text();
            const transaction = 'tag as returned';
    
            Swal.fire({
                title: 'Confirm Tagging of Product As Returned',
                text: 'Are you sure you want to tag this product as returned?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Returned',
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
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Product As Returned Success', 'The producct has been tagged as returned successfully.', 'success');
                                window.location.reload();
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
                                    showNotification('Tag Product As Retuned Error', response.message, 'danger');
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

        $(document).on('click','#tag-product-as-ropa',function() {
            const product_id = $('#product-id').text();
            const transaction = 'tag as ROPA';
    
            Swal.fire({
                title: 'Confirm Tagging of Product As ROPA',
                text: 'Are you sure you want to tag this product as ROPA?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'ROPA',
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
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Product As ROPA Success', 'The producct has been tagged as ROPA successfully.', 'success');
                                window.location.reload();
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
                                    showNotification('Tag Product As Retuned Error', response.message, 'danger');
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

        $(document).on('click','#tag-product-as-repossessed',function() {
            const product_id = $('#product-id').text();
            const transaction = 'tag as repossessed';
    
            Swal.fire({
                title: 'Confirm Tagging of Product As Repossessed',
                text: 'Are you sure you want to tag this product as repossessed?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Repossessed',
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
                            product_id : product_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Product As Repossessed Success', 'The producct has been tagged as repossessed successfully.', 'success');
                                window.location.reload();
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
                                    showNotification('Tag Product As Repossessed Error', response.message, 'danger');
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

        $(document).on('click','.delete-product-expense',function() {
            const product_expense_id = $(this).data('product-expense-id');
            const transaction = 'delete product expense';
    
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
                        url: 'controller/product-controller.php',
                        dataType: 'json',
                        data: {
                            product_expense_id : product_expense_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Expense Success', 'The expense has been deleted successfully.', 'success');
                                reloadDatatable('#product-expense-table');
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

        $(document).on('click','.delete-product-document',function() {
            const product_document_id = $(this).data('product-document-id');
            const transaction = 'delete product document';
    
            Swal.fire({
                title: 'Confirm Product Document Deletion',
                text: 'Are you sure you want to delete this product document?',
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
                            product_document_id : product_document_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Document Success', 'The product document has been deleted successfully.', 'success');
                                reloadDatatable('#product-document-table');
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

        $(document).on('click', '#table-view-tab', function() {
            $('#datatable-length').removeClass('d-none');
        });
        
        $(document).on('click', '#card-view-tab', function() {
            $('#datatable-length').addClass('d-none');
        });

        $(document).on('click','#print-qr-code',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-multiple-product-qr-code.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print QR Code Error', 'No selected product.', 'danger');
            }
        });

        $(document).on('click','#print-qr-code-horizontal',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-multiple-product-qr-code-horizontal.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print QR Code Error', 'No selected product.', 'danger');
            }
        });

        $('#datatable-length').on('change', function() {
            var table = $('#product-table').DataTable();
            var length = $(this).val(); 
            table.page.len(length).draw();
        });

        $(document).on('click','#generate-qr-code',function() {
            displayDetails('get product qr code details');
        });

        $(document).on('click','#discard-create',function() {
            discardCreate('product.php');
        });

        $(document).on('click','#apply-filter',function() {
            productExpenseTable('#product-expense-table');
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
            quantity: {
                required: true
            },
            preorder: {
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
            quantity: {
                required: 'Please enter the quantity'
            },
            preorder: {
                required: 'Please choose the pre-order'
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
            const transaction = 'save new product';
        
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

function productTable(datatable_name, buttons = false, show_all = false){
    const type = 'product table';
    var product_search = $('#product_search').val();
    var filter_product_cost_min = $('#filter_product_cost_min').val();
    var filter_product_cost_max = $('#filter_product_cost_max').val();
    var filter_product_price_min = $('#filter_product_price_min').val();
    var filter_product_price_max = $('#filter_product_price_max').val();
    var product_status_filter = $('.product-status-filter:checked').val();
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
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'CATEGORY' },
        { 'data' : 'PRODUCT_PRICE' },
        { 'data' : 'FOR_SALE_DATE' },
        { 'data' : 'PRODUCT_STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '20%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_search' : product_search,
                'filter_product_cost_min' : filter_product_cost_min,
                'filter_product_cost_max' : filter_product_cost_max,
                'filter_product_price_min' : filter_product_price_min,
                'filter_product_price_max' : filter_product_price_max,
                'product_status_filter' : product_status_filter,
                'company_filter' : company_filter,
                'product_category_filter' : product_category_filter,
                'product_subcategory_filter' : product_subcategory_filter,
                'warehouse_filter' : warehouse_filter,
                'body_type_filter' : body_type_filter,
                'color_filter' : color_filter
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

function productExpenseTable(datatable_name, buttons = false, show_all = false){
    const type = 'product expense table';
    const product_id = $('#product-id').text();
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
            'url' : 'view/_product_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_id' : product_id,
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

function productDocumentTable(datatable_name, buttons = false, show_all = false){
    const type = 'product document table';
    const product_id = $('#product-id').text();
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
            'url' : 'view/_product_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_id' : product_id
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
            }
        },
        messages: {
            product_price: {
                required: 'Please enter the product price'
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
                    disableFormSubmitButton('submit-landed-cost-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Landed Cost Success' : 'Update Landed Cost Success';
                        const notificationDescription = response.insertRecord ? 'The landed cost has been inserted successfully.' : 'The landed cost has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-landed-cost-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function productExpenseForm(){
    $('#product-expense-form').validate({
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
            const product_id = $('#product-id').text();
            const transaction = 'save product expense';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&product_id=' + product_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-expense');
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
                    enableFormSubmitButton('submit-product-expense', 'Submit');
                    reloadDatatable('#product-expense-table');
                    $('#product-expense-offcanvas').offcanvas('hide');
                    resetModalForm('product-expense-form');
                }
            });
        
            return false;
        }
    });
}

function productDocumentForm(){
    $('#product-document-form').validate({
        rules: {
            document_type: {
                required: true
            },
            product_document: {
                required: true
            },
        },
        messages: {
            document_type: {
                required: 'Please choose the document type'
            },
            product_document: {
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
            const product_id = $('#product-id').text();
            const transaction = 'add product document';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('product_id', product_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-document');
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
                    enableFormSubmitButton('submit-product-document', 'Submit');
                    reloadDatatable('#product-document-table');
                    $('#product-document-offcanvas').offcanvas('hide');
                    resetModalForm('product-document-form');
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
                        $(document).on('click','#print-qr',function() {
                            window.open(window.open('print-product-qr-code.php?product_id='+ product_id +'&description='+response.description+'&stock_number='+ response.fullStockNumber));
                        });

                        createProductQRCode('product-qr-code-container', product_id, response.description, response.fullStockNumber);
                        $('#product_id').val(product_id);
                        $('#description').val(response.description);
                        $('#stock_number').val(response.fullStockNumber);
                        $('#engine_number').val(response.engineNumber);
                        $('#chassis_number').val(response.chassisNumber);
                        $('#plate_number').val(response.plateNumber);
                        $('#running_hours').val(response.runningHours);
                        $('#mileage').val(response.mileage);
                        $('#length').val(response.length);
                        $('#product_price').val(response.productPrice);
                        $('#product_cost').val(response.productCost);
                        $('#remarks').val(response.remarks);
                        $('#quantity').val(response.quantity);

                        $('#orcr_no').val(response.orcrNo);
                        $('#orcr_date').val(response.orcrDate);
                        $('#orcr_expiry_date').val(response.orcrExpiryDate);
                        $('#received_from').val(response.receivedFrom);
                        $('#received_from_address').val(response.receivedFromAddress);
                        $('#received_from_id_number').val(response.receivedFromIDNumber);
                        $('#unit_description').val(response.unitDescription);
                        $('#product_status').val(response.productStatus);
                        
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
                        checkOptionExist('#preorder', response.preorder, '');
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
                },
                complete: function(){
                    calculateConvertedAmount();
                    calculateTotalLandedCost();
                }
            });
            break;
    }
}

function calculateConvertedAmount() {
    var unit_cost = parseFloat($('#unit_cost').val()) || 0;
    var fx_rate = parseFloat($('#fx_rate').val()) || 0;
    var total = unit_cost * fx_rate;

    $('#converted_amount').val(total.toFixed(2));
}

function calculateTotalLandedCost() {
    var package_deal = parseFloat($('#package_deal').val()) || 0;
    var taxes_duties = parseFloat($('#taxes_duties').val()) || 0;
    var freight = parseFloat($('#freight').val()) || 0;
    var lto_registration = parseFloat($('#lto_registration').val()) || 0;
    var royalties = parseFloat($('#royalties').val()) || 0;
    var conversion = parseFloat($('#conversion').val()) || 0;
    var arrastre = parseFloat($('#arrastre').val()) || 0;
    var wharrfage = parseFloat($('#wharrfage').val()) || 0;
    var insurance = parseFloat($('#insurance').val()) || 0;
    var aircon = parseFloat($('#aircon').val()) || 0;
    var import_permit = parseFloat($('#import_permit').val()) || 0;
    var others = parseFloat($('#others').val()) || 0;

    var total = package_deal + taxes_duties + freight + lto_registration + royalties + conversion + arrastre + wharrfage + insurance + aircon + import_permit + others;

    $('#total_landed_cost').val(total.toFixed(2));
}