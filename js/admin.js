if (jQuery('#toplevel_page_salon').hasClass('wp-menu-open')) {
  jQuery('#wpbody-content .wrap').addClass('sln-bootstrap');
  jQuery('#wpbody-content .wrap').attr('id', 'sln-salon--admin');
}
function initDatepickers($) {
    $('.sln_datepicker input').each(function () {
        if ($(this).hasClass('started')) {
            return;
        } else {
            $(this)
                .addClass('started')
                .datetimepicker({
                    format: $(this).data('format'),
                    weekStart: $(this).data('weekstart'),
                    minuteStep: 60,
                    autoclose: true,
                    minView: 2,
                    maxView: 4,
                    todayBtn: true,
                    language: $(this).data('locale')
                })
                .on('show', function () {
                    $('body').trigger('sln_date');
                })
                .on('place', function () {
                    $('body').trigger('sln_date');
                })
                .on('changeMonth', function () {
                    $('body').trigger('sln_date');
                })
                .on('changeYear', function () {
                    $('body').trigger('sln_date');
                })
            ;
        }
    });
}

function initTimepickers($) {
    $('.sln_timepicker input').each(function () {
        if ($(this).hasClass('started')) {
            return;
        } else {
            var picker = $(this)
                .addClass('started')
                .datetimepicker({
                    format: $(this).data('format'),
                    minuteStep: $(this).data('interval'),
                    autoclose: true,
                    minView: $(this).data('interval') == 60 ? 1 : 0,
                    maxView: 1,
                    startView: 1,
                    showMeridian: $(this).data('meridian') ? true : false,
                })
                .on('show', function () {
                    $('body').trigger('sln_date');
                })
                .on('place', function () {
                    $('body').trigger('sln_date');
                })

                .data('datetimepicker').picker;
            picker.addClass('timepicker');
        }
    });
}

function sln_checkServices($) {
    var form = $('#post');
    var data = form.serialize() + "&action=salon&method=CheckServices&part=allServices&security=" + salon.ajax_nonce;
    $.ajax({
        url: salon.ajax_url,
        data: data,
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            if (!data.success) {
                var alertBox = $('<div class="alert alert-danger"></div>');
                $.each(data.errors, function () {
                    alertBox.append('<p>').html(this);
                });
            }
            else {
                $('#sln_booking_services').find('.alert').remove();
                $.each(data.services, function(index, value) {
                    var serviceItem = $('#_sln_booking_attendants_' + index);
                    if (value.status == -1) {
                        $.each(value.errors, function(index, value) {
                            var alertBox = $('<div class="row col-xs-12 col-sm-12 col-md-12"><div class="' + ($('#salon-step-date').attr('data-m_attendant_enabled') ? 'col-md-offset-2 col-md-6' : 'col-md-8') + '"><p class="alert alert-danger">' + value + '</p></div></div>');
                            serviceItem.parent().parent().next().after(alertBox);
                        });
                    }
                    serviceItem.parent().parent().find('label.time:first').html(value.startsAt);
                    serviceItem.parent().parent().find('label.time:last').html(value.endsAt);
                });
            }
        }
    });
}

