<?php
/**
 * @var array $headers
 * @var array $rows
 * @var array $columns
 * @var array $required
 */

$selectItems = array(
	'' => __('Select a column', 'salon-booking-system'),
);
foreach($headers as $header) {
	$selectItems[$header] = $header;
}
?>

<tr>
	<?php foreach($columns as $col): ?>
		<th><strong><?php echo ucfirst(str_replace('_', ' ', $col)) . (in_array($col, $required) ? '*' : '') ?></strong></th>
	<?php endforeach; ?>
</tr>
<tr>
	<?php foreach($columns as $i => $col): ?>
		<td><div class="form-group sln-select sln-select--info-label">
				<?php
				$settings = array('attrs' => array('data-action' => 'sln_import_matching_select', 'data-col' => $i));
				if (in_array($col, $required)) {
					$settings['attrs']['required'] = 'required';
				}
				SLN_Form::fieldSelect("import_matching[{$col}]", $selectItems, $col, $settings, true) ?>
			</div></td>
	<?php endforeach; ?>
</tr>

<?php foreach($rows as $row): ?>
	<tr class="import_matching">
		<?php foreach($columns as $i => $col): ?>
			<td data-col="<?php echo $i; ?>" placeholder="<?php _e('Preview', 'salon-booking-system'); ?>"><span class="<?php echo isset($row[$col]) ? 'pull-left' : 'half-opacity'; ?>"><?php echo isset($row[$col]) ? $row[$col] : (__('Preview', 'salon-booking-system')); ?></span></td>
		<?php endforeach; ?>
	</tr>
<?php endforeach; ?>
<tr class="empty">
	<?php foreach($columns as $col): ?>
		<td>&nbsp;</td>
	<?php endforeach; ?>
</tr>
