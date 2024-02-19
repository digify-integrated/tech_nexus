(function($) {
    'use strict';

    $(function() {
        var page = 1;
        var is_loading = false;
        var customerCard = $('#customer-card');
        var loadContent = $('#load-content');
        var customerSearch = $('#customer_search');
        var lastSearchValue = '';
        var age_filter_slider = $('#age-filter').slider();

        var debounceTimeout;

        function debounce(func, delay) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(func, delay);
        }

        function loadCustomerCard(current_page, is_loading, clearExisting) {
            if (is_loading) return;

            var customer_search = customerSearch.val();
            var customer_status_filter = $('.customer-status-filter:checked').val();
            var age_filter = $('#age-filter').val();
            var company_filter_values = [];
            var department_filter_values = [];
            var job_position_filter_values = [];
            var branch_filter_values = [];
            var customer_type_filter_values = [];
            var job_level_filter_values = [];
            var gender_filter_values = [];
            var civil_status_filter_values = [];
            var blood_type_filter_values = [];
            var religion_filter_values = [];

            $('.company-filter:checked').each(function() {
                company_filter_values.push($(this).val());
            });

            $('.department-filter:checked').each(function() {
                department_filter_values.push($(this).val());
            });

            $('.job-position-filter:checked').each(function() {
                job_position_filter_values.push($(this).val());
            });

            $('.branch-filter:checked').each(function() {
                branch_filter_values.push($(this).val());
            });

            $('.customer-type-filter:checked').each(function() {
                customer_type_filter_values.push($(this).val());
            });

            $('.job-level-filter:checked').each(function() {
                job_level_filter_values.push($(this).val());
            });

            $('.gender-filter:checked').each(function() {
                gender_filter_values.push($(this).val());
            });

            $('.civil-status-filter:checked').each(function() {
                civil_status_filter_values.push($(this).val());
            });

            $('.blood-type-filter:checked').each(function() {
                blood_type_filter_values.push($(this).val());
            });

            $('.religion-filter:checked').each(function() {
                religion_filter_values.push($(this).val());
            });
        
            var company_filter = company_filter_values.join(', ');
            var department_filter = department_filter_values.join(', ');
            var job_position_filter = job_position_filter_values.join(', ');
            var branch_filter = branch_filter_values.join(', ');
            var customer_type_filter = customer_type_filter_values.join(', ');
            var job_level_filter = job_level_filter_values.join(', ');
            var gender_filter = gender_filter_values.join(', ');
            var civil_status_filter = civil_status_filter_values.join(', ');
            var blood_type_filter = blood_type_filter_values.join(', ');
            var religion_filter = religion_filter_values.join(', ');
        
            lastSearchValue = customer_search;

            is_loading = true;
            const type = 'customer card';

            if (clearExisting) {
                customerCard.empty();
            }

            loadContent.removeClass('d-none');

            $.ajax({
                url: 'view/_customer_generation.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    current_page: current_page,
                    customer_search: customer_search,
                    customer_status_filter: customer_status_filter,
                    company_filter: company_filter,
                    department_filter: department_filter,
                    job_position_filter: job_position_filter,
                    branch_filter: branch_filter,
                    customer_type_filter: customer_type_filter,
                    job_level_filter: job_level_filter,
                    gender_filter: gender_filter,
                    civil_status_filter: civil_status_filter,
                    blood_type_filter: blood_type_filter,
                    religion_filter: religion_filter,
                    age_filter: age_filter,
                    type: type
                },
                success: function(response) {
                    is_loading = false;

                    loadContent.addClass('d-none');

                    if (response.length === 0) {
                        if (current_page === 1) {
                            customerCard.html('<div class="col-lg-12 text-center">No customer found.</div>');
                        }
                        return;
                    }

                    response.forEach(function(item) {
                        customerCard.append(item.customerCard);
                    });

                    customerCard.find('.no-search-result').remove();
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

        function resetAndLoadCustomerCard() {
            page = 1;
            loadCustomerCard(page, false, true);
        }

        function debounceAndReset() {
            debounce(function() {
                resetAndLoadCustomerCard();
            }, 300);
        }

        if (customerCard.length) {
            loadCustomerCard(page, is_loading, true);
            customerSearch.on('keyup', function() {
                debounceAndReset();
            });

            $(document).on('click','#apply-filter',function() {
                debounceAndReset();
            });
        }

        customerSearch.val(lastSearchValue);

        if($('#add-customer-form').length){
            addCustomerForm();
        }

        if($('#personal-information-form').length){
            personalInformationForm();
        }

        if($('#employment-information-form').length){
            employmentInformationForm();
        }

        if($('#customer-id').length){
            displayDetails('get personal information details');

            if($('#personal-information-summary').length){
                personalInformationSummary();
            }

            if($('#contact-information-summary').length){
                contactInformationSummary();
            }

            if($('#contact-information-form').length){
                contactInformationForm();
            }

            if($('#contact-address-summary').length){
                customerAddressSummary();
            }

            if($('#contact-address-form').length){
                customerAddressForm();
            }

            if($('#contact-identification-form').length){
                customerIdentificationForm();
            }

            if($('#contact-identification-summary').length){
                customerIdentificationSummary();
            }

            if($('#contact-family-background-form').length){
                customerFamilyBackgroundForm();
            }

            if($('#contact-family-background-summary').length){
                customerFamilyBackgroundSummary();
            }

            $(document).on('click','.update-contact-information',function() {
                const contact_information_id = $(this).data('contact-information-id');
        
                sessionStorage.setItem('contact_information_id', contact_information_id);
                
                displayDetails('get contact information details');
            });

            $(document).on('click','.tag-contact-information-as-primary',function() {
                const contact_information_id = $(this).data('contact-information-id');
                const customer_id = $('#customer-id').text();
                const transaction = 'tag contact information as primary';
        
                Swal.fire({
                    title: 'Confirm Contact Information Tag As Primary',
                    text: 'Are you sure you want to tag this contact information as primary?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Tag As Primary',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_information_id : contact_information_id, 
                                customer_id : customer_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Contact Information As Primary Success', 'The contact information has been tagged as primary successfully.', 'success');
                                    contactInformationSummary();
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
                                        showNotification('Tag Contact Information As Primary Error', response.message, 'danger');
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

            $(document).on('click','.delete-contact-information',function() {
                const contact_information_id = $(this).data('contact-information-id');
                const transaction = 'delete contact information';
        
                Swal.fire({
                    title: 'Confirm Contact Information Deletion',
                    text: 'Are you sure you want to delete this contact information?',
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
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_information_id : contact_information_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Contact Information Success', 'The contact information has been deleted successfully.', 'success');
                                    contactInformationSummary();
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
                                        showNotification('Delete Contact Information Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-address',function() {
                resetModalForm("contact-address-form");
            });

            $(document).on('click','.update-contact-address',function() {
                const contact_address_id = $(this).data('contact-address-id');
        
                sessionStorage.setItem('contact_address_id', contact_address_id);
                
                displayDetails('get contact address details');
            });

            $(document).on('click','.tag-contact-address-as-primary',function() {
                const contact_address_id = $(this).data('contact-address-id');
                const customer_id = $('#customer-id').text();
                const transaction = 'tag contact address as primary';
        
                Swal.fire({
                    title: 'Confirm Address Tag As Primary',
                    text: 'Are you sure you want to tag this address as primary?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Tag As Primary',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_address_id : contact_address_id, 
                                customer_id : customer_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Address As Primary Success', 'The address has been tagged as primary successfully.', 'success');
                                    customerAddressSummary();
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
                                        showNotification('Tag Address As Primary Error', response.message, 'danger');
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

            $(document).on('click','.delete-contact-address',function() {
                const contact_address_id = $(this).data('contact-address-id');
                const transaction = 'delete contact address';
        
                Swal.fire({
                    title: 'Confirm Address Deletion',
                    text: 'Are you sure you want to delete this address?',
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
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_address_id : contact_address_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Address Success', 'The address has been deleted successfully.', 'success');
                                    customerAddressSummary();
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
                                        showNotification('Delete Address Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-identification',function() {
                resetModalForm("contact-identification-form");
            });

            $(document).on('click','.update-contact-identification',function() {
                const contact_identification_id = $(this).data('contact-identification-id');
        
                sessionStorage.setItem('contact_identification_id', contact_identification_id);
                
                displayDetails('get contact identification details');
            });

            $(document).on('click','.tag-contact-identification-as-primary',function() {
                const contact_identification_id = $(this).data('contact-identification-id');
                const customer_id = $('#customer-id').text();
                const transaction = 'tag contact identification as primary';
        
                Swal.fire({
                    title: 'Confirm Customer Identification Tag As Primary',
                    text: 'Are you sure you want to tag this customer identification as primary?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Tag As Primary',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_identification_id : contact_identification_id, 
                                customer_id : customer_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Customer Address As Primary Success', 'The customer identification has been tagged as primary successfully.', 'success');
                                    customerIdentificationSummary();
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
                                        showNotification('Tag Customer Address As Primary Error', response.message, 'danger');
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

            $(document).on('click','.delete-contact-identification',function() {
                const contact_identification_id = $(this).data('contact-identification-id');
                const transaction = 'delete contact identification';
        
                Swal.fire({
                    title: 'Confirm Customer Identification Deletion',
                    text: 'Are you sure you want to delete this customer identification?',
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
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_identification_id : contact_identification_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Customer Identification Success', 'The customer identification has been deleted successfully.', 'success');
                                    customerIdentificationSummary();
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
                                        showNotification('Delete Customer Identification Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-family-background',function() {
                resetModalForm("contact-family-background-form");
            });

            $(document).on('click','.update-contact-family-background',function() {
                const contact_family_background_id = $(this).data('contact-family-background-id');
        
                sessionStorage.setItem('contact_family_background_id', contact_family_background_id);
                
                displayDetails('get contact family background details');
            });

            $(document).on('click','.delete-contact-family-background',function() {
                const contact_family_background_id = $(this).data('contact-family-background-id');
                const transaction = 'delete contact family background';
        
                Swal.fire({
                    title: 'Confirm Family Background Deletion',
                    text: 'Are you sure you want to delete this family background?',
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
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                contact_family_background_id : contact_family_background_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Family Background Success', 'The family background has been deleted successfully.', 'success');
                                    customerFamilyBackgroundSummary();
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
                                        showNotification('Delete Family Background Error', response.message, 'danger');
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

            if($('#contact-image-form').length){
                updateCustomerImageForm();
            }

            $(document).on('click','#change-status-to-active',function() {
                var customer_id = $('#customer-id').text();
                const transaction = 'update contact status to active';
        
                Swal.fire({
                    title: 'Confirm Customer Status Activation',
                    text: 'Are you sure you want to change the status of this customer to active?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Activate',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                customer_id : customer_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Customer Status Activation Success', 'The customer has been activated successfully.', 'success');
                                    window.location.reload();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.noPrimaryAddress) {
                                        showNotification('Customer Status Activation Error', 'There is not set primary address of the customer.', 'danger');
                                    }
                                    else if (response.noPrimaryContactInformation) {
                                        showNotification('Customer Status Activation Error', 'There is not set primary contact information of the customer.', 'danger');
                                    }
                                    else if (response.noPrimaryIdentification) {
                                        showNotification('Customer Status Activation Error', 'There is not set primary contact identification of the customer.', 'danger');
                                    }
                                    else if (response.notExist) {
                                        window.location = '404.php';
                                    }
                                    else {
                                        showNotification('Customer Status Activation Error', response.message, 'danger');
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

            $(document).on('click','#change-status-to-for-updating',function() {
                var customer_id = $('#customer-id').text();
                const transaction = 'update contact status to for updating';
        
                Swal.fire({
                    title: 'Confirm Customer Status For Updating',
                    text: 'Are you sure you want to change the status of this customer to for updating?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'For Updating',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/customer-controller.php',
                            dataType: 'json',
                            data: {
                                customer_id : customer_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Customer Status Activation Success', 'The customer has been activated successfully.', 'success');
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
                                        showNotification('Customer Status Activation Error', response.message, 'danger');
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
        }

        $(document).on('click','#discard-create',function() {
            discardCreate('customer.php');
        });
    });
})(jQuery);

function customerCard(current_page, is_loading){
    if (is_loading) return;
    var customer_search = $('#customer_search').val();

    is_loading = true;

    const type = 'customer card';
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            current_page : current_page,
            customer_search : customer_search,
            type : type
        },
        beforeSend: function(){
            $('#load-content').removeClass('d-none');
            if(current_page == 1){
                $('#customer-card').html('');
            }
        },
        success: function(response) {
            is_loading = false;

            if (response.length == 0) {
                if(current_page == 1){
                    document.getElementById('customer-card').innerHTML = '<div class="col-lg-12 text-center">No customer found.</div>';
                }
                return;
            }
            
            response.forEach(function(item) {
                $('#customer-card').append(item.customerCard);
            });

            current_page++;
            $('#customer-card').find('.no-search-result').remove();
        },
        complete: function(){
            $('#load-content').addClass('d-none');
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

function personalInformationSummary(){
    const type = 'personal information summary';
    var customer_id = $('#customer-id').text();
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            customer_id : customer_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('personal-information-summary').innerHTML = response[0].personalInformationSummary;
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

function contactInformationSummary(){
    const type = 'contact information summary';
    var customer_id = $('#customer-id').text();
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            customer_id : customer_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-information-summary').innerHTML = response[0].contactInformationSummary;
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

function customerAddressSummary(){
    const type = 'contact address summary';
    var customer_id = $('#customer-id').text();
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            customer_id : customer_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-address-summary').innerHTML = response[0].contactAddressSummary;
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

function customerIdentificationSummary(){
    const type = 'contact identification summary';
    var customer_id = $('#customer-id').text();
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            customer_id : customer_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-identification-summary').innerHTML = response[0].contactIdentificationSummary;
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

function customerFamilyBackgroundSummary(){
    const type = 'contact family background summary';
    var customer_id = $('#customer-id').text();
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            customer_id : customer_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-family-background-summary').innerHTML = response[0].contactFamilyBackgroundSummary;
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

function addCustomerForm(){
    $('#add-customer-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            birthday: {
                required: true
            },
            birth_place: {
                required: true
            },
            gender: {
                required: true
            },
            civil_status: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
            },
            birthday: {
                required: 'Please enter the birthday'
            },
            birth_place: {
                required: 'Please enter the birth place'
            },
            gender: {
                required: 'Please choose the gender'
            },
            civil_status: {
                required: 'Please choose the civil status'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'add customer';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Customer Success';
                        const notificationDescription = 'The customer has been inserted successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        
                        if($('#search-customer-tag').length){
                            window.location = 'search-customer.php?search&id=' + response.customerID;
                        }
                        else{
                            window.location = 'customer.php?id=' + response.customerID;
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

function personalInformationForm(){
    $('#personal-information-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            birthday: {
                required: true
            },
            birth_place: {
                required: true
            },
            gender: {
                required: true
            },
            civil_status: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
            },
            birthday: {
                required: 'Please enter the birthday'
            },
            birth_place: {
                required: 'Please enter the birth place'
            },
            gender: {
                required: 'Please choose the gender'
            },
            civil_status: {
                required: 'Please choose the civil status'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save personal information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-personal-information-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Personal Information Success';
                        const notificationDescription = 'The personal information has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-personal-information-data', 'Save');
                    personalInformationSummary();
                    displayDetails('get personal information details');
                    $('#personal-information-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function updateCustomerImageForm(){
    $('#contact-image-form').validate({
        rules: {
            customer_image: {
                required: true
            }
        },
        messages: {
            customer_image: {
                required: 'Please choose the customer image'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'change customer image';
            var formData = new FormData(form);
            formData.append('customer_id', customer_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Customer Image Success';
                        const notificationDescription = 'The customer image has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.notExist) {
                            setNotification('Transaction Error', 'The customer image does not exists.', 'danger');
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
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function contactInformationForm(){
    $('#contact-information-form').validate({
        rules: {
            contact_information_type_id: {
                required: true
            },
            contact_information_email: {
                contactInformationRequired: true
            },
            contact_information_mobile: {
                contactInformationRequired: true
            },
            contact_information_telephone: {
                contactInformationRequired: true
            }
        },
        messages: {
            contact_information_type_id: {
                required: 'Please choose the contact information type'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save contact information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-information');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Contact Information Success' : 'Update Contact Information Success';
                        const notificationDescription = response.insertRecord ? 'The contact information has been inserted successfully.' : 'The contact information has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-information', 'Submit');
                    $('#contact-information-offcanvas').offcanvas('hide');
                    contactInformationSummary();
                    resetModalForm('contact-information-form');
                }
            });
        
            return false;
        }
    });
}

function customerAddressForm(){
    $('#contact-address-form').validate({
        rules: {
            address_type_id: {
                required: true
            },
            contact_address: {
                required: true
            },
            city_id: {
                required: true
            },
        },
        messages: {
            address_type_id: {
                required: 'Please choose the address type'
            },
            contact_address: {
                required: 'Please enter the address'
            },
            city_id: {
                required: 'Please choose the city'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save contact address';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-address');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Address Success' : 'Update Address Success';
                        const notificationDescription = response.insertRecord ? 'The address has been inserted successfully.' : 'The address has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-address', 'Submit');
                    $('#contact-address-offcanvas').offcanvas('hide');
                    customerAddressSummary();
                    resetModalForm('contact-address-form');
                }
            });
        
            return false;
        }
    });
}

function customerIdentificationForm(){
    $('#contact-identification-form').validate({
        rules: {
            id_type_id: {
                required: true
            },
            id_number: {
                required: true
            }
        },
        messages: {
            id_type_id: {
                required: 'Please choose the ID type'
            },
            id_number: {
                required: 'Please enter the ID number'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save contact identification';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-identification');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Customer Identification Success' : 'Update Customer Identification Success';
                        const notificationDescription = response.insertRecord ? 'The customer identification has been inserted successfully.' : 'The customer identification has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-identification', 'Submit');
                    $('#contact-identification-offcanvas').offcanvas('hide');
                    customerIdentificationSummary();
                    resetModalForm('contact-identification-form');
                }
            });
        
            return false;
        }
    });
}

function customerFamilyBackgroundForm(){
    $('#contact-family-background-form').validate({
        rules: {
            family_name: {
                required: true
            },
            family_background_relation_id: {
                required: true
            },
            family_background_birthday: {
                required: true
            }
        },
        messages: {
            family_name: {
                required: 'Please enter the family name'
            },
            family_background_relation_id: {
                required: 'Please choose the relation'
            },
            family_background_birthday: {
                required: 'Please choose the birthday'
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save contact family background';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-family-background');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Family Background Success' : 'Update Family Background Success';
                        const notificationDescription = response.insertRecord ? 'The family background has been inserted successfully.' : 'The family background has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-family-background', 'Submit');
                    $('#contact-family-background-offcanvas').offcanvas('hide');
                    customerFamilyBackgroundSummary();
                    resetModalForm('contact-family-background-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get personal information details':
            var customer_id = $('#customer-id').text();
            
            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    customer_id : customer_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('personal-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#first_name').val(response.firstName);
                        $('#last_name').val(response.lastName);
                        $('#middle_name').val(response.middleName);
                        $('#suffix').val(response.suffix);
                        $('#nickname').val(response.nickname);
                        $('#birthday').val(response.birthday);
                        $('#birth_place').val(response.birthPlace);
                        $('#height').val(response.height);
                        $('#weight').val(response.weight);
                        $('#bio').val(response.bio);

                        $('#customer_bio').text(response.bio);
                        $('#customer_name').text(response.fileAs);

                        $('#emp_image').attr('src', response.customerImage);
                        $('#customer_summary_image').attr('src', response.customerImage);

                        checkOptionExist('#gender', response.genderID, '');
                        checkOptionExist('#civil_status', response.civilStatusID, '');
                        checkOptionExist('#religion', response.religionID, '');
                        checkOptionExist('#blood_type', response.bloodTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Personal Information Details Error', response.message, 'danger');
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
        case 'get contact information details':
            var contact_information_id = sessionStorage.getItem('contact_information_id');

            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_information_id : contact_information_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_information_id').val(contact_information_id);
                        $('#contact_information_email').val(response.email);
                        $('#contact_information_mobile').val(response.mobile);
                        $('#contact_information_telephone').val(response.telephone);
                        
                        checkOptionExist('#contact_information_type_id', response.contactInformationTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Contact Information Details Error', response.message, 'danger');
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
        case 'get contact address details':
            var contact_address_id = sessionStorage.getItem('contact_address_id');

            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_address_id : contact_address_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-address-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_address_id').val(contact_address_id);
                        $('#contact_address').val(response.address);
                        
                        checkOptionExist('#address_type_id', response.addressTypeID, '');
                        checkOptionExist('#city_id', response.cityID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Address Details Error', response.message, 'danger');
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
        case 'get contact identification details':
            var contact_identification_id = sessionStorage.getItem('contact_identification_id');

            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_identification_id : contact_identification_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-identification-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_identification_id').val(contact_identification_id);
                        $('#id_number').val(response.idNumber);
                        
                        checkOptionExist('#id_type_id', response.idTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Customer Identification Details Error', response.message, 'danger');
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
        case 'get contact family background details':
            var contact_family_background_id = sessionStorage.getItem('contact_family_background_id');

            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_family_background_id : contact_family_background_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-family-background-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_family_background_id').val(contact_family_background_id);
                        $('#family_name').val(response.familyName);
                        $('#family_background_birthday').val(response.birthday);
                        $('#family_background_email').val(response.email);
                        $('#family_background_mobile').val(response.mobile);
                        $('#family_background_telephone').val(response.telephone);
                        
                        checkOptionExist('#family_background_relation_id', response.relationID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Family Background Details Error', response.message, 'danger');
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