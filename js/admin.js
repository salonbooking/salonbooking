jQuery(function ($) {
    function bindRemove(){
        $('button[data-collection="remove"]').unbind('click').on('click',function () {
            $(this).parent().parent().parent().remove();
            return false;
        });
    }
    var prototype = $('#sln-availabilities div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data('count');
    prototype.remove();
    bindRemove();

    $('button[data-collection="addnew"]').click(function () {
        $('#sln-availabilities div.items').append('<div class="item">'+html.replace(/__new__/g, count)+'</div>');
        count++;
        bindRemove();
        return false;
    });
    $('#booking-accept, #booking-refuse').click(function(){
       $('#post_status').val($(this).data('status'));
       $('#save-post').click();
    });
    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    }).focus(function () { $(this).select2('open'); });
    $('.sln-select select').select2({
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%'
    }).focus(function () { $(this).select2('open'); });
    $('.sln-toolbox-trigger').click(function() {
        $(this).parent().toggleClass('open');
        event.preventDefault();
    });
    $('.sln-toolbox-trigger-mob').click(function() {
        $(this).parent().find('.sln-toolbox').toggleClass('open');
        event.preventDefault();
    });
    $('.sln-box-info-trigger button').click(function() {
        $(this).parent().parent().parent().toggleClass('sln-box--info-visible');
        event.preventDefault();
    });
    // TIME RANGE //
$("#slider-range, #slider-range-b").slider({
    range: true,
    min: 480,
    max: 1260,
    step: 15,
    values: [540, 1020],
    slide: function (e, ui) {
        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);

        if (hours1.length == 1) hours1 = '0' + hours1;
        if (minutes1.length == 1) minutes1 = '0' + minutes1;
        if (minutes1 == 0) minutes1 = '00';
        if (hours1 >= 12) {
            if (hours1 == 12) {
                hours1 = hours1;
                minutes1 = minutes1 + "";
            } else {
                //hours1 = hours1 - 12;
                hours1 = hours1;
                minutes1 = minutes1 + "";
            }
        } else {
            hours1 = hours1;
            minutes1 = minutes1 + "";
        }
        if (hours1 == 0) {
            hours1 = 12;
            minutes1 = minutes1;
        }



        $('.slider-time').html(hours1 + ':' + minutes1);

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';
        if (hours2 >= 12) {
            if (hours2 == 12) {
                hours2 = hours2;
                minutes2 = minutes2 + "";
            } else if (hours2 == 24) {
                hours2 = 11;
                minutes2 = "59";
            } else {
                //hours2 = hours2 - 12;
                hours2 = hours2;
                minutes2 = minutes2 + "";
            }
        } else {
            hours2 = hours2;
            minutes2 = minutes2 + "";
        }

        $('.slider-time2').html(hours2 + ':' + minutes2);
    }
});
});
