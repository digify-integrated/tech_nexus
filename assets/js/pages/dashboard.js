(function($) {
    'use strict';

    $(function() {
        if($('#record-attendance-form').length){
            recordAttendanceForm();
        }

        document.querySelector('#record-attendance').addEventListener('click', async function() {
            getLocation('location');
        
            const videContainer = document.getElementById('video-container');
            const attendanceVideo = document.getElementById('attendance-video');
            const attendanceImage = document.getElementById('attendance-image');
            const captureAttendance = document.getElementById('capture-attendance');
            const submitAttendance = document.getElementById('submit-attendance');
        
            attendanceImage.classList.add('d-none');
            submitAttendance.classList.add('d-none');
        
            $('#record-attendance-modal').modal('show');
        
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                attendanceVideo.srcObject = stream;
        
                attendanceVideo.addEventListener('loadedmetadata', function() {
                    attendanceVideo.classList.remove('d-none');
                    videContainer.classList.remove('d-none');
                    captureAttendance.classList.remove('d-none');
                });
        
                document.getElementById('record-attendance-modal').addEventListener('hidden.bs.modal', function (e) {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        videContainer.classList.add('d-none');
                        captureAttendance.classList.add('d-none');
                    }
                });
            } catch (error) {
                document.getElementById('record-attendance-modal').modal('hide');
                showNotification('Open Video Source Error', 'An error occurred while trying to open the video source: ' + error, 'danger');
            }
        });
        
        document.querySelector('#capture-attendance').addEventListener('click', function () {
            const location = $('#location').val();
          
            if (location !== '') {
                const attendanceVideo = document.getElementById('attendance-video');
                
                if (attendanceVideo.srcObject) {
                    $('#attendance-image, #submit-attendance').removeClass('d-none');
                    $(this).addClass('d-none');
                    $('#attendance-video, #capture-attendance').addClass('d-none');
            
                    const attendanceImage = document.getElementById('attendance-image');
            
                    attendanceImage.width = attendanceVideo.videoWidth;
                    attendanceImage.height = attendanceVideo.videoHeight;
            
                    attendanceImage.getContext('2d').drawImage(attendanceVideo, 0, 0, attendanceImage.width, attendanceImage.height);
            
                    sessionStorage.setItem('attendance_image_data', attendanceImage.toDataURL('image/png'));
            
                    attendanceVideo.srcObject.getTracks().forEach(track => track.stop());
                }
                else {
                    showNotification('Capture Image Error', 'Failed to capture image. Video not started.', 'danger');
                }
            }
            else {
                showNotification('Capture Image Error', 'Failed to capture image. Location cannot be determined.', 'danger');
            }
        });
    });
})(jQuery);

function recordAttendanceForm(){
    $('#record-attendance-form').validate({
        submitHandler: function(form) {
            const attendance_image_data = sessionStorage.getItem('attendance_image_data');

            const transaction = 'save attendance record regular';

            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('attendance_image_data', attendance_image_data);
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-attendance');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Attendance Check-In Success' : 'Attendance Check-Out Success';
                        const notificationDescription = response.insertRecord ? 'The attendance record has been inserted successfully.' : 'The attendance record has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        location.reload();
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
                    enableFormSubmitButton('submit-attendance', 'Submit');
                }
            });
        
            return false;
        }
    });
}