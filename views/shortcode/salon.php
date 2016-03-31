<?php
/**
 * @var string               $content
 * @var SLN_Shortcode_Salon $salon
 */
//$labels = array(
//    'date'      => __('date', 'salon-booking-system'),
//    'services'  => __('services', 'salon-booking-system'),
//    'secondary' => __('secondary', 'salon-booking-system'),
//    'details'   => __('details', 'salon-booking-system'),
//    'summary'   => __('summary', 'salon-booking-system'),
//    'thankyou'  => __('thankyou', 'salon-booking-system'),
//);
?>

<?php
// Start the session
session_start();
$customsize = $_GET["size"];
if (isset($customsize)) {
    $_SESSION["size"] = $customsize;
} else {
  $_SESSION["size"] = "600";
}
?>
<div id="sln-salon" class="sln-bootstrap container-fluid <?php
            $size = $_SESSION["size"];
            if ($size == '900') { echo 'sln-salon--l'; }
            else if ($size == '600') { echo 'sln-salon--m'; }
            else if ($size == '400') { echo 'sln-salon--s'; }
            else { echo 'sln-salon--s'; }
            echo ' sln-step-' . $salon->getCurrentStep(); ?>">
    <?php
    if ($trial_exp)
        echo '<h2 class="sln_notice">' . __('Your free version is expired - upgrade to PRO', 'salon-booking-system') . '</h2>';

    ?>
        <h1><?php _e('Book an appointment', 'salon-booking-system'); ?></h1>
<?php echo $content ?>

        <?php
        /* <ul class="salon-bar nav nav-pills nav-justified thumbnail">
          <?php $i = 0;
          foreach ($salon->getSteps() as $step) : $i++; ?>
          <li <?php echo $step == $salon->getCurrentStep() ? 'class="active"' : ''?>>
          <?php echo $i ?>. <?php echo $labels[$step] ?>
          </li>
          <?php endforeach ?>
          </ul>
         */

        ?>
<?php /*
<div class="sln-stepper">
<?php $i = 0;
          foreach ($salon->getSteps() as $step) : $i++; ?>
  <div class="step <?php echo $step == $salon->getCurrentStep() ? 'step--active' : ''?>">
    <span class="step-number"><?php echo $i ?></span> <span class="step-label"><?php echo $step; ?></span>
  </div>
<?php endforeach ?>
<div id="sln-notifications"></div>
</div>
*/ ?>
