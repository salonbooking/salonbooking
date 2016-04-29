<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_ServicesStep $step
 */
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
$bb             = $plugin->getBookingBuilder();
$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
$services = $step->getServices();
?>
    <?php include '_errors.php'; ?>
<form id="salon-step-services" method="post" action="<?php echo $formAction ?>" role="form">
	<?php
	$label = __('What do you need?', 'salon-booking-system');
	$value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

	if(current_user_can('manage_options')) {
	?>
		<h2 class="sln-step-title sln-edit-label-text"><?php echo $value; ?></h2>
		<input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
	<?php
	} else {
		?>
		<h2 class="sln-step-title"><?php echo $value; ?></h2>
		<?php
	}
	?>
<?php
	if ($size == '900') { ?>
		<div class="row sln-box--main">
			<div class="col-md-8"><?php include "_services.php"; ?></div>
			<div class="col-sm-12 col-md-4 sln-box--formactions"><?php include "_form_actions.php" ?></div>
		</div>
	<?php
	// IF SIZE 900 // END
	} else if ($size == '600') { ?>
		<div class="row sln-box--main"><div class="col-md-12"><?php include "_services.php"; ?></div></div>
		<div class="row sln-box--main sln-box--formactions">
           <div class="col-md-12">
           <?php include "_form_actions.php" ?></div>
        </div>
	<?php
	// IF SIZE 600 // END
	} else if ($size == '400') { ?>
		<div class="row sln-box--main"><div class="col-md-12"><?php include "_services.php"; ?></div></div>
		<div class="row sln-box--main"><div class="col-md-12"><?php include "_form_actions.php" ?></div></div>
	<?php
	// IF SIZE 400 // END
	} else  { ?>
	<?php
	// ELSE // END
	}
?>
</form>
