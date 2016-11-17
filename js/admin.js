if (jQuery('#toplevel_page_salon').hasClass('wp-menu-open')) {
  jQuery('#wpbody-content .wrap').addClass('sln-bootstrap');
  jQuery('#wpbody-content .wrap').attr('id', 'sln-salon--admin');
}


jQuery(function ($) {
    $(function() {
        $('#color-background').colorpicker({
            //color: 'transparent',
            //color: 'rgba(0, 66, 88, 1)',
            format: 'rgba',
            customClass: 'sln-colorpicker-widget',
            sliders: {
                saturation: {
                    maxLeft: 160,
                    maxTop: 160
                },
                hue: {
                    maxTop: 160
                },
                alpha: {
                    maxTop: 160
                }
            },
            colorSelectors: {
                'rgba(255,255,255,1)': 'rgba(255,255,255,1)',
                'rgba(0,0,0,1)': 'rgba(0,0,0,1)',
                'rgba(2,119,189,1)': 'rgba(2,119,189,1)'
            }
        });
        $('#color-main').colorpicker({
            //color: 'transparent',
            //color: 'rgba(0, 66, 88, 1)',
            format: 'rgb',
            customClass: 'sln-colorpicker-widget',
            sliders: {
                saturation: {
                    maxLeft: 160,
                    maxTop: 160
                },
                hue: {
                    maxTop: 160
                },
                alpha: {
                    maxTop: 160
                }
            },
            colorSelectors: {
                'rgba(2,119,189,1)': 'rgba(2,119,189,1)'
            }
        });
        $('#color-text').colorpicker({
            //color: 'transparent',
            //color: 'rgba(0, 66, 88, 1)',
            format: 'rgb',
            customClass: 'sln-colorpicker-widget',
            sliders: {
                saturation: {
                    maxLeft: 160,
                    maxTop: 160
                },
                hue: {
                    maxTop: 160
                },
                alpha: {
                    maxTop: 160
                }
            },
            colorSelectors: {
                'rgba(68,68,68,1)': 'rgba(68,68,68,1)',
                'rgba(0,0,0,1)': 'rgba(0,0,0,1)',
                'rgba(255,255,255,1)': 'rgba(255,255,255,1)'
            }
        });
    });
    $(function() {
        if($('#sln-tab-style').length){
            var color_background = $('#color-background input').val(),
                color_main = $('#color-main input').val(),
                color_text = $('#color-text input').val();
            $('#color-main-a').val(color_main);
            $('#color-text-a').val(color_text);
            var mainAlphaB = .75,
                mainAlphaC = .5,
                mainVal = $('#color-main-a').val(),
                a = mainVal.slice(4).split(','),
                mainShadeB ='rgba(' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaB + ')',
                mainShadeC = 'rgba(' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaC + ')';
            $('#color-main-b').val(mainShadeB);
            $('#color-main-c').val(mainShadeC);
            var textAlphaB = .75,
                textAlphaC = .5,
                textVal = $('#color-text-a').val(),
                b = textVal.slice(4).split(','),
                textShadeB ='rgba(' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaB + ')',
                textShadeC = 'rgba(' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaC + ')';
            $('#color-text-b').val(textShadeB);
            $('#color-text-c').val(textShadeC);
            $('.sln-colors-sample .wrapper').css('background-color', color_background);
            $('.sln-colors-sample h1').css('color', color_main);
            $('.sln-colors-sample button').css('background-color', color_main);
            $('.sln-colors-sample button').css('color', color_background);
            $('.sln-colors-sample input').css('border-color', color_main);
            $('.sln-colors-sample input').css('color', color_main);
            $('.sln-colors-sample input').css('background-color', color_background);
            $('.sln-colors-sample p').css('color', color_text);
            $('.sln-colors-sample label').css('color', mainShadeB);
            $('.sln-colors-sample small').css('color', textShadeB);

            $('#color-background').colorpicker().on('changeColor', function(e) {
                $('.sln-colors-sample .wrapper')[0].style.backgroundColor = e.color;
                $('.sln-colors-sample input')[0].style.backgroundColor = e.color;
                $('.sln-colors-sample button')[0].style.color = e.color;
                $('#color-background-a').val(e.color);
            });

            $('#color-main').colorpicker().on('changeColor', function(e) {
                var mainAlphaB = .75,
                    mainAlphaC = .5,
                    bum = e.color;
                $('#color-main-a').val(bum);
                var mainVal = $('#color-main-a').val(),
                    a = mainVal.slice(4).split(','),
                    mainShadeB ='rgba' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaB + ')',
                    mainShadeC = 'rgba' + a[0] + ',' + parseInt(a[1]) + ',' + parseInt(a[2]) + ',' + mainAlphaC + ')';
                $('#color-main-b').val(mainShadeB);
                $('#color-main-c').val(mainShadeC);
                $('.sln-colors-sample h1')[0].style.color = e.color;
                $('.sln-colors-sample button')[0].style.backgroundColor = e.color;
                //$('.sln-colors-sample label')[0].style.color = e.color;
                $('.sln-colors-sample label').css('color', mainShadeB);
                $('.sln-colors-sample input')[0].style.borderColor = e.color;
                //$('.sln-colors-sample input').css('border-color', shadeB);
                $('.sln-colors-sample input')[0].style.color = e.color;
            });
            $('#color-text').colorpicker().on('changeColor', function(e) {
                var textAlphaB = .75,
                    textAlphaC = .5,
                    bum = e.color;
                $('#color-text-a').val(bum);
                var textVal = $('#color-text-a').val(),
                    b = textVal.slice(4).split(','),
                    textShadeB ='rgba' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaB + ')',
                    textShadeC = 'rgba' + b[0] + ',' + parseInt(b[1]) + ',' + parseInt(b[2]) + ',' + textAlphaC + ')';
                $('#color-text-b').val(textShadeB);
                $('#color-text-c').val(textShadeC);
                $('.sln-colors-sample p')[0].style.color = e.color;
                $('.sln-colors-sample small').css('color', textShadeB);
            });
        }
    });
// COLOR PICKER // END
});
    
