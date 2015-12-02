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
        jQuery('#sln-salon-my-account [name=sln-rating]').each(function() {
            if (jQuery(this).val()) {
                slnMyAccount.createRaty(jQuery(this));
            }
        });
    },

    createRaty: function ($rating) {
        var $ratyElem = $rating.parent().find('.rating');
        $ratyElem.raty({
            score: jQuery($rating).val(),
            space: false,
            path: salon.images_folder,

            starType : 'i',
            starOff:"glyphicon glyphicon-star-empty",
            starOn:"glyphicon glyphicon-star",

            click: function(score) {
                jQuery.ajax({
                    url: salon.ajax_url,
                    data: {
                        action: 'salon',
                        method: 'setBookingRating',
                        id: jQuery($ratyElem).attr('id'),
                        score: score
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

                        }
                    },
                    error: function(data){alert('error'); console.log(data);}
                });
            }
        });
        $ratyElem.css('display', 'inline-block');
    },

    rate: function (btn) {
            this.createRaty(jQuery(btn).parent().find('[name=sln-rating]'));
            jQuery(btn).remove();
            return false;
    },

    init: function () {
        if (jQuery('#sln-salon-my-account').size()) {
            this.loadContent();
        }
    }
};

jQuery(document).ready(function() {
    slnMyAccount.init();
});