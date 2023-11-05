(function($) {
    'use strict';

    $(function() {
        var page = 1;
        var is_loading = false;
        var $employeeCard = $('#employee-card');
        var $loadContent = $('#load-content');
        var $employeeSearch = $('#employee_search');
        var lastSearchValue = '';
        var age_filter_slider = $('#age-filter').slider();

        var debounceTimeout;

        function debounce(func, delay) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(func, delay);
        }

        function loadEmployeeCard(current_page, is_loading, clearExisting) {
            if (is_loading) return;

            var employee_search = $employeeSearch.val();
            var employment_status_filter = $('.employment-status-filter:checked').val();
            var age_filter = $('#age-filter').val();
            var department_filter_values = [];
            var job_position_filter_values = [];
            var branch_filter_values = [];
            var employee_type_filter_values = [];
            var job_level_filter_values = [];
            var gender_filter_values = [];
            var civil_status_filter_values = [];
            var blood_type_filter_values = [];
            var religion_filter_values = [];

            $('.department-filter:checked').each(function() {
                department_filter_values.push($(this).val());
            });

            $('.job-position-filter:checked').each(function() {
                job_position_filter_values.push($(this).val());
            });

            $('.branch-filter:checked').each(function() {
                branch_filter_values.push($(this).val());
            });

            $('.employee-type-filter:checked').each(function() {
                employee_type_filter_values.push($(this).val());
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
        
            var department_filter = department_filter_values.join(', ');
            var job_position_filter = job_position_filter_values.join(', ');
            var branch_filter = branch_filter_values.join(', ');
            var employee_type_filter = employee_type_filter_values.join(', ');
            var job_level_filter = job_level_filter_values.join(', ');
            var gender_filter = gender_filter_values.join(', ');
            var civil_status_filter = civil_status_filter_values.join(', ');
            var blood_type_filter = blood_type_filter_values.join(', ');
            var religion_filter = religion_filter_values.join(', ');
        
            lastSearchValue = employee_search;

            is_loading = true;
            const type = 'employee card';

            if (clearExisting) {
                $employeeCard.empty();
            }

            $loadContent.removeClass('d-none');

            $.ajax({
                url: 'view/_employee_generation.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    current_page: current_page,
                    employee_search: employee_search,
                    employment_status_filter: employment_status_filter,
                    department_filter: department_filter,
                    job_position_filter: job_position_filter,
                    branch_filter: branch_filter,
                    employee_type_filter: employee_type_filter,
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

                    $loadContent.addClass('d-none');

                    if (response.length === 0) {
                        if (current_page === 1) {
                            $employeeCard.html('<div class="col-lg-12 text-center">No employee found.</div>');
                        }
                        return;
                    }

                    response.forEach(function(item) {
                        $employeeCard.append(item.employeeCard);
                    });

                    $employeeCard.find('.no-search-result').remove();
                },
                error: function(xhr, status, error) {
                    is_loading = false;

                    $loadContent.addClass('d-none');

                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
        }

        function resetAndLoadEmployeeCard() {
            page = 1;
            loadEmployeeCard(page, false, true);
        }

        function debounceAndReset() {
            debounce(function() {
                resetAndLoadEmployeeCard();
            }, 300);
        }

        if ($employeeCard.length) {
            loadEmployeeCard(page, is_loading, true);

            age_filter_slider.on('slideStop', function(slideEvt) {
                debounceAndReset();
            });

            $employeeSearch.on('keyup', function() {
                debounceAndReset();
            });

            const filterClasses = [
                '.employment-status-filter',
                '.department-filter',
                '.job-position-filter',
                '.branch-filter',
                '.employee-type-filter',
                '.job-level-filter',
                '.gender-filter',
                '.civil-status-filter',
                '.blood-type-filter',
                '.religion-filter'
            ];
            
            filterClasses.forEach(filterClass => {
                $(document).on('change', filterClass, debounceAndReset);
            });

            $('#filter_birthday_start_date').on('changeDate', function(e) {
                debounceAndReset();
            });

            $('#filter_birthday_end_date').on('changeDate', function(e) {
                debounceAndReset();
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    if (!is_loading) {
                        page++;
                        loadEmployeeCard(page, is_loading, false);
                    }
                }
            });
        }

        $employeeSearch.val(lastSearchValue);

        if($('#add-employee-form').length){
            addEmployeeForm();
        }

        if($('#personal-information-form').length){
            personalInformationForm();
        }

        if($('#employment-information-form').length){
            employmentInformationForm();
        }

        if($('#employee-id').length){
            displayDetails('get personal information details');
            displayDetails('get employment information details');

            if($('#personal-information-summary').length){
                personalInformationSummary();
            }

            if($('#employment-information-summary').length){
                employmentInformationSummary();
            }

            if($('#contact-information-summary').length){
                contactInformationSummary();
            }

            if($('#contact-information-form').length){
                contactInformationForm();
            }

            if($('#contact-address-summary').length){
                employeeAddressSummary();
            }

            if($('#contact-address-form').length){
                employeeAddressForm();
            }

            if($('#contact-identification-form').length){
                employeeIdentificationForm();
            }

            if($('#contact-identification-summary').length){
                employeeIdentificationSummary();
            }

            if($('#contact-educational-background-form').length){
                employeeEducationalBackgroundForm();
            }

            if($('#contact-educational-background-summary').length){
                employeeEducationalBackgroundSummary();
            }

            if($('#contact-family-background-form').length){
                employeeFamilyBackgroundForm();
            }

            if($('#contact-family-background-summary').length){
                employeeFamilyBackgroundSummary();
            }

            if($('#contact-emergency-contact-form').length){
                employeeEmergencyContactForm();
            }

            if($('#contact-emergency-contact-summary').length){
                employeeEmergencyContactSummary();
            }

            if($('#contact-training-form').length){
                employeeTrainingForm();
            }

            if($('#contact-training-summary').length){
                employeeTrainingSummary();
            }

            if($('#contact-skills-form').length){
                employeeSkillsForm();
            }

            if($('#contact-skills-summary').length){
                employeeSkillsSummary();
            }

            if($('#contact-talents-form').length){
                employeeTalentsForm();
            }

            if($('#contact-talents-summary').length){
                employeeTalentsSummary();
            }

            if($('#contact-hobby-form').length){
                employeeHobbyForm();
            }

            if($('#contact-hobby-summary').length){
                employeeHobbySummary();
            }

            if($('#contact-employment-history-form').length){
                employeeEmploymentHistoryForm();
            }

            if($('#contact-employment-history-summary').length){
                employeeEmploymentHistorySummary();
            }

            if($('#contact-employee-license-form').length){
                employeeLicenseForm();
            }

            if($('#contact-employee-license-summary').length){
                employeeLicenseSummary();
            }

            if($('#contact-employee-language-form').length){
                employeeLanguageForm();
            }

            if($('#contact-employee-language-summary').length){
                employeeLanguageSummary();
            }

            $(document).on('click','#add-contact-information',function() {
                resetModalForm("contact-information-form");
            });

            $(document).on('click','.update-contact-information',function() {
                const contact_information_id = $(this).data('contact-information-id');
        
                sessionStorage.setItem('contact_information_id', contact_information_id);
                
                displayDetails('get contact information details');
            });

            $(document).on('click','.tag-contact-information-as-primary',function() {
                const contact_information_id = $(this).data('contact-information-id');
                const employee_id = $('#employee-id').text();
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_information_id : contact_information_id, 
                                employee_id : employee_id, 
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
                            url: 'controller/employee-controller.php',
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
                const employee_id = $('#employee-id').text();
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_address_id : contact_address_id, 
                                employee_id : employee_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Address As Primary Success', 'The address has been tagged as primary successfully.', 'success');
                                    employeeAddressSummary();
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_address_id : contact_address_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Address Success', 'The address has been deleted successfully.', 'success');
                                    employeeAddressSummary();
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
                const employee_id = $('#employee-id').text();
                const transaction = 'tag contact identification as primary';
        
                Swal.fire({
                    title: 'Confirm Employee Identification Tag As Primary',
                    text: 'Are you sure you want to tag this employee identification as primary?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_identification_id : contact_identification_id, 
                                employee_id : employee_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Employee Address As Primary Success', 'The employee identification has been tagged as primary successfully.', 'success');
                                    employeeIdentificationSummary();
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
                                        showNotification('Tag Employee Address As Primary Error', response.message, 'danger');
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
                    title: 'Confirm Employee Identification Deletion',
                    text: 'Are you sure you want to delete this employee identification?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_identification_id : contact_identification_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Employee Identification Success', 'The employee identification has been deleted successfully.', 'success');
                                    employeeIdentificationSummary();
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
                                        showNotification('Delete Employee Identification Error', response.message, 'danger');
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

            $(document).on('click','#add-educational-background',function() {
                resetModalForm("contact-educational-background-form");
            });

            $(document).on('click','.update-contact-educational-background',function() {
                const contact_educational_background_id = $(this).data('contact-educational-background-id');
        
                sessionStorage.setItem('contact_educational_background_id', contact_educational_background_id);
                
                displayDetails('get contact educational background details');
            });

            $(document).on('click','.delete-contact-educational-background',function() {
                const contact_educational_background_id = $(this).data('contact-educational-background-id');
                const transaction = 'delete contact educational background';
        
                Swal.fire({
                    title: 'Confirm Educational Background Deletion',
                    text: 'Are you sure you want to delete this educational background?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_educational_background_id : contact_educational_background_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Educational Background Success', 'The educational background has been deleted successfully.', 'success');
                                    employeeEducationalBackgroundSummary();
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
                                        showNotification('Delete Educational Background Error', response.message, 'danger');
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_family_background_id : contact_family_background_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Family Background Success', 'The family background has been deleted successfully.', 'success');
                                    employeeFamilyBackgroundSummary();
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

            $(document).on('click','#add-contact-emergency-contact',function() {
                resetModalForm("contact-emergency-contact-form");
            });

            $(document).on('click','.update-contact-emergency-contact',function() {
                const contact_emergency_contact_id = $(this).data('contact-emergency-contact-id');
        
                sessionStorage.setItem('contact_emergency_contact_id', contact_emergency_contact_id);
                
                displayDetails('get contact emergency contact details');
            });

            $(document).on('click','.delete-contact-emergency-contact',function() {
                const contact_emergency_contact_id = $(this).data('contact-emergency-contact-id');
                const transaction = 'delete contact emergency contact';
        
                Swal.fire({
                    title: 'Confirm Emergency Contact Deletion',
                    text: 'Are you sure you want to delete this emergency contact?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_emergency_contact_id : contact_emergency_contact_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Emergency Contact Success', 'The emergency contact has been deleted successfully.', 'success');
                                    employeeEmergencyContactSummary();
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
                                        showNotification('Delete Emergency Contact Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-training',function() {
                resetModalForm("contact-training-form");
            });

            $(document).on('click','.update-contact-training',function() {
                const contact_training_id = $(this).data('contact-training-id');
        
                sessionStorage.setItem('contact_training_id', contact_training_id);
                
                displayDetails('get contact training details');
            });

            $(document).on('click','.delete-contact-training',function() {
                const contact_training_id = $(this).data('contact-training-id');
                const transaction = 'delete contact training';
        
                Swal.fire({
                    title: 'Confirm Training & Seminar Deletion',
                    text: 'Are you sure you want to delete this training and seminar?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_training_id : contact_training_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Training & Seminar Success', 'The training & seminar has been deleted successfully.', 'success');
                                    employeeTrainingSummary();
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
                                        showNotification('Delete Training & Seminar Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-skills',function() {
                resetModalForm("contact-skills-form");
            });

            $(document).on('click','.update-contact-skills',function() {
                const contact_skills_id = $(this).data('contact-skills-id');
        
                sessionStorage.setItem('contact_skills_id', contact_skills_id);
                
                displayDetails('get contact skills details');
            });

            $(document).on('click','.delete-contact-skills',function() {
                const contact_skills_id = $(this).data('contact-skills-id');
                const transaction = 'delete contact skills';
        
                Swal.fire({
                    title: 'Confirm Skills Deletion',
                    text: 'Are you sure you want to delete this skills?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_skills_id : contact_skills_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Skills Success', 'The skills has been deleted successfully.', 'success');
                                    employeeSkillsSummary();
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
                                        showNotification('Delete Skills Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-talents',function() {
                resetModalForm("contact-talents-form");
            });

            $(document).on('click','.update-contact-talents',function() {
                const contact_talents_id = $(this).data('contact-talents-id');
        
                sessionStorage.setItem('contact_talents_id', contact_talents_id);
                
                displayDetails('get contact talents details');
            });

            $(document).on('click','.delete-contact-talents',function() {
                const contact_talents_id = $(this).data('contact-talents-id');
                const transaction = 'delete contact talents';
        
                Swal.fire({
                    title: 'Confirm Talents Deletion',
                    text: 'Are you sure you want to delete this talents?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_talents_id : contact_talents_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Talents Success', 'The talents has been deleted successfully.', 'success');
                                    employeeTalentsSummary();
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
                                        showNotification('Delete Talents Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-hobby',function() {
                resetModalForm("contact-hobby-form");
            });

            $(document).on('click','.update-contact-hobby',function() {
                const contact_hobby_id = $(this).data('contact-hobby-id');
        
                sessionStorage.setItem('contact_hobby_id', contact_hobby_id);
                
                displayDetails('get contact hobby details');
            });

            $(document).on('click','.delete-contact-hobby',function() {
                const contact_hobby_id = $(this).data('contact-hobby-id');
                const transaction = 'delete contact hobby';
        
                Swal.fire({
                    title: 'Confirm Hobby Deletion',
                    text: 'Are you sure you want to delete this hobby?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_hobby_id : contact_hobby_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Hobby Success', 'The hobby has been deleted successfully.', 'success');
                                    employeeHobbySummary();
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
                                        showNotification('Delete Hobby Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-employment-history',function() {
                resetModalForm("contact-employment-history-form");
            });

            $(document).on('click','.update-contact-employment-history',function() {
                const contact_employment_history_id = $(this).data('contact-employment-history-id');
        
                sessionStorage.setItem('contact_employment_history_id', contact_employment_history_id);
                
                displayDetails('get contact employment history details');
            });

            $(document).on('click','.delete-contact-employment-history',function() {
                const contact_employment_history_id = $(this).data('contact-employment-history-id');
                const transaction = 'delete contact employment history';
        
                Swal.fire({
                    title: 'Confirm Employment History Deletion',
                    text: 'Are you sure you want to delete this employment history?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_employment_history_id : contact_employment_history_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Employment History Success', 'The employment history has been deleted successfully.', 'success');
                                    employeeEmploymentHistorySummary();
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
                                        showNotification('Delete Employment History Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-employee-license',function() {
                resetModalForm("contact-employee-license-form");
            });

            $(document).on('click','.update-contact-employee-license',function() {
                const contact_license_id = $(this).data('contact-employee-license-id');
        
                sessionStorage.setItem('contact_license_id', contact_license_id);
                
                displayDetails('get contact license details');
            });

            $(document).on('click','.delete-contact-employee-license',function() {
                const contact_license_id = $(this).data('contact-employee-license-id');
                const transaction = 'delete contact license';
        
                Swal.fire({
                    title: 'Confirm License & Certification Deletion',
                    text: 'Are you sure you want to delete this license & certification?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_license_id : contact_license_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete License & Certification Success', 'The license & certification has been deleted successfully.', 'success');
                                    employeeLicenseSummary();
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
                                        showNotification('Delete License & Certification Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-employee-language',function() {
                resetModalForm("contact-employee-language-form");
            });

            $(document).on('click','.update-contact-employee-language',function() {
                const contact_language_id = $(this).data('contact-employee-language-id');
        
                sessionStorage.setItem('contact_language_id', contact_language_id);
                
                displayDetails('get contact language details');
            });

            $(document).on('click','.delete-contact-employee-language',function() {
                const contact_language_id = $(this).data('contact-employee-language-id');
                const transaction = 'delete contact language';
        
                Swal.fire({
                    title: 'Confirm Language Deletion',
                    text: 'Are you sure you want to delete this language?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_language_id : contact_language_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Language Success', 'The language has been deleted successfully.', 'success');
                                    employeeLanguageSummary();
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
                                        showNotification('Delete Language Error', response.message, 'danger');
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

            $('#employee_image').change(function() {
                var selectedFile = $(this)[0].files[0];

                if (selectedFile) {
                    const transaction = 'change employee image';
                    const employee_id = $('#employee-id').text();

                    var formData = new FormData();
                    formData.append('employee_id', employee_id);
                    formData.append('transaction', transaction);
                    formData.append('employee_image', selectedFile);
            
                    $.ajax({
                        type: 'POST',
                        url: 'controller/employee-controller.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showNotification('Employee Image Change Success', 'The employee image has been successfully updated.', 'success');
                                displayDetails('get personal information details');
                            }
                            else{
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Employee Image Change Error', response.message, 'danger');
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
        }
    });
})(jQuery);

function employeeCard(current_page, is_loading){
    if (is_loading) return;
    var employee_search = $('#employee_search').val();

    is_loading = true;

    const type = 'employee card';
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            current_page : current_page,
            employee_search : employee_search,
            type : type
        },
        beforeSend: function(){
            $('#load-content').removeClass('d-none');
            if(current_page == 1){
                $('#employee-card').html('');
            }
        },
        success: function(response) {
            is_loading = false;

            if (response.length == 0) {
                if(current_page == 1){
                    document.getElementById('employee-card').innerHTML = '<div class="col-lg-12 text-center">No employee found.</div>';
                }
                return;
            }
            
            response.forEach(function(item) {
                $('#employee-card').append(item.employeeCard);
            });

            current_page++;
            $('#employee-card').find('.no-search-result').remove();
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
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
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

function employmentInformationSummary(){
    const type = 'employment information summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('employment-information-summary').innerHTML = response[0].employmentInformationSummary;
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
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
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

function employeeAddressSummary(){
    const type = 'contact address summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
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

function employeeIdentificationSummary(){
    const type = 'contact identification summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
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

function employeeEducationalBackgroundSummary(){
    const type = 'contact educational background summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-educational-background-summary').innerHTML = response[0].contactEducationalBackgroundSummary;
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

function employeeFamilyBackgroundSummary(){
    const type = 'contact family background summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
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

function employeeEmergencyContactSummary(){
    const type = 'contact emergency contact summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-emergency-contact-summary').innerHTML = response[0].contactEmergencyContactSummary;
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

function employeeTrainingSummary(){
    const type = 'contact training summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-training-summary').innerHTML = response[0].contactTrainingSummary;
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

function employeeSkillsSummary(){
    const type = 'contact skills summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-skills-summary').innerHTML = response[0].contactSkillsSummary;
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

function employeeTalentsSummary(){
    const type = 'contact talents summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-talents-summary').innerHTML = response[0].contactTalentsSummary;
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

function employeeHobbySummary(){
    const type = 'contact hobby summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-hobby-summary').innerHTML = response[0].contactHobbySummary;
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

function employeeEmploymentHistorySummary(){
    const type = 'contact employment history summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-employment-history-summary').innerHTML = response[0].contactEmploymentHistorySummary;
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

function employeeLicenseSummary(){
    const type = 'contact license summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-employee-license-summary').innerHTML = response[0].contactLicenseSummary;
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

function employeeLanguageSummary(){
    const type = 'contact language summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-employee-language-summary').innerHTML = response[0].contactLanguageSummary;
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

function addEmployeeForm(){
    $('#add-employee-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            company_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            department_id: {
                required: true
            },
            job_position_id: {
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
            company_id: {
                required: 'Please choose the company'
            },
            branch_id: {
                required: 'Please choose the branch'
            },
            department_id: {
                required: 'Please choose the department'
            },
            job_position_id: {
                required: 'Please choose the job position'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'add employee';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Employee Success';
                        const notificationDescription = 'The employee has been inserted successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'employee.php?id=' + response.employeeID;
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save personal information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
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

function employmentInformationForm(){
    $('#employment-information-form').validate({
        rules: {
            badge_id: {
                required: true
            },
            company_id: {
                required: true
            },
            department_id: {
                required: true
            },
            job_position_id: {
                required: true
            },
            gender: {
                required: true
            },
            employee_type_id: {
                required: true
            },
            job_level_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            onboard_date: {
                required: true
            },
        },
        messages: {
            badge_id: {
                required: 'Please enter the badge ID'
            },
            company_id: {
                required: 'Please choose the company'
            },
            department_id: {
                required: 'Please choose the department'
            },
            job_position_id: {
                required: 'Please choose the job position'
            },
            employee_type_id: {
                required: 'Please choose the employee type'
            },
            job_level_id: {
                required: 'Please choose the job level'
            },
            branch_id: {
                required: 'Please choose the job level'
            },
            onboard_date: {
                required: 'Please choose the on-board date'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save employment information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-employment-information-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Employment Information Success';
                        const notificationDescription = 'The employment information has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-employment-information-data', 'Save');
                    employmentInformationSummary();
                    displayDetails('get employment information details');
                    $('#employment-information-offcanvas').offcanvas('hide');
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
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

function employeeAddressForm(){
    $('#contact-address-form').validate({
        rules: {
            address_type_id: {
                required: true
            },
            contact_address: {
                required: true
            },
            contact_address_city_id: {
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
            contact_address_city_id: {
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact address';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
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
                    employeeAddressSummary();
                    resetModalForm('contact-address-form');
                }
            });
        
            return false;
        }
    });
}

function employeeIdentificationForm(){
    $('#contact-identification-form').validate({
        rules: {
            id_type_id: {
                required: true
            },
            contact_id_number: {
                required: true
            }
        },
        messages: {
            id_type_id: {
                required: 'Please choose the ID type'
            },
            contact_id_number: {
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact identification';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-identification');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Employee Identification Success' : 'Update Employee Identification Success';
                        const notificationDescription = response.insertRecord ? 'The employee identification has been inserted successfully.' : 'The employee identification has been updated successfully.';
                        
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
                    employeeIdentificationSummary();
                    resetModalForm('contact-identification-form');
                }
            });
        
            return false;
        }
    });
}

function employeeEducationalBackgroundForm(){
    $('#contact-educational-background-form').validate({
        rules: {
            educational_stage_id: {
                required: true
            },
            contact_institution_name: {
                required: true
            },
            educational_background_start_month: {
                required: true
            },
            educational_background_start_year: {
                required: true
            },
            educational_background_end_month: {
                educationalStageDateGreaterOrEqual: true,
                educationalStageEndMonthYearRequired: true
            },
            educational_background_end_year: {
                educationalStageDateGreaterOrEqual: true,
                educationalStageEndMonthYearRequired: true
            }
        },
        messages: {
            educational_stage_id: {
                required: 'Please choose the educational stage'
            },
            contact_institution_name: {
                required: 'Please enter the institution name'
            },
            educational_background_start_month: {
                required: 'Please choose the start month'
            },
            educational_background_start_year: {
                required: 'Please choose the start year'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact educational background';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-educational-background');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Educational Background Success' : 'Update Educational Background Success';
                        const notificationDescription = response.insertRecord ? 'The educational background has been inserted successfully.' : 'The educational background has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-educational-background', 'Submit');
                    $('#contact-educational-background-offcanvas').offcanvas('hide');
                    employeeEducationalBackgroundSummary();
                    resetModalForm('contact-educational-background-form');
                }
            });
        
            return false;
        }
    });
}

function employeeFamilyBackgroundForm(){
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact family background';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
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
                    employeeFamilyBackgroundSummary();
                    resetModalForm('contact-family-background-form');
                }
            });
        
            return false;
        }
    });
}

function employeeEmergencyContactForm(){
    $('#contact-emergency-contact-form').validate({
        rules: {
            emergency_contact_name: {
                required: true
            },
            emergency_contact_relation_id: {
                required: true
            },
            emergency_contact_email: {
                contactEmergencyContactRequired: true
            },
            emergency_contact_mobile: {
                contactEmergencyContactRequired: true
            },
            emergency_contact_telephone: {
                contactEmergencyContactRequired: true
            }
        },
        messages: {
            emergency_contact_name: {
                required: 'Please enter the emergency contract name'
            },
            emergency_contact_relation_id: {
                required: 'Please choose the relation'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact emergency contact';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-emergency-contact');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Emergency Contact Success' : 'Update Emergency Contact Success';
                        const notificationDescription = response.insertRecord ? 'The emergency contact has been inserted successfully.' : 'The emergency contact has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-emergency-contact', 'Submit');
                    $('#contact-emergency-contact-offcanvas').offcanvas('hide');
                    employeeEmergencyContactSummary();
                    resetModalForm('contact-emergency-contact-form');
                }
            });
        
            return false;
        }
    });
}

function employeeTrainingForm(){
    $('#contact-training-form').validate({
        rules: {
            training_name: {
                required: true
            },
            training_date: {
                required: true
            }
        },
        messages: {
            training_name: {
                required: 'Please enter the training name'
            },
            training_date: {
                required: 'Please choose the training date'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact training';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-training');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Training & Seminar Success' : 'Update Training & Seminar Success';
                        const notificationDescription = response.insertRecord ? 'The training & seminar has been inserted successfully.' : 'The training & seminar has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-training', 'Submit');
                    $('#contact-training-offcanvas').offcanvas('hide');
                    employeeTrainingSummary();
                    resetModalForm('contact-training-form');
                }
            });
        
            return false;
        }
    });
}

function employeeSkillsForm(){
    $('#contact-skills-form').validate({
        rules: {
            skill_name: {
                required: true
            }
        },
        messages: {
            skill_name: {
                required: 'Please enter the skill name'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact skills';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-skills');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Skills Success' : 'Update Skills Success';
                        const notificationDescription = response.insertRecord ? 'The skills has been inserted successfully.' : 'The skills has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-skills', 'Submit');
                    $('#contact-skills-offcanvas').offcanvas('hide');
                    employeeSkillsSummary();
                    resetModalForm('contact-skills-form');
                }
            });
        
            return false;
        }
    });
}

function employeeTalentsForm(){
    $('#contact-talents-form').validate({
        rules: {
            talent_name: {
                required: true
            }
        },
        messages: {
            talent_name: {
                required: 'Please enter the talent name'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact talents';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-talents');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Talents Success' : 'Update Talents Success';
                        const notificationDescription = response.insertRecord ? 'The talents has been inserted successfully.' : 'The talents has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-talents', 'Submit');
                    $('#contact-talents-offcanvas').offcanvas('hide');
                    employeeTalentsSummary();
                    resetModalForm('contact-talents-form');
                }
            });
        
            return false;
        }
    });
}

function employeeHobbyForm(){
    $('#contact-hobby-form').validate({
        rules: {
            hobby_name: {
                required: true
            }
        },
        messages: {
            hobby_name: {
                required: 'Please enter the hobby name'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact hobby';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-hobby');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Hobby Success' : 'Update Hobby Success';
                        const notificationDescription = response.insertRecord ? 'The hobby has been inserted successfully.' : 'The hobby has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-hobby', 'Submit');
                    $('#contact-hobby-offcanvas').offcanvas('hide');
                    employeeHobbySummary();
                    resetModalForm('contact-hobby-form');
                }
            });
        
            return false;
        }
    });
}

function employeeEmploymentHistoryForm(){
    $('#contact-employment-history-form').validate({
        rules: {
            employment_history_last_position_held: {
                required: true
            },
            employment_history_company: {
                required: true
            },
            employment_history_address: {
                required: true
            },
            employment_history_start_month: {
                required: true
            },
            employment_history_start_year: {
                required: true
            },
            employment_history_end_month: {
                employmentHistoryDateGreaterOrEqual: true,
                employmentHistoryEndMonthYearRequired: true
            },
            employment_history_end_year: {
                employmentHistoryDateGreaterOrEqual: true,
                employmentHistoryEndMonthYearRequired: true
            },
            starting_salary: {
                required: true
            }
        },
        messages: {
            employment_history_last_position_held: {
                required: 'Please enter the last position held'
            },
            employment_history_company: {
                required: 'Please enter the company name'
            },
            employment_history_address: {
                required: 'Please enter the company address'
            },
            employment_history_start_month: {
                required: 'Please choose the start month'
            },
            employment_history_start_year: {
                required: 'Please choose the start year'
            },
            starting_salary: {
                required: 'Please enter the starting salary'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact employment history';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-employment-history');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Employment History Success' : 'Update Employment History Success';
                        const notificationDescription = response.insertRecord ? 'The employment history has been inserted successfully.' : 'The employment history has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-contact-employment-history', 'Submit');
                    $('#contact-employment-history-offcanvas').offcanvas('hide');
                    employeeEmploymentHistorySummary();
                    resetModalForm('contact-employment-history-form');
                }
            });
        
            return false;
        }
    });
}

function employeeLicenseForm(){
    $('#contact-employee-license-form').validate({
        rules: {
            contact_license_name: {
                required: true
            },
            contact_license_issuing_organization: {
                required: true
            },
            license_start_month: {
                required: true
            },
            license_start_year: {
                required: true
            },
            license_end_month: {
                employeeLicenseDateGreaterOrEqual: true,
                employeeLicenseEndMonthYearRequired: true
            },
            license_end_year: {
                employeeLicenseDateGreaterOrEqual: true,
                employeeLicenseEndMonthYearRequired: true
            }
        },
        messages: {
            contact_license_name: {
                required: 'Please enter the license name'
            },
            contact_license_issuing_organization: {
                required: 'Please enter the issuing organization'
            },
            license_start_month: {
                required: 'Please choose the start month'
            },
            license_start_year: {
                required: 'Please choose the start year'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact license';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-employee-license');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert License & Certification Success' : 'Update License & Certification Success';
                        const notificationDescription = response.insertRecord ? 'The license & certification has been inserted successfully.' : 'The license & certification has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        $('#contact-employee-license-offcanvas').offcanvas('hide');
                        employeeLicenseSummary();
                        resetModalForm('contact-employee-license-form');
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
                    enableFormSubmitButton('submit-contact-employee-license', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function employeeLanguageForm(){
    $('#contact-employee-language-form').validate({
        rules: {
            language_id: {
                required: true
            },
            language_proficiency_id: {
                required: true
            }
        },
        messages: {
            language_id: {
                required: 'Please choose the language'
            },
            language_proficiency_id: {
                required: 'Please choose the language proficiency'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact language';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-employee-language');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Language Success' : 'Update Language Success';
                        const notificationDescription = response.insertRecord ? 'The language has been inserted successfully.' : 'The language has been updated successfully.';

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
                    $('#contact-employee-language-offcanvas').offcanvas('hide');
                    employeeLanguageSummary();
                    resetModalForm('contact-employee-language-form');
                    enableFormSubmitButton('submit-contact-employee-language', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get personal information details':
            var employee_id = $('#employee-id').text();
            
            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_id : employee_id, 
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

                        $('#employee_bio').text(response.bio);
                        $('#employee_name').text(response.employeeName);

                        $('#emp_image').attr('src', response.employeeImage);
                        $('#employee_summary_image').attr('src', response.employeeImage);

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
        case 'get employment information details':
            var employee_id = $('#employee-id').text();
            
            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_id : employee_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('employment-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#badge_id').val(response.badgeID);
                        $('#onboard_date').val(response.onboardDate);

                        document.getElementById('employee-status-badge').innerHTML = response.isActiveBadge;

                        $('#job_position').text(response.jobPositionName);

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#job_position_id', response.jobPositionID, '');
                        checkOptionExist('#employee_type_id', response.employeeTypeID, '');
                        checkOptionExist('#job_level_id', response.jobLevelID, '');
                        checkOptionExist('#branch_id', response.branchID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employment Information Details Error', response.message, 'danger');
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
                url: 'controller/employee-controller.php',
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
                url: 'controller/employee-controller.php',
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
                        checkOptionExist('#contact_address_city_id', response.cityID, '');
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
                url: 'controller/employee-controller.php',
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
                        $('#contact_id_number').val(response.idNumber);
                        
                        checkOptionExist('#id_type_id', response.idTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employee Identification Details Error', response.message, 'danger');
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
        case 'get contact educational background details':
            var contact_educational_background_id = sessionStorage.getItem('contact_educational_background_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_educational_background_id : contact_educational_background_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-educational-background-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_educational_background_id').val(contact_educational_background_id);
                        $('#contact_institution_name').val(response.institutionName);
                        $('#contact_degree_earned').val(response.degreeEarned);
                        $('#contact_field_of_study').val(response.fieldOfStudy);
                        $('#educational_background_course_highlights').val(response.courseHighlights);
                        
                        checkOptionExist('#educational_background_start_month', response.startMonth, '');
                        checkOptionExist('#educational_background_start_year', response.startYear, '');
                        checkOptionExist('#educational_background_end_month', response.endMonth, '');
                        checkOptionExist('#educational_background_end_year', response.endYear, '');
                        checkOptionExist('#educational_stage_id', response.educationalStageID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Educational Background Details Error', response.message, 'danger');
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
                url: 'controller/employee-controller.php',
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
        case 'get contact emergency contact details':
            var contact_emergency_contact_id = sessionStorage.getItem('contact_emergency_contact_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_emergency_contact_id : contact_emergency_contact_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-emergency-contact-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_emergency_contact_id').val(contact_emergency_contact_id);
                        $('#emergency_contact_name').val(response.emergencyContactName);
                        $('#emergency_contact_email').val(response.email);
                        $('#emergency_contact_mobile').val(response.mobile);
                        $('#emergency_contact_telephone').val(response.telephone);
                        
                        checkOptionExist('#emergency_contact_relation_id', response.relationID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Emergency Contact Details Error', response.message, 'danger');
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
        case 'get contact training details':
            var contact_training_id = sessionStorage.getItem('contact_training_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_training_id : contact_training_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-training-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_training_id').val(contact_training_id);
                        $('#training_name').val(response.trainingName);
                        $('#training_date').val(response.trainingDate);
                        $('#training_location').val(response.trainingLocation);
                        $('#training_provider').val(response.trainingProvider);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Training & Seminar Details Error', response.message, 'danger');
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
        case 'get contact skills details':
            var contact_skills_id = sessionStorage.getItem('contact_skills_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_skills_id : contact_skills_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-skills-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_skills_id').val(contact_skills_id);
                        $('#skill_name').val(response.skillName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Skills Details Error', response.message, 'danger');
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
        case 'get contact talents details':
            var contact_talents_id = sessionStorage.getItem('contact_talents_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_talents_id : contact_talents_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-talents-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_talents_id').val(contact_talents_id);
                        $('#talent_name').val(response.talentName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Talents Details Error', response.message, 'danger');
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
        case 'get contact hobby details':
            var contact_hobby_id = sessionStorage.getItem('contact_hobby_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_hobby_id : contact_hobby_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-hobby-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_hobby_id').val(contact_hobby_id);
                        $('#hobby_name').val(response.hobbyName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Hobby Details Error', response.message, 'danger');
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
        case 'get contact employment history details':
            var contact_employment_history_id = sessionStorage.getItem('contact_employment_history_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_employment_history_id : contact_employment_history_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-hobby-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_employment_history_id').val(contact_employment_history_id);
                        $('#employment_history_last_position_held').val(response.lastPositionHeld);
                        $('#employment_history_company').val(response.company);
                        $('#employment_history_address').val(response.address);
                        $('#starting_salary').val(response.startingSalary);
                        $('#final_salary').val(response.finalSalary);
                        $('#basic_function').val(response.basicFunction);

                        checkOptionExist('#employment_history_start_month', response.startMonth, '');
                        checkOptionExist('#employment_history_start_year', response.startYear, '');
                        checkOptionExist('#employment_history_end_month', response.endMonth, '');
                        checkOptionExist('#employment_history_end_year', response.endYear, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employment History Details Error', response.message, 'danger');
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
        case 'get contact license details':
            var contact_license_id = sessionStorage.getItem('contact_license_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_license_id : contact_license_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-hobby-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_license_id').val(contact_license_id);
                        $('#contact_license_name').val(response.licenseName);
                        $('#contact_license_issuing_organization').val(response.issuingOrganization);
                        $('#contact_license_description').val(response.description);

                        checkOptionExist('#license_start_month', response.startMonth, '');
                        checkOptionExist('#license_start_year', response.startYear, '');
                        checkOptionExist('#license_end_month', response.endMonth, '');
                        checkOptionExist('#license_end_year', response.endYear, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get License Details Error', response.message, 'danger');
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
        case 'get contact language details':
            var contact_language_id = sessionStorage.getItem('contact_language_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_language_id : contact_language_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-hobby-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_language_id').val(contact_language_id);

                        checkOptionExist('#language_id', response.languageID, '');
                        checkOptionExist('#language_proficiency_id', response.languageProficiencyID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get License Details Error', response.message, 'danger');
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