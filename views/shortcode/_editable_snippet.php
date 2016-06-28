<?php

if (empty($tag)) {
	$tag = 'h2';
}

switch ($tag) {
	case 'span':
		$textClasses = 'text-min label';
		$inputClasses = 'input-min';
		$classes = 'label';
		break;
	case 'label':
		$textClasses = '';
		$inputClasses = '';
		$classes = '';
		break;
	case 'h1':
		$textClasses = 'sln-salon-title';
		$inputClasses = '';
		$classes = 'sln-salon-title';
		break;
	case 'h2':
	default:
		$textClasses = 'salon-step-title';
		$inputClasses = '';
		$classes = 'salon-step-title';
}

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
	<<?php echo $tag; ?> class="<?php echo $classes ?>"><?php echo $value; ?></<?php echo $tag; ?>>
	<?php
}
unset($tag, $textClasses, $inputClasses, $classes);