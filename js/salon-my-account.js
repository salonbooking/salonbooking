// algolplus

var slnMyAccount = {
    cancelBooking: function (id) {
        if (!confirm(salon.confirm_cancellation_text)) {
            return;
        }

        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'cancelBooking',
                id: id
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else if (data.success != 1) {
                    alert('error');
                    console.log(data);
                } else {
                    slnMyAccount.loadContent('cancelled');
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
    },

    loadContent: function (option) {
        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'myAccountDetails',
                option: option
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else {
                    jQuery('#sln-salon-my-account').html(data.content);
                    slnMyAccount.createRatings();
                    jQuery("[data-toggle='tooltip']").tooltip();
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
    },

    createRatings: function () {
        jQuery('[name=sln-rating]').each(function() {
            if (jQuery(this).val()) {
                slnMyAccount.createRaty(jQuery(this), true);
            }
        });
    },

    createRaty: function ($rating, readOnly) {
        readOnly = readOnly == undefined ? false : readOnly;
        var $ratyElem = $rating.parent().find('.rating');
        $ratyElem.raty({
            score: jQuery($rating).val(),
            space: false,
            path: salon.images_folder,
            readOnly: readOnly,
            starType : 'i',
            starOff:"glyphicon glyphicon-star-empty",
            starOn:"glyphicon glyphicon-star",
        });
        $ratyElem.css('display', 'block');
    },

    showRateForm: function (id) {
        this.createRaty(jQuery("#ratingModal .rating"));
        jQuery("#ratingModal textarea").attr('id', id);
        jQuery("#ratingModal textarea").val('');

        jQuery("#ratingModal #step2").css('display', 'none');
        jQuery("#ratingModal").modal('show');
        jQuery("#ratingModal #step1").css('display', 'block');

        return false;
    },

    sendRate: function() {
        if (jQuery("#ratingModal .rating").raty('score') == undefined || jQuery("#ratingModal textarea").val() == '')
            return false;

        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'setBookingRating',
                id: jQuery("#ratingModal textarea").attr('id'),
                score: jQuery("#ratingModal .rating").raty('score'),
                comment: jQuery("#ratingModal textarea").val(),
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else if (data.success != 1) {
                    alert('error');
                    console.log(data);
                } else {
                    jQuery("#ratingModal #step1").css('display', 'none');
                    jQuery("#ratingModal #step2").css('display', 'block');

                    jQuery('#ratingModal .close').delay(2000).queue(function () {
                        jQuery(this).click();
                        slnMyAccount.loadContent();
                        jQuery(this).dequeue();
                    });
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
        return false;
    },

    init: function () {
        if (jQuery('#sln-salon-my-account').size()) {
            this.loadContent();
        }
        else /*if (jQuery('[name=post_type]').val() == 'sln_booking')*/ {
            this.createRatings();
        }
    }
};

function addClassIfNarrow(element, narrowClass) {
    if (element.length > 0) {
        jQuery(window).on("load resize",function(){
            var elementWidth = element.width();
            if (elementWidth < 600){
                element.addClass(narrowClass);
            } else {
                element.removeClass(narrowClass);
            }
        });
    }
}

jQuery(document).ready(function() {
    slnMyAccount.init();
    addClassIfNarrow(jQuery('#sln-salon-my-account'), 'mobile-version')
});