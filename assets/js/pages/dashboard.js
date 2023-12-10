(function($) {
    'use strict';

    $(function() {
        document.querySelector('#record-attendance').addEventListener('click', async function() {
            getLocation('location');

            let attendance_video = document.getElementById('attendance-video');
            $('#attendance-video').removeClass('d-none');
            $('#capture-attendance').removeClass('d-none');
            $('#attendance-image').addClass('d-none');

            $('#record-attendance-modal').modal('show');
        
            let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            attendance_video.srcObject = stream;
        
            $('#record-attendance-modal').on('hidden.bs.modal', function (e) {
                stream.getTracks().forEach(track => track.stop());
            });
        });
        
        document.querySelector('#capture-attendance').addEventListener('click', function() {
            $('#attendance-image').removeClass('d-none');
            $('#attendance-video').addClass('d-none');
            $('#capture-attendance').addClass('d-none');

            let attendance_video = document.getElementById('attendance-video');
            let attendance_image = document.getElementById('attendance-image');
        
            attendance_image.width = attendance_video.videoWidth;
            attendance_image.height = attendance_video.videoHeight;
        
            attendance_image.getContext('2d').drawImage(attendance_video, 0, 0, attendance_image.width, attendance_image.height);
        
            let image_data_url = attendance_image.toDataURL('image/png');
        
            console.log(image_data_url);
        
            if (attendance_video.srcObject) {
                attendance_video.srcObject.getTracks().forEach(track => track.stop());
            }
        });
    });
})(jQuery);