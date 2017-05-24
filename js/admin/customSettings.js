jQuery(function ($) {
    if ($('.sln-panel').length) {
        initSlnPanel($);
    }

    sln_settingsLogo($);
    sln_settingsPayment($);
    sln_settingsCheckout($);
    sln_settingsGeneral($);
});

function sln_settingsLogo($) {

    $('[data-action=select-logo]').click(function () {
        $('#' + $(this).attr('data-target')).click();
    });

    $("[data-action=select-file-logo]").change(function () {
        $(this).closest('form').find('input:first').click();
    });

    $('[data-action=delete-logo]').click(function () {
        $('#' + $(this).attr('data-target-reset')).val('');
        $('#' + $(this).attr('data-target-show')).removeClass('hide');
        $('#' + $(this).attr('data-target-remove')).remove();
    });
}

function sln_settingsPayment($) {

    //$('#salon_settings_pay_method').change(function(){
    //    $('.payment-mode-data').hide();
    //    $('#payment-mode-'+$(this).val()).show();
    //}).change();

    $('input.sln-pay_method-radio').change(function () {
        $('.payment-mode-data').hide().removeClass('sln-box--fadein');
        $('#payment-mode-' + $(this).data('method')).show().addClass('sln-box--fadein');
    });

    $('#salon_settings_pay_method').change(function () {
        $('.payment-mode-data').hide();
        $('#payment-mode-' + $(this).val()).show();
    }).change();

    $('input.sln-pay_method-radio').each(function () {
        if ($(this).is(':checked')) {
            $('#payment-mode-' + $(this).data('method')).show().addClass('sln-box--fadein');
        }
    });

    $('#salon_settings_pay_deposit').change(function(){
        var current  = $(this).val();
        var expected = $('#salon_settings_pay_deposit_fixed_amount').data('relate-to');
        $('#salon_settings_pay_deposit_fixed_amount').attr('disabled', current === expected ? false : 'disabled');
    }).change();
}

function sln_settingsCheckout($) {
    $('#salon_settings_enabled_force_guest_checkout').change(function () {
        if ($(this).is(':checked')) {
            $('#salon_settings_enabled_guest_checkout').attr('checked', 'checked').change();
        }
    }).change();
}

function sln_settingsGeneral($) {
    $('#salon_settings_m_attendant_enabled').change(function () {
        if ($(this).is(':checked')) {
            $('#salon_settings_attendant_enabled').attr('checked', 'checked').change();
        }
    }).change();


    $('#salon_settings_follow_up_interval').change(function () {
        $('#salon_settings_follow_up_interval_custom_hint').css('display', $(this).val() === 'custom' ? '' : 'none');
        $('#salon_settings_follow_up_interval_hint').css('display', $(this).val() !== 'custom' ? '' : 'none');
    }).change();
}


function initSlnPanel($) {
    $('.sln-panel .collapse').on('shown.bs.collapse', function () {
        $(this).parent().find('.sln-paneltrigger').addClass('sln-btn--active');
        $(this).parent().addClass('sln-panel--active');
    }).on('hide.bs.collapse', function () {
        $(this).parent().find('.sln-paneltrigger').removeClass('sln-btn--active');
        $(this).parent().removeClass('sln-panel--active');
    });
    $('.sln-panel--oncheck .sln-panel-heading input:checkbox').change(function () {
        if ($(this).is(':checked')) {
            $(this).parent().parent().parent().find('.sln-paneltrigger').removeClass('sln-btn--disabled');
        } else {
            $(this).parent().parent().parent().find('.sln-paneltrigger').addClass('sln-btn--disabled');
            $(this).parent().parent().parent().find('.collapse').collapse('hide');
        }
    });
    $('.sln-panel--oncheck .sln-panel-heading input').each(function () {
        if ($(this).is(':checked')) {
            $(this).parent().parent().parent().find('.sln-paneltrigger').removeClass('sln-btn--disabled');
        } else {
            $(this).parent().parent().parent().find('.sln-paneltrigger').addClass('sln-btn--disabled');
        }
    });
}


