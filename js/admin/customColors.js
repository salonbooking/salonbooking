jQuery(function ($) {
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
});