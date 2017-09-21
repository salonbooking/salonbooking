<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */
$customer = $booking->getCustomer();
$data['to']      = $customer->get('user_email');
$data['subject'] = $plugin->getSettings()->getSalonName();
$manageBookingsLink = true;

$feedback_url = home_url() . '?sln_customer_login=' . $customer->getHash() . '&feedback_id=' . $booking->getId();

include dirname(__FILE__).'/_header.php';
?>


<!--<?php echo $feedback_url ?>-->
    <tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
            <p style="margin-left: 49px; margin-right: 49px;">
                <?php
                $msg = "Hi [NAME],
                thank you for visiting us at the shop last friday.
                We would be very happy to hear from you how was your experience at <b>[SALON NAME].</b>\n
                Plase take two minutes to send us a quick private review.";
                $msg = __( $msg );
				$msg = str_replace(array('[NAME]', '[SALON NAME]'), array($customer->getName(), $plugin->getSettings()->getSalonName()), $msg);
				$msg = nl2br($msg);
				echo $msg;
                ?>
                
            </p>
		</td>
	</tr>

    <tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
        <td height="80" valign="middle" bgcolor="#f2f2f2">
            <hr style="border: solid 1px #fff; margin: 0 16px;">
            <table width="502" border="0" align="left" cellpadding="0" cellspacing="0"  style="margin-left: 49px; margin-right: 49px;">
                <tbody>
                <tr>
                    <td width="272" align="center" valign="middle">
                        <p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:normal;">
                            <?php
                            _e('Click on this button to send us your feedback', 'salon-booking-system');
                            ?>
                        </p>
                    </td>

                    <td align="right" valign="top">
                        <p style="padding-left: 20px;">
                            <a href="<?php echo $feedback_url ?>"style="
                                            text-transform: uppercase;
                                            display: inline-block;
                                            padding: 10px 20px;
                                            margin-bottom: 0;
                                            font-size: 12px;
                                            font-weight: 400;
                                            line-height: 1.42857143;
                                            text-align: center;
                                            white-space: nowrap;
                                            vertical-align: middle;
                                            -ms-touch-action: manipulation;
                                            touch-action: manipulation;
                                            cursor: pointer;
                                            -webkit-user-select: none;
                                            -moz-user-select: none;
                                            -ms-user-select: none;
                                            user-select: none;
                                            background-image: none;
                                            border: 1px solid transparent;
                                            border-radius: 3px;
                                            color: #fff;
                                            background-color: #0d569f;
                                            text-decoration: none;"><?php _e('Submit a review','salon-booking-system'); ?></a>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<hr style="border: solid 1px #fff; margin: 20px 16px;">
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

<?php
include dirname(__FILE__).'/_footer.php';