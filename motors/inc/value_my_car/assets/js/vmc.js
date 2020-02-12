(function ($) {
    $(document).ready(function() {
        var userFiles = [];
        var timeId = '';

        $('input[name="photo"]').on('change', function() {
            if(userFiles.length < 3) {
                var _form = $('form[name="vmc-form"]');

                var fd = new FormData;

                fd.append('action', 'stm_ajax_get_file_size');

                fd.append('security', getFileSize);

                $('input[type="file"]').each(function () {
                    if ($(this)[0].files.length) fd.append($(this).attr('name'), $(this)[0].files[0]);

                    var file = $(this)[0].files[0];

                    var msg = '';

                    if (!file.type.match(/^image/)) {
                        msg = file_type;
                    } else if (file.size > 3145728) {
                        msg = file_size;
                    }

                    if (msg == '') {
                        $('.file-wrap .error').text('');
                        userFiles.push(file);
                        $(this).val(null);
                        updateFileList();
                    } else {
                        $('.file-wrap .error').text(msg);
                    }
                });
            } else {
                $(this).val(null);
                $('.file-wrap .error').text(max_img_quant);
            }
        });

        $("#vmc-btn").on('click', function (e) {

            var _form = $('form[name="vmc-form"]');
            var btn = $(this);

            var $this = $(this);

            e.preventDefault();

            var fd = new FormData;

            var form_data = _form.serializeArray();

            if (typeof $(this).parent().find('input[name="motors-gdpr-agree"]')[0] !== 'undefined') {
                var gdprAgree = ($(this).parent().find('input[name="motors-gdpr-agree"]')[0].checked) ? 'agree' : 'not_agree';
                fd.append('motors-gdpr-agree', gdprAgree);
            }

            form_data.forEach(function(element) {
                fd.append(element['name'], element['value']);
            });

            fd.append('action', 'stm_ajax_value_my_car');
            fd.append('security', valueMyCar);

            $.each(userFiles, function (i, file) {
                if (typeof(file) !== undefined) {
                    if (typeof(file) !== 'number') {
                        fd.append('files[' + i + ']', file);
                    }
                }
            });

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    btn.addClass('loading');
                },
                success: function (data) {
                    btn.removeClass('loading');
                    if(timeId != '') {
                        clearTimeout(timeId);
                    }

                    $('form[name="vmc-form"]')[0].reset();
                    $('.vmc-photo-row').remove();
                    userFiles = [];

                    $('.notification-wrapper').addClass('shown');
                    $('.notification-wrap').addClass(data.status);
                    $('.notification-wrapper').find('.message').html(data.msg);



                    timeId = setTimeout(function () {
                        $('.notification-wrapper').show().removeClass('shown');

                    }, 5000);
                }
            });
        });

        $('.notification-close').on('click', function () {
            $('.notification-wrapper').show().removeClass('shown');
        });

        $('body').on('click', '.file-name-wrap .fa-close', function () {
            var dataIndex = $(this).attr('data-index');
            $(this).parent().parent().remove();
        userFiles.splice(dataIndex);
        });

        function updateFileList() {
            var html = '';
            var position = userFiles.length - 1;
            var file = userFiles[position];

            if (typeof(file) !== undefined) {
                if (typeof(file) !== 'number') {
                    html += '<div class="col-md-3 col-sm-6 col-xs-12 stm-select-col vmc-photo-row" style="display: block;"><span class="file-name-wrap">' + file.name + '<i class="fa fa-close" data-index="' + position + '"></i></span></div>';
                }
            }

            $('.vmc-file-wrap').before(html);
        }
    });
})(jQuery);