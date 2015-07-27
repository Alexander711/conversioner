$(function () {
    $("#file_upload").uploadify({
        'buttonImage': '/images/add.png',
        'swf': '/js/uploadify/uploadify.swf',
        'uploader': '/generate_widget/temporary_uploading_img_contact_window',
        'queueID': 'queue_uploadify',
        'auto': true,
        'fileTypeExts': '*.jpg',
        'fileSizeLimit': '5MB',
        'height': 16,
        'width': 16,
        'onUploadStart': function () {
            var name_uploaded_image = $("input[name='name_uploaded_image']", '#add_widget_form').val();
            var id_widget = $("input[name='id']", '#add_widget_form').val()

            if (name_uploaded_image != '' && id_widget == 0) {
                del_temporary_upload_img(name_uploaded_image);
            }

            if (name_uploaded_image != '' && id_widget != 0) {
                $("input[name='name_uploaded_image']", '#add_widget_form').val('');
            }
        },
        'onUploadSuccess': function (file, data, response) {
            if (data == 'extension') {
                $('.errors_add_widget').html('<span>Можно загружать только jpg изображения</span><br/>');
                $('.errors_add_widget').show().delay(5000).fadeOut(1000);
            } else if (data == 'size') {
                $('.errors_add_widget').html('<span>Размер файла не должен превышать 5мб</span><br/>');
                $('.errors_add_widget').show().delay(5000).fadeOut(1000);
            } else {
                $("input[name='name_uploaded_image']", "#add_widget_form").val(data);
                $("input[name='img_window']", "#add_widget_form").prop('checked', false);
                $(".change_img_operator_body .img_upload_success").show();
            }
        },
        'onSelectError': function (file, errorCode) {
            if (errorCode == -110) {
                error_mess = '<span>Размер файла не должен превышать 5мб</span><br/>'
                $('.errors_add_widget').html(error_mess);
                $('.errors_add_widget').show().delay(5000).fadeOut(1000);
            }
        },
        overrideEvents: ['onSelectError', 'onDialogClose']
    });

});