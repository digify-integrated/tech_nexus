(function($) {
    'use strict';

    $(function() {
        var page = 1;
        var is_loading = false;
        var documentCard = $('#document-card');
        var loadContent = $('#load-content');
        var documentSearch = $('#document_search');
        var lastSearchValue = '';

        var debounceTimeout;

        function debounce(func, delay) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(func, delay);
        }

        function loadDocumentCard(current_page, is_loading, clearExisting) {
            if (is_loading) return;

            var document_search = documentSearch.val();
            var filter_upload_date_start_date = $('#filter_upload_date_start_date').val();
            var filter_upload_date_end_date = $('#filter_upload_date_end_date').val();
            var filter_publish_date_start_date = $('#filter_publish_date_start_date').val();
            var filter_publish_date_end_date = $('#filter_publish_date_end_date').val();
            var document_category_filter_values = [];
            var department_filter_values = [];

            $('.document-category-filter:checked').each(function() {
                document_category_filter_values.push($(this).val());
            });

            $('.department-filter:checked').each(function() {
                department_filter_values.push($(this).val());
            });
        
            var document_category_filter = document_category_filter_values.join(', ');
            var department_filter = department_filter_values.join(', ');
        
            lastSearchValue = document_search;

            is_loading = true;
            const type = 'document card';

            if (clearExisting) {
                documentCard.empty();
            }

            loadContent.removeClass('d-none');

            $.ajax({
                url: 'view/_document_generation.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    current_page: current_page,
                    document_search: document_search,
                    filter_upload_date_start_date: filter_upload_date_start_date,
                    filter_upload_date_end_date: filter_upload_date_end_date,
                    filter_publish_date_start_date: filter_publish_date_start_date,
                    filter_publish_date_end_date: filter_publish_date_end_date,
                    document_category_filter: document_category_filter,
                    department_filter: department_filter,
                    type: type
                },
                success: function(response) {
                    is_loading = false;

                    loadContent.addClass('d-none');

                    if (response.length === 0) {
                        if (current_page === 1) {
                            documentCard.html('<div class="col-lg-12 text-center">No document found.</div>');
                        }
                        return;
                    }

                    response.forEach(function(item) {
                        documentCard.append(item.documentCard);
                    });

                    documentCard.find('.no-search-result').remove();
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

        function resetAndLoadDocumentCard() {
            page = 1;
            loadDocumentCard(page, false, true);
        }

        function debounceAndReset() {
            debounce(function() {
                resetAndLoadDocumentCard();
            }, 300);
        }

        if (documentCard.length) {
            loadDocumentCard(page, is_loading, true);
            documentSearch.on('keyup', function() {
                debounceAndReset();
            });

            $(document).on('click','#apply-filter',function() {
                debounceAndReset();
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    if (!is_loading) {
                        page++;
                        loadDocumentCard(page, is_loading, false);
                    }
                }
            });
        }

        documentSearch.val(lastSearchValue);

        if($('#preview-protected-document-form').length){
            previewProtectedDocumentForm();
        }

        $(document).on('click','#unpublish-document',function() {
            const document_id = $('#document-id').text();
            const transaction = 'unpublish document';
    
            Swal.fire({
                title: 'Confirm Document Unpublish',
                text: 'Are you sure you want to unpublish this document?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Unpublish',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/document-controller.php',
                        dataType: 'json',
                        data: {
                            document_id : document_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Unpublish Document Success', 'The document has been unpublished successfully.', 'success');
                                window.location = 'document.php';
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
                                    showNotification('Unpublish Document Error', response.message, 'danger');
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

        if($('#document-version-history-summary').length){
            documentVersionHistorySummary();
        }
    });
})(jQuery);

function documentCard(current_page, is_loading){
    if (is_loading) return;
    var document_search = $('#document_search').val();

    is_loading = true;

    const type = 'document card';
            
    $.ajax({
        url: 'view/_document_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            current_page : current_page,
            document_search : document_search,
            type : type
        },
        beforeSend: function(){
            $('#load-content').removeClass('d-none');
            if(current_page == 1){
                $('#document-card').html('');
            }
        },
        success: function(response) {
            is_loading = false;

            if (response.length == 0) {
                if(current_page == 1){
                    document.getElementById('document-card').innerHTML = '<div class="col-lg-12 text-center">No document found.</div>';
                }
                return;
            }
            
            response.forEach(function(item) {
                $('#document-card').append(item.documentCard);
            });

            current_page++;
            $('#document-card').find('.no-search-result').remove();
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

function previewProtectedDocumentForm(){
    $('#preview-protected-document-form').validate({
        rules: {
            document_password: {
              required: true,
            },
        },
        messages: {
            document_password: {
              required: 'Please enter the document password'
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
        const transaction = 'preview protected document';
        const document_id = $('#document-id').text();
  
        $.ajax({
            type: 'POST',
            url: 'controller/document-controller.php',
            data: $(form).serialize() + '&transaction=' + transaction + '&document_id=' + document_id,
            dataType: 'json',
            beforeSend: function() {
                disableFormSubmitButton('submit-preview-protected-document-data');
            },
            success: function(response) {
                if (response.success) {
                    $('#preview-protected-document-offcanvas').offcanvas('hide');
                    resetModalForm('preview-protected-document-form');
                    window.open(response.documentLink, '_blank');
                }
                else{
                    if(response.isInactive){
                        window.location = 'logout.php?logout';
                    }
                    else if (response.incorrectPassword) {
                        showNotification('Preview Protected Document Error', 'The entered document password is incorrect. Please double-check and ensure that you have entered the correct password.', 'danger');
                    }
                    else if (response.notExist) {
                        window.location = '404.php';
                    }
                    else{
                        showNotification('Preview Protected Document Error', response.message, 'danger');
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
                enableFormSubmitButton('submit-preview-protected-document-data', 'Preview Document');
            }
        });
  
        return false;
      }
    });
}

function documentVersionHistorySummary(){
    const type = 'document version history summary';
    var document_id = $('#document-id').text();
            
    $.ajax({
        url: 'view/_document_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            document_id : document_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('document-version-history-summary').innerHTML = response[0].documentVersionHistorySummary;
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