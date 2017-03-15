if (jQuery('#toplevel_page_salon').hasClass('wp-menu-open')) {
  jQuery('#wpbody-content .wrap').addClass('sln-bootstrap');
  jQuery('#wpbody-content .wrap').attr('id', 'sln-salon--admin');
}


jQuery(function ($) {
/*
    $('#booking-accept, #booking-refuse').click(function(){
       $('#post_status').val($(this).data('status'));
       $('#save-post').click();
*/
    $('#booking-accept, #booking-refuse').click(function () {
        $('#_sln_booking_status').val($(this).data('status'));
        $('#save-post').click();
    });

    $('.sln-toolbox-trigger').click(function(event) {
        $(this).parent().toggleClass('open');
        event.preventDefault();
    });
    $('.sln-toolbox-trigger-mob').click(function(event) {
        $(this).parent().find('.sln-toolbox').toggleClass('open');
        event.preventDefault();
    });
    $('.sln-box-info-trigger button').click(function(event) {
        $(this).parent().parent().parent().toggleClass('sln-box--info-visible');
        event.preventDefault();
    });
     $('.sln-box-info-content:after').click(function(event) {
        event.preventDefault();
    });

    if($('.sln-admin-sidebar').length) {
        $('.sln-admin-sidebar').affix({
        offset: {
            top: $('.sln-admin-sidebar').offset().top - 40
        }
        });
    }
    $('[data-action=change-service-type]').change(function() {
        var $this   = $(this);
        var $target = $($this.attr('data-target'));
        if($this.is(':checked')) {
            $target.removeClass('hide');
        }
        else {
            $target.addClass('hide');
        }
    });

    $('[data-action=change-secondary-service-mode]').change(function() {
        var $this   = $(this);
        var $target = $($this.attr('data-target'));
        if($this.val() === 'service') {
            $target.removeClass('hide');
        }
        else {
            $target.addClass('hide');
        }
    });
    //$( document ).ajaxComplete(function( event, request, settings ) {
    //  alert('test al');
    //});
    $(window).bind("load", function() {
        if ( $( ".sln-calendar--wrapper" ).length ) {
            $('.sln-calendar--wrapper--sub').css('opacity', '1');
            $('.sln-calendar--wrapper').removeClass('sln-calendar--wrapper--loading');
        }
    });

    initImporter($('#import-customers-drag'), 'Customers');
    initImporter($('#import-services-drag'), 'Services');
    initImporter($('#import-assistants-drag'), 'Assistants');
});

function initImporter($item, mode) {
    var $importArea = $item;

    $importArea[0].ondragover = function() {
        $importArea.addClass('hover');
        return false;
    };

    $importArea[0].ondragleave = function() {
        $importArea.removeClass('hover');
        return false;
    };

    $importArea[0].ondrop = function(event) {
        event.preventDefault();
        $importArea.removeClass('hover').addClass('drop');

        var file = event.dataTransfer.files[0];

        $importArea.file = file;

        $importArea.find('.text').html(file.name);
        importShowInfo();
    };

    jQuery('[data-action=sln_import][data-target=' + $importArea.attr('id') + ']').click(function() {
        if (!$importArea.file) {
            return false;
        }
        $importArea.find('.progress-bar').attr('aria-valuenow', 0).css('width', '0%');
        importShowPB();

        var data = new FormData();

        data.append('action', 'salon');
        data.append('method', 'import'+mode);
        data.append('step', 'start');
        data.append('file', $importArea.file);

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, //(Don't process the files)
            contentType: false,
            success: function(response) {
                if (response.success) {
                    console.log(response);
                    importProgressPB(response.data.total, response.data.left);
                }
                else {
                    importShowError();
                }
            },
            error: function() {
                importShowError();
            }
        });

        $importArea.file = false;

        return false;
    });

    function importProgressPB(total, left) {
        total = parseInt(total);
        left = parseInt(left);

        var value = ((total - left) / total) * 100;
        $importArea.find('.progress-bar').attr('aria-valuenow', value).css('width', value+'%');

        if (left != 0) {
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'salon',
                    method: 'import'+mode,
                    step: 'process',
                },
                cache: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        importProgressPB(response.data.total, response.data.left);
                    }
                    else {
                        importShowError();
                    }
                },
                error: function() {
                    importShowError();
                }
            });
        }
        else {
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'salon',
                    method: 'import'+mode,
                    step: 'finish',
                },
                cache: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        importShowSuccess();
                    }
                    else {
                        importShowError();
                    }
                },
                error: function() {
                    importShowError();
                }
            });
        }
    }

    function importShowPB() {
        $importArea.find('.alert').addClass('hide');
        $importArea.find('.progress').removeClass('hide');
    }

    function importShowInfo() {
        $importArea.find('.alert, .progress').addClass('hide');
        $importArea.find('.info').removeClass('hide');
    }

    function importShowSuccess() {
        $importArea.find('.info, .alert, .progress').addClass('hide');
        $importArea.find('.alert-success').removeClass('hide');
    }

    function importShowError() {
        $importArea.find('.info, .alert, .progress').addClass('hide');
        $importArea.find('.alert-danger').removeClass('hide');
    }
}