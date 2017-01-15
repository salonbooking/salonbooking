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
.day-calbar{
    display: block;
    margin-top: -5px;
    margin-bottom: 5px;
    height: 5px;
    width: 100%;
    background-color: #dfdfdf;
}
.day-calbar .busy{
    display: block;
    background-color: red;
    height: 5px;
    float: left;
}
.day-calbar .free{
    display: block;
    height: 5px;
    float: left;
    background-color: green;
}
.week-calbar{
    display: block;
    margin-top: -5px;
    margin-bottom: 5px;
    height: 5px;
    width: 100%;
    background-color: #dfdfdf;
}
.week-calbar .busy{
    display: block;
    background-color: red;
    height: 5px;
    float: left;
}
.week-calbar .free{
    display: block;
    height: 5px;
    float: left;
    background-color: green;
}
.month-calbar{
    display: block;
    height: 5px;
    width: 100%;
    background-color: #dfdfdf;
}
.month-calbar .busy{
    display: block;
    background-color: red;
    height: 5px;
    float: left;
}
.month-calbar .free{
    display: block;
    height: 5px;
    float: left;
    background-color: green;
}
</style>
<div class="wrap sln-bootstrap">
    <h1><?php _e('Calendar','salon-booking-system')?> - <span class="current-view--title"></span></h1>
</div>
<div class="clearfix"></div>
<div class="container-fluid sln-calendar--wrapper">
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

<div class="row sln-calendar-view sln-box">
<h2 class="col-xs-12 col-md-6 sln-box-title current-view--title"></h2>
    <div class="col-xs-12 col-md-6 form-inline">
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
