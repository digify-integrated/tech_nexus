// Form validation rules
$.validator.addMethod('password_strength', function(value) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
}, 'Password must contain at least one lowercase and uppercase letter, one number, one special character, and be 8 characters or longer.');


// Rule for legal age
$.validator.addMethod('employee_age', function(value, element, min) {
    var today = new Date();
    var birthDate = new Date(value);
    var age = today.getFullYear() - birthDate.getFullYear();
  
    if (age > min+1) { return true; }
  
    var m = today.getMonth() - birthDate.getMonth();
  
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) { age--; }
  
    return age >= min;
}, 'The employee must be at least 18 years old and above');

// Rule for contact information
$.validator.addMethod('contactInformationRequired', function(value, element) {
    var emailIsEmpty = $("#contact_information_email").val() === "";
    var mobileIsEmpty = $("#contact_information_mobile").val() === "";
    var telephoneIsEmpty = $("#contact_information_telephone").val() === "";

    return !(emailIsEmpty && mobileIsEmpty && telephoneIsEmpty);
}, 'Please enter either email, mobile, or telephone');

// Rule for contact emergency contact
$.validator.addMethod('contactEmergencyContactRequired', function(value, element) {
    var emailIsEmpty = $("#emergency_contact_email").val() === "";
    var mobileIsEmpty = $("#emergency_contact_mobile").val() === "";
    var telephoneIsEmpty = $("#emergency_contact_telephone").val() === "";

    return !(emailIsEmpty && mobileIsEmpty && telephoneIsEmpty);
}, 'Please enter either email, mobile, or telephone');

$.validator.addMethod("educationalStageDateGreaterOrEqual", function (value, element, param) {
    var startMonth = parseInt($('#educational_background_start_month').val(), 10);
    var startYear = parseInt($('#educational_background_start_year').val(), 10);
    var endMonth = parseInt($('#educational_background_end_month').val(), 10);
    var endYear = parseInt($('#educational_background_end_year').val(), 10);

    if (isNaN(endYear) || isNaN(endMonth)) {
        return true;
    }

    if (endYear > startYear) {
        return true;
    } else if (endYear === startYear && endMonth >= startMonth) {
        return true;
    }

    return false;
}, "End date cannot be earlier than the start date");

$.validator.addMethod("educationalStageEndMonthYearRequired", function(value, element) {
    var endMonth = $('#educational_background_end_month').val();
    var endYear = $('#educational_background_end_year').val();

    if ((endMonth || endYear) && !(endMonth && endYear)) {
        return false;
    }

    return true;
}, "End month and year cannot be empty if either has a value.");

$.validator.addMethod("employmentHistoryDateGreaterOrEqual", function (value, element, param) {
    var startMonth = parseInt($('#employment_history_start_month').val(), 10);
    var startYear = parseInt($('#employment_history_start_year').val(), 10);
    var endMonth = parseInt($('#employment_history_end_month').val(), 10);
    var endYear = parseInt($('#employment_history_end_year').val(), 10);

    if (isNaN(endYear) || isNaN(endMonth)) {
        return true;
    }

    if (endYear > startYear) {
        return true;
    } else if (endYear === startYear && endMonth >= startMonth) {
        return true;
    }

    return false;
}, "End date cannot be earlier than the start date");

$.validator.addMethod("employmentHistoryEndMonthYearRequired", function(value, element) {
    var endMonth = $('#employment_history_end_month').val();
    var endYear = $('#employment_history_end_year').val();

    if ((endMonth || endYear) && !(endMonth && endYear)) {
        return false;
    }

    return true;
}, "End month and year cannot be empty if either has a value.");

$.validator.addMethod("employeeLicenseDateGreaterOrEqual", function (value, element, param) {
    var startMonth = parseInt($('#license_start_month').val(), 10);
    var startYear = parseInt($('#license_start_year').val(), 10);
    var endMonth = parseInt($('#license_end_month').val(), 10);
    var endYear = parseInt($('#license_end_year').val(), 10);

    if (isNaN(endYear) || isNaN(endMonth)) {
        return true;
    }

    if (endYear > startYear) {
        return true;
    } else if (endYear === startYear && endMonth >= startMonth) {
        return true;
    }

    return false;
}, "End date cannot be earlier than the start date");

$.validator.addMethod("employeeLicenseEndMonthYearRequired", function(value, element) {
    var endMonth = $('#license_end_month').val();
    var endYear = $('#license_end_year').val();

    if ((endMonth || endYear) && !(endMonth && endYear)) {
        return false;
    }

    return true;
}, "End month and year cannot be empty if either has a value.");

