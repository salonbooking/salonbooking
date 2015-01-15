<?php
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
$data['to'] = $booking->getEmail();
$data['subject'] = 'New booking summary for '.$plugin->format()->date($booking->getDate()). ' - '.$plugin->format()->time($booking->getTime());
include dirname(__FILE__).'/_header.php';
?>
<p ><?php _e('Dear', 'sln') ?>
    <strong><?php echo esc_attr($booking->getFirstname()) . ' ' . esc_attr($booking->getLastname()); ?></strong>
    <br/>
    <?php _e('Here the details of your booking:', 'sln') ?>
</p>
<p><?php _e('Date and time booked', 'sln') ?>: <strong><?php echo $plugin->format()->date($booking->getDate()); ?></strong><?php echo $plugin->format()->time($booking->getTime()) ?></span></p>
<h2><?php _e('Services booked', 'sln') ?></h2>
<ul class="list-unstyled">
    <?php foreach ($plugin->getServices() as $service) : if($booking->hasService($service)): ?>
        <li><?php echo $service->getName(); ?>
                - <?php echo $plugin->format()->money($service->getPrice()) ?></li>
    <?php endif; endforeach ?>
    <li><?php _e('Total amount', 'sln') ?> - <?php echo $plugin->format()->money($booking->getAmount()) ?></li>
</ul>
<p>Note: <?php echo esc_attr($booking->getNote())?></p>
<?php if ($plugin->settings->get('confirmation')) : ?>
<p><strong>Please wait the confirmation</strong></p>
<?php endif ?>
<?php
include dirname(__FILE__).'/_footer.php';
?>
