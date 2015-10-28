
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
                    minView: $(this).data('interval') == 60 ? 1: 0,
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
    var isValid;
    var items = $('#salon-step-date').data('intervals');
    var doingFunc = false;
    var func = function () {
        if(doingFunc) return;
        setTimeout(function(){
           doingFunc = true;
        $('[data-ymd]').addClass('disabled');
        $.each(items.dates, function(key, value) {
           //console.log(value);
           $('.day[data-ymd="'+value+'"]').removeClass('disabled');
        });

        $.each(items.times, function(key, value) {
           $('.hour[data-ymd="'+value+'"]').removeClass('disabled'); 
           $('.minute[data-ymd="'+value+'"]').removeClass('disabled'); 
           $('.hour[data-ymd="'+value.split(':')[0]+':00"]').removeClass('disabled');
        });
            doingFunc = false;
       },200);
        return true;
    }
    func();
    $('body').on('sln_date', func);
    function validate(obj, autosubmit) {
        var form = $(obj).closest('form');
        var validatingMessage = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> '+salon.txt_validating;
        var data = form.serialize();
        data += "&action=salon&method=checkDate&security=" + salon.ajax_nonce;
        $('#sln-notifications').html(validatingMessage);
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
                    $('#sln-notifications').html('').append(alertBox);
                    $('#sln-step-submit').attr('disabled', true);
                    isValid = false;
                } else {
                    $('#sln-step-submit').attr('disabled', false);
                    $('#sln-notifications').html('');
                    isValid = true;
                    if (autosubmit)
                        submit();
                }
                bindIntervals(data.intervals);
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
        validate(this, false);
    });
    initDatepickers($);
    initTimepickers($);
}


jQuery(function ($) {
    function bindRemove(){
        $('button[data-collection="remove"]').unbind('click').on('click',function () {
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
        $('#sln-availabilities div.items').append('<div class="item">'+html.replace(/__new__/g, count)+'</div>');
        count++;
        bindRemove();
        return false;
    });
    $('#booking-accept, #booking-refuse').click(function(){
       $('#post_status').val($(this).data('status')); 
       $('#save-post').click();
    });

    if($('#sln_booking-details').length){
        sln_adminDate($);
    }
});
