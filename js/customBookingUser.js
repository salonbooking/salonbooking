function customBookingUser($) {
    $('#sln-update-user-field').select2({
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%',
    placeholder: $('#sln-update-user-field').data('placeholder'),
    language: {
       noResults: function(){
           return $('#sln-update-user-field').data('nomatches');
       }
    },


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
    },
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
