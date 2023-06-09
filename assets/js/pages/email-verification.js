$(document).ready(function () {  
    $('#signin-form').validate({
      rules: {
        email: {
          required: true,
          email: true
        },
        password: {
          required: true
        }
      },
      messages: {
        email: {
          required: 'Please enter your email address',
          email: 'Please enter a valid email address'
        },
        password: {
          required: 'Please enter your password'
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
        const transaction = 'verify email';
  
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
                if (response.emailVerification) {
                  var encryptedUserID = response.encryptedUserID;
                  window.location.href = 'email-verification.php?id=' + encryptedUserID + '&type=email';
                }
                else if (response.passwordExpiry) {
                  var encryptedUserID = response.encryptedUserID;
                  window.location.href = 'password-reset.php?id=' + encryptedUserID + '&type=reset';
                }
                else if (response.twoFactorAuth) {
                  var encryptedUserID = response.encryptedUserID;
                  window.location.href = 'otp-verification.php?id=' + encryptedUserID;
                }
                else {
                  window.location.href = 'dashboard.php';
                }
            } 
            else {
                showNotification('Email Verification Error', response.message, 'danger');
            }
          
          },
          error: function(xhr, status, error) {
            showNotification('Email Verification Error', 'An error occurred while processing your request. Please try again later.', 'danger');
          },
          complete: function() {
            enableFormSubmitButton('verify', 'Continue');
          }
        });
  
        return false;
      }
    });
  });