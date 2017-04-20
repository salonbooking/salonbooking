<?php
$addAjax = apply_filters('sln.template.calendar.ajaxUrl','');
?>
<script type="text/javascript">
    var salon;
    var calendar_translations = {
        'Go to daily view': '<?php _e('Go to daily view', 'salon-booking-system') ?>'
    };
<?php $today = new DateTime()?>
jQuery(function($){
    initSalonCalendar(
        $,
        salon.ajax_url+"&action=salon&method=calendar&security="+salon.ajax_nonce+'<?php echo $addAjax ?>',
//        '<?php echo SLN_PLUGIN_URL ?>/js/events.json.php',
        '<?php echo $today->format('Y-m-d') ?>',
        '<?php echo SLN_PLUGIN_URL ?>/views/js/calendar/'
    );
});
</script>
<style>
.day-calbar,
.week-calbar{
    display: block;
    margin-bottom: 8px;
    height: 8px;
    width: 100%;
    background-color: #dfdfdf;
}
.week-calbar{
    margin-top: -8px;
}
.month-calbar{
    display: block;
    height: 8px;
    width: 100%;
    background-color: #dfdfdf;
}
.calbar .busy{
    display: block;
    background-color: red;
    height: 8px;
    float: left;
}
.calbar .free{
    display: block;
    height: 8px;
    float: left;
    background-color: green;
}
.calbar-tooltip{
    background-color: #c7dff3;
    display: inline-block;
    width: 340px;
    height: 50px;
    padding: 5px;
    margin: -20px 0 -10px -80px;
}
.calbar-tooltip span{
    float: left;
    display: block;
    width: 33%;
    color: #666;
}
.calbar-tooltip strong{
    font-size: 16px;
    color: #0C6EB6;
    display: block;
    clear: both;
}
#cal-day-box .day-event-panel-border{
    z-index: 610;
    position: absolute;
    height: inherit;
    width: 1px;
    background-color: #d4d4d4;
    top: -10px;
    left: 80px;
}
#cal-day-box .day-event{
    width: 7.4% !important;
    max-width: 7.4% !important;
    left: 82px;
}
#cal-day-box .cal-day-assistants{
    margin: 0 0 0 80px;
    width: 91.2%;
}
#cal-day-box .cal-day-assistant{
    display: inline-block;
    text-align: center;
    width: 8.4% !important;
    margin-right: -4px;
}
#cal-day-box .day-highlight{
    border-left: none !important;
}
</style>
<div class="wrap sln-bootstrap">
    <h1><?php _e('Calendar','salon-booking-system')?> - <span class="current-view--title"></span></h1>
</div>
<div class="clearfix"></div>
<div class="container-fluid sln-calendar--wrapper sln-calendar--wrapper--loading">
<div class="sln-calendar--wrapper--sub" style="opacity: 0;">
    <!--<div class="row">
        <div class="col-md-11">
            <div class="page-header pull-left">
                <h3 class="current-view--title"></h3>
            </div>
        </div>
    </div>-->


<div class="row">
    <div class="col-md-12 btn-group nav-tab-wrapper sln-nav-tab-wrapper">
        <a href="<?php echo get_admin_url()?>edit.php?post_type=sln_booking" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--booking sln-booking-add hidden-xs" id="calendar-add-new"><?php _e('Add new booking', 'salon-booking-system') ?></a>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="day">
        <button class="" data-calendar-view="day"><?php _e('Day', 'salon-booking-system')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="week">
        <button class="" data-calendar-view="week"><?php _e('Week', 'salon-booking-system')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="month">
        <button class=" active" data-calendar-view="month"><?php _e('Month', 'salon-booking-system')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="year">
        <button class="" data-calendar-view="year"><?php _e('Year', 'salon-booking-system')?></button>
        </div>
        <?php do_action('sln.template.calendar.navtabwrapper') ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6 sln-box-title current-view--title"></div>
    <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-switch cal-day-filter">
        <div class="pull-right">
            <span class="sln-fake-label"><?php _e('Assistants view', 'salon-booking-system') ?></span>
            <?php SLN_Form::fieldCheckbox(
                "sln-calendar-assistants-mode-switch",
                false
            )
            ?>
            <label for="sln-calendar-assistants-mode-switch" class="sln-switch-btn" data-on="On" data-off="Off"></label>
        </div>
    </div>
</div>

