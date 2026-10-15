(function($) {
    'use strict';

    $(function() {
        if($('#cif-table').length){
            cifTable('#cif-table');
        }

        if($('#supplier-form').length){
            supplierForm();
        }

        if($('#spaf-form').length){
            spafForm();
        }

        if($('#cif-id').length){
            displayCIFDetails();
            loadSupplierQuotationForm();
        }

        $(document).on('click','.cancel-cif',function() {
            const cif_id = $(this).data('cif-id');
            const transaction = 'cancel cif';
    
            Swal.fire({
                title: 'Confirm CIF Cancellation',
                text: 'Are you sure you want to cancel this cif?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel',
                cancelButtonText: 'Close',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            cif_id : cif_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Cancel CIF Success', 'The cif has been cancelled successfully.', 'success');
                                reloadDatatable('#cif-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Cancel CIF Error', 'The cif does not exist.', 'danger');
                                    reloadDatatable('#cif-table');
                                }
                                else {
                                    showNotification('Cancel CIF Error', response.message, 'danger');
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

        $(document).on('click','#cancel-cif',function() {
            const cif_id = $('#cif-id').text();
            const transaction = 'cancel cif';
    
            Swal.fire({
                title: 'Confirm CIF Cancellation',
                text: 'Are you sure you want to cancel this cif?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel',
                cancelButtonText: 'Close',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            cif_id : cif_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Cancel CIF Success', 'The cif has been cancelled successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Cancel CIF Error', 'The cif does not exist.', 'danger');
                                    reloadDatatable('#cif-table');
                                }
                                else {
                                    showNotification('Cancel CIF Error', response.message, 'danger');
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

        $(document).on('click','.submit-cif',function() {
            const cif_id = $(this).data('cif-id');
            const transaction = 'complete cif';
    
            Swal.fire({
                title: 'Confirm CIF Deletion',
                text: 'Are you sure you want to delete this cif?',
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
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            cif_id : cif_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Complete CIF Success', 'The cif has been completed successfully.', 'success');
                                reloadDatatable('#cif-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Complete CIF Error', 'The cif does not exist.', 'danger');
                                    reloadDatatable('#cif-table');
                                }
                                else {
                                    showNotification('CanceCompletel CIF Error', response.message, 'danger');
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

        $(document).on('click','#submit-cif',function() {
            const cif_id = $('#cif-id').text();
            const transaction = 'complete cif';
    
            Swal.fire({
                title: 'Confirm CIF Completion',
                text: 'Are you sure you want to tag this cif as completed?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            cif_id : cif_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Complete CIF Success', 'The cif has been completed successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Complete CIF Error', 'The cif does not exist.', 'danger');
                                    reloadDatatable('#cif-table');
                                }
                                else {
                                    showNotification('CanceCompletel CIF Error', response.message, 'danger');
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

        $(document).on('click','.supplier-cif',function() {
            const cif_id = $(this).data('cif-id');

            $("#cif_id").val(cif_id);
        });

       // 1. Open modal and fetch suppliers list
        $(document).on('click', '.add-spaf-btn', function() {
            const supplier_id = $(this).data('supplier-id');
            $('#spaf_supplier_id').val(supplier_id);
            displaySuggestedAmount();
        });

        $(document).on('click','.update-spaf-btn',function() {
            const spaf_id = $(this).data('spaf-id');

            $("#spaf_id").val(spaf_id);
            displaySPAFDetails();
        });

        $(document).on('click', '.quotation-cif', function() {
            const cif_id = $(this).data('cif-id');$('#modal_cif_id').val(cif_id);
            
            // Fetch and populate dynamic inputs
            loadSupplierQuotationForm(cif_id);
            $('#quotation-modal').modal('show');
        });

        // 2. Handle inline delete file click
        $(document).on('click', '.delete-file-btn', function() {
            const cif_id = $('#cif').text();
            const supplier_id = $(this).data('supplier-id');
            const transaction = 'delete supplier quotation';
    
            Swal.fire({
                title: 'Confirm Quotation',
                text: 'Are you sure you want to delete this quotation?',
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
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            cif_id : cif_id, 
                            supplier_id : supplier_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Quotation Success', 'The quotation has been deleted successfully.', 'success');
                                loadSupplierQuotationForm();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Quotation Error', 'The quotation does not exist.', 'danger');
                                    loadSupplierQuotationForm();
                                }
                                else {
                                    showNotification('Delete Quotation Error', response.message, 'danger');
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

        // 3. Submit Quotations Form via AJAX
        $(document).on('submit', '#quotation-form', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('transaction', 'save supplier quotations');
            formData.append('cif_id', $('#cif-id').text());

            $.ajax({
                url: 'controller/lead-controller.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification('Success', response.message, 'success');
                        loadSupplierQuotationForm();
                    } else {
                        showNotification('Error', response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    showErrorDialog('An error occurred while saving quotations.');
                }
            });
        });

        $(document).on('change', '#unit_price, #dealer_discount', function() {
            calculateNetPrice();
        });
    });
})(jQuery);

function cifTable(datatable_name, buttons = false, show_all = false){
    const type = 'cif table 2';

    let status_filter = [];
    var settings;

    $('.status-filter:checked').each(function () {
        status_filter.push($(this).val());
    });

    const filter_status = status_filter.join(', ');

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'REFERENCE_NO' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'COMPANY' },
        { 'data' : 'SUPPLIER' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'STATUS' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_status': filter_status},
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

function supplierForm(){
    $('#supplier-form').validate({
        rules: {
            supplier_id: {
                required: true
            },
        },
        messages: {
            supplier_id: {
                required: 'Please choose the supplier'
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
            const transaction = 'save supplier';
            const cif_id = $('#cif-id').text();
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&cif_id=' + cif_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-supplier');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save Supplier Success';
                        const notificationDescription = 'The supplier has been saved successfully.';

                       loadSupplierQuotationForm();
                        
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
                    enableFormSubmitButton('submit-supplier', 'Save');
                    $('#supplier-modal').modal('hide');
                    resetModalForm('supplier-form');
                }
            });
        
            return false;
        }
    });
}

function displayCIFDetails(){
    let transaction = 'get cif details';
    var cif_id = $('#cif-id').text();
    
    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            cif_id : cif_id, 
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#reference_no_label').text(response.cif_reference_no);
                $('#customer_label').text(response.file_as);
                $('#product_label').text(response.product);
                $('#company_label').text(response.companyName);
                $('#add_on_label').text(response.add_ons);
                $('#remarks_label').text(response.remarks);
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Get CIF Details Error', response.message, 'danger');
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
    });
}

function displaySuggestedAmount(){
    let transaction = 'get cif supplier details';
    var cif_id = $('#cif-id').text();
    var spaf_supplier_id = $('#spaf_supplier_id').val();
    
    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            cif_id : cif_id, 
            spaf_supplier_id : spaf_supplier_id, 
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#unit_price').val(response.quoted_amount);
                calculateNetPrice();
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Get CIF Details Error', response.message, 'danger');
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
    });
}

function loadSupplierQuotationForm() {
    var cif_id = $('#cif-id').text();
    var cif_status = $('#cif_status').val();

    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            cif_id: cif_id,
            transaction: 'get cif suppliers for quotation'
        },
        success: function(response) {
            let container = $('#supplier-quotation-container');
            container.empty();

            if (response.success && response.suppliers.length > 0) {
                var disabled = '';
                var hidden = '';

                if (cif_status != 'On-Process') {
                    disabled = 'disabled';
                    hidden = 'd-none';
                }

                let html = '';

                response.suppliers.forEach(function(sup) {
                    let fileSection = '';
                    
                    if (sup.supplier_quotation) {
                        fileSection = `
                            <div class="d-flex align-items-center justify-content-between p-2 rounded border mb-2 bg-light">
                                <a href="${sup.supplier_quotation}" target="_blank" class="text-decoration-none small text-truncate me-2 fw-medium">
                                    <i class="ti ti-file-description text-primary me-1"></i> View Current File
                                </a>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 delete-file-btn ${hidden}" data-supplier-id="${sup.cif_supplier_id}" title="Delete File">
                                    <i class="ti ti-trash"></i>
                                </button>
                                <input type="hidden" name="suppliers[${sup.cif_supplier_id}][delete_file]" value="0" class="delete-file-flag">
                            </div>
                        `;
                    }

                    // 1. Add SPAF button: ONLY when status is On-Process AND quotation file exists
                    let spafButton = '';
                    if (cif_status === 'On-Process' && sup.supplier_quotation) {
                        spafButton = `
                            <button type="button" class="btn btn-sm btn-primary add-spaf-btn shadow-sm" data-supplier-id="${sup.supplier_id}" data-bs-toggle="modal" data-bs-target="#spaf-modal">
                                <i class="ti ti-plus me-1"></i> Add SPAF
                            </button>
                        `;
                    }

                    // 2. Collapsible section for SPAFs
                    let spafCollapsible = '';
                    if (!sup.supplier_quotation) {
                        let spafListHtml = '';
                        let spafCount = sup.spafs && sup.spafs.length > 0 ? sup.spafs.length : 0;
                        
                        if (spafCount > 0) {
                            sup.spafs.forEach(function(spaf) {
                                let updateSpafBtn = '';
                                if (cif_status === 'On-Process') {
                                    updateSpafBtn = `
                                        <button type="button" class="btn btn-sm btn-outline-warning px-2 py-1 me-1 update-spaf-btn" data-spaf-id="${spaf.spaf_id}" data-bs-toggle="modal" data-bs-target="#spaf-modal" title="Update SPAF">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>
                                    `;
                                }

                                // Formatted pricing values with fallback
                                let unitPrice = parseFloat(spaf.unit_price || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                let dealerDisc = parseFloat(spaf.dealer_discount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                let netPrice = parseFloat(spaf.net_price || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                                spafListHtml += `
                                    <div class="list-group-item list-group-item-action p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="fw-bold fs-6">${spaf.spaf_reference_no || 'SPAF'}</span>
                                                <span class="text-muted ms-2"><i class="ti ti-calendar me-1"></i>${spaf.created_at || ''}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                ${updateSpafBtn}
                                                <a href="print-inquiry-spaf.php?id=${spaf.spaf_id}" target="_blank" class="btn btn-sm btn-outline-secondary px-2 py-1" title="View/Print SPAF">
                                                    <i class="ti ti-printer"></i> Print
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row g-2 p-2 rounded text-secondary mt-2">
                                            <div class="col-md-2">
                                                <strong>Unit Price:</strong> ${unitPrice}
                                            </div>
                                            <div class="col-md-2">
                                                <strong>Discount:</strong> ${dealerDisc}
                                            </div>
                                            <div class="col-md-2 fw-bold">
                                                Net Price: <span class="text-success">${netPrice}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            spafListHtml = `<div class="p-3 text-muted small text-center">No SPAF created yet for this supplier.</div>`;
                        }

                        let collapseId = `spafCollapse_${sup.cif_supplier_id}`;

                        spafCollapsible = `
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-secondary text-uppercase tracking-wide">
                                        <i class="ti ti-files me-1"></i> SPAF Created (${spafCount})
                                    </span>
                                    <button class="btn btn-sm btn-outline-secondary border px-2 py-1" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                        <span class="small"><i class="ti ti-chevron-down"></i> Toggle List</span>
                                    </button>
                                </div>
                                <div class="collapse" id="${collapseId}">
                                    <div class="card border rounded overflow-hidden">
                                        <div class="list-group list-group-flush">
                                            ${spafListHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Supplier Card Layout
                    html += `
                        <div class="card shadow-sm border mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center py-3 bg-light">
                                <h5 class="mb-0 fw-bold">
                                    <i class="ti ti-building-store text-primary me-2"></i> ${sup.supplier_name}
                                </h5>
                                ${spafButton}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Quotation File Section -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted uppercase">Supplier Quotation</label>
                                        ${fileSection}
                                        <input type="file" class="form-control form-control-sm ${hidden}" name="suppliers[${sup.cif_supplier_id}][quotation_file]">
                                    </div>

                                    <!-- Quoted Amount Section -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted uppercase">Quoted Amount</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" name="suppliers[${sup.cif_supplier_id}][quoted_amount]" min="0" step="0.01" value="${sup.quoted_amount || '0'}" ${disabled}>
                                        </div>
                                    </div>

                                    <!-- Remarks Section -->
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted uppercase">Remarks</label>
                                        <textarea class="form-control form-control-sm" name="suppliers[${sup.cif_supplier_id}][remarks]" rows="2" placeholder="Add remarks or notes..." ${disabled}>${sup.remarks || ''}</textarea>
                                    </div>
                                </div>

                                ${spafCollapsible}
                            </div>
                        </div>
                    `;
                });
                container.html(html);
            } else {
                container.html(`
                    <div class="card shadow-sm border py-5 text-center">
                        <div class="card-body">
                            <i class="ti ti-info-circle text-muted display-4 mb-2"></i>
                            <p class="text-muted mb-0">No suppliers assigned to this CIF.</p>
                        </div>
                    </div>
                `);
            }
        }
    });
}

function spafForm(){
    $('#spaf-form').validate({
        rules: {
            unit_price: {
                required: true
            },
        },
        messages: {
            unit_price: {
                required: 'Please enter the unit price'
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
            const transaction = 'save spaf';
            const cif_id = $('#cif-id').text();
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&cif_id=' + cif_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-spaf');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save SPAF Success';
                        const notificationDescription = 'The spaf has been saved successfully.';
                        
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
                    enableFormSubmitButton('submit-spaf', 'Save');
                     loadSupplierQuotationForm();
                    $('#spaf-modal').modal('hide');
                    resetModalForm('spaf-form');
                }
            });
        
            return false;
        }
    });
}

function calculateNetPrice(){
    const unitPrice = parseFloat($('#unit_price').val()) || 0;
    const dealerDiscount = parseFloat($('#dealer_discount').val()) || 0;

    let netPrice = unitPrice - dealerDiscount;

    if (netPrice < 0) {
        netPrice = 0;
    }

    $('#net_price').val(netPrice.toFixed(2));
}

function displaySPAFDetails(){
    let transaction = 'get spaf details';
    var spaf_id = $('#spaf_id').val();
    
    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            spaf_id : spaf_id, 
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#unit_price').val(response.unit_price);
                $('#dealer_discount').val(response.dealer_discount);
                $('#net_price').val(response.net_price);
                $('#spaf_supplier_id').val(response.supplier_id);
                $('#remarks').val(response.remarks);
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Get SPAF Details Error', response.message, 'danger');
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
    });
}