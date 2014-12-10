jQuery(function ($) {
    var prototype = $('#sln-availabilities div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data('count');
    prototype.remove();

    $('button[data-collection="remove"]').on('click',function () {
        $(this).parent().remove();
        return false;
    });
    $('button[data-collection="addnew"]').click(function () {
        $('#sln-availabilities div.items').append('<div class="item">'+html.replace(/__new__/g, count)+'</div>');
        count++;
        return false;
    });
});