jQuery(function ($) {
    if ($('#_sln_booking_firstname').length) {
        sln_prepareToValidatingBooking($);
    }
    if ($('#sln_booking-details').length) {
        sln_adminDate($);
    }
    $('#calculate-total').click(sln_calculateTotal);

    customBookingUser($);
    sln_manageAddNewService($);
    sln_manageCheckServices($);
    if (sln_isShowOnlyBookingElements($)) {
        sln_showOnlyBookingElements($);
    }
});

function sln_isShowOnlyBookingElements($) {
    return $('#salon-step-date').data('mode') === 'sln_editor';
}

function sln_showOnlyBookingElements($) {
    $('.wp-toolbar').css('padding-top', '0');
    $('#adminmenuback').hide();
    $('#adminmenuwrap').hide();
    $('#wpcontent').css('margin-left', '0');
    $('#wpadminbar').hide();
    $('#wpbody-content').css('padding-bottom', '0');
    $('#screen-meta').hide();
    $('#screen-meta-links').hide();
    $('.wrap').css('margin-top', '0');
    $('#post').prevAll().hide();
    $('#poststuff').css('padding-top', '0');
    $('#post-body-content').css('margin-bottom', '0');
    $('#postbox-container-1').hide();
    $('#post-body').css('width', '100%');
    $('#wpfooter').hide();
}

function sln_validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function sln_prepareToValidatingBooking($) {
    var form = $('#_sln_booking_firstname').closest('form');
    $(form).submit(sln_validateBooking);
}

function sln_validateBooking() {
    var $ = jQuery;
    $('.sln-invalid').removeClass('sln-invalid');
    $('.sln-error').remove();
    var hasErrors = false;

    var toValidate = [
        '#_sln_booking_service_select'
    ];

    var fields = $('#salon-step-date').attr('data-required_user_fields').split(',');
    $.each(fields, function(k, val) {
        if(val !== '') toValidate.push('#_sln_booking_' + val);
    });

    $.each(toValidate, function (k, val) {
        if (val == '#_sln_booking_email') {
            if (!sln_validateEmail($(val).val())) {
                $(val).addClass('sln-invalid').parent().append('<div class="sln-error error">This field is not a valid email</div>');
                if (!hasErrors) $(val).focus();
                hasErrors = true;
            }
        } else if (val == '#_sln_booking_service_select') {
            if (!$('[name=_sln_booking\\[services\\]\\[\\]]').size()) {
                $(val).addClass('sln-invalid').parent().append('<div class="sln-error error">This field is required</div>');
                if (!hasErrors) $(val).focus();
                hasErrors = true;
            }
        } else if (!$(val).val()) {
            $(val).addClass('sln-invalid').parent().append('<div class="sln-error error">This field is required</div>');
            if (!hasErrors) $(val).focus();
            hasErrors = true;
        }
    });
    return !hasErrors;
}

function customBookingUser($) {
    $('#sln-update-user-field').select2({
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%',
        placeholder: $('#sln-update-user-field').data('placeholder'),
        language: {
            noResults: function () {
                return $('#sln-update-user-field').data('nomatches');
            }
        },


        ajax: {
            url: salon.ajax_url + '&action=salon&method=SearchUser&security=' + salon.ajax_nonce,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    s: params.term
                };
            },
            minimumInputLength: 3,
            processResults: function (data, page) {
                return {
                    results: data.result
                };
            },
        }
    });

    $('#sln-update-user-field').on('select2:select', function () {
        var message = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> ';

        var data = "&action=salon&method=UpdateUser&s=" + $('#sln-update-user-field').val() + "&security=" + salon.ajax_nonce;
        $('#sln-update-user-message').html(message);
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (!data.success) {
                    var alertBox = $('<div class="alert alert-danger"></div>');
                    $(data.errors).each(function () {
                        alertBox.append('<p>').html(this);
                    });
                    $('#sln-update-user-message').html(alertBox);
                } else {
                    var alertBox = $('<div class="alert alert-success">' + data.message + '</div>');
                    $('#sln-update-user-message').html(alertBox);
                    $.each(data.result, function (key, value) {
                        if (key == 'id') $('#post_author').val(value);
                        else $('#_sln_booking_' + key).val(value);
                    });
                    $('[name="_sln_booking_createuser"]').attr('checked', false);
                }
            }
        });
        return false;
    });
}