jQuery(function ($) {
    if($('#_sln_booking_firstname').length){
        sln_validateBooking($);
    }
    function calculateTotal(){
        var tot = 0;
        $('[name=_sln_booking\\[services\\]\\[\\]]').each(function(){
            tot = (parseFloat(tot) + parseFloat($(this).data('price'))).toFixed(2);
        });
        $('#_sln_booking_amount').val(tot);
        if($('#salon-step-date').data('deposit') > 0)
            $('#_sln_booking_deposit').val(((tot / 100).toFixed(2) * $('#salon-step-date').data('deposit')).toFixed(2))
        return false;
    }
    function bindRemoveFunction() {
        $(this).parent().parent().parent().remove();
        return false;
    }

    function bindRemove() {
        $('button[data-collection="remove"]').unbind('click', bindRemoveFunction).on('click', bindRemoveFunction);
    }

    var prototype = $('.sln-booking-rules div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data('count');
    prototype.remove();

    var prototypeHoliday = $('.sln-booking-holiday-rules div[data-collection="prototype"]');
    var htmlHoliday = prototypeHoliday.html();
    var countHoliday = prototypeHoliday.data('count');
    prototypeHoliday.remove();

    initDatepickers($);
    initTimepickers($);
    bindRemove();

    $('button[data-collection="addnew"]').click(function (e) {
        e.preventDefault();
        $('#sln-booking-rules-wrapper').append('<div class="sln-booking-rule">' + html.replace(/__new__/g, count) + '</div>');
        count++;
        bindRemove();
        customSliderRange($, $('.slider-range'))
        initDatepickers($);
        initTimepickers($);
        $('[data-unhide]').change();

    });

    $('button[data-collection="addnewholiday"]').click(function (e) {
        e.preventDefault();
        $('#sln-booking-holiday-rules-wrapper').append(htmlHoliday.replace(/__new__/g, countHoliday) );
        countHoliday++;
        initDatepickers($);
        initTimepickers($);
        bindRemove();
    });
>>>>>>> master
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

jQuery(function ($) {
    $('body').on('change', '[data-unhide]', function () {
        $($(this).data('unhide')).toggle($(this).is(':checked') ? false : true);
    });
    $('[data-unhide]').change();
});