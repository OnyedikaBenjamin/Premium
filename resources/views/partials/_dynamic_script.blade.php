<script>
(function($) {
    "use strict";
    
    $(document).ready(function(){
        $('.select2js').select2({
                width: '100%',
					// dropdownParent: jQuery(this).parent()
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        if($(".datepicker").length > 0){
            $(".datepicker").flatpickr({
                // dateFormat: "d-m-Y"
            });
        }
        if($(".max-datepicker").length > 0){
            $(".max-datepicker").flatpickr({
                // dateFormat: "d-m-Y",
                maxDate: "today"
            });
        }
        if($(".min-datepicker").length > 0){
            $(".min-datepicker").flatpickr({
                // dateFormat: "d-m-Y",
                minDate: "today"
            });
        }

        if($(".min-datetimepicker").length > 0){
            $(".min-datetimepicker").flatpickr({
                // dateFormat: "d-m-Y",
                enableTime: true,
                minDate: "today",
                time_24hr: true,
            });
        }

        if($(".datetimepicker").length > 0){
            $(".datetimepicker").flatpickr({
                // dateFormat: "d-m-Y",
                enableTime: true,
                // minDate: "today",
                time_24hr: true,
            });
        }

        if($(".expire-datetimepicker").length > 0){
            $(".expire-datetimepicker").flatpickr({
                enableTime: true,
                time_24hr: true,
                minDate: new Date().fp_incr(2) // 2 days from now
            });
        }
        if($(".min-datetimepicker-time").length > 0){
            $(".min-datetimepicker-time").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        }

        function errorMessage(message) {
            Snackbar.show({
                text: message,
                pos: 'bottom-center',
                backgroundColor: '#dc3545',
                actionTextColor: 'white'
            });
        }

        function showMessage(message) {
            Snackbar.show({
                text: message,
                pos: 'bottom-center'
            });
        }
        
        $(document).on('click', '.loadRemoteModel', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            if (url.indexOf('#') == 0) {
                $(url).modal('open');
            } else {
                $.get(url, function(data) {
                    $('#remoteModelData').html(data);
                    $('#remoteModelData').modal();
                    $('form').validator();
                    $(".datepicker").flatpickr({
                        dateFormat: "d-m-Y"
                    });
                });
            }
        });

        $(document).on('click', '[data-form="ajax"]', function(f) {
            $('form').validator('update');
            f.preventDefault();
            var current = $(this);
            current.addClass('disabled');
            var form = $(this).closest('form');
            var url = form.attr('action');
            var fd = new FormData(form[0]);

            $.ajax({
                type: "POST",
                url: url,
                data: fd, // serializes the form's elements.
                success: function(e) {
                    if (e.status == true) {
                        if (e.event == "submited") {
                            showMessage(e.message);
                            $(".modal").modal('hide');
                        }
                        if(e.event == 'refresh'){
                            // showMessage(e.message);
                            window.location.reload();
                        }
                        if(e.event == "callback"){
                            showMessage(e.message);
                            $(".modal").modal('hide');
                            location.reload();
                        }
                    }
                    if (e.status == false) {
                        if (e.event == 'validation') {
                            errorMessage(e.message);
                        }
                    }
                },
                error: function(error) {

                },
                cache: false,
                contentType: false,
                processData: false,
            });
            f.preventDefault(); // avoid to execute the actual submit of the form.

        });

        $(document).on('change','.change_status', function() {

            var status = $(this).prop('checked') == true ? 1 : 0;
            
            var key_name = $(this).attr('data-name');
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('changeStatus') }}",
                data: { 'status': status, 'id': id ,'type': type ,[key_name]: key_name },
                success: function(data){
                    if(data.status == false){
                        errorMessage(data.message)
                    }else{
                        showMessage(data.message);
                    }
                }
            });
        })
        $(document).ready(function () {
            
        })

        $(document).on('click', '[data-toggle="tabajax"]', function(e) {
            e.preventDefault();
            var selectDiv = this;
            ajaxMethodCall(selectDiv);
        });
        
        function ajaxMethodCall(selectDiv) {

            var $this = $(selectDiv),
                loadurl = $this.attr('data-href'),
                targ = $this.attr('data-target'),
                id = selectDiv.id || '';

            $.post(loadurl, function(data) {
                $(targ).html(data);
                $('form').append('<input type="hidden" name="active_tab" value="'+id+'" />');
            });

            $this.tab('show');
            return false;
        }

      

        $('form[data-toggle="validator"]').on('submit', function (e) {
            window.setTimeout(function () {
                var errors = $('.has-error')
                if (errors.length) {
                    $('html, body').animate({ scrollTop: "0" }, 500);
                    e.preventDefault()
                }
            }, 0);
        });   
        
        $(document).on('click','[data--confirmation="true"]',function(e){
            e.preventDefault();
            var form = $(this).attr('data--submit');

            var title = $(this).attr('data-title');

            var message = $(this).attr('data-message');

            var ajaxtype = $(this).attr('data--ajax');
            if(form == 'confirm_form') {
                $('#confirm_form').attr('action', $(this).attr('href'));
            }
            let __this = this

            confirmation(form,title,message,ajaxtype,__this);
        });

        function confirmation(form,title = "{{ __('messages.confirmation') }}",message = "{{ __('messages.delete_msg') }}",ajaxtype=false,_this){
            $.confirm({
            content: message,
            type: '',
            title: title,
            buttons: {
                yes: {
                    action: function () {
                        
                        if(ajaxtype == 'true') {
                            let url = _this;

                            let data = $('[data--submit="'+form+'"]').serializeArray();
                            $.post(url, data).then(response => {
                                if(response.status) {

                                    if(response.image != null){
                                        $(_this).remove();
                                        $('#'+response.preview).attr('src',response.image)
                                        if (jQuery.inArray(response.preview, ["service_attachment_preview"]) !== -1) {
                                            $('#'+response.preview+"_"+response.id).remove()
                                            let total_file = $('.remove-file').length;
                                            if(total_file == 0){
                                                $('.service_attachment_div').remove();
                                            }
                                        }
                                        if(response.preview == 'site_logo_preview'){
                                            $('.'+response.preview).attr('src',response.image);
                                        }
                                        if(response.preview == 'site_favicon_preview'){
                                            $('.'+response.preview).attr('href',response.image);
                                        }
                                        showMessage(response.message)
                                        return true;
                                    }
                                    $('.dataTable').DataTable().ajax.reload( null, false );
                                    showMessage(response.message)
                                }
                                if(response.status == false){
                                    errorMessage(response.message)
                                }
                            })
                        } else {
                            if (form !== undefined && form){
                                $(document).find('[data--submit="'+form+'"]').submit();
                            }else{
                                return true;
                            }
                        }
                    }
                },
                no: {
                    action: function () {}
                },
            },
            theme: 'material'
        });
        return false;
    }

        $('.notification_list').on('click',function(){
            notificationList();
        });

        $(document).on('click','.notifyList',function(){

            notificationList($(this).attr('data-type'));
        });

         $(document).on('click','.notification_data',function(event){
            event.stopPropagation();
         })

        function notificationList(type=''){
            var url = "{{ route('notification.list') }}";
            $.ajax({
                type: 'get',
                url: url,
                data: {'type':type},
                success: function(res){

                    $('.notification_data').html(res.data);
                    getNotificationCounts();
                    if(res.type == "markas_read"){
                        notificationList();
                    }
                    $('.notify_count').removeClass('notification_tag').text('');
                }
            });
        }

        function getNotificationCounts(){
            var url = "{{ route('notification.counts') }}";
            $.ajax({
                type: 'get',
                url: url,
                success: function(res){

                        console.log(res);
                    if(res.counts > 0){
                       
                        $('.notify_count').removeClass('d-none');
                        $('.notify_count').addClass('notification_tag').text(res.counts);
                        setNotification(res.counts);
                        $('.notification_list span.dots').removeClass('d-none');
                        
                      
                    }else{
                        $('.notify_count').addClass('notification_tag').text( res.unread_total_count);
                        setNotification( res.unread_total_count);
                        $('.notify_count').removeClass('d-none')
                        $('.notification_list span.dots').removeClass('d-none')


                        if(res.counts <= 0 && res.unread_total_count > 0){
                        $('.notification_list span.dots').removeClass('d-none')
                       }else{
                       $('.notify_count').addClass('d-none')
                       $('.notification_list span.dots').addClass('d-none')
                      }

                    }

                    
                }
            });
        }

        getNotificationCounts();

        setInterval(getNotificationCounts, 600000);
        
        function setNotification(count){
            if(Number(count) >= 100){
                $('.notify_count').text('99+');
            }
        }

        $(document).on('click','.change-lang',function (e) {
            let lang = $(this).attr('data-lang');
            setCookie('handyman_LANG',lang)
        })

        $(document).on('change', '.custom-file-input', function() {
            readURL(this);
        })

        function readURL(input) {
            var target = $(input).attr('data--target');
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var field_name = $(input).attr('name');
                var msg = "{{ __('messages.image_png_gif') }}";
                var selected_file = [];

                if (jQuery.inArray(field_name, ["service_attachment[]"]) !== -1) {
                    for (var i = 0; i < $(input).get(0).files.length; ++i) {
                        var file_name = $(input).get(0).files[i].name;
                        // console.log(file_name)
                        res = isAttachments(file_name);
                        msg = $(input).attr('data-file-error');
                        if (res == false) {
                            $('.selected_file').text('');
                            errorMessage(msg);
                            $(input).val("");
                            return false;
                        }else{
                            selected_file.push(file_name);
                            $('.selected_file').text(selected_file);
                        }
                    }
                } else if(jQuery.inArray(field_name, ["provider_document"]) !== -1){
                    var res = isDocuments(input.files[0].name);
                    if ($('.selected_file').length > 0) {
                        $('.selected_file').text(input.files[0].name);
                    }
                }  else {
                    var res = isImage(input.files[0].name);
                    if ($('.selected_file').length > 0) {
                        $('.selected_file').text(input.files[0].name);
                    }
                }

                if (res == false) {
                    errorMessage(msg)
                    $(input).val("");
                    return false;
                }
                reader.onload = function(e) {
                    $('.'+target).attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }

            var modal = $(input).attr('data--modal');

            if (modal !== undefined && modal !== null && modal === 'modal')
                $('.image_upload-modal').modal('hide');

        }

        function getExtension(filename) {
            var parts = filename.split('.');
            return parts[parts.length - 1];
        }

        function isImage(filename) {
            var ext = getExtension(filename);
            switch (ext.toLowerCase()) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'svg':
                case 'ico':
                    return true;
            }
            return false;
        }

        function isDocuments(filename) {
            var ext = getExtension(filename);
            var validExtensions = ["jpg", "pdf", "jpeg", "gif", "png"];

            if (jQuery.inArray(ext.toLowerCase(), validExtensions) !== -1) {
                return true;
            }
            return false;
        }

        function isAttachments(filename) {
            var ext = getExtension(filename);
            var validExtensions = ["jpg", "pdf", "jpeg", "gif", "png", "mp4", "avi"];
            // console.log(ext);
            if (jQuery.inArray(ext.toLowerCase(), validExtensions) !== -1) {
                return true;
            }
            return false;
        }
    });
})(jQuery);
</script>