function sln_calculateTotal() {
    var tot = 0;
    var $ = jQuery;
    $('[name=_sln_booking\\[services\\]\\[\\]]').each(function () {
        tot = (parseFloat(tot) + parseFloat($(this).data('price'))).toFixed(2);
    });
    $('#_sln_booking_amount').val(tot);

    var bookingDeposit = 0;
    var depositAmount  = $('#salon-step-date').data('deposit_amount');
    var isDepositFixed = $('#salon-step-date').data('deposit_is_fixed');
    if (isDepositFixed) {
        bookingDeposit = Math.min(tot, depositAmount);
    }
    else {
        bookingDeposit = (tot / 100) * depositAmount;
    }
    $('#_sln_booking_deposit').val(bookingDeposit.toFixed(2));
    sln_calculateTotalDuration();
    return false;
}

function sln_calculateTotalDuration() {
    var $ = jQuery
    var duration = 0;
    $('[name=_sln_booking\\[services\\]\\[\\]]').each(function () {
        duration += parseInt($(this).data('duration'));
    });
    var i = duration % 60;
    var h = (duration - i)/60;
    if(i < 10) {
        i = '0'+i;
    }
    if(h < 10) {
        h = '0'+h;
    }

    $('#sln-duration').val(h+':'+i);
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
            $('.day[data-ymd]').removeClass('full');
            $.each(items.fullDays, function (key, value) {
                console.log(value);
                $('.day[data-ymd="' + value + '"]').addClass('red full');
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
                if (firstValidate) {
                    $('#sln-notifications').html('');
                    firstValidate = false;
                } else if (!data.success) {
                    var alertBox = $('<div class="alert alert-danger"></div>');
                    $(data.errors).each(function () {
                        alertBox.append('<p>').html(this);
                    });
                    $('#sln-notifications').html('').append(alertBox);
                } else {
                    $('#sln-notifications').html('').append('<div class="alert alert-success">' + $('#sln-notifications').data('valid-message') + '</div>');

                }
                bindIntervals(data.intervals);
                sln_checkServices($);
            }
        });
    }

    function bindIntervals(intervals) {
        items = intervals;
        func();
        //putOptions($('#_sln_booking_date'), intervals.suggestedDate);
        //putOptions($('#_sln_booking_time'), intervals.suggestedTime);
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
    sln_initResendNotification();
    sln_initResendPaymentSubmit();
}

function sln_manageAddNewService($) {
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

    $('button[data-collection="addnewserviceline"]').click(function () {
        var serviceVal = $('#_sln_booking_service_select').val();
        var attendantVal = $('#_sln_booking_attendant_select').val();
        if (((attendantVal == undefined || attendantVal == '') && $('#_sln_booking_attendant_select option').size() > 1) ||
            $('[name="_sln_booking[services][]"] option[value="' + serviceVal + '"]:selected').size()
        ) {
            return false;
        }
        $('.sln-booking-service-line label.time').html('');

        var line = getNewBookingServiceLineString(serviceVal, attendantVal);
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
            $.each(servicesData[serviceVal].attendants, function (index, value) {
                selectHtml += '<option value="' + value + '" ' + (value == attendantVal ? 'selected' : '') + ' >' + attendantsData[value] + '</option>';
            });
        }
        else {
            selectHtml += '<option value="" selected >n.d.</option>';
        }

        $('#_sln_booking_attendants_' + serviceVal).html(selectHtml).change();

        if ($('#salon-step-date').data('isnew'))
            sln_calculateTotal();

        sln_createSelect2();
        sln_createSelect2NoSearch();
        sln_bindRemoveBookingsServices();
        sln_bindChangeAttendantSelects();
        sln_checkServices($);
        return false;
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
            } else {
                $('#sln_booking_services').find('.alert').remove();
                sln_processServices($, data.services);
            }
        }
    });
}

