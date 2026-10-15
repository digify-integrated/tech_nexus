(function($) {
    'use strict';

    $(function() {
        if($('#pricing-threshold-table').length){
            pricingThresholdTable('#pricing-threshold-table');
        }

        if($('#pricing-threshold-form').length){
            pricingThresholdForm();
        }

        $(document).on('click','.update-pricing-threshold',function() {
            const quotation_standard_pricing_id = $(this).data('quotation-standard-pricing-id'); 

            $("#quotation_standard_pricing_id").val(quotation_standard_pricing_id);
            displayQuotationDetails(quotation_standard_pricing_id);
        });

        $(document).on('click','.delete-pricing-threshold',function() {
            const quotation_standard_pricing_id = $(this).data('quotation-standard-pricing-id');
            const transaction = 'delete pricing threshold';
    
            Swal.fire({
                title: 'Confirm Pricing Threshold Deletion',
                text: 'Are you sure you want to delete this pricing threshold?',
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
                            quotation_standard_pricing_id : quotation_standard_pricing_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Pricing Threshold Success', 'The pricing threshold has been deleted successfully.', 'success');
                                reloadDatatable('#pricing-threshold-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Pricing Threshold Error', 'The pricing threshold does not exist.', 'danger');
                                    reloadDatatable('#pricing-threshold-table');
                                }
                                else {
                                    showNotification('Delete Pricing Threshold Error', response.message, 'danger');
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

        $(document).on('click','#delete-pricing-threshold',function() {
            let quotation_standard_pricing_id = [];
            const transaction = 'delete multiple pricing threshold';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    quotation_standard_pricing_id.push(element.value);
                }
            });
    
            if(quotation_standard_pricing_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Unit Categories Deletion',
                    text: 'Are you sure you want to delete these unit categories?',
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
                                quotation_standard_pricing_id: quotation_standard_pricing_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Pricing Threshold Success', 'The selected unit categories have been deleted successfully.', 'success');
                                    reloadDatatable('#pricing-threshold-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Pricing Threshold Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Pricing Threshold Error', 'Please select the unit categories you wish to delete.', 'danger');
            }
        });
    });
})(jQuery);

function pricingThresholdTable(datatable_name, buttons = false, show_all = false){
    const type = 'pricing threshold table';

    let product_subcategory_filter = [];
    var settings;

    $('.product-subcategory-filter:checked').each(function () {
        product_subcategory_filter.push($(this).val());
    });

    const filter_product_subcategory = product_subcategory_filter.join(', ');

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CATEGORY' },
        { 'data' : 'YEAR_MODEL' },
        { 'data' : 'TRANSMISSION_TYPE' },
        { 'data' : 'LENGTH' },
        { 'data' : 'PRICE_THRESHOLD' },
        { 'data' : 'DISCOUNT_THRESHOLD' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_subcategory_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_product_subcategory': filter_product_subcategory},
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

function pricingThresholdForm(){
    $('#pricing-threshold-form').validate({
        rules: {
            product_subcategory_id: {
                required: true
            },
            price_threshold: {
                required: true
            },
            transmission_type: {
                required: true
            },
            length: {
                required: true
            },
            length_unit: {
                required: true
            },
            discount_threshold: {
                required: true
            }
        },
        messages: {
            product_subcategory_id: {
                required: 'Please choose the category'
            },
            price_threshold: {
                required: 'Please enter the price threshold'
            },
            transmission_type: {
                required: 'Please select the transmission type'
            },
            length: {
                required: 'Please enter the length'
            },
            length_unit: {
                required: 'Please select the length unit'
            },
            discount_threshold: {
                required: 'Please enter the discount threshold'
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
            const transaction = 'save pricing threshold';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-subcategory-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pricing-threshold');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save Pricing Threshold Success';
                        const notificationDescription = 'The pricing threshold has been saved successfully.';
                        
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
                    enableFormSubmitButton('submit-pricing-threshold', 'Save');
                     reloadDatatable('#pricing-threshold-table');
                    $('#pricing-threshold-modal').modal('hide');
                    resetModalForm('pricing-threshold-form');
                }
            });
        
            return false;
        }
    });
}

function displayQuotationDetails(quotation_standard_pricing_id){       
    let transaction = 'get quotation standard pricing details';    

            $.ajax({
                url: 'controller/product-subcategory-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    quotation_standard_pricing_id : quotation_standard_pricing_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('pricing-threshold-form');
                },
                success: function(response) {
                    if (response.success) {
                        checkOptionExist('#product_subcategory_id', response.product_subcategory_id, '');
                        checkOptionExist('#transmission_type', response.transmission_type, '');
                        checkOptionExist('#length_unit', response.length_unit, '');
                        checkOptionExist('#year_model', response.year_model, '');

                        $('#price_threshold').val(response.price_threshold);
                        $('#length').val(response.length);
                        $('#discount_threshold').val(response.discount_threshold);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Pricing Threshold Details Error', response.message, 'danger');
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