(function ($) {
    "use strict";

    $(function () {
        if ($("#inventory-report-table").length) {
            inventoryProductReport("#inventory-report-table");
        }

        if ($("#product-inventory-additional-form").length) {
            productInventoryAdditionalForm();
        }

        if ($("#product-inventory-batch-form").length) {
            productInventoryBatchForm();
        }

        if ($("#inventory-report-batch-table").length) {
            inventoryProductReportBatch("#inventory-report-batch-table", true, false);
        }

        if ($("#inventory-report-scan-history-table").length) {
            inventoryProductReportScanHistory("#inventory-report-scan-history-table", true, false);
        }

        if ($("#inventory-report-scan-excess-table").length) {
            inventoryProductReportScanExcess("#inventory-report-scan-excess-table", true, false);
        }

        if ($("#inventory-report-scan-additional-table").length) {
            inventoryProductReportScanAdditional("#inventory-report-scan-additional-table", true, false);
        }

        $(document).on("click", "#open-inventory-report", function () {
            confirmInventoryAction("open inventory report", "Confirm Open Inventory Report", "Are you sure you want to open the inventory report?");
        });

        $(document).on("click", "#close-inventory-report", function () {
            confirmInventoryAction("close inventory report", "Confirm Close Inventory Report", "Are you sure you want to close the inventory report?");
        });

        $(document).on("click", "#scan-modal", function () {
            stopScanner().then(() => startScanner());
        });

        $(document).on('click','#add-product-inventory-additional',function() {
            resetModalForm("product-inventory-additional-form");
        });

        $(document).on('click','.update-product-inventory-additional',function() {
            const product_inventory_scan_additional_id = $(this).data('product-inventory-additional-id');
    
            sessionStorage.setItem('product_inventory_scan_additional_id', product_inventory_scan_additional_id);
            
            displayDetails('get product inventory additional details');
        });

        $(document).on('click','.update-product-inventory-batch',function() {
            const product_inventory_batch_id = $(this).data('product-inventory-batch-id');
    
            $('#product_inventory_batch_id').val(product_inventory_batch_id);
            $('#product-inventory-batch-offcanvas-label').text('Tag As Missing');
            $('#remarks_type').val('missing');
        });

        $(document).on('click','.update-product-inventory-batch2',function() {
            const product_inventory_batch_id = $(this).data('product-inventory-batch-id');
    
            $('#product_inventory_batch_id').val(product_inventory_batch_id);
            $('#product-inventory-batch-offcanvas-label').text('Add Remarks');
            $('#remarks_type').val('remarks');
        });

        $(document).on('click','.delete-product-inventory-additional',function() {
            const product_inventory_scan_additional_id = $(this).data('product-inventory-additional-id');
            const transaction = 'delete inventory report scan additional';
    
            Swal.fire({
                title: 'Confirm Product Inventory Unaccounted Deletion',
                text: 'Are you sure you want to delete this unaccounted product inventory?',
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
                        url: 'controller/product-inventory-report-controller.php',
                        dataType: 'json',
                        data: {
                            product_inventory_scan_additional_id : product_inventory_scan_additional_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Inventory Unaccounted Success', 'The product inventory unaccounted has been deleted successfully.', 'success');
                                reloadDatatable('#inventory-report-scan-additional-table');
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
                                    showNotification('Delete Product Inventory Unaccounted Error', response.message, 'danger');
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

// Global scanner instance
let scanner = null;

function inventoryProductReport(datatable_name, buttons = false, show_all = false){
    const type = 'product inventory report table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BATCH_NUMBER' },
        { 'data' : 'OPEN_DATE' },
        { 'data' : 'CLOSE_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '28%', 'aTargets': 1 },
        { 'width': '28%', 'aTargets': 2 },
        { 'width': '28%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_inventory_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                handleAjaxError(xhr, status, error);
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

function inventoryProductReportBatch(datatable_name, buttons = false, show_all = false){
    const type = 'product inventory report batch table';
    const product_inventory_id = $("#product-inventory-id").text();
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'DESCRIPTION' },
        { 'data' : 'SCAN_STATUS' },
        { 'data' : 'SCAN_DATE' },
        { 'data' : 'SCAN_BY' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_inventory_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_inventory_id' : product_inventory_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                handleAjaxError(xhr, status, error);
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

function inventoryProductReportScanHistory(datatable_name, buttons = false, show_all = false){
    const type = 'product inventory report scan history table';
    const product_inventory_id = $("#product-inventory-id").text();
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'SCAN_DATE' },
        { 'data' : 'SCAN_BY' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_inventory_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_inventory_id' : product_inventory_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                handleAjaxError(xhr, status, error);
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

function inventoryProductReportScanExcess(datatable_name, buttons = false, show_all = false){
    const type = 'product inventory report scan excess table';
    const product_inventory_id = $("#product-inventory-id").text();
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'SCAN_DATE' },
        { 'data' : 'SCAN_BY' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_inventory_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_inventory_id' : product_inventory_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                handleAjaxError(xhr, status, error);
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

function inventoryProductReportScanAdditional(datatable_name, buttons = false, show_all = false){
    const type = 'product inventory report scan additional table';
    const product_inventory_id = $("#product-inventory-id").text();
    var settings;

    const column = [ 
        { 'data' : 'STOCK_NUMBER' },
        { 'data' : 'ADDED_BY' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_inventory_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_inventory_id' : product_inventory_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                handleAjaxError(xhr, status, error);
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

function productInventoryAdditionalForm(){
    $('#product-inventory-additional-form').validate({
        rules: {
            stock_number: {
                required: true
            },
        },
        messages: {
            stock_number: {
                required: 'Please enter the stock number'
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
            const product_inventory_id = $('#product-inventory-id').text();
            const transaction = 'save inventory report scan additional';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-inventory-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&product_inventory_id=' + product_inventory_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-inventory-additional');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Product Inventory Unaccounted Success', 'The product inventory unaccounted has saved successfully.', 'success');
                        inventoryProductReportScanAdditional("#inventory-report-scan-additional-table");
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-product-inventory-additional', 'Submit');
                    $('#product-inventory-additional-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function productInventoryBatchForm(){
    $('#product-inventory-batch-form').validate({
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
            const product_inventory_id = $('#product-inventory-id').text();
            const transaction = 'tag as missing';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-inventory-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&product_inventory_id=' + product_inventory_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-inventory-batch');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Tag As Missing Success', 'The product inventory has bee tagged as missing successfully.', 'success');
                        reloadDatatable("#inventory-report-batch-table");
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-product-inventory-batch', 'Submit');
                    $('#product-inventory-batch-offcanvas').offcanvas('hide');
                    resetModalForm("product-inventory-batch-form");
                }
            });
        
            return false;
        }
    });
}

// Function to confirm open/close inventory actions
function confirmInventoryAction(transaction, title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        confirmButtonClass: "btn btn-success mt-2",
        cancelButtonClass: "btn btn-secondary ms-2 mt-2",
        buttonsStyling: false,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "controller/product-inventory-report-controller.php",
                dataType: "json",
                data: { transaction: transaction },
                success: function (response) {
                    if (response.success) {
                        setNotification(`${title} Success`, `The inventory report has been ${transaction.replace("inventory report", "")}ed successfully.`, "success");
                        window.location.reload();
                    } else {
                        handleAjaxError(response);
                    }
                },
                error: handleAjaxError,
            });
        }
    });
}

// Function to start the scanner
function startScanner() {
    if (!scanner) {
        scanner = new Html5Qrcode("reader"); // Initialize scanner if not already
    }

    scanner.start(
        { facingMode: "environment" }, // Use the rear camera
        {
            fps: 20, // Frames per second
            qrbox: { width: 250, height: 250 }, // Scanner box size
        },
        (decodedText) => {
            let productId = extractProductIdFromVCard(decodedText);

            if (productId) {
                scanSound.play(); // Play scan sound
                sendScannedData(productId); // Send extracted product_id to PHP
                stopScanner().then(() => startScanner()); // Restart scanner
            } else {
                console.error("PRODUCT_ID not found in vCard.");
            }
        },
        (errorMessage) => {
            console.log(errorMessage); // Log errors
        }
    );
}

// Function to stop the scanner properly
function stopScanner() {
    return new Promise((resolve, reject) => {
        if (scanner) {
            scanner.stop()
                .then(() => {
                    scanner.clear();
                    resolve();
                })
                .catch((err) => {
                    console.error("Error stopping scanner:", err);
                    reject(err);
                });
        } else {
            resolve();
        }
    });
}

// Function to extract PRODUCT_ID from vCard format
function extractProductIdFromVCard(vcardText) {
    let match = vcardText.match(/PRODUCT_ID:(.+)/);
    return match ? match[1].trim() : null;
}

// Function to send scanned data to PHP
function sendScannedData(product_id) {
    const transaction = "scan inventory report";
    const product_inventory_id = $("#product-inventory-id").text();

    $.ajax({
        type: "POST",
        url: "controller/product-inventory-report-controller.php",
        dataType: "json",
        data: {
            transaction: transaction,
            product_inventory_id: product_inventory_id,
            product_id: product_id,
        },
        success: function (response) {
            if (response.success) {
                sessionStorage.setItem('product_id', product_id);
                displayDetails('get product details');

                showNotification("Scan Product Success", "The product has been scanned successfully.", "success");
                inventoryProductReportBatch("#inventory-report-batch-table");
                inventoryProductReportScanHistory("#inventory-report-scan-history-table");
                inventoryProductReportScanExcess("#inventory-report-scan-excess-table");
            } else {
                handleAjaxError(response);
            }
        },
        error: handleAjaxError,
    });
}

// Function to handle AJAX errors
function handleAjaxError(xhr, status, error) {
    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
    if (xhr.responseText) {
        fullErrorMessage += `, Response: ${xhr.responseText}`;
    }
    showErrorDialog(fullErrorMessage);
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get product inventory additional details':
            var product_inventory_scan_additional_id = sessionStorage.getItem('product_inventory_scan_additional_id');
                
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    product_inventory_scan_additional_id : product_inventory_scan_additional_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#product_inventory_scan_additional_id').val(product_inventory_scan_additional_id);
                        $('#stock_number').val(response.stockNumber);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Product Inventory Scan Additional Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get product details':
            var product_id = sessionStorage.getItem('product_id');
                
            $.ajax({
                url: 'controller/product-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    product_id : product_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#product_stock_number').val(response.fullStockNumber);
                        $('#description').val(response.description);
                        $('#category').val(response.productCategoryName);
                        $('#engine_no').val(response.engineNumber);
                        $('#chassis_no').val(response.chassisNumber);
                        $('#body_type').val(response.bodyTypeName);
                        $('#color').val(response.colorName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Product Inventory Scan Additional Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function(){
                 $('#productModal').modal('show');
                }
            });
            break;
    }
}