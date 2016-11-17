jQuery(function ($  ) {

    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    });
    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    }).focus(function () {
        $(this).select2('open');
    });
    $('.sln-select select').select2({
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%'
    }).focus(function () {
        $(this).select2('open');
    });

    sln_createSelect2();
    sln_createSelect2NoSearch();
});

function sln_createSelect2() {
    jQuery('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    });
}

function sln_createSelect2NoSearch() {
    jQuery('.sln-select-wrapper-no-search select').select2({
        tags: "true",
        width: '100%',
        minimumResultsForSearch: Infinity
    });
}