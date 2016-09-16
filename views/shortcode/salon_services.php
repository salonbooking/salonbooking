<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_ServicesStep $step
 */
if ($plugin->getSettings()->isDisabled()) {
	$message = $plugin->getSettings()->getDisabledMessage();
	?>
	<div class="sln-alert sln-alert--problem">
		<?php echo empty($message) ? __('On-line booking is disabled', 'salon-booking-system') : $message ?>
	</div>
	<?php
} else {
	$style = $step->getShortcode()->getStyleShortcode();
	$size = SLN_Enum_ShortcodeStyle::getSize($style);
	$bb             = $plugin->getBookingBuilder();
	$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
	$services = $step->getServices();
	?>
	    <?php include '_errors.php'; ?>
	<form id="salon-step-services" method="post" action="<?php echo $formAction ?>" role="form">
		<?php
		$args = array(
				'label'        => __('What do you need?', 'salon-booking-system'),
				'tag'          => 'h2',
				'textClasses'  => 'salon-step-title',
				'inputClasses' => '',
				'tagClasses'   => 'salon-step-title',
		);
		echo $plugin->loadView('shortcode/_editable_snippet', $args);
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
	<?php
}