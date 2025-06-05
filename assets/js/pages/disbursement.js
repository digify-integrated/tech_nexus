(function($) {
    'use strict';

    $(function() {
        if($('#disbursement-table').length){
            disbursementTable('#disbursement-table');
        }

        if($('#check-disbursement-table').length){
            checkDisbursementTable('#check-disbursement-table');
        }

        if($('#journal-voucher-table').length){
            journalVoucherTable('#journal-voucher-table');
        }
        
        if($('#particulars-table').length){
            particularsTable('#particulars-table');
        }
        
        if($('#check-table').length){
            checkTable('#check-table');
        }

        if($('#disbursement-form').length){
            disbursementForm();
        }

        if($('#cancel-disbursement-form').length){
            cancelDisbursementForm();
        }

        if($('#cancel-disbursement-check-form').length){
            cancelDisbursementCheckForm();
        }

        if($('#negotiated-disbursement-check-form').length){
            negotiatedDisbursementCheckForm();
        }

        if($('#reverse-disbursement-form').length){
            reverseDisbursementForm();
        }

        if($('#particulars-form').length){
            particularsForm();
        }

        if($('#check-form').length){
            checkForm();
        }

        if($('#disbursement-id').length){
            displayDetails('get disbursement details');
        }

        if($('#total-disbursement').length){
            getDisbursementTotal();
        }

        if($('#total-particulars').length){
            getDisbursementParticularsTotal();
        }

        if($('#total-check').length){
            getDisbursementCheckTotal();
        }

        $(document).on('click','.delete-disbursement',function() {
            const disbursement_id = $(this).data('disbursement-id');
            const transaction = 'delete disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Deletion',
                text: 'Are you sure you want to delete this disbursement?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Disbursement Success', 'The disbursement has been deleted successfully.', 'success');
                                reloadDatatable('#disbursement-table');
                                reloadDatatable('#check-disbursement-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Disbursement Error', 'The disbursement does not exist.', 'danger');
                                    reloadDatatable('#disbursement-table');
                                    reloadDatatable('#check-disbursement-table');
                                }
                                else {
                                    showNotification('Delete Disbursement Error', response.message, 'danger');
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

        $(document).on('click','#delete-disbursement',function() {
            let disbursement_id = [];
            const transaction = 'delete multiple disbursement';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_id.push(element.value);
                }
            });
    
            if(disbursement_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Deletion',
                    text: 'Are you sure you want to delete these disbursement?',
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
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_id: disbursement_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Disbursement Success', 'The selected disbursement have been deleted successfully.', 'success');
                                        reloadDatatable('#disbursement-table');
                                        reloadDatatable('#check-disbursement-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Disbursement Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Disbursement Error', 'Please select the disbursement you wish to delete.', 'danger');
            }
        });
        
        $(document).on('click','#post-disbursement',function() {
            let disbursement_id = [];
            const transaction = 'post multiple disbursement';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_id.push(element.value);
                }
            });
    
            if(disbursement_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Post',
                    text: 'Are you sure you want to post these disbursement?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Post',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_id: disbursement_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Post Disbursements Success', 'The selected disbursement have been posted successfully.', 'success');
                                    reloadDatatable('#disbursement-table');
                                    reloadDatatable('#check-disbursement-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Post Disbursements Error', response.message, 'danger');
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
                showNotification('Post Multiple Disbursements Error', 'Please select the disbursements you wish to post.', 'danger');
            }
        });
        
        $(document).on('click','#replenish-disbursement',function() {
            let disbursement_id = [];
            const transaction = 'replenish multiple disbursement';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_id.push(element.value);
                }
            });
    
            if(disbursement_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Replenishment',
                    text: 'Are you sure you want to replenish these disbursement?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Replenish',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_id: disbursement_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Replenish Disbursements Success', 'The selected disbursement have been replenished successfully.', 'success');
                                    reloadDatatable('#disbursement-table');
                                    reloadDatatable('#check-disbursement-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Replenish Disbursements Error', response.message, 'danger');
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
                showNotification('Replenish Multiple Disbursements Error', 'Please select the deposits you wish to replenish.', 'danger');
            }
        });

        $(document).on('click','#delete-disbursement-details',function() {
            const disbursement_id = $('#disbursement-id').text();
            const disbursement_category = $('#disbursement_category').val();
            const transaction = 'delete disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Deletion',
                text: 'Are you sure you want to delete this disbursement?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Disbursement Success', 'The disbursement has been deleted successfully.', 'success');
                                

                                if(disbursement_category == 'disbursement petty cash'){
                                    window.location = 'disbursement.php';
                                }
                                else if(disbursement_category == 'disbursement voucher'){
                                    window.location = 'journal-voucher.php?';
                                }
                                else{
                                    window.location = 'check-disbursement.php';
                                }
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
                                    showNotification('Delete Disbursement Error', response.message, 'danger');
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

        $(document).on('click','#replenish-disbursement-details',function() {
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'replenish disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Replenishment',
                text: 'Are you sure you want to replenish this disbursement?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Replenish',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Replenish Disbursement Success', 'The disbursement has been replenished successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notPosted) {
                                    showNotification('Replenish Disbursement Error', 'The disbursement is not posted. Cannot be replenished.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Replenish Disbursement Error', response.message, 'danger');
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

        $(document).on('click','#post-disbursement-details',function() {
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'post disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Posting',
                text: 'Are you sure you want to post this disbursement?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Post',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Post Disbursement Success', 'The disbursement has been posted successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.disbursementZero) {
                                    showNotification('Post Disbursement Error', 'The particulars cannot be zero.', 'danger');
                                }
                                else if (response.disbursementGreater) {
                                    showNotification('Post Disbursement Error', 'The particulars cannot be greater than PHP 5,000.', 'danger');
                                }
                                else if (response.checkZero) {
                                    showNotification('Post Disbursement Error', 'The check cannot be zero.', 'danger');
                                }
                                else if (response.checkNotEqual) {
                                    showNotification('Post Disbursement Error', 'The check amount total and particulars is not equal.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Post Disbursement Error', response.message, 'danger');
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

        $(document).on('click','.print',function() {
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'print disbursement';
            const disbursement_category = $('#disbursement_category').val();
    
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                dataType: 'json',
                data: {
                    disbursement_id : disbursement_id, 
                    transaction : transaction
                },
                success: function (response) {
                    if (response.success) {
                        if(disbursement_category === 'disbursement check'){
                            window.location = 'print-cv-disbursement-voucher.php?id=' + disbursement_id;
                        }
                        else{
                            window.location = 'print-disbursement-voucher.php?id=' + disbursement_id;
                        }
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.disbursementZero) {
                            showNotification('Print Disbursement Error', 'The particulars cannot be zero.', 'danger');
                        }
                        else if (response.disbursementGreater) {
                            showNotification('Print Disbursement Error', 'The particulars cannot be greater than PHP 5,000.', 'danger');
                        }
                        else if (response.checkZero) {
                            showNotification('Print Disbursement Error', 'The check cannot be zero.', 'danger');
                        }
                        else if (response.checkNotEqual) {
                            showNotification('Print Disbursement Error', 'The check amount total and particulars is not equal.', 'danger');
                        }
                        else if (response.notExist) {
                            window.location = '404.php';
                        }
                        else {
                            showNotification('Print Disbursement Error', response.message, 'danger');
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
        });

        $(document).on('click','#discard-create',function() {
            const disbursement_category = $('#disbursement_category').val();

            if(disbursement_category == 'disbursement petty cash'){
                discardCreate('disbursement.php');
            }
            else if(disbursement_category == 'disbursement voucher'){
                discardCreate('journal-voucher.php');
            }
            else{
                discardCreate('check-disbursement.php');
            }
        });

        $(document).on('click','#add-particulars',function() {
            $('#disbursement_particulars_id').val('');
            $('#particulars_amount').val('');
            $('#remarks').val('');

                       
            checkOptionExist('#particulars_company_id', '', '');
            checkOptionExist('#chart_of_account_id', '', '');
            checkOptionExist('#with_vat', 'No', '');
            checkOptionExist('#with_withholding', 'No', '');
            checkOptionExist('#tax_quarter', '', '');
        });

        $(document).on('change','#payable_type',function() {
            checkOptionExist('#customer_id', '', '');
            checkOptionExist('#misc_id', '', '');

            if($(this).val() === 'Customer'){
                $('#misc-select').addClass('d-none');
                $('#customer-select').removeClass('d-none');
            }
            else{
                $('#customer-select').addClass('d-none');
                $('#misc-select').removeClass('d-none');
            }
        });

        $(document).on('click','.update-disbursement-particulars',function() {
            const disbursement_particulars_id = $(this).data('disbursement-particulars-id');
    
            sessionStorage.setItem('disbursement_particulars_id', disbursement_particulars_id);
            
            displayDetails('get disbursement particulars details');
        });

        $(document).on('click','.delete-disbursement-particulars',function() {
            const disbursement_particulars_id = $(this).data('disbursement-particulars-id');
            const transaction = 'delete disbursement particulars';
    
            Swal.fire({
                title: 'Confirm Disbursement Particulars Deletion',
                text: 'Are you sure you want to delete this disbursement particulars?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_particulars_id : disbursement_particulars_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Disbursement Particulars Success', 'The disbursement particulars has been deleted successfully.', 'success');
                                reloadDatatable('#particulars-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Disbursement Particulars Error', 'The disbursement particulars does not exists.', 'danger');
                                    reloadDatatable('#particulars-table');
                                }
                                else {
                                    showNotification('Delete Disbursement Particulars Error', response.message, 'danger');
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

        $(document).on('click','#add-check',function() {
            resetModalForm('check-form');

            document.querySelectorAll('.update-hidden').forEach(function(element) {
                element.classList.remove('d-none');
            });
        });

        $(document).on('click','.update-disbursement-check',function() {
            const disbursement_check_id = $(this).data('disbursement-check-id');
    
            sessionStorage.setItem('disbursement_check_id', disbursement_check_id);

            document.querySelectorAll('.update-hidden').forEach(function(element) {
                element.classList.add('d-none');
            });
            
            displayDetails('get disbursement check details');
        });

        $(document).on('click','.cancel-disbursement-check',function() {
            const disbursement_check_id = $(this).data('disbursement-check-id');
    
            sessionStorage.setItem('disbursement_check_id', disbursement_check_id);
        });

        $(document).on('click','.delete-disbursement-check',function() {
            const disbursement_check_id = $(this).data('disbursement-check-id');
            const transaction = 'delete disbursement check';
    
            Swal.fire({
                title: 'Confirm Disbursement Check Deletion',
                text: 'Are you sure you want to delete this disbursement check?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_check_id : disbursement_check_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Disbursement Check Success', 'The disbursement check has been deleted successfully.', 'success');
                                reloadDatatable('#check-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Disbursement Check Error', 'The disbursement check does not exists.', 'danger');
                                    reloadDatatable('#check-table');
                                }
                                else {
                                    showNotification('Delete Disbursement Check Error', response.message, 'danger');
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

        $(document).on('click','.transmit-disbursement-check',function() {
            const disbursement_check_id = $(this).data('disbursement-check-id');
            const transaction = 'transmit disbursement check';
    
            Swal.fire({
                title: 'Confirm Check Transmittal',
                text: 'Are you sure you want to transmit this check?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Transmit',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_check_id : disbursement_check_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Transmit Check Success', 'The check has been transmitted successfully.', 'success');
                                checkTable('#check-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.disbursementZero) {
                                    showNotification('Transmit Disbursement Error', 'The check amount cannot be zero.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Transmit Disbursement Error', response.message, 'danger');
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

        $(document).on('click','#outstanding-disbursement-check',function() {
            let disbursement_check_id = [];
            const transaction = 'outstanding multiple disbursement check';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_check_id.push(element.value);
                }
            });
    
            if(disbursement_check_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Outstanding Check',
                    text: 'Are you sure you want to tag these as outstanding check?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Outstanding',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_check_id: disbursement_check_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Outstanding Check Success', 'The selected disbursement checks have been tagged as outstanding check successfully.', 'success');
                                    disbursementTable('#disbursement-check-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Outstanding Check Error', response.message, 'danger');
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
                showNotification('Outstanding Check Error', 'Please select the disbursement checks you wish to outstanding.', 'danger');
            }
        });

        $(document).on('click','#outstanding-disbursement-pdc',function() {
            let disbursement_check_id = [];
            const transaction = 'outstanding multiple disbursement pdc';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_check_id.push(element.value);
                }
            });
    
            if(disbursement_check_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Outstanding PDC',
                    text: 'Are you sure you want to tag these as outstanding PDC?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Outstanding',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_check_id: disbursement_check_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Outstanding PDC Success', 'The selected disbursement checks have been tagged as outstanding PDC successfully.', 'success');
                                    disbursementTable('#disbursement-check-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Outstanding PDC Error', response.message, 'danger');
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
                showNotification('Outstanding PDC Error', 'Please select the disbursement checks you wish to outstanding.', 'danger');
            }
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get disbursement details');

            enableForm();
        });

        $(document).on('click','#print-report',function() {
            var checkedBoxes = [];
            var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
            var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                const disbursement_category = $('#disbursement_category').val();
                window.open('print-disbursement-report.php?id=' + checkedBoxes + '&type=' + disbursement_category + '&ptype=full&sdate=' + filter_transaction_date_start_date + '&edate=' + filter_transaction_date_end_date, '_blank');
            }
            else{
                showNotification('Print Report Error', 'No selected disbursement.', 'danger');
            }
        });

        $(document).on('click','#print-report-less',function() {
            var checkedBoxes = [];
            var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
            var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                const disbursement_category = $('#disbursement_category').val();
                window.open('print-disbursement-report.php?id=' + checkedBoxes + '&type=' + disbursement_category + '&ptype=less&sdate=' + filter_transaction_date_start_date + '&edate=' + filter_transaction_date_end_date, '_blank');
            }
            else{
                showNotification('Print Report Error', 'No selected disbursement.', 'danger');
            }
        });

        $(document).on('click','#apply-filter',function() {
            disbursementTable('#disbursement-table');
            checkDisbursementTable('#check-disbursement-table');
            getDisbursementTotal();
        });

        $(document).on('click','#print',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('disbursement-print.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print Disbursements Error', 'No selected disbursement.', 'danger');
            }
        });

        $(document).on('change','#with_vat',function() {
            calculateTax();
        });

        $(document).on('change','#with_vat',function() {
            calculateTax();
        });

        $(document).on('change','#with_withholding',function() {
            calculateTax();
        });

        $(document).on('change','#particulars_amount',function() {
            calculateTax();
        });
    });
})(jQuery);

function disbursementTable(datatable_name, buttons = false, show_all = false){
    const type = 'disbursement table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_replenishment_date_start_date = $('#filter_replenishment_date_start_date').val();
    var filter_replenishment_date_end_date = $('#filter_replenishment_date_end_date').val();
    var fund_source_filter = $('.fund-source-filter:checked').val();
    var transaction_type_filter = $('.transaction-type-filter:checked').val();

    var disbursement_status_filter = [];

    $('.disbursement-status-checkbox:checked').each(function() {
        disbursement_status_filter.push($(this).val());
    });

    var filter_disbursement_status = disbursement_status_filter.join(', ');

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'COMPANY_NAME' },
        { 'data' : 'TRANSACTION_NUMBER' },
        { 'data' : 'TOTAL_AMOUNT' },
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'STATUS' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'FUND_SOURCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': '15%','bSortable': false, 'aTargets': 11 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'filter_replenishment_date_start_date' : filter_replenishment_date_start_date,
                'filter_replenishment_date_end_date' : filter_replenishment_date_end_date,
                'fund_source_filter' : fund_source_filter,
                'filter_disbursement_status' : filter_disbursement_status,
                'transaction_type_filter' : transaction_type_filter,
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
        'order': [[ 1, 'desc' ]],
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

function checkDisbursementTable(datatable_name, buttons = false, show_all = false){
    const type = 'check disbursement table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var fund_source_filter = $('.fund-source-filter:checked').val();
    var disbursement_status_filter = $('.disbursement-status-filter:checked').val();
    var transaction_type_filter = $('.transaction-type-filter:checked').val();

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'COMPANY_NAME' },
        { 'data' : 'TRANSACTION_NUMBER' },
        { 'data' : 'TOTAL_AMOUNT' },
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'STATUS' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'FUND_SOURCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': '15%','bSortable': false, 'aTargets': 11 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'fund_source_filter' : fund_source_filter,
                'disbursement_status_filter' : disbursement_status_filter,
                'transaction_type_filter' : transaction_type_filter,
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
        'order': [[ 4, 'desc' ]],
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

function journalVoucherTable(datatable_name, buttons = false, show_all = false){
    const type = 'journal voucher table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var fund_source_filter = $('.fund-source-filter:checked').val();
    var disbursement_status_filter = $('.disbursement-status-filter:checked').val();
    var transaction_type_filter = $('.transaction-type-filter:checked').val();

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'COMPANY_NAME' },
        { 'data' : 'TRANSACTION_NUMBER' },
        { 'data' : 'TOTAL_AMOUNT' },
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'STATUS' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'FUND_SOURCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': '15%','bSortable': false, 'aTargets': 11 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'fund_source_filter' : fund_source_filter,
                'disbursement_status_filter' : disbursement_status_filter,
                'transaction_type_filter' : transaction_type_filter,
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
        'order': [[ 4, 'desc' ]],
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

function particularsTable(datatable_name, buttons = false, show_all = false){
    const type = 'particulars table';
    const disbursement_id = $('#disbursement-id').text();

    var settings;

    const column = [
        { 'data' : 'PARTICULARS' },
        { 'data' : 'COMPANY' },
        { 'data' : 'PARTICULAR_AMOUNT' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'disbursement_id' : disbursement_id
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
        'order': [[ 0, 'asc' ]],
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

function checkTable(datatable_name, buttons = false, show_all = false){ 
    const type = 'check table';
    const disbursement_id = $('#disbursement-id').text();

    var settings;

    const column = [
        { 'data' : 'BANK_BRANCH' },
        { 'data' : 'CHECK_NAME' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'CHECK_AMOUNT' },
        { 'data' : 'REVERSAL_DATE' },
        { 'data' : 'TRANSMITTED_DATE' },
        { 'data' : 'OUTSTANDING_DATE' },
        { 'data' : 'NEGOTIATED_DATE' },
        { 'data' : 'CHECK_STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': '15%','bSortable': false, 'aTargets': 10 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'disbursement_id' : disbursement_id
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
        'order': [[ 0, 'asc' ]],
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

function disbursementForm(){
    $('#disbursement-form').validate({
        rules: {
            customer_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='payable_type']").val() === 'Customer';
                    }
                }
            },
            misc_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='payable_type']").val() === 'Miscellaneous';
                    }
                }
            },
            company_id: {
                required: true
            },
            transaction_type: {
                required: true
            },
            transaction_number: {
                required: true
            },
            disbursement_amount: {
                required: true
            },
            particulars: {
                required: true
            },
            transaction_date: {
                required: true
            },
            fund_source: {
                required: true
            },
        },
        messages: {
            customer_id: {
                required: 'Please choose the customer'
            },
            misc_id: {
                required: 'Please choose the customer'
            },
            company_id: {
                required: 'Please choose the company'
            },
            transaction_type: {
                required: 'Please choose the mode of payment'
            },
            transaction_number: {
                required: 'Please enter the transaction number'
            },
            disbursement_amount: {
                required: 'Please enter the disbursement amount'
            },
            particulars: {
                required: 'Please enter the particulars'
            },
            transaction_date: {
                required: 'Please choose the transaction date'
            },
            fund_source: {
                required: 'Please choose the fund source'
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
            const disbursement_id = $('#disbursement-id').text();
            const disbursement_category = $('#disbursement_category').val();
            const transaction = 'save disbursement';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_id=' + disbursement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Disbursement Success' : 'Update Disbursement Success';
                        const notificationDescription = response.insertRecord ? 'The disbursement has been inserted successfully.' : 'The disbursement  has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');

                        if(disbursement_category == 'disbursement petty cash'){
                            window.location = 'disbursement.php?id=' + response.disbursementID;
                        }
                        else if(disbursement_category == 'disbursement voucher'){
                            window.location = 'journal-voucher.php?id=' + response.disbursementID;
                        }
                        else{
                            window.location = 'check-disbursement.php?id=' + response.disbursementID;
                        }
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

function particularsForm(){
    $('#particulars-form').validate({
        rules: {
            chart_of_account_id: {
                required: true
            },
            disbursement_amount: {
                required: true
            },
            particulars_company_id: {
                required: true
            },
            tax_quarter: {
                required: function () {
                    return $("#with_withholding").val() != "No";
                }
            }
        },
        messages: {
            chart_of_account_id: {
                required: 'Please choose the particulars'
            },
            disbursement_amount: {
                required: 'Please enter the disbursement amount'
            },
            particulars_company_id: {
                required: 'Please choose the company'
            },
            tax_quarter: {
                required: 'Please choose the tax quarter'
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
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'save particulars';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_id=' + disbursement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-particulars');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Particulars Success' : 'Update Particulars Success';
                        const notificationDescription = response.insertRecord ? 'The particulars has been inserted successfully.' : 'The particulars has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        particularsTable('#particulars-table');
                        $('#particulars-offcanvas').offcanvas('hide');
                        getDisbursementParticularsTotal();
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
                    enableFormSubmitButton('submit-particulars', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function checkForm(){
    $('#check-form').validate({
        rules: {
            bank_branch: {
                required: true
            },
            check_date: {
                required: true
            },
            check_amount: {
                required: true
            },
        },
        messages: {
            bank_branch: {
                required: 'Please choose the bank/branch'
            },
            check_date: {
                required: 'Please choose the check date'
            },
            check_amount: {
                required: 'Please enter the check amount'
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
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'save check';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_id=' + disbursement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-check');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Check Success' : 'Update Check Success';
                        const notificationDescription = response.insertRecord ? 'The check has been inserted successfully.' : 'The has has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        checkTable('#check-table');
                        getDisbursementCheckTotal();
                        $('#check-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-check', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function cancelDisbursementForm(){
    $('#cancel-disbursement-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'tag disbursement as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_id=' + disbursement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-disbursement');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Cancel Disbursement Success';
                        const notificationDescription = 'The disbursement has been tag as cancelled successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.hasNegotiatedCheck) {
                            showNotification('Cancel Disbursement Error', 'Cannot cancel disbursement. There is a negotiated check.', 'danger');
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
                    enableFormSubmitButton('submit-cancel-disbursement', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function cancelDisbursementCheckForm(){
    $('#cancel-disbursement-check-form').validate({
        rules: {
            check_cancellation_reason: {
                required: true
            },
        },
        messages: {
            check_cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            let disbursement_check_id = sessionStorage.getItem('disbursement_check_id');
            const transaction = 'tag disbursement check as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_check_id=' + disbursement_check_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-disbursement');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Cancel Check Success';
                        const notificationDescription = 'The check has been tag as cancelled successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        checkTable('#check-table');
                        $('#cancel-disbursement-check-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-cancel-disbursement', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function negotiatedDisbursementCheckForm(){
    $('#negotiated-disbursement-check-form').validate({
        rules: {
            negotiated_date: {
                required: true
            },
        },
        messages: {
            negotiated_date: {
                required: 'Please choose the negotiated date'
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
            let disbursement_check_id = sessionStorage.getItem('disbursement_check_id');
            const transaction = 'negotiated disbursement check';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_check_id=' + disbursement_check_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-negotiated-disbursement');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Negotiated Check Success';
                        const notificationDescription = 'The check has been tag as negotiated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        checkTable('#check-table');
                        $('#negotiated-disbursement-check-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-negotiated-disbursement', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function reverseDisbursementForm(){
    $('#reverse-disbursement-form').validate({
        rules: {
            reversal_remarks: {
                required: true
            },
        },
        messages: {
            reversal_remarks: {
                required: 'Please enter the reversal remarks'
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
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'tag disbursement as reversed';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_id=' + disbursement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-reverse-disbursement');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Reverse Disbursement Success';
                        const notificationDescription = 'The disbursement has been tag as reversed successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.hasNegotiatedCheck) {
                            showNotification('Reverse Disbursement Error', 'Cannot reverse disbursement. There is a negotiated check.', 'danger');
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
                    enableFormSubmitButton('submit-reverse-disbursement', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function getDisbursementTotal(){
    const transaction = 'get disbursement total';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var fund_source_filter = $('.fund-source-filter:checked').val();
    var transaction_type_filter = $('.transaction-type-filter:checked').val();
    const disbursement_category = $('#disbursement_category').val();

    var disbursement_status_filter = [];

    $('.disbursement-status-checkbox:checked').each(function() {
        disbursement_status_filter.push($(this).val());
    });

    var filter_disbursement_status = disbursement_status_filter.join(', ');
        
    $.ajax({
        type: 'POST',
        url: 'controller/disbursement-controller.php',
        data: 'transaction=' + transaction + 
        '&filter_transaction_date_start_date=' + filter_transaction_date_start_date + 
        '&filter_transaction_date_end_date=' + filter_transaction_date_end_date +
        '&fund_source_filter=' + fund_source_filter +
        '&filter_disbursement_status=' + filter_disbursement_status +
        '&transaction_type_filter=' + transaction_type_filter +
        '&disbursement_category=' + disbursement_category,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#total-disbursement').text(response.total);
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

function getDisbursementParticularsTotal(){
    const transaction = 'get disbursement particulars total';
    const disbursement_id = $('#disbursement-id').text();
        
    $.ajax({
        type: 'POST',
        url: 'controller/disbursement-controller.php',
        data: 'transaction=' + transaction + 
        '&disbursement_id=' + disbursement_id,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#total-particulars').text(response.total);
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

function getDisbursementCheckTotal(){
    const transaction = 'get disbursement check total';
    const disbursement_id = $('#disbursement-id').text();
        
    $.ajax({
        type: 'POST',
        url: 'controller/disbursement-controller.php',
        data: 'transaction=' + transaction + 
        '&disbursement_id=' + disbursement_id,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#total-check').text(response.total);
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get disbursement details':
            const disbursement_id = $('#disbursement-id').text();
            
            $.ajax({
                url: 'controller/disbursement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    disbursement_id : disbursement_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('disbursement-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#transaction_number').val(response.transactionNumber);
                        $('#particulars').val(response.particulars);
                        $('#transaction_date').val(response.transactionDate);

                        checkOptionExist('#payable_type', response.payableType, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#fund_source', response.fundSource, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#company_id', response.companyID, '');

                        if(response.payableType === 'Customer'){
                            checkOptionExist('#customer_id', response.customerID, '');
                        }
                        else{
                            checkOptionExist('#misc_id', response.customerID, '');
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Disbursement Details Error', response.message, 'danger');
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
        case 'get disbursement particulars details':
            var disbursement_particulars_id = sessionStorage.getItem('disbursement_particulars_id');
                    
            $.ajax({
                url: 'controller/disbursement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    disbursement_particulars_id : disbursement_particulars_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#disbursement_particulars_id').val(disbursement_particulars_id);
                        $('#particulars_amount').val(response.particularsAmount);
                        $('#remarks').val(response.remarks);

                       
                        checkOptionExist('#particulars_company_id', response.companyID, '');
                        checkOptionExist('#chart_of_account_id', response.chartOfAccountID, '');
                        checkOptionExist('#with_vat', response.withVat, '');
                        checkOptionExist('#with_withholding', response.withWithholding, '');
                        checkOptionExist('#tax_quarter', response.taxQuarter, '');

                        calculateTax();
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Disbursement Particulars Details Error', response.message, 'danger');
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
        case 'get disbursement check details':
            var disbursement_check_id = sessionStorage.getItem('disbursement_check_id');
                    
            $.ajax({
                url: 'controller/disbursement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    disbursement_check_id : disbursement_check_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#disbursement_check_id').val(disbursement_check_id);
                        $('#check_number').val(response.checkNumber);
                        $('#check_date').val(response.checkDate);
                        $('#check_name').val(response.checkName);
                        $('#check_amount').val(response.checkAmount);

                        checkOptionExist('#bank_branch', response.bankBranch, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Disbursement Check Details Error', response.message, 'danger');
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
    }
}

function calculateTax() {
    const withVat = $('#with_vat').val() === 'Yes';
    const withWithholding = parseFloat($('#with_withholding').val()) || 0;
    const particularsAmount = parseFloat($('#particulars_amount').val()) || 0;

    const vatRate = 0.12;

    // Work in cents
    const particularsAmountCents = Math.round(particularsAmount * 100);
    const vatBaseCents = withVat
        ? Math.round(particularsAmountCents / 1.12)
        : particularsAmountCents;
    const vatAmountCents = withVat
        ? Math.round(vatBaseCents * vatRate)
        : 0;

    const withholdingCents = Math.round(vatBaseCents * (withWithholding / 100));
    const totalAmountCents = particularsAmountCents - withholdingCents;

    // Display (convert cents back to decimal and fix to 2 places)
    $('#base_amount').val((vatBaseCents / 100).toFixed(2));
    $('#vat_amount').val((vatAmountCents / 100).toFixed(2));
    $('#withholding_amount').val((withholdingCents / 100).toFixed(2));
    $('#total_amount').val((totalAmountCents / 100).toFixed(2));
}

