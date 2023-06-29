$(document).ready(function () {  
    $('#forgot-password-form').validate({
        rules: {
            email: {
              required: true,
            }
        },
        messages: {
            email: {
              required: 'Please enter email address'
            },
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
        const transaction = 'forgot password';
  
        $.ajax({
          type: 'POST',
          url: 'controller/user-controller.php',
          data: $(form).serialize() + '&transaction=' + transaction,
          dataType: 'JSON',
          beforeSend: function() {
            disableFormSubmitButton('forgot-password');
          },
          success: function(response) {
            if (response.success) {
              setNotification('Forgot Password Success', 'We have sent a password reset link to your registered email address. Please follow the instructions in the email to securely reset your password.', 'success');
              window.location.href = 'index.php';
            } 
            else {
              showNotification('Forgot Password Error', response.message, 'danger');
            }
          },
          error: function(xhr, status, error) {
            var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;

            var response = xhr.responseText;
            fullErrorMessage += ', Response: ' + response;
          
            showErrorDialog(fullErrorMessage);
          },
          complete: function() {
            enableFormSubmitButton('forgot-password', 'Send Password Reset Email');
          }
        });
  
        return false;
      }
    });
  });