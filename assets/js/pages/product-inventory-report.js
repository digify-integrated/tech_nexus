(function ($) {
    "use strict";

    $(function () {
        if ($("#inventory-report-table").length) {
            inventoryProductReport("#inventory-report-table");
        }

        if ($("#inventory-report-batch-table").length) {
            inventoryProductReportBatch("#inventory-report-batch-table");
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
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
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
                showNotification("Scan Product Success", "The product has been scanned successfully.", "success");
                inventoryProductReportBatch("#inventory-report-batch-table");
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
