jQuery(function ($) {


});

function applyDiscountCode() {
    var $ = jQuery;
    var code = $('#sln_discount').val();

    var data = "sln[discount]=" + code + "&action=salon&method=applyDiscountCode&security=" + salon.ajax_nonce;

    $.ajax({
        url: salon.ajax_url,
        data: data,
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            $('#sln_discount_status').find('.sln-alert').remove();
            var alertBox;
            if (data.success) {
                $('#sln_discount_value').html(data.discount);
                $('.sln-total-price').html(data.total);
                alertBox = $('<div class="sln-alert sln-alert--success"></div>');
            }
            else {
                alertBox = $('<div class="sln-alert sln-alert--problem"></div>');
            }
            $(data.errors).each(function () {
                alertBox.append('<p>').html(this);
            });
            $('#sln_discount_status').html('').append(alertBox);
        },
        error: function(data){alert('error'); console.log(data);}
    });

    return false;
}
