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

				$msg = sprintf(__('Hi %s,<br /><br /><strong>Salon Booking</strong> plugin is about to expire, are you satisfied by its features?<br /><br />If so, are you planning to <strong><a href="http://salon.wordpresschef.it/salon-booking-plugin-pricing/">buy a PRO license</a></strong> to keep on using it?<br /><br /><br />If so <strong><a href="http://salon.wordpresschef.it/invite-friends-get-30-discount-first-purchase/">click here</a></strong> to get a special <strong>30%% discount</strong> on your first purchase.<br /><br />In case of any doubts don\'t hesitate sending us an email to <strong>support@wpchef.it</strong><br /><br />Have a nice day!<br /><br />The Salon Booking staff<br /><br />salon.wordpresschef.it', 'salon-booking-system'), $name);
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