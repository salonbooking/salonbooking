<?php
/**
 * @var SLN_Plugin $plugin
 */

$data['to']      = $plugin->getSettings()->getSalonEmail();
$data['subject'] = __('Salon Booking plugin is about to expire buy now a license', 'salon-booking-system');

include dirname(__FILE__).'/_header.php';
?>

	<tr style="font-size: 16px; font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="55" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				$user = get_user_by('ID', 1);
				$name = $user->first_name . ' ' . $user->last_name;

				$msg = sprintf(__("Hi %s,\n
Salon Booking plugin is about to expire, are you satisfied by its features?\n
Are you planning to buy a PRO license to keep on using it?\n
If so <strong>click here to get a special 30 discount</strong> on your first purchase.\n
If you are not willing to buy a PRO version help us on improving our plugin and answer to some questions.\n
We'll be very happy to hear from your feedback.\n
In case of doubts don't hesitate sending us an email to <strong>support@wpchef.it</strong>\n
Have a nice day!\n
The Salon Booking staff", 'salon-booking-system'), $name);
				$msg = nl2br($msg);
				echo $msg;
				?>
			</p>
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="40" align="middle" valign="middle" bgcolor="#f2f2f2">
			<hr style="border: solid 1px #fff; margin: 0 16px;">
			<p style="margin-left: 49px; margin-right: 49px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666;"></p>
		</td>
	</tr>
</table>
</body>
</html>