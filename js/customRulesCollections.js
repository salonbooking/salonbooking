jQuery(function () {
    jQuery('.sln-booking-rules').each(function () {
        initBookingRules(jQuery(this));
    });
    jQuery('.sln-booking-holiday-rules').each(function () {
        initBookingHolidayRules(jQuery(this));
    });
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
        e.preventDefault();
        wrapper.append('<div class="sln-booking-rule">' + html.replace(/__new__/g, count) + '</div>');
        count++;
        bindRemove();
        customSliderRange(jQuery, jQuery('.slider-range'))
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