function sln_processServices($, services) {
    if(!services) return;
    $.each(services, function (index, value) {
        var serviceItem = $('#_sln_booking_service_' + index);
        if (value.status == -1) {
            $.each(value.errors, function (index, value) {
                var alertBox = $('<div class="row col-xs-12 col-sm-12 col-md-12"><div class="'
                    + ($('#salon-step-date').attr('data-m_attendant_enabled') ?
                        'col-md-offset-2 col-md-6'
                        : 'col-md-8')
                    + '"><p class="alert alert-danger">' + value + '</p></div></div>');
                serviceItem.parent().parent().next().after(alertBox);
            });
        }
        serviceItem.parent().parent().find('label.time:first').html(value.startsAt);
        serviceItem.parent().parent().find('label.time:last').html(value.endsAt);
    });
}

function sln_manageCheckServices($) {

    if (typeof servicesData == 'string') {
        servicesData = $.parseJSON(servicesData);
    }
    if (typeof attendantsData == 'string') {
        attendantsData = $.parseJSON(attendantsData);
    }
    $('#_sln_booking_service_select').change(function () {
        var html = '';
        if (servicesData[$(this).val()] != undefined) {
            $.each(servicesData[$(this).val()].attendants, function (index, value) {
                html += '<option value="' + value + '">' + attendantsData[value] + '</option>';
            });
        }
        $('#_sln_booking_attendant_select option:not(:first)').remove();
        $('#_sln_booking_attendant_select').append(html).change();
    }).change();

    sln_bindRemoveBookingsServices();
    sln_bindChangeAttendantSelects();
}


function sln_bindRemoveBookingsServices() {
    function sln_bindRemoveBookingsServicesFunction() {
        if (jQuery('#salon-step-date').data('isnew'))
            sln_calculateTotal();
        if (jQuery('#_sln_booking_service_select').size()) {
            sln_checkServices(jQuery);
        }
        return false;
    }

    bindRemove();
    jQuery('button[data-collection="remove"]')
        .unbind('click', sln_bindRemoveBookingsServicesFunction)
        .on('click', sln_bindRemoveBookingsServicesFunction);
}

function sln_bindChangeAttendantSelects() {
    function bindChangeAttendantSelectsFunction() {
        sln_checkServices(jQuery);
    }

    jQuery('select[data-attendant]')
        .unbind('change', bindChangeAttendantSelectsFunction)
        .on('change', bindChangeAttendantSelectsFunction);
}

function sln_initResendNotification(){
    var $ = jQuery;
    $('#resend-notification-submit').click(function () {
        var data = "post_id=" + $('#post_ID').val() + "&emailto=" + $('#resend-notification').val() + "&message=" + $('#resend-notification-text').val() + "&action=salon&method=ResendNotification&security=" + salon.ajax_nonce;
        var validatingMessage = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> ';
        $('#resend-notification-message').html(validatingMessage);
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success)
                    $('#resend-notification-message').html('<div class="alert alert-success">' + data.success + '</div>');
                else if (data.error)
                    $('#resend-notification-message').html('<div class="alert alert-danger">' + data.error + '</div>');
            }
        });
        return false;
    });
}

function sln_initResendPaymentSubmit(){
    var $ = jQuery;
    $('#resend-payment-submit').click(function () {
        var data = "post_id=" + $('#post_ID').val() + "&emailto=" + $('#resend-payment').val() + "&action=salon&method=ResendPaymentNotification&security=" + salon.ajax_nonce;
        var validatingMessage = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> ';
        $('#resend-payment-message').html(validatingMessage);
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success)
                    $('#resend-payment-message').html('<div class="alert alert-success">' + data.success + '</div>');
                else if (data.error)
                    $('#resend-payment-message').html('<div class="alert alert-danger">' + data.error + '</div>');
            }
        });
        return false;
    });
}
