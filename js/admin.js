if (jQuery('#toplevel_page_salon').hasClass('wp-menu-open')) {
  jQuery('#wpbody-content .wrap').addClass('sln-bootstrap');
  jQuery('#wpbody-content .wrap').attr('id', 'sln-salon--admin');
}


jQuery(function ($) {
/*
    $('#booking-accept, #booking-refuse').click(function(){
       $('#post_status').val($(this).data('status'));
       $('#save-post').click();
*/
    $('#booking-accept, #booking-refuse').click(function () {
        $('#_sln_booking_status').val($(this).data('status'));
        $('#save-post').click();
    });

    $('.sln-toolbox-trigger').click(function(event) {
        $(this).parent().toggleClass('open');
        event.preventDefault();
    });
    $('.sln-toolbox-trigger-mob').click(function(event) {
        $(this).parent().find('.sln-toolbox').toggleClass('open');
        event.preventDefault();
    });
    $('.sln-box-info-trigger button').click(function(event) {
        $(this).parent().parent().parent().toggleClass('sln-box--info-visible');
        event.preventDefault();
    });
     $('.sln-box-info-content:after').click(function(event) {
        alert('ciccio');
        event.preventDefault();
    });

    if($('.sln-admin-sidebar').length) {
        $('.sln-admin-sidebar').affix({
        offset: {
            top: $('.sln-admin-sidebar').offset().top - 40
        }
        });
    }
});