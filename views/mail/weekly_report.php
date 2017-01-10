<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 * @var array $stats
 */

$data['to']      = $plugin->getSettings()->getSalonEmail();
$data['subject'] = __('Weekly Report', 'salon-booking-system');

include dirname(__FILE__).'/_header.php';
?>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="55" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$user = wp_get_current_user();
				$name = $user->first_name . ' ' . $user->last_name;

				$msg = sprintf(__("Dear %s,\nthese is a summary of what happened on your salon last week:", 'salon-booking-system'), $name);
				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="70" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$msg = sprintf(
					__('You received a total of <strong>%s online reservations ( %s )</strong>, <strong>%s of them have been paid online ( %s )</strong> and <strong>%s have been paid later ( %s )</strong>.', 'salon-booking-system'),
					$stats['total']['count'],
					$plugin->format()->moneyFormatted($stats['total']['amount']),
					$stats['paid']['count'],
					$plugin->format()->moneyFormatted($stats['paid']['amount']),
					$stats['pay_later']['count'],
					$plugin->format()->moneyFormatted($stats['pay_later']['amount'])
				);
				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="50" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$msg = sprintf(
					__('<strong>%s reservations has been canceled</strong> from your customers.', 'salon-booking-system'),
					$stats['canceled']
				);
				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$msg = sprintf(
					__("The <strong>most booked services</strong> have been:\n", 'salon-booking-system')
				);

				$i = 1;
				foreach($stats['services'] as $sID => $count) {
					$msg .= "\n{$i}. " . $plugin->createService($sID)->getName() . " ( {$count} )";

					$i ++;
					if ($i > 5) {
						break;
					}
				}

				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
		</td>
	</tr>

	<?php if (!empty($stats['attendants'])) : ?>
		<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
			<td height="105" valign="middle" bgcolor="#f2f2f2">
				<p style="margin-left: 49px; margin-right: 49px;">
					<?php
					$msg = sprintf(
						__("The <strong>most booked assistants</strong> have been:\n", 'salon-booking-system')
					);

					$i = 1;
					foreach($stats['attendants'] as $sID => $count) {
						$msg .= "\n{$i}. " . $plugin->createAttendant($sID)->getName() . " ( {$count} )";

						$i ++;
						if ($i > 5) {
							break;
						}
					}

					$msg = nl2br($msg);
					echo $msg;
					?>
				</p>
			</td>
		</tr>
	<?php endif; ?>

	<?php
	$count = reset($stats['weekdays']);
	if ($count) {
		$weekday = key($stats['weekdays']);
		?>
		<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
			<td height="70" valign="middle" bgcolor="#f2f2f2">
				<p style="margin-left: 49px; margin-right: 49px;">
					<?php
					$msg = sprintf(
						__('The most booked day of the week has been <strong>%s</strong> when you received <strong>%s reservations</strong>.', 'salon-booking-system'),
						SLN_Enum_DaysOfWeek::getLabel($weekday),
						$count
					);
					$msg = nl2br($msg);
					echo $msg;
					?>
				</p>
			</td>
		</tr>
		<?php
	}
	?>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="50" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$msg = sprintf(
					__("This week you've got <strong>%s new online customers</strong>.", 'salon-booking-system'),
					$stats['new_customers']
				);
				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
		</td>
	</tr>

	<?php
	$amount = reset($stats['customers']);
	if ($amount) {
		$customerID = key($stats['customers']);
		?>
		<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
			<td height="70" valign="middle" bgcolor="#f2f2f2">
				<p style="margin-left: 49px; margin-right: 49px;">
					<?php
					$customer = new SLN_Wrapper_Customer($customerID);
					$msg = sprintf(
						__('Your customer <strong>%s</strong> has been the most valuable one <strong>spending %s</strong> of your services.', 'salon-booking-system'),
						$customer->getName(),
						$plugin->format()->moneyFormatted($amount)
					);
					$msg = nl2br($msg);
					echo $msg;
					?>
				</p>
			</td>
		</tr>
		<?php
	}
	?>

<?php
include dirname(__FILE__).'/_footer.php';