<div class="row sln-calendar-view sln-box">
    <div class="col-xs-12 col-md-6">
        <div class="row cal-day-filter">
            <div class="col-md-6 sln-select form-group">
                <select id="sln-calendar-user-field"
                        data-nomatches="<?php _e('no users found','salon-booking-system')?>"
                        data-placeholder="<?php _e('digit a customer name')?>"
                        class="form-control">
                </select>
            </div>
            <div class="col-md-6 sln-select form-group">
                <?php
                /** @var SLN_Wrapper_Service[] $services */
                $services = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_SERVICE)->getAll();
                $items    = array();
                foreach($services as $s) {
                    $items[$s->getId()] = $s->getName();
                }
                SLN_Form::fieldSelect(
                    'sln-calendar-services-field',
                    $items,
                    array(),
                    array('attrs' => array('multiple' => true, 'placeholder' => __('filter by service', 'salon-booking-system'))),
                    true
                ); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 form-inline">
        <div class="row">
            <div class="col-md-4">
                <div class="cal-day-filter cal-day-pagination"></div>
            </div>
            <div class="col-md-8">
                <div class="sln-calendar-viewnav btn-group">
                    <div class="sln-btn sln-btn--light sln-btn--medium  sln-btn--icon sln-btn--icon--left sln-icon--arrow--left" data-calendar-view="day">
                        <button class="f-row" data-calendar-nav="prev"><?php _e('Previous', 'salon-booking-system') ?></button>
                    </div>
                    <div class="sln-btn sln-btn--light sln-btn--medium" data-calendar-view="day">
                        <button class="f-row" data-calendar-nav="today"><?php _e('Today', 'salon-booking-system')?></button>
                    </div>
                    <div class="sln-btn sln-btn--light sln-btn--medium  sln-btn--icon sln-icon--arrow--right" data-calendar-view="day">
                        <button class="f-row f-row--end" data-calendar-nav="next"><?php _e('Next', 'salon-booking-system') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="clearfix"></div>
        <div id="calendar"></div>
    <div class="clearfix"></div>

<!-- row sln-calendar-wrapper // END -->
</div>
<div class="row">
    <div class="col-xs-12 col-md-9">
        <form action="<?php echo admin_url('admin.php?page=' . SLN_Admin_Tools::PAGE)?>" method="post">
            <h2><?php _e('Export reservations into a CSV file', 'salon-booking-system') ?></h2>
            <div class="row">
                <?php
                $f         = $plugin->getSettings()->get('date_format');
                $weekStart = $plugin->getSettings()->get('week_start');
                $jsFormat  = SLN_Enum_DateFormat::getJsFormat($f);
                ?>
                <div class="form-group col-xs-12 col-md-3 sln_datepicker">
                    <label for="<?php echo SLN_Form::makeID("export[from]") ?>"><?php _e('from', 'salon-booking-system') ?></label>
                    <input type="text" class="form-control sln-input" id="<?php echo SLN_Form::makeID("export[from]") ?>" name="export[from]"
                           required="required" data-format="<?php echo $jsFormat?>" data-weekstart="<?php echo $weekStart ?>"
                           data-locale="<?php echo strtolower(substr(get_locale(),0,2))?>"
                    />
                </div>
                <div class="form-group col-xs-12 col-md-3 sln_datepicker">
                    <label for="<?php echo SLN_Form::makeID("export[to]") ?>"><?php _e('to', 'salon-booking-system') ?></label>
                    <input type="text" class="form-control sln-input" id="<?php echo SLN_Form::makeID("export[to]") ?>" name="export[to]"
                           required="required" data-format="<?php echo $jsFormat?>" data-weekstart="<?php echo $weekStart ?>"
                           data-locale="<?php echo strtolower(substr(get_locale(),0,2))?>"
                    />
                </div>
                <div class="form-group col-xs-12 col-md-3">
                    <button type="submit" id="action" name="sln-tools-export" value="export"
                            class="sln-btn sln-btn--main sln-btn--medium sln-btn--icon sln-icon--file sln-booking-add pull-left">
                        <?php _e('Export', 'salon-booking-system') ?></button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xs-12 col-md-3 pull-right">
     <a href="<?php echo get_admin_url()?>edit.php?post_type=sln_booking" class="sln-btn sln-btn--main sln-btn--medium sln-btn--icon sln-icon--booking sln-booking-add" id="calendar-add-new"><?php _e('Add new booking', 'salon-booking-system') ?></a>
    </div>
</div>
<div class="row">
<div class="col-md-11">
    <h4><?php _e('Bookings status legend','salon-booking-system')?></h4> 
<ul>
<li><span class="pull-left event event-warning"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::PENDING) ?></li> 
<li><span class="pull-left event event-success"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::PAID) ?> <?php _e('or','salon-booking-system')?> <?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::CONFIRMED)?></li>
<li><span class="pull-left event event-info"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::PAY_LATER) ?></li>
<li><span class="pull-left event event-danger"></span><?php echo SLN_Enum_BookingStatus::getLabel(SLN_Enum_BookingStatus::CANCELED) ?></li>
</ul>
<div class="clearfix"></div>
        </div>
</div>
</div>
</div>
