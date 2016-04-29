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
$style = $salon->getStyleShortcode();
$class = SLN_Enum_ShortcodeStyle::getClass($style);
?>
<div id="sln-salon" class="sln-bootstrap container-fluid <?php
            echo $class;
            echo ' sln-step-' . $salon->getCurrentStep(); ?>">
    <?php
    if ($trial_exp)
        echo '<h2 class="sln_notice">' . __('Your free version is expired - upgrade to PRO', 'salon-booking-system') . '</h2>';

    $label = __('Book an appointment', 'salon-booking-system');
    $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

    if(current_user_can('manage_options')) {
    ?>
        <div class="editable">
            <h1 class="sln-salon-title text">
                <?php echo $value; ?>
            </h1>
            <div class="input">
                <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
            </div>
            <i class="fa fa-gear fa-fw"></i>
        </div>
    <?php
    }
    else {
    ?>
        <h1 class="sln-salon-title"><?php echo $value; ?></h1>
    <?php
    }
    echo $content;
    ?>

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
</div>
*/ ?>
<div id="sln-notifications"></div>
