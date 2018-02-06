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
        var $exclusive = $('#exclusive_service');
        if($this.is(':checked')) {
            $target.removeClass('hide');
            $exclusive.addClass('hide');
            $('#_sln_service_exclusive').val(0);
        }
        else {
            $target.addClass('hide');
            $exclusive.removeClass('hide');
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

    $('#_sln_service_price')
    .on( 'sln_add_error_tip', function( e, element, error_type ) {
            var offset = element.position();

            if ( element.parent().find( '.sln_error_tip' ).length === 0 ) {
                element.after( '<div class="sln_error_tip ' + error_type + '">' + salon_admin[error_type] + '</div>' );
                element.parent().find( '.sln_error_tip' )
                    .css( 'left', offset.left + element.width() - ( element.width() / 2 ) - ( $( '.sln_error_tip' ).width() / 2 ) )
                    .css( 'top', offset.top + element.height() )
                    .fadeIn( '100' );
            }
        })
    .on( 'sln_remove_error_tip', function( e, element, error_type ) {
        element.parent().find( '.sln_error_tip.' + error_type ).fadeOut( '100', function() { $( this ).remove(); } );
    })    
    .on( 'blur', function() {
        $( '.sln_error_tip' ).fadeOut( '100', function() { $( this ).remove(); } );
    })
    .on( 'change', function() {
            var regex = new RegExp( '[^\-0-9\%\\' + salon_admin.mon_decimal_point + ']+', 'gi' );
            var value    = $( this ).val();
            var newvalue = value.replace( regex, '' );

            if ( value !== newvalue ) {
                $( this ).val( newvalue );
            }
    })  
    .on( 'keyup', function() {
            var regex, error;            
            regex = new RegExp( '[^\-0-9\%\\' + salon_admin.mon_decimal_point + ']+', 'gi' );
            error = 'i18n_mon_decimal_error';            
            var value    = $( this ).val();
            var newvalue = value.replace( regex, '' );

            if ( value !== newvalue ) {
                $( '#_sln_service_price' ).triggerHandler( 'sln_add_error_tip', [ $( this ), error ] );
            } else {
                $( '#_sln_service_price' ).triggerHandler( 'sln_remove_error_tip', [ $( this ), error ] );
            }
    })
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
