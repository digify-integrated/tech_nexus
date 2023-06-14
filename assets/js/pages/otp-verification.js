$(document).ready(function () {  
    $('#otp-form').validate({
      rules: {
        otp: {
          required: true
        }
      },
      messages: {
        otp: {
          required: 'Please enter the verification code'
        }
      },
      errorPlacement: function (error, element) {
        if (element.hasClass('select2')) {
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
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next().find('.select2-selection__rendered').addClass('is-invalid');
        } 
        else {
          $(element).addClass('is-invalid');
        }
      },
      unhighlight: function(element) {
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next().find('.select2-selection__rendered').removeClass('is-invalid');
        }
        else {
          $(element).removeClass('is-invalid');
        }
      },
      submitHandler: function(form) {
        const transaction = 'otp authentication';
  
        $.ajax({
          type: 'POST',
          url: 'controller/user-controller.php',
          data: $(form).serialize() + '&transaction=' + transaction,
          dataType: 'JSON',
          beforeSend: function() {
            disableFormSubmitButton('verify');
          },
          success: function(response) {
            if (response.success) {
              window.location.href = 'dashboard.php';
            } 
            else {
              if(response.errorRedirect){
                window.location.href = 'error.php?type=' + response.errorType;
              }
              else{
                showNotification('OTP Verification Error', response.message, 'danger');
              }
            }          
          },
          error: function(xhr, status, error) {
            var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;

            var response = xhr.responseText;
            fullErrorMessage += ', Response: ' + response;
          
            showErrorDialog(fullErrorMessage);
          },
          complete: function() {
            enableFormSubmitButton('verify', 'Continue');
          }
        });
  
        return false;
      }
    });
  });