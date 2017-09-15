jQuery(function($){
    initSalonCalendarUserSelect2($);
});

function calendar_getHourFunc() {
    return function (hour, part) {
        var time_start = this.options.time_start.split(":");
        var time_split = parseInt(this.options.time_split);
        var h = "" + (parseInt(time_start[0]) + hour * Math.max(time_split / 60, 1));
        var m = "" + (time_split * part + parseInt((hour == 0) ? parseInt(time_start[1]) : 0));
        var d = new Date();
        d.setHours(h)
        d.setMinutes(m);
        return moment(d).format(calendarGetTimeFormat());
    }
}

function calendar_getTimeFunc() {
    return function (part) {
        var time_start = this.options.time_start.split(":");
        var time_split = parseInt(this.options.time_split);
        var h = "" + ( parseInt(time_start[0]) );
        var m = "" + ( parseInt(time_start[1]) + time_split * part );
        var d = new Date();
        d.setHours(h)
        d.setMinutes(m);
        return moment(d).format(calendarGetTimeFormat());
    }
}

function calendar_getTransFunc() {
    return function (label) {
        return calendar_translations[label];
    }
}

function calendarGetTimeFormat() {
    // http://momentjs.com/docs/#/displaying/format/
    // vs http://www.malot.fr/bootstrap-datetimepicker/#options
    if (!salon.moment_time_format)
        salon.moment_time_format = salon.time_format
            .replace('ii', 'mm')
            .replace('hh', '{|}')
            .replace('H', 'h')
            .replace('{|}', 'H')
            .replace('p', 'a')
            .replace('P', 'A')
        ;
    return salon.moment_time_format;
}

function initSalonCalendar($, ajaxUrl, ajaxDay, templatesUrl) {

    var options = {
		time_start:         $('#calendar').data('timestart'),
		time_end:           $('#calendar').data('timeend'),
		time_split:         $('#calendar').data('timesplit'),
	
        events_source: ajaxUrl,
        view: 'month',
        tmpl_path: templatesUrl,
        tmpl_cache: false,
        format12: true,
        day: ajaxDay,
        onAfterEventsLoad: function (events) {
            if (!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');
            $.each(events, function (key, val) {
                $(document.createElement('li'))
                    .html(val.event_html)
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function (view) {
            $('.current-view--title').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
            $.each(sln_stats, function (key, val) {
                var calbar = $('.calbar[data-day="' + key + '"]');
                var append = '';
                if (val.busy > 0) {
                    append += '<span class="busy" style="width: ' + val.busy + '%"></span>';
                }
                if (val.free > 0) {
                    append += '<span class="free" style="width: ' + val.free + '%"></span>';
                }
                calbar.attr('data-original-title', val.text).html(append);

            });
        },
        classes: {
            months: {
                general: 'label'
            }
        },
        cal_day_pagination: '<button type="button" class="btn %class" data-page="%page"></button>',
        on_page: 11,
        _page: 0,
    };
    initDatepickers($);
    // CALENDAR
    //$('.cal-month-day.cal-day-inmonth [data-toggle="tooltip"]').click(function(e) {
    $(document).on("click", ".cal-month-day.cal-day-inmonth span", function (e) {
        $('.tooltip').hide();
        event.preventDefault(e);
    });

    var calendar = $('#calendar').calendar(options);
    $('.btn-group button[data-calendar-nav]').each(function () {
        var $this = $(this);
        $this.click(function () {
            calendar.navigate($this.data('calendar-nav'));
        });
    });

    $('.btn-group button[data-calendar-view]').each(function () {
        var $this = $(this);
        $this.click(function () {
            calendar.view($this.data('calendar-view'));
        });
    });

    $('#sln-calendar-user-field').change(function() {
        calendar.options._customer = parseInt($(this).val());
        calendar._render();
        calendar.options.onAfterViewLoad.call(calendar, calendar.options.view);
    });
    $('#sln-calendar-services-field').change(function() {
        var _events = $(this).val();
        if (Array.isArray(_events)) {
            _events = _events.map(parseInt);
        }
        else {
            _events = [];
        }

        calendar.options._services = _events;
        calendar._render();
        calendar.options.onAfterViewLoad.call(calendar, calendar.options.view);
    });

    $('#sln-calendar-assistants-mode-switch').change(function() {
        calendar.options._assistants_mode = $(this).is(':checked');
        calendar._render();
        calendar.options.onAfterViewLoad.call(calendar, calendar.options.view);
    });

    calendar.setLanguage($('html').attr('lang'));
    calendar.view();

    /*
     $('#first_day').change(function(){
     var value = $(this).val();
     value = value.length ? parseInt(value) : null;
     calendar.setOptions({first_day: value});
     calendar.view();
     });

     $('#language').change(function(){
     calendar.setLanguage($(this).val());
     calendar.view();
     });

     $('#events-in-modal').change(function(){
     var val = $(this).is(':checked') ? $(this).val() : null;
     calendar.setOptions({modal: val});
     });
     $('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
     //e.preventDefault();
     //e.stopPropagation();
     });
     */
}

function initSalonCalendarUserSelect2($) {
    $('#sln-calendar-user-field').select2({
        allowClear: true,
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%',
        placeholder: $('#sln-calendar-user-field').data('placeholder'),
        language: {
            noResults: function () {
                return $('#sln-calendar-user-field').data('nomatches');
            }
        },
        ajax: {
            url: salon.ajax_url + '&action=salon&method=SearchUser&security=' + salon.ajax_nonce,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    s: params.term
                };
            },
            minimumInputLength: 3,
            processResults: function (data, page) {
                return {
                    results: data.result
                };
            },
        }
    });
}