function sln_adminDate($) {
    var items = $('#salon-step-date').data('intervals');
    var doingFunc = false;

    var func = function () {
        if (doingFunc) return;
        setTimeout(function () {
            doingFunc = true;
            $('[data-ymd]').removeClass('disabled');
            $('[data-ymd]').addClass('red');
            $.each(items.dates, function (key, value) {
                $('.day[data-ymd="' + value + '"]').removeClass('red');
            });

            $.each(items.times, function (key, value) {
                $('.hour[data-ymd="' + value + '"]').removeClass('red');
                $('.minute[data-ymd="' + value + '"]').removeClass('red');
                $('.hour[data-ymd="' + value.split(':')[0] + ':00"]').removeClass('red');
            });
            doingFunc = false;
        }, 200);
        return true;
    }
    func();
    $('body').on('sln_date', func);
    var firstValidate = true;
    function validate(obj) {
        var form = $(obj).closest('form');
        var validatingMessage = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> ' + salon.txt_validating;
        var data = form.serialize();
        data += "&action=salon&method=checkDate&security=" + salon.ajax_nonce;
        $('#sln-notifications').html(validatingMessage);
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if(firstValidate){
                    $('#sln-notifications').html('');
                    firstValidate = false;
                } else if (!data.success) {
                    var alertBox = $('<div class="alert alert-danger"></div>');
                    $(data.errors).each(function () {
                        alertBox.append('<p>').html(this);
                    });
                    $('#sln-notifications').html('').append(alertBox);
                } else {
                    $('#sln-notifications').html('').append('<div class="alert alert-success">'+ $('#sln-notifications').data('valid-message')+'</div>');
                }
                bindIntervals(data.intervals);
                sln_checkServices($);
            }
        });
    }

    function bindIntervals(intervals) {
        items = intervals;
        func();
        putOptions($('#sln_date'), intervals.suggestedDate);
        putOptions($('#sln_time'), intervals.suggestedTime);
    }

    function putOptions(selectElem, value) {
        selectElem.val(value);
    }

    $('#_sln_booking_date, #_sln_booking_time').change(function () {
        validate(this);
    });
    validate($('#_sln_booking_date'));
    initDatepickers($);
    initTimepickers($);
    $('#resend-notification-submit').click(function(){
        var data = "post_id="+$('#post_ID').val()+"&emailto="+$('#resend-notification').val()+"&action=salon&method=ResendNotification&security=" + salon.ajax_nonce;
        var validatingMessage = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> ';
        $('#resend-notification-message').html(validatingMessage);
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if(data.success)
                    $('#resend-notification-message').html('<div class="alert alert-success">'+data.success+'</div>');
                else if(data.error)
                    $('#resend-notification-message').html('<div class="alert alert-danger">'+data.error+'</div>');
            }
        });
        return false;
    });
}

