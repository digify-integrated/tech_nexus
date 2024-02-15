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
            var employment_status_filter = $('.employment-status-filter:checked').val();
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
                    employment_status_filter: employment_status_filter,
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

function addCustomerForm(){
    $('#add-customer-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
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
                        window.location = 'customer.php?id=' + response.customerID;
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
        case 'get employment information details':
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
                    resetForm('employment-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#badge_id').val(response.badgeID);
                        $('#onboard_date').val(response.onboardDate);
                        $('#kiosk_pin_code').val(response.kioskPinCode);
                        $('#biometrics_id').val(response.biometricsID);

                        document.getElementById('customer-status-badge').innerHTML = response.isActiveBadge;

                        $('#job_position').text(response.jobPositionName);

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#job_position_id', response.jobPositionID, '');
                        checkOptionExist('#customer_type_id', response.customerTypeID, '');
                        checkOptionExist('#job_level_id', response.jobLevelID, '');
                        checkOptionExist('#branch_id', response.branchID, '');
                        checkOptionExist('#manager_id', response.managerID, '');
                        checkOptionExist('#work_schedule_id', response.workScheduleID, '');
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
        case 'get customer qr code details':
            var customer_id = $('#customer-id').text();
            
            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    customer_id : customer_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {                        
                        createCustomerQRCode('customer-qr-code-container', response.fileAs, response.badgeID);
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
        case 'get contact educational background details':
            var contact_educational_background_id = sessionStorage.getItem('contact_educational_background_id');

            $.ajax({
                url: 'controller/customer-controller.php',
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
                        $('#institution_name').val(response.institutionName);
                        $('#degree_earned').val(response.degreeEarned);
                        $('#field_of_study').val(response.fieldOfStudy);
                        $('#course_highlights').val(response.courseHighlights);
                        
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
        case 'get contact emergency contact details':
            var contact_emergency_contact_id = sessionStorage.getItem('contact_emergency_contact_id');

            $.ajax({
                url: 'controller/customer-controller.php',
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
                url: 'controller/customer-controller.php',
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
                url: 'controller/customer-controller.php',
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
                url: 'controller/customer-controller.php',
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
                url: 'controller/customer-controller.php',
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
                url: 'controller/customer-controller.php',
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
                url: 'controller/customer-controller.php',
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
                        $('#license_name').val(response.licenseName);
                        $('#issuing_organization').val(response.issuingOrganization);
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
                url: 'controller/customer-controller.php',
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
                            showNotification('Get Language Details Error', response.message, 'danger');
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
        case 'get contact bank details':
            var contact_bank_id = sessionStorage.getItem('contact_bank_id');

            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_bank_id : contact_bank_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-hobby-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_bank_id').val(contact_bank_id);
                        $('#account_number').val(response.accountNumber);

                        checkOptionExist('#bank_id', response.bankID, '');
                        checkOptionExist('#bank_account_type_id', response.bankAccountTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Bank Details Error', response.message, 'danger');
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