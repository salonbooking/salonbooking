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
    });
    $('.sln-select select').select2({
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%'
    });
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
});