function sln_validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
function sln_validateBooking($){
        var form = $('#_sln_booking_firstname').closest('form');
        $(form).submit(function(){
            $('.sln-invalid').removeClass('sln-invalid');
            $('.sln-error').remove();
            var hasErrors = false;
            $.each([
                '#_sln_booking_firstname',
                '#_sln_booking_lastname',
                '#_sln_booking_email',
                '#_sln_booking_phone',
                '#_sln_booking_service_select',
            ], function(k, val){
                if (val == '#_sln_booking_phone' && !$('[name=_sln_booking_createuser]').is(':checked')) {
                    return;
                }else if(val == '#_sln_booking_email'){
                    if (!$('[name=_sln_booking_createuser]').is(':checked') && !$(val).val()) {
                        return;
                    }else if(!sln_validateEmail($(val).val())){
                        $(val).addClass('sln-invalid').parent().append('<div class="sln-error error">This field is not a valid email</div>');
                        if(!hasErrors) $(val).focus();
                        hasErrors = true;
                    }
                }else if(val == '#_sln_booking_service_select'){
                    if (!$('[name=_sln_booking\\[services\\]\\[\\]]').size()) {
                        $(val).addClass('sln-invalid').parent().append('<div class="sln-error error">This field is required</div>');
                        if(!hasErrors) $(val).focus();
                        hasErrors = true;
                    }
                }else if(!$(val).val()){
                    $(val).addClass('sln-invalid').parent().append('<div class="sln-error error">This field is required</div>');
                    if(!hasErrors) $(val).focus();
                    hasErrors = true;
                }
            });
            return !hasErrors;
        });
}
// COLOR PICKER
jQuery(function ($) {
    $(function() {
        $('#color-background').colorpicker({
            //color: 'transparent',
            //color: 'rgba(0, 66, 88, 1)',
            format: 'rgba',
            customClass: 'sln-colorpicker-widget',
            sliders: {
                saturation: {
                    maxLeft: 160,
                    maxTop: 160
                },
                hue: {
                    maxTop: 160
                },
                alpha: {
                    maxTop: 160
                }
            },
            colorSelectors: {
                'rgba(255,255,255,1)': 'rgba(255,255,255,1)',
                'rgba(0,0,0,1)': 'rgba(0,0,0,1)',
                'rgba(2,119,189,1)': 'rgba(2,119,189,1)'
            }
        });
        $('#color-main').colorpicker({
            //color: 'transparent',
            //color: 'rgba(0, 66, 88, 1)',
            format: 'rgb',
            customClass: 'sln-colorpicker-widget',
            sliders: {
                saturation: {
                    maxLeft: 160,
                    maxTop: 160
                },
                hue: {
                    maxTop: 160
                },
                alpha: {
                    maxTop: 160
                }
            },
            colorSelectors: {
                'rgba(2,119,189,1)': 'rgba(2,119,189,1)'
            }
        });
        $('#color-text').colorpicker({
            //color: 'transparent',
            //color: 'rgba(0, 66, 88, 1)',
            format: 'rgb',
            customClass: 'sln-colorpicker-widget',
            sliders: {
                saturation: {
                    maxLeft: 160,
                    maxTop: 160
                },
                hue: {
                    maxTop: 160
                },
                alpha: {
                    maxTop: 160
                }
            },
            colorSelectors: {
                'rgba(68,68,68,1)': 'rgba(68,68,68,1)',
                'rgba(0,0,0,1)': 'rgba(0,0,0,1)',
                'rgba(255,255,255,1)': 'rgba(255,255,255,1)'
            }
        });
    });
    $(function() {
        if($('#sln-tab-style').length){
            var color_background = $('#color-background input').val(),
                color_main = $('#color-main input').val(),
                color_text = $('#color-text input').val();
            $('#color-main-a').val(color_main);
            $('#color-text-a').val(color_text);
            var mainAlphaB = .75,
                mainAlphaC = .5,
                mainVal = $('#color-main-a').val(),
                a = mainVal.slice(4).split(','),
                mainShadeB ='rgba(' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaB + ')',
                mainShadeC = 'rgba(' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaC + ')';
            $('#color-main-b').val(mainShadeB);
            $('#color-main-c').val(mainShadeC);
            var textAlphaB = .75,
                textAlphaC = .5,
                textVal = $('#color-text-a').val(),
                b = textVal.slice(4).split(','),
                textShadeB ='rgba(' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaB + ')',
                textShadeC = 'rgba(' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaC + ')';
            $('#color-text-b').val(textShadeB);
            $('#color-text-c').val(textShadeC);
            $('.sln-colors-sample .wrapper').css('background-color', color_background);
            $('.sln-colors-sample h1').css('color', color_main);
            $('.sln-colors-sample button').css('background-color', color_main);
            $('.sln-colors-sample button').css('color', color_background);
            $('.sln-colors-sample input').css('border-color', color_main);
            $('.sln-colors-sample input').css('color', color_main);
            $('.sln-colors-sample input').css('background-color', color_background);
            $('.sln-colors-sample p').css('color', color_text);
            $('.sln-colors-sample label').css('color', mainShadeB);
            $('.sln-colors-sample small').css('color', textShadeB);

            $('#color-background').colorpicker().on('changeColor', function(e) {
                $('.sln-colors-sample .wrapper')[0].style.backgroundColor = e.color;
                $('.sln-colors-sample input')[0].style.backgroundColor = e.color;
                $('.sln-colors-sample button')[0].style.color = e.color;
                $('#color-background-a').val(e.color);
            });

            $('#color-main').colorpicker().on('changeColor', function(e) {
                var mainAlphaB = .75,
                    mainAlphaC = .5,
                    bum = e.color;
                $('#color-main-a').val(bum);
                var mainVal = $('#color-main-a').val(),
                    a = mainVal.slice(4).split(','),
                    mainShadeB ='rgba' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaB + ')',
                    mainShadeC = 'rgba' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaC + ')';
                $('#color-main-b').val(mainShadeB);
                $('#color-main-c').val(mainShadeC);
                $('.sln-colors-sample h1')[0].style.color = e.color;
                $('.sln-colors-sample button')[0].style.backgroundColor = e.color;
                //$('.sln-colors-sample label')[0].style.color = e.color;
                $('.sln-colors-sample label').css('color', mainShadeB);
                $('.sln-colors-sample input')[0].style.borderColor = e.color;
                //$('.sln-colors-sample input').css('border-color', shadeB);
                $('.sln-colors-sample input')[0].style.color = e.color;
            });
            $('#color-text').colorpicker().on('changeColor', function(e) {
                var textAlphaB = .75,
                    textAlphaC = .5,
                    bum = e.color;
                $('#color-text-a').val(bum);
                var textVal = $('#color-text-a').val(),
                    b = textVal.slice(4).split(','),
                    textShadeB ='rgba' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaB + ')',
                    textShadeC = 'rgba' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaC + ')';
                $('#color-text-b').val(textShadeB);
                $('#color-text-c').val(textShadeC);
                $('.sln-colors-sample p')[0].style.color = e.color;
                $('.sln-colors-sample small').css('color', textShadeB);
            });
        }
    });
// COLOR PICKER // END
});
    
