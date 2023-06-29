$(document).ready(function () {  
    $('#password-reset-form').validate({
        rules: {
            password: {
              required: true,
              password_strength: true
            },
            confirm_password: {
              required: true,
              equalTo: '#password'
            }
        },
        messages: {
            password: {
              required: 'Please enter password'
            },
            confirm_password: {
              required: 'Please re-enter your password for confirmation',
              equalTo: 'The passwords you entered do not match. Please make sure to enter the same password in both fields'
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
        const transaction = 'password reset';
  
        $.ajax({
          type: 'POST',
          url: 'controller/user-controller.php',
          data: $(form).serialize() + '&transaction=' + transaction,
          dataType: 'JSON',
          beforeSend: function() {
            disableFormSubmitButton('password-reset');
          },
          success: function(response) {
            if (response.success) {
              setNotification('Password Reset Success', 'Your password has been successfully updated. For security reasons, please use your new password to log in.', 'success');
              window.location.href = 'index.php';
            } 
            else {
              if(response.errorRedirect){
                window.location.href = 'error.php?type=' + response.errorType;
              }
              else{
                showNotification('Password Reset Error', response.message, 'danger');
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
            enableFormSubmitButton('password-reset', 'Reset Password');
          }
        });
  
        return false;
      }
    });
  });