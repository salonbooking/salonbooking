<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */

$data['to']      = $customer->get('user_email');
$data['subject'] = $plugin->getSettings()->getSalonName();
$manageBookingsLink = true;

include dirname(__FILE__).'/_header.php';
?>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$msg = $plugin->getSettings()->get('follow_up_message');
				$msg = str_replace(array('[NAME]', '[SALON NAME]'), array($customer->getName(), $plugin->getSettings()->getSalonName()), $msg);
				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
			<hr style="border: solid 1px #fff; margin: 0 16px;">
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<?php
					$title   = __('Our address', 'salon-booking-system');
					$address = $plugin->getSettings()->get('gen_address');
					?>
					<td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo $title; ?></td>
					<td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $address; ?></td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<table width="564" border="0" align="center" cellpadding="0" cellspacing="0" align="center" valign="top" bgcolor="#ffffff" style="padding: 0 52px;">
				<tbody>
				<tr>
					<?php
					$title = __('Contacts', 'salon-booking-system');
					$text = '';
					$m = $plugin->getSettings()->getSalonEmail();
					$phone = $plugin->getSettings()->get('gen_phone');
					?>
					<td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo $title ?></td>
					<td align="left" valign="top">
						<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
							<tbody>
							<tr>
								<td height="27">&nbsp;</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $text ?></td>
							</tr>
							<tr>
								<td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal;">
									<a href="mailto:<?php echo $m ?>"
									   style="color:#666666; text-decoration:none;"><?php echo $m ?></a></td>
							</tr>
							<tr>
								<td height="22">&nbsp;</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $phone ?></td>
							</tr>
							<tr>
								<td height="35">&nbsp;</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php _e('Important notes,','salon-booking-system') ?></p>
			<p style="margin-left: 49px; margin-right: 49px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:normal;">
				<?php
				echo $plugin->getSettings()->get('gen_timetable');
				?>
			</p>
		</td>
	</tr>

<?php
include dirname(__FILE__).'/_footer.php';