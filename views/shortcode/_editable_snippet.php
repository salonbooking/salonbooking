<?php

$args = array(
    'label'        => '',
    'tag'          => 'h2',
    'textClasses'  => 'salon-step-title',
    'inputClasses' => '',
    'tagClasses'   => 'salon-step-title',
);

foreach($args as $k => $v) {
	if (!isset($$k)) {
		$$k = $v;
	}
}
$value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

if(current_user_can('manage_options')) {
	?>
	<div class="editable">
        <<?php echo $tag; ?> class="text <?php echo $textClasses ?>">
            <?php echo $value; ?>
        </<?php echo $tag; ?>>
		<div class="input <?php echo $inputClasses ?>">
			<input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
		</div>
		<i class="fa fa-gear fa-fw"></i>
	</div>
	<?php
} else {
	?>
	<<?php echo $tag; ?> class="<?php echo $tagClasses ?>"><?php echo $value; ?></<?php echo $tag; ?>>
	<?php
}