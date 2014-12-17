$(function () {
    if ($('#saloon-step-services').length || $('#saloon-step-services').length) {
        $('.service-items input[type="checkbox"]').click(function () {
            var tot = 0;
            $('.service-items input[type="checkbox"]').each(function () {
                if ($(this).is(':checked')) {
                    tot += $(this).data('price');
                }
            });
            $('#services-total').text(tot);
        });
    }
});