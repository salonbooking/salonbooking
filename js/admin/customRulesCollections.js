jQuery(function () {
    jQuery('.sln-booking-rules').each(function () {
        initBookingRules(jQuery(this));
    });
    jQuery('.sln-booking-holiday-rules').each(function () {
        initBookingHolidayRules(jQuery(this));
    });


    jQuery('body').on('change', '[data-unhide]', function () {
        jQuery(jQuery(this).data('unhide')).toggle(jQuery(this).is(':checked') ? false : true);
    });
    jQuery('[data-unhide]').change();
});

function bindRemoveFunction() {
    jQuery(this).parent().parent().parent().remove();
    return false;
}

function bindRemove() {
    jQuery('button[data-collection="remove"]')
        .unbind('click', bindRemoveFunction)
        .on('click', bindRemoveFunction);
}

function initBookingRules(elem) {
    var prototype = elem.find('div[data-collection="prototype"]');
    var wrapper = elem.find('.sln-booking-rules-wrapper');
    var html = prototype.html();
    var count = prototype.data('count');
    prototype.remove();


    jQuery('button[data-collection="addnew"]').click(function (e) {
        count++;
        e.preventDefault();
        wrapper.append(html.replace(/__new__/g, count));
        bindRemove();

        initDatepickers(jQuery);
        initTimepickers(jQuery)
        customSliderRange(jQuery, jQuery('.slider-range'))
        jQuery('[data-unhide]').change();
    });
    bindRemove();
}

function initBookingHolidayRules(elem) {
    var prototype = elem.find('div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data('count');
    var wrapper = elem.find('.sln-booking-holiday-rules-wrapper');
    prototype.remove();


    jQuery('button[data-collection="addnewholiday"]').click(function (e) {
        e.preventDefault();
        wrapper.append(html.replace(/__new__/g, count));
        count++;
        initDatepickers(jQuery);
        initTimepickers(jQuery);
        bindRemove();
    });
    bindRemove();
}

