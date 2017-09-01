var discount_rule_html;
jQuery(function ($) {
    var $rule_wrapper  = $('.sln_discount_rule[data-rule-id=__new_discount_rule__]').wrap('<p></p>').closest('p');
    discount_rule_html = $rule_wrapper.html();
    $rule_wrapper.remove();

    bindDiscountTypeChange($);
    bindDiscountRuleModeChange($);
    bindDiscountRuleRemove($);
    bindDiscountRuleAdd($);
});

function bindDiscountTypeChange($) {
    $('[data-type=discount-type]').unbind('change').on('change', function() {
        $('.sln_discount_type').addClass('hide');
        $('.sln_discount_type--'+$(this).val()).removeClass('hide');
    });
}

function bindDiscountRuleModeChange($) {
    $('[data-type=discount-rule-mode]').unbind('change').on('change', function() {
        var $rule = $(this).closest('.sln_discount_rule');
        $rule.find('.sln_discount_rule_mode_details').addClass('hide');
        $rule.find('.sln_discount_rule_mode_details--'+$(this).val()).removeClass('hide');
    }).change();
}

function bindDiscountRuleAdd($) {
    $('[data-action=add-discount-rule]').unbind('click').on('click', function() {

        var id = 0;
        if ($('.sln_discount_rule').size() > 0) {
            id = parseInt($('.sln_discount_rule:last').attr('data-rule-id')) + 1;
        }
        var rule_html = discount_rule_html.replace(/__new_discount_rule__/g, id).replace(/hide/, ''); // remove only first 'hide'

        $('#sln_discount_rules').append($(rule_html));

        bindDiscountRuleModeChange($);
        bindDiscountRuleRemove($);

        sln_createSelect2Full($);
        initDatepickers($);
    });
}

function bindDiscountRuleRemove($) {
    $('[data-action=remove-discount-rule]').unbind('click').on('click', function() {
        $(this).closest('.sln_discount_rule').remove();
    });
}