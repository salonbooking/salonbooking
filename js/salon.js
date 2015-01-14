Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

jQuery(function ($) {
    if ($('#salon-step-services').length || $('#salon-step-secondary').length) {
        sln_serviceTotal($);
    }
    if ($('#salon-step-date').length) {
        sln_stepDate($);
    }
});

function sln_stepDate($) {
    var isValid;

    function validate(obj, autosubmit) {
        var form = $(obj).closest('form');
        var validatingMessage = '<img src="' + salon.loading + '" alt="Loading ..." width="16" height="16" /> validating...';
        var data = form.serialize();
        data+= "&action=salon&method=checkDate&security="+salon.ajax_nonce;
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
                        $('#sln-step-submit').click();
                }
                bindIntervals(data.intervals);
            }
        });
    }

    function bindIntervals(intervals){
        putOptions($('#sln_date_day'), intervals.days, intervals.suggestedDay);
        putOptions($('#sln_date_month'), intervals.months, intervals.suggestedMonth);
        putOptions($('#sln_date_year'), intervals.years, intervals.suggestedYear);
        putOptions($('#sln_time'), intervals.times, intervals.suggestedTime);
    }

    function putOptions(selectElem, newOptions, value){
        selectElem.empty(); // remove old options
        $.each(newOptions, function(key, value) {
            selectElem.append($("<option></option>")
                .attr("value", key).text(value));
        });
        selectElem.val(value);
    }

    $('#sln_date_day, #sln_date_month, #sln_date_year, #sln_time').change(function () {
        validate(this,false);
    });
    $('#salon-step-date').submit(function () {
        if (!isValid) {
            validate(this, true);
            return false;
        }else{
            return true;
        }
    })
}

function sln_serviceTotal($) {
    var $checkboxes = $('.sln-service-list input[type="checkbox"]');
    var $totalbox = $('#services-total');

    function evalTot() {
        var tot = 0;
        $checkboxes.each(function () {
            if ($(this).is(':checked')) {
                tot += $(this).data('price');
            }
        });
        $totalbox.text(tot.formatMoney(2) + $totalbox.data('symbol'));
    }

    $checkboxes.click(function () {
        evalTot();
    });
    evalTot();
}