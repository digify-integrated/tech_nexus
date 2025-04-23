(function ($) {
    "use strict";

    $(function () {
        $(document).on("click", "#scan-modal", function () {
            startScanner();
        });

        if($('#unit-transfer-form').length){
            unitTransferForm();
        }

        if($('#unit-receive-form').length){
            unitReceiveForm();
        }

        if($('#update-unit-transfer-form').length){
            unitTransferDetailsForm();
        }
        
        if($('#unit-transfer-table').length){
            unitTransferTable('#unit-transfer-table');
        }

        $(document).on('click','.update-unit-transfer',function() {
            const unit_transfer_id = $(this).data('unit-transfer-id');
    
            sessionStorage.setItem('unit_transfer_id', unit_transfer_id);
            
            displayDetails('get unit transfer details');
        });

        $(document).on('click','.cancel-unit-transfer',function() {
            const unit_transfer_id = $(this).data('unit-transfer-id');
            const transaction = 'cancel unit transfer';
    
            Swal.fire({
                title: 'Confirm Unit Transfer',
                text: 'Are you sure you want to delete this pdc manual input?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel',
                cancelButtonText: 'Close',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/unit-transfer-controller.php',
                        dataType: 'json',
                        data: {
                            unit_transfer_id : unit_transfer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Cancel Unit Transfer Success', 'The unit transferred has been cancelled successfully', 'success');
                                unitTransferTable('#unit-transfer-table');
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
                                    showNotification('Delete PDC Manual Input Error', response.message, 'danger');
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

function unitTransferTable(datatable_name, buttons = false, show_all = false){
    const type = 'unit transfer table';
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'TRANSFER_DETAILS' },
        { 'data' : 'TRANSFERRED_DETAILS' },
        { 'data' : 'RECEIVE_DETAILS' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_unit_transfer_generation.php',
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

function unitTransferForm(){
    $('#unit-transfer-form').validate({
        rules: {
            transferred_from: {
                required: true
            },
            transferred_to: {
                required: true
            },
        },
        messages: {
            transferred_from: {
                required: 'Please choose the transferred from'
            },
            transferred_to: {
                required: 'Please choose the transferred to'
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
            const transaction = 'save unit transfer';
        
            $.ajax({
                type: 'POST',
                url: 'controller/unit-transfer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-unit-transfer');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Save Unit Transfer Success', 'The unit transferred has been created successfully', 'success');
                        unitTransferTable('#unit-transfer-table');
                        $('#unitTransferModal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.openTransfer) {
                            showNotification('Unit Transfer Error', 'There is an opened unit transfer linked to this unit.', 'danger');
                        }
                        else if (response.sameWarehouse) {
                            showNotification('Unit Transfer Error', 'The unit location is the same location to the transferred location.', 'danger');
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
                    enableFormSubmitButton('submit-unit-transfer', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function unitTransferDetailsForm(){
    $('#update-unit-transfer-form').validate({
        rules: {
            transferred_to_update: {
                required: true
            },
        },
        messages: {
            transferred_to_update: {
                required: 'Please choose the transferred to'
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
            const transaction = 'save unit transfer details';
            var unit_transfer_id = sessionStorage.getItem('unit_transfer_id');
        
            $.ajax({
                type: 'POST',
                url: 'controller/unit-transfer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&unit_transfer_id=' + unit_transfer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-update-unit-transfer');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Save Unit Transfer Success', 'The unit transferred has been created successfully', 'success');
                        unitTransferTable('#unit-transfer-table');
                        $('#unit-transfer-offcanvas').offcanvas('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.openTransfer) {
                            showNotification('Unit Transfer Error', 'There is an opened unit transfer linked to this unit.', 'danger');
                        }
                        else if (response.sameWarehouse) {
                            showNotification('Unit Transfer Error', 'The unit location is the same location to the transferred location.', 'danger');
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
                    enableFormSubmitButton('submit-update-unit-transfer', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function unitReceiveForm(){
    $('#unit-receive-form').validate({
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
            const transaction = 'save unit receive';
        
            $.ajax({
                type: 'POST',
                url: 'controller/unit-transfer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-unit-receive');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Receive Unit Transfer Success', 'The unit transferred has been received successfully', 'success');
                        unitTransferTable('#unit-transfer-table');
                        $('#unitReceiveModal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.notAuthorize) {
                            showNotification('Receive Unit Transfer Error', 'You are not authorized to receive the unit.', 'danger');
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
                    enableFormSubmitButton('submit-unit-receive', 'Submit');
                }
            });
        
            return false;
        }
    });
}

// Global scanner instance
let scanner = null;

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
                url: "controller/unit-transfer-controller.php",
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
                stopScanner();
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
    const transaction = "scan unit";

    $.ajax({
        type: "POST",
        url: "controller/unit-transfer-controller.php",
        dataType: "json",
        data: {
            transaction: transaction,
            product_id: product_id,
        },
        success: function (response) {
            if (response.success) {
                showNotification("Scan Product Success", "The product has been scanned successfully.", "success");
                
                $('#product_id').val(product_id);
                $('#transfer_unit_id').val(response.unitTransferID);

                displayDetails('get product details');

                if(response.unitTransferID == '' || response.unitTransferID == null){
                    $('#unitTransferModal').modal('show');
                }
                else{
                    $('#unitReceiveModal').modal('show');
                }
                
                $('#scanModal').modal('hide');
            } else {
                if (response.notAuthorize) {
                    showNotification('Receive Unit Transfer Error', 'You are not authorized to receive the unit.', 'danger');
                    $('#scanModal').modal('hide');
                }
                else{
                    handleAjaxError(response);
                }
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
        case 'get unit transfer details':
            var unit_transfer_id = sessionStorage.getItem('unit_transfer_id');
                    
            $.ajax({
                url: 'controller/unit-transfer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    unit_transfer_id : unit_transfer_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#transfer_remarks_update').val(response.transferRemarks);
                        checkOptionExist('#transferred_to_update', response.transferredTo, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Unit Transfer Details Error', response.message, 'danger');
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
            var product_id = $('#product_id').val();
                
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
                        if(response.warehouseID != '' || response.warehouseID != null){
                            $('#transferred-from-row').addClass('d-none');
                        }
                        else{
                            $('#transferred-from-row').removeClass('d-none');
                        }

                        checkOptionExist('#transferred_from', response.warehouseID, '');
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