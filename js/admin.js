function initDatepickers($) {
    $('.sln_datepicker input').each(function () {
        if ($(this).hasClass('started')) {
            return;
        } else {
            $(this)
                .addClass('started')
                .datetimepicker({
                    format: $(this).data('format'),
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
function sln_adminDate($) {
    var items = $('#salon-step-date').data('intervals');
    var doingFunc = false;
    var dataServices = {};

    var func = function () {
        if (doingFunc) return;
        setTimeout(function () {
            doingFunc = true;
            $('[data-ymd]').removeClass('disabled');
            $('[data-ymd]').addClass('red');
            $.each(items.dates, function (key, value) {
                //console.log(value);
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
//console.log(data);
                if (!data.success) {
                    var alertBox = $('<div class="alert alert-danger"></div>');
                    $(data.errors).each(function () {
                        alertBox.append('<p>').html(this);
                    });
                    $('#sln-notifications').html('').append(alertBox);
                } else {
                    $('#sln-notifications').html('');
                }
                updateServices(obj);
                bindIntervals(data.intervals);
            }
        });
    }

    function updateServices(obj) {
        var form = $(obj).closest('form');
        var data = form.serialize() + "&action=salon&method=CheckServices&security=" + salon.ajax_nonce;
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
                    dataServices = data.services;
                }
            }
        });
    }

    function bindIntervals(intervals) {
//        putOptions($('#sln_date_day'), intervals.days, intervals.suggestedDay);
//        putOptions($('#sln_date_month'), intervals.months, intervals.suggestedMonth);
//        putOptions($('#sln_date_year'), intervals.years, intervals.suggestedYear);
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
    $('#_sln_services').on('select2:open',function(){
        var notifications = $('#sln-services-notifications');
        $.each(dataServices, function (key, value) {
            var box = $('.select2-results__option[id$="sln_services_' + key + '"]');
            if(value)
                box.addClass('red').data('message','<div class="alert alert-danger">' + value + '</div>');
            else
                box.removeClass('red').data('message', '');
            box.unbind('hover').hover(function(){});
        });
        $('.select2-results__option[id*=sln_services]').unbind('hover').hover(
            function(){ notifications.html($(this).data('message')) },
            function(){ notifications.html('') }
        );
    });
    initDatepickers($);
    initTimepickers($);

}


jQuery(function ($) {
    function bindRemove() {
        $('button[data-collection="remove"]').unbind('click').on('click', function () {
            $(this).parent().parent().parent().remove();
            return false;
        });
    }

    var prototype = $('#sln-availabilities div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data('count');
    prototype.remove();
    bindRemove();

    $('button[data-collection="addnew"]').click(function () {
        $('#sln-availabilities div.items').append('<div class="item">' + html.replace(/__new__/g, count) + '</div>');
        count++;
        bindRemove();
        return false;
    });
    $('#booking-accept, #booking-refuse').click(function () {
        $('#post_status').val($(this).data('status'));
        $('#save-post').click();
    });


    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    });
    if ($('#sln_booking-details').length) {
        sln_adminDate($);
    }

    $('#sln-update-user-field').select2({
         ajax: {
    url: salon.ajax_url+'&action=salon&method=SearchUser&security=' + salon.ajax_nonce,
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
    }
    }});

    $('#sln-update-user-field').on('select2:select', function(){
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
                        console.log([key, value]);
                        if (key == 'id') $('#post_author').val(value);
                        else $('#_sln_booking_' + key).val(value);
                    });
                }
            }
        });
        return false;
    });
});