jQuery(function ($) {
    if($('#_sln_booking_firstname').length){
        sln_validateBooking($);
    }
    function calculateTotal(){
        var tot = 0;
        $('[name=_sln_booking\\[services\\]\\[\\]]').each(function(){
            tot = (parseFloat(tot) + parseFloat($(this).data('price'))).toFixed(2);
        });
        $('#_sln_booking_amount').val(tot);
        if($('#salon-step-date').data('deposit') > 0)
            $('#_sln_booking_deposit').val(((tot / 100).toFixed(2) * $('#salon-step-date').data('deposit')).toFixed(2))
        return false;
    }
    function bindRemoveFunction() {
        $(this).parent().parent().parent().remove();
        return false;
    }

    function bindRemove() {
        $('button[data-collection="remove"]').unbind('click', bindRemoveFunction).on('click', bindRemoveFunction);
    }

    var prototype = $('.sln-booking-rules div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data('count');
    prototype.remove();

    var prototypeHoliday = $('.sln-booking-holiday-rules div[data-collection="prototype"]');
    var htmlHoliday = prototypeHoliday.html();
    var countHoliday = prototypeHoliday.data('count');
    prototypeHoliday.remove();

    initDatepickers($);
    initTimepickers($);
    bindRemove();

    $('button[data-collection="addnew"]').click(function (e) {
        e.preventDefault();
        $('#sln-booking-rules-wrapper').append('<div class="sln-booking-rule">' + html.replace(/__new__/g, count) + '</div>');
        count++;
        bindRemove();
        customSliderRange($, $('.slider-range'))
    });

    $('button[data-collection="addnewholiday"]').click(function (e) {
        e.preventDefault();
        $('#sln-booking-holiday-rules-wrapper').append(htmlHoliday.replace(/__new__/g, countHoliday) );
        countHoliday++;
        initDatepickers($);
        initTimepickers($);
        bindRemove();
    });
/*
    $('#booking-accept, #booking-refuse').click(function(){
       $('#post_status').val($(this).data('status'));
       $('#save-post').click();
*/
    $('#booking-accept, #booking-refuse').click(function () {
        $('#post_status').val($(this).data('status'));
        $('#save-post').click();
    });


    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    });
    $('#calculate-total').click(calculateTotal);
    if ($('#sln_booking-details').length) {
        sln_adminDate($);
    }
    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    }).focus(function () { $(this).select2('open'); });
    $('.sln-select select').select2({
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%'
    }).focus(function () { $(this).select2('open'); });
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
        alert('ciccio');
        event.preventDefault();
    });
    //$('#salon_settings_pay_method').change(function(){
    //    $('.payment-mode-data').hide();
    //    $('#payment-mode-'+$(this).val()).show();
    //}).change();
   $('input.sln-pay_method-radio').change(function() {
        $('.payment-mode-data').hide().removeClass('sln-box--fadein');
        $('#payment-mode-'+$(this).data('method')).show().addClass('sln-box--fadein');
    });

    var url = location.search;
    if ( url.indexOf( "post_type=sln_service" ) > -1 ) {
        $( "tbody" ).sortable( {
            start: function ( event, ui ) {
                $( ui.item ).data( "startindex", ui.item.index() );
            },
            stop: function ( event, ui ) {
                var $item = ui.item;
                var startIndex = $item.data( "startindex" ) + 1;
                var newIndex = $item.index() + 1;
                if ( newIndex != startIndex ) {
                    var i = 1,
                        pos = [ ];
                    $( 'tr' ).map( function () {
                        var post = $( this )[0].id;
                        if ( post.indexOf( "post-" ) > -1 ) {
                            post = post.split( 'post-' )[1];
                            pos.push(post + '_' + i);
                            i++;
                        }

                    } );
                    jQuery.ajax( {
                        type: "POST",
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            action: 'sln_service',
                            method: 'save_position',
                            data: 'positions=' + pos ,
                        }
                    } ).done( function ( msg ) {
                        console.log( msg );
                    } );
                }
            }
        } );
        $( "tbody" ).disableSelection( );
    }
    if ( url.indexOf( "taxonomy=sln_service_category" ) > -1 ) {
        $( "tbody" ).sortable( {
            start: function ( event, ui ) {
                $( ui.item ).data( "startindex", ui.item.index() );
            },
            stop: function ( event, ui ) {
                var $item = ui.item;
                var startIndex = $item.data( "startindex" ) + 1;
                var newIndex = $item.index() + 1;
                if ( newIndex != startIndex ) {
                    var i = 1,
                        pos = [ ];
                    $( 'tr' ).map( function () {

                        var post = $( this )[0].id;
                        if ( post.indexOf( "tag-" ) > -1 ) {
                            post = post.split( 'tag-' )[1];
                            pos.push(post);
                            i++;
                        }

                    } );
                    //var post_id = ui.item[0].id;
                    jQuery.ajax( {
                        type: "POST",
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            action: 'sln_service',
                            method: 'save_cat_position',
                            data: 'positions=' + pos ,
                        }
                    } ).done( function ( msg ) {
                        console.log( msg );
                    } );
                }
            }
        } );
        $( "tbody" ).disableSelection( );
    }

    $('#salon_settings_pay_method').change(function(){
        $('.payment-mode-data').hide();
        $('#payment-mode-'+$(this).val()).show();
    }).change();

   $('input.sln-pay_method-radio').each(function() {
    if($(this).is(':checked')) { $('#payment-mode-'+$(this).data('method')).show().addClass('sln-box--fadein'); }
    });
    $('#salon_settings_m_attendant_enabled').change(function(){
        if ($(this).is(':checked')) {
            $('#salon_settings_attendant_enabled').attr('checked', 'checked').change();
        }
    }).change();

    $('#salon_settings_enabled_force_guest_checkout').change(function(){
        if ($(this).is(':checked')) {
            $('#salon_settings_enabled_guest_checkout').attr('checked', 'checked').change();
        }
    }).change();

    $('#salon_settings_follow_up_interval').change(function(){
        $('#salon_settings_follow_up_interval_custom_hint').css('display', $(this).val() === 'custom' ? '' : 'none');
        $('#salon_settings_follow_up_interval_hint').css('display', $(this).val() !== 'custom' ? '' : 'none');
    }).change();

   $('.sln-panel .collapse').on('shown.bs.collapse', function() {
        $(this).parent().find('.sln-paneltrigger').addClass('sln-btn--active');
        $(this).parent().addClass('sln-panel--active');
    }).on('hide.bs.collapse', function() {
        $(this).parent().find('.sln-paneltrigger').removeClass('sln-btn--active');
        $(this).parent().removeClass('sln-panel--active');
    });
    $('.sln-panel--oncheck .sln-panel-heading input:checkbox').change(function () {
        if($(this).is(':checked')) {
            $(this).parent().parent().parent().find( '.sln-paneltrigger' ).removeClass('sln-btn--disabled');
        } else {
            $(this).parent().parent().parent().find( '.sln-paneltrigger' ).addClass('sln-btn--disabled');
            $(this).parent().parent().parent().find( '.collapse' ).collapse('hide');
        }
    });
    $('.sln-panel--oncheck .sln-panel-heading input').each(function() {
        if($(this).is(':checked')) {
            $(this).parent().parent().parent().find( '.sln-paneltrigger' ).removeClass('sln-btn--disabled');
        } else {
            $(this).parent().parent().parent().find( '.sln-paneltrigger' ).addClass('sln-btn--disabled');
        }
    });
    // CALENDAR
    //$('.cal-month-day.cal-day-inmonth [data-toggle="tooltip"]').click(function(e) {
    $(document).on("click",".cal-month-day.cal-day-inmonth span", function (e) {
        $('.tooltip').hide();
        event.preventDefault(e);
    });

    function createSelect2() {
        $('.sln-select-wrapper select').select2({
            tags: "true",
            width: '100%'
        });
    }
    createSelect2();

    function createSelect2NoSearch() {
        $('.sln-select-wrapper-no-search select').select2({
            tags: "true",
            width: '100%',
            minimumResultsForSearch: Infinity
        });
    }
    createSelect2NoSearch();

    if (typeof servicesData !== 'undefined') {
        servicesData = $.parseJSON(servicesData);
    }
    if (typeof attendantsData !== 'undefined') {
        attendantsData = $.parseJSON(attendantsData);
    }
    $('#_sln_booking_service_select').change(function() {
        var html = '';
        if (servicesData[$(this).val()] != undefined) {
            $.each(servicesData[$(this).val()].attendants, function( index, value ) {
                html += '<option value="'+ value +'">' + attendantsData[value] + '</option>';
            });
        }
        $('#_sln_booking_attendant_select option:not(:first)').remove();
        $('#_sln_booking_attendant_select').append(html).change();
    }).change();



    function bindRemoveBookingsServicesFunction() {
        if($('#salon-step-date').data('isnew'))
            calculateTotal();
        if ($('#_sln_booking_service_select').size()) {
            sln_checkServices($);
        }
        return false;
    }

    function bindRemoveBookingsServices() {
        bindRemove();
        $('button[data-collection="remove"]').unbind('click', bindRemoveBookingsServicesFunction).on('click', bindRemoveBookingsServicesFunction);
    }

    function bindChangeAttendantSelectsFunction() {
        sln_checkServices($);
    }
    function bindChangeAttendantSelects() {
        $('select[data-attendant]').unbind('change', bindChangeAttendantSelectsFunction).on('change', bindChangeAttendantSelectsFunction);
    }

    function getNewBookingServiceLineString(serviceId, attendantId) {
        var line = lineItem;
        line = line.replace(/__service_id__/g, serviceId);
        line = line.replace(/__attendant_id__/g, attendantId);
        line = line.replace(/__service_title__/g, servicesData[serviceId].title);
        line = line.replace(/__attendant_name__/g, attendantsData[attendantId]);
        line = line.replace(/__service_price__/g, servicesData[serviceId].price);
        line = line.replace(/__service_duration__/g, servicesData[serviceId].duration);
        line = line.replace(/__service_break_duration__/g, servicesData[serviceId].break_duration);
        return line;
    }

    bindRemoveBookingsServices();

    $('select[data-attendant]').each(function() {
        var serviceVal = $(this).attr('data-service');
        var attendantVal = $(this).val();
        var selectHtml = '';
        if (jQuery.inArray(attendantVal, ['','0']) !== false) {
            selectHtml += '<option value="" selected >n.d.</option>';
        }
        $.each(servicesData[serviceVal].attendants, function( index, value ) {
            selectHtml += '<option value="'+ value +'" ' + (value == attendantVal ? 'selected' : '') + ' >' + attendantsData[value] + '</option>';
        });
        $(this).html(selectHtml).change();
    });
    bindChangeAttendantSelects();

    $('button[data-collection="addnewserviceline"]').click(function () {
        var serviceVal = $('#_sln_booking_service_select').val();
        var attendantVal = $('#_sln_booking_attendant_select').val();
        if (((attendantVal == undefined || attendantVal == '') && $('#_sln_booking_attendant_select option').size() > 1) ||
            $('[name=_sln_booking\\[services\\]\\[\\]] option[value=' + serviceVal + ']:selected').size()
        ) {
            return false;
        }
        $('.sln-booking-service-line label.time').html('');

        var line = getNewBookingServiceLineString(serviceVal,attendantVal);
        var added = false;
        $('[name=_sln_booking\\[services\\]\\[\\]]').each(function () {
            if (!added && servicesData[serviceVal].exec_order <= servicesData[$(this).val()].exec_order) {
                $(this).parent().parent().before(line);
                added = true;
            }
        });

        if (!added) {
            $('.sln-booking-service-action').before(line);
        }

        var selectHtml = '';
        if (servicesData[serviceVal].attendants.length) {
            $.each(servicesData[serviceVal].attendants, function( index, value ) {
                selectHtml += '<option value="'+ value +'" ' + (value == attendantVal ? 'selected' : '') + ' >' + attendantsData[value] + '</option>';
            });
        }
        else {
            selectHtml += '<option value="" selected >n.d.</option>';
        }

        $('#_sln_booking_attendants_' + serviceVal).html(selectHtml).change();

        if($('#salon-step-date').data('isnew'))
            calculateTotal();

        createSelect2();
        createSelect2NoSearch();
        bindRemoveBookingsServices();
        bindChangeAttendantSelects();
        sln_checkServices($);
        return false;
    });
});
