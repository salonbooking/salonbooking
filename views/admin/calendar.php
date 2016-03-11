<link rel="stylesheet" href="<?php echo SLN_PLUGIN_URL ?>/css/calendar.css">
<script type="text/javascript">
    var salon;
    var calendar_translations = {
        'Go to daily view': '<?php _e('Go to daily view', 'salon-booking-system') ?>'
    };
</script>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/calendar_language/<?php echo str_replace('_','-',get_locale())?>.js"></script>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/underscore-min.js"></script>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/calendar-app.js?20160224"></script>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/calendar.js?20160224"></script>
<script>
<?php $today = new DateTime()?>
jQuery(function($){
    initSalonCalendar(
        $,
        salon.ajax_url+"&action=salon&method=calendar&security="+salon.ajax_nonce,
//        '<?php echo SLN_PLUGIN_URL ?>/js/events.json.php',
        '<?php echo $today->format('Y-m-d') ?>',
        '<?php echo SLN_PLUGIN_URL ?>/views/js/calendar/'
    );
});
</script>
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
        <a href="<?php echo get_admin_url()?>edit.php?post_type=sln_booking" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--booking sln-booking-add hidden-xs" id="calendar-add-new">Add new booking</a>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="day">
        <button class="" data-calendar-view="day"><?php _e('Day')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="week">
        <button class="" data-calendar-view="week"><?php _e('Week')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="month">
        <button class=" active" data-calendar-view="month"><?php _e('Month')?></button>
        </div>
        <div class="sln-btn sln-btn--borderonly sln-btn--medium" data-calendar-view="year">
        <button class="" data-calendar-view="year"><?php _e('Year')?></button>
        </div>
    </div>
</div>

<div class="row sln-calendar-view sln-box">
<h2 class="col-xs-12 col-md-6 sln-box-title current-view--title"></h2>
    <div class="col-xs-12 col-md-6 form-inline">
            <div class="sln-calendar-viewnav btn-group">
    <div class="sln-btn sln-btn--light sln-btn--medium  sln-btn--icon sln-btn--icon--left sln-icon--arrow--left" data-calendar-view="day">
        <button class="f-row" data-calendar-nav="prev"><?php _e('Previous') ?></button>
    </div>
    <div class="sln-btn sln-btn--light sln-btn--medium" data-calendar-view="day">
        <button class="f-row" data-calendar-nav="today"><?php _e('Today')?></button>
    </div>
    <div class="sln-btn sln-btn--light sln-btn--medium  sln-btn--icon sln-icon--arrow--right" data-calendar-view="day">
        <button class="f-row f-row--end" data-calendar-nav="next"><?php _e('Next') ?></button>
    </div>
    </div>
    </div>

        <div class="clearfix"></div>
        <div id="calendar"></div>
    <div class="clearfix"></div>

<!-- row sln-calendar-wrapper // END -->
</div>
<div class="row">
    <div class="col-md-6 pull-right">
     <a href="<?php echo get_admin_url()?>edit.php?post_type=sln_booking" class="sln-btn sln-btn--main sln-btn--medium sln-btn--icon sln-icon--booking sln-booking-add hidden-xs" id="calendar-add-new">Add new booking</a>
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
