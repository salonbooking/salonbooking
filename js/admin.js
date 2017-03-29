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
        if ( $("#sln-salon--admin.sln-calendar--wrapper--loading").length ) {
            $('.sln-calendar--wrapper--sub').css('opacity', '1');
            $('.sln-calendar--wrapper').removeClass('sln-calendar--wrapper--loading sln-calendar--wrapper');
        }
        if ( $( ".sln-calendar--wrapper" ).length ) {
            $('.sln-calendar--wrapper--sub').css('opacity', '1');
            $('.sln-calendar--wrapper').removeClass('sln-calendar--wrapper--loading');
        }
    });

    if ($('#import-customers-drag').size() > 0) {
        initImporter($('#import-customers-drag'), 'Customers');
    }
    if ($('#import-services-drag').size() > 0) {
        initImporter($('#import-services-drag'), 'Services');
    }
    if ($('#import-assistants-drag').size() > 0) {
        initImporter($('#import-assistants-drag'), 'Assistants');
    }
});

var importRows;
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
        importShowFileInfo();
    };

    jQuery('[data-action=sln_import][data-target=' + $importArea.attr('id') + ']').click(function() {
        var $importBtn = jQuery(this);
        $importBtn.button('loading');
        if (!$importArea.file) {
            $importBtn.button('reset');
            return false;
        }
        $importArea.find('.progress-bar').attr('aria-valuenow', 0).css('width', '0%');
        importShowInfo();

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
                $importBtn.button('reset');
                if (response.success) {
                    console.log(response);
                    importRows = response.data.rows;

                    var $modal = jQuery('#import-matching-modal');

                    var $modalBtn = $modal.find('[data-action=sln_import_matching]');
                    $modalBtn.button('reset');

                    $modal.find('table tbody').html(response.data.matching);
                    jQuery('#wpwrap').css('z-index', 'auto');
                    $modal.modal({
                        keyboard: false,
                        backdrop: true,
                    });
                    sln_createSelect2Full(jQuery);
                    validImportMatching();
                    $modal.find('[data-action=sln_import_matching_select]').change(changeImportMatching);

                    jQuery('[data-action=sln_import_matching]').unbind('click').click(function() {
                        if (!validImportMatching()) {
                            return false;
                        }
                        $modalBtn.button('loading');

                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'salon',
                                method: 'import'+mode,
                                step: 'matching',
                                form: $modal.closest('form').serialize(),
                            },
                            cache: false,
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                $modal.modal('hide');
                                if (response.success) {
                                    importShowPB();
                                    importProgressPB(response.data.total, response.data.left);
                                }
                                else {
                                    importShowError();
                                }
                            },
                            error: function() {
                                $modal.modal('hide');
                                importShowError();
                            }
                        });
                    });
                }
                else {
                    importShowError();
                }
            },
            error: function() {
                $importBtn.button('reset');
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
        $importArea.find('.info, .alert').addClass('hide');
        $importArea.find('.progress').removeClass('hide');
    }

    function importShowFileInfo() {
        $importArea.find('.alert, .progress').addClass('hide');
        $importArea.find('.info').removeClass('hide');
    }

    function importShowInfo() {
        $importArea.find('.text').html($importArea.find('.text').attr('placeholder'));
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

function changeImportMatching() {
    var $select = jQuery(this);
    var field   = $select.val();
    var col     = $select.attr('data-col');

    $select.closest('table').find('tr.import_matching').each(function(index, v) {
        var $cell = jQuery(this).find('td[data-col=' + col + '] span');

        var text;
        if (importRows[index] !== undefined && importRows[index][field] !== undefined) {
            $cell.addClass('pull-left').removeClass('half-opacity').html(importRows[index][field]);
        }
        else {
            $cell.removeClass('pull-left').addClass('half-opacity').html($cell.closest('td').attr('placeholder'));
        }
    });

    validImportMatching();
}

function validImportMatching() {
    var $modal = jQuery('#import-matching-modal');

    var valid = true;
    $modal.find('select').each(function() {
        if (jQuery(this).prop('required') && jQuery(this).val() == '') {
            valid = false;
        }
    });

    if (valid) {
        $modal.find('.alert').addClass('hide');
        $modal.find('[data-action=sln_import_matching]').prop('disabled', false);
    }
    else {
        $modal.find('.alert').removeClass('hide');
        $modal.find('[data-action=sln_import_matching]').prop('disabled', 'disabled');
    }

    return valid;
